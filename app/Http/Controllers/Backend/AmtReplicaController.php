<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Model\Amt;
use App\Model\AmtAlsRpt;
use App\Model\AmtCell;
use App\Model\AmtDiag;
use App\Model\AmtDiagGroup;
use App\Model\AmtReplica;
use App\Model\AmtReplicaDiag;
use App\Model\AmtReplicaDiagGroup;
use App\Model\AmtReplicaLog;
use App\Model\Child;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;
use Wck;
use Symfony\Component\HttpFoundation\Response;

class AmtReplicaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $replicas = Auth::user()->replicas;

        return view('backend/amt_replica/index', compact('replicas'));
    }

    /**
     * 顯示題目
     *
     * @param  App\Model\AmtReplica $replica
     * @return \Illuminate\Http\Response
     */
    public function edit(AmtReplica $replica, Request $request)
    {
        $replicaDiags = AmtCell::findFreshDiags($replica->currentGroup);

        if (true === Wck::isEmpty($replicaDiags)) {
            return true === $this->_switch($replica, false) 
                ? redirect("/backend/amt_replica/{$replica->id}/edit") 
                : redirect("/backend/amt_replica/{$replica->id}/finish")
            ;
        }

        /**
         * 此 AmtReplica 的 Child 之預設 Level
         * 
         * @var integer
         */
        $level = $replica->getLevel();

        return view('backend/amt_replica/edit', compact('replica', 'replicaDiags', 'level'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend/amt_replica/create');
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @todo 新增的過程之後透過 Observer 模式處理
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* 
        |--------------------------------------------------------------------------
        | 初始化評測所需實體
        |--------------------------------------------------------------------------
        |
        | 1. 新增 AmtReplica(此Model可當作Amt的複印品, Amt 為評測的模板, AmtReplica 可想像為印製出來的考卷, 
        |    受測者填寫的是印製出來的考卷)
        |
        | 2. 產生對應的 AmtReplicaLog, 此實體用來記錄答題過程 
        |    @example [{"d": [1,2,3], "l": 7, "s": 0},{...}] 
        |    
        |    d: AmtReplicaDiag 的 id, l 為 level, s為 AmtReplica 指向之 AmtReplicaDiagGroup 的狀態
        | 
        | 3. 將已經完成的 AmtDiagGroup 選取出來(目前做到 19), 將 groups 和隸屬之 AmtDiag 建立
        |    對應之 AmtReplicaDiagGroup 和  AmtReplicaDiag
        |
        | 4. 將新增之 AmtReplica.currentGroup 指向第一個 AmtReplicaDiagGroup,
        |    AmtReplicaDiagGroup.currentCell 指向 AmtReplicaDiagGroup::findEntryMapCell 
        |    找到的 AmtCell
        |
        |    AmtReplica(考卷)的第一個 AmtReplicaDiagGroup(大題)
        |
        | 5. 新增 AmtAlsReport, AmtReplica.report 指向此AmtAlsReport
        | 
        | 6. 檢查目前指向的 AmtCell 是否需要作答, 若否進行 switch(), 讓 AmtReplica 指到需要作答的 AmtCell
        | 
        */
        DB::beginTransaction();

        /**
         * 此 AmtReplica 是否尚未結束
         * 
         * @var boolean
         */
        $isNotFinish = true;

        /**
         * 欲綁定之 Child 
         * 
         * @var \App\Model\Child
         */
        $child = Child::find($request->get('child_id'));

        /**
         * 欲用來複製的評測模板
         * 
         * @var \App\Model\Amt
         */
        $amt = Amt::find($request->get('amt_id'));

        if (is_null($child) || is_null($amt)) {
            abort(Response::HTTP_NOT_FOUND, '受測者或評測模板為空!');
        }

        try {
            /**
             * 新增之 AmtReplica 實體, 即問卷
             * 
             * @var \App\Model\AmtReplica
             */
            $replica = AmtReplica::create([
                'amt_id' => $amt->id,
                'creater_id' => Auth::user()->id,
                'child_id' => $child->id,
                'status' => AmtReplica::STATUS_ORIGIN_ID
            ]);

            /**
             * 新增之 AmtReplicaLog 實體, 記錄作答過程, 回到上一題功能需要透過此實體實現
             * 
             * @var \App\Model\AmtReplicaLog
             */
            $log = AmtReplicaLog::create(['replica_id' => $replica->id]);

            $amt->groups()->each(function ($group) use ($replica) {
                /**
                 * 新增之 AmtReplicaDiagGroup 實體, 可想像為建立初始化問卷的大題/題組
                 * 
                 * @var \App\Model\AmtReplicaDiagGroup
                 */
                $replicaGroup = AmtReplicaDiagGroup::create([
                    'replica_id' => $replica->id,
                    'group_id' => $group->id
                ]);

                /**
                 * 新增之 AmtReplicaDiag 實體, 可想像為建立初始化大題中的題目
                 *
                 * @var \App\Model\AmtReplicaDiag
                 */
                $group->diags()->get()->each(function ($diag) use ($replicaGroup) {
                    $replicaDiag = AmtReplicaDiag::create([
                        'diag_id' => $diag->id,
                        'group_id' => $replicaGroup->id
                    ]);
                });
            });

            /**
             * 取得目前應該給使用者作答之 group
             * 
             * @var \App\Model\AmtReplicaDiagGroup
             */
            $replicaCurrentDiagGroup = $replica->groups()->first();

            /**
             * 進入 AmtGroup 透過的 AmtCell
             * 
             * @var \App\Model\AmtCell 
             */
            $entryCell = $replicaCurrentDiagGroup->findEntryMapCell();

            if (is_null($entryCell)) {
                abort(Response::HTTP_FORBIDDEN, '小孩年齡過小, 目前沒有評測必要');
            }

            //  綁定指向的 Cell
            $replicaCurrentDiagGroup->update(['current_cell_id' => $entryCell->id]);

            // 更新 replica group 指標
            $replica->update(['current_group_id' => $replicaCurrentDiagGroup->id]);

            $report = AmtAlsRpt::create([
                'owner_id' => Auth::user()->id,
                'replica_id' => $replica->id
            ]);

            $replica->update(['report_id' => $report->id]);

            if ($replicaCurrentDiagGroup->currentCell->isEmpty()) {
                $isNotFinish = $this->switchGroup($replica);
            }

            DB::commit();

            return true === $isNotFinish 
                ? redirect('/backend/child')->with('success', "{$replica->child->name}{$replica->child->getSex()}的評測新增囉!")
                : redirect('/backend/child')->with('warning', "{$replica->child->name}{$replica->child->getSex()} 此問卷沒有作答必要");
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect('backend/child')->with('error', "{$e->getMessage()}");
        }
    }

    /**
     * 答題
     *
     * @param  App\Model\AmtReplica  $replica
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(AmtReplica $replica, Request $request)
    {
        /* 
        |--------------------------------------------------------------------------
        | 更新作答結果並導向 @edit
        |--------------------------------------------------------------------------
        |
        | 1. 根據 post 過來的 replica_diag_id 和 值, 更新 AmtReplicaDiag.value
        |
        | 2. 記錄答題 AmtReplicaLog
        |
        | 3. 檢核答案
        |
        | 4. switchCell or switchGroup or finish
        */
        
        /**
         * 取得目前應該給使用者作答之 group
         * 
         * @var \App\Model\AmtReplicaDiagGroup
         */
        $currentReplicaGroup = $replica->currentGroup;

        DB::beginTransaction();

        /**
         * The passed diagId:value pairs
         * 
         * @var array
         */
        $pairs = array_filter($request->all(), function ($k) {
            return is_numeric($k);
        });

        try {   
            // 更新 AmtReplicaDiag 之值
            foreach ($pairs as $diagId => $value) {
                AmtReplicaDiag::find($diagId)->update(['value' => json_encode($value)]);
            }  

            // 將動作添加至 AmtReplicaLog
            $replica->log->add([
                'd' => array_keys($pairs), 
                'l' => $request->get('level'), 
                's' => $currentReplicaGroup->status,
                'cc' => $currentReplicaGroup->currentCell->id,
                'rc' => is_null($currentReplicaGroup->resultCell) ? NULL : $currentReplicaGroup->resultCell->id
            ])->save();

            /**
             * 是否有成功切換 Cell 或是 Group, 如果沒有表示該 AmtReplica 已經作答完畢,
             * 應導向使用者至 @finish
             * 
             * @var bool
             */
            $hasSwitched = $this->_switch($this, $replica->currentGroup->currentCell->isPass($replica->currentGroup));

            DB::commit();

            return true === $hasSwitched
                ? redirect("/backend/amt_replica/{$replica->id}/edit")->with('success', '作答狀態更新')
                : redirect("/backend/amt_replica/{$replica->id}/finish")->with('success', '評測完成!')
            ;
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect("/backend/amt_replica/{$replica->id}/edit")->with('error', "{$e->getMessage()}");
        }
    }

    /**
     * AmtReplica 答題結束的處理
     * 
     * @param  App\Model\AmtReplica  $replica
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function finish(AmtReplica $replica, Request $request)
    {
        if (!is_null($replica->findPendingDiagGroups()->first())) {
            return redirect("/backend/amt_replica/{$replica->id}/edit")->with('error', "評測{$replica}尚未作答完畢!");
        } 

        DB::beginTransaction();

        try {
            $replica->update(['status' => AmtReplica::STATUS_DONE_ID]);

            DB::commit();

            $request->session()->flash('success', "{$replica->child->name}{$replica->child->getSex()}評測完畢囉!");

            return view("/backend/amt_replica/finish", compact('replica'));
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect("/backend/amt_replica")->with('error', "{$e->getMessage()}");
        }
    }

    /**
     * 回到上一題
     * 
     * @param  App\Model\AmtReplica $replica
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function prev(AmtReplica $replica)
    {
        /*
        |--------------------------------------------------------------------------
        | 上一步的動作
        |--------------------------------------------------------------------------
        |
        | 1. AmtReplica: AmtReplica.currentGroup 設為 AmtReplicaDiag.group
        | 2. AmtReplicaGroup: AmtReplicaGroup.level 重設為 log['l'], AmtReplicaGroup.status 重設為 log['s']
        | 3. AmtReplicaDiag: 把log['d']指到的 AmtReplicaDiag之[value,standard_id] 設為 NULL, level 改為 0
        | 4. AmtReplicaLog: 最後的紀錄物件將其刪除
        */
       
        DB::beginTransaction();

        try {
            /**
             * 最後的動作紀錄
             * 
             * @var array
             */
            $record = $replica->log->getLast();

            /**
             * 前一次答題時的出題
             * 
             * @var \App\Model\AmtReplicaDiag
             */
            $replicaDiags = AmtReplicaDiag::whereIn('id', $record->d)->get();
            
            /**
             * 前一次答題時 AmtReplica 的 currentGroup
             * 
             * @var \App\Model\AmtReplicaDiagGroup
             */
            $replicaGroup = $replicaDiags->first()->group;

            // ~1
            $replica->update(['current_group_id' => $replicaGroup->id]);
            
            // ~2
            $replicaGroup->update([
                'level' => $record->l,
                'status' => $record->s,
                'current_cell_id' => $record->cc,
                'result_cell_id' => $record->rc
            ]);

            // ~3
            $replicaDiags->each(function ($replicaDiag) use ($record) {
                $replicaDiag->update(['value' => NULL]);
            });

            // ~4
            $replica->log->pop()->save();

            DB::commit();

            return redirect("/backend/amt_replica/{$replica->id}/edit")->with('success', "返回上一題");
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect("/backend/amt_replica/{$replica->id}/edit")->with('warning', "{$e->getMessage()}");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Model\AmtReplica  $replica
     * @return \Illuminate\Http\Response
     */
    public function show(AmtReplica $replica)
    {
        return view('backend/amt_replica/show', compact('replica'));
    }

    /**
     * Destroy the specified resource.
     *
     * @param  App\Model\AmtReplica  $replica
     * @return \Illuminate\Http\Response
     */
    public function destroy(AmtReplica $replica)
    {
        $replica->delete();

        return redirect('/backend/amt_replica')->with('success', "{$replica->id}刪除完成!");
    }

    /**
     * 有成功切換 AmtReplicaDiagGroup 的指向 Cell, return true
     *
     * 若沒有成功切換, 表示該 AmtReplicaDiagGroup 已經完成, AmtReplicaDiagGroup 呼叫 finish(),
     * 將該 AmtReplicaDiagGroup 狀態更新為完成.
     * 
     * 並且讓 AmtReplica $this 呼叫 switchGroup() 切換 AmtReplicaDiagGroup.
     *
     * 若切換 AmtReplicaGroup 失敗, 表示此 AmtReplica 已經完成,
     * AmtReplica 呼叫 finish(), 將 AmtReplica 狀態更新為完成
     *
     * 因此, 回傳 true 時, 要將使用者導向 @edit 繼續作答.
     * 若回傳 false, 則將使用者導向完成頁面 @finish
     *
     * @todo  此 function 日後應該移動至 Service 處理
     *
     * @param  \App\Model\AmtReplica $replica
     * @param  bool $isPass
     * @return bool
     */
    protected function _switch(AmtReplica $replica, $isPass)
    {
        if (true === $replica->currentGroup->switchCell($isPass)) {
            return true;
        }

        return $this->switchGroup($replica);
    }

    /**
     * 切換 AmtReplica 指向的 AmtReplicaDiagGroup,
     * @todo 此 function 日後應該移動至 Service 處理
     * 
     * @param  \App\Model\AmtReplica $replica
     * @return bool
     */
    protected function switchGroup(AmtReplica $replica)
    {
        if (!is_null($replica->currentGroup)) {
            $replica->currentGroup->finish();
        }

        if (true === $replica->swtichGroup()) {            
            return true;
        }

        $replica->finish();

        return false;
    }
}
