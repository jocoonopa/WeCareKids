<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests;
use App\Model\AmtDiag;
use App\Model\AmtDiagGroup;
use App\Model\AmtReplica;
use App\Model\AmtReplicaDiag;
use App\Model\AmtReplicaDiagGroup;
use App\Model\AmtReplicaLog;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;

class AmtReplicaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $replicas = AmtReplica::all();

        return view('backend/amt_replica/index', compact('replicas'));
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
        | 4. 將新增之 AmtReplica 指向 findPendingDiagGroups() 取得的第一個 AmtReplicaDiagGroup,
        |    最為 AmtReplica(考卷)的第一個 AmtReplicaDiagGroup(大題)
        | 
        */
        DB::beginTransaction();

        try {
            /**
             * 新增之 AmtReplica 實體, 即問卷
             * 
             * @var \App\Model\AmtReplica
             */
            $replica = AmtReplica::create([
                'amt_id' => $request->get('amt_id', 1),
                'creater_id' => Auth::user()->id,
                'child_id' => $request->get('child_id'),
                'status' => AmtReplica::STATUS_ORIGIN_ID
            ]);

            /**
             * 新增之 AmtReplicaLog 實體, 記錄作答過程, 回到上一題功能需要透過此實體實現
             * 
             * @var \App\Model\AmtReplicaLog
             */
            $log = AmtReplicaLog::create(['replica_id' => $replica->id]);

            AmtDiagGroup::findValid()->each(function ($group) use ($replica) {
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
            $replicaCurrentDiagGroup = $replica->findPendingDiagGroups()->first();

            // 更新 replica group 指標
            $replica->update(['current_group_id' => $replicaCurrentDiagGroup->id]);

            DB::commit();

            return redirect('/backend/child')->with('success', "{$replica->child->name}{$replica->child->getSex()}的評測新增囉!");
        } catch (\Exception $e) {
            DB::rollback();

            return redirect('backend/child')->with('error', "{$e->getMessage()}");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Model\AmtReplica $replica
     * @return \Illuminate\Http\Response
     */
    public function edit(AmtReplica $replica, Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | 選題邏輯
        |--------------------------------------------------------------------------
        |
        | 1. 取得目前 AmtReplica 指向的 AmtReplicaDiag
        | 
        | 2. 如果目前指向的 AmtReplicaDiag level 值為0, 表示第一次進入該 AmtReplicaDiag, 應該使用 child@getLevel() 為
        |    level 值, 變數 $isFirstEntry 設為 true
        | 
        | 3. 從目前 AmtReplicaDiagGroup(大題) 找出所有尚未作答(value為NULL)的 AmtReplicaDiag (小題)
        | 
        | 4. 迭代找到的 AmtReplicaDiag collection, 配合 AmtDiag@findMatchStandards() 方法確認每個 AmtReplicaDiag 是否有
        |    符合目前 level 的 AmtDiagStandard, 如果沒有則過濾該 AmtReplicaDiag
        | 
        | 5. 過濾後的 AmtReplicaDiag collection 即為目前 child 需要作答之 AmtReplicaDiag, 返回作答頁面
        |
        | @5. 若過濾後 AmtReplicaDiag collection 為空, 接著檢查是否存在已經作答過之題目, 若不存在, 表示此 Group 根本沒有需要作答的 diag, 
        |     將其狀態設為 AmtReplica::STATUS_SKIP_ID(略過)
        |
        | @6. 接著呼叫 AmtReplica 的 swtichGroup() 方法切換目前 Replica 指向的 AmtReplicaDiagGroup,
        |     若swtichGroup()回傳 NULL, **表示該 Replica 已經沒有需要作答之 AmtReplicaDiagGroup **, 導向評測結束頁面 @finish
        */
       
        /**
         * 取得目前應該給使用者作答之 group
         * 
         * @var \App\Model\AmtReplicaDiagGroup
         */
        $replicaCurrentDiagGroup = $replica->currentGroup;

        if (is_null($replicaCurrentDiagGroup)) {
            return redirect('/backend/amt_replica')->with('warning', '評測已經作答完畢');
        }

        /**
         * 是否為第一次作答該 Group(大題)
         *
         * 特別存成變數是因為後面尋找符合的 AmtDiagStandard 時也會用上該值
         * 
         * @var boolean
         */
        $isFirstEntry = (0 === $replicaCurrentDiagGroup->level);

        /**
         * 小朋友的預設進入等級
         * 
         * @var integer
         */
        $level = (true === $isFirstEntry) 
            ? $replica->child->getLevel($replica->created_at)
            : $replicaCurrentDiagGroup->level
        ;

        /**
         * 從目前作答之 group, 迭代所有隸屬的 diags,
         * 透過過濾器過濾掉沒有找到對應符合的 standard
         * 
         * @var \App\Model\AmtReplicaDiags
         */
        $replicaDiags = $replicaCurrentDiagGroup->diags()->whereNull('value')->get()
            ->filter(function ($replicaDiag) use ($level, $isFirstEntry) {
                $count = $replicaDiag->diag->findMatchStandards($level, $isFirstEntry)->count();

                return 0 < $count;
            });

        // 若存在需要作答之 diag, 則輸出作答頁面
        if (0 < $replicaDiags->count()) {
            return view('backend/amt_replica/edit', compact('replica', 'replicaDiags', 'level'));
        }

        //  若也沒有存在已經作答之 diag, 表示此 Group 根本沒有需要作答的 diag, 將其狀態設為 AmtReplica::STATUS_SKIP_ID(略過)
        if (0 === $replicaCurrentDiagGroup->diags()->whereNotNull('value')->count()) {
            $replicaCurrentDiagGroup->update(['status' => AmtReplica::STATUS_SKIP_ID]);
        }

        return is_null($replica->swtichGroup()) 
            ? redirect("/backend/amt_replica/{$replica->id}/finish")->with('success', '評測完成!')
            : redirect("/backend/amt_replica/{$replica->id}/edit")->with('success', "大題: {$replica->currentGroup->id }作答完畢")
        ;
    }

    /**
     * Update the specified resource in storage.
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
        | 2. 透過 AmtReplica@statisticscCurrentGroup() 統計結算並更新目前指向的 AmtReplicaDiagGroup 
        |    及其隸屬之 AmtReplicaDiag 的 level, 並設置 AmtReplicaDiag 指向所符合的 AmtDiagStandard
        |    
        |    PS:注意 statisticscCurrentGroup() 並不考慮child 的 default level, 因為這邊的重點是確保系統能遵循正確邏輯
        |    帶出題目． 最後呈現的 level 是另外透過 AmtReplicaDiagGroup@updateLevel() 在 @finish 時呼叫更新
        |
        | 3. 記錄答題 AmtReplicaLog
        */
        
        /**
         * 取得目前應該給使用者作答之 group
         * 
         * @var \App\Model\AmtReplicaDiagGroup
         */
        $currentReplicaGroup = $replica->currentGroup;

        /**
         * 新增的log
         * 
         * @var array
         */
        $appendLog = [
            'd' => [], 
            'l' => $request->get('level'), 
            's' => $currentReplicaGroup->status
        ];

        DB::beginTransaction();

        try {
            foreach ($request->all() as $diagId => $value) {
                if (!is_numeric($diagId)) {
                    continue;
                }

                // 記錄作答之 AmtReplicaDiagId
                $appendLog['d'][] = $diagId;

                $replicaDiag = AmtReplicaDiag::find($diagId);
                $replicaDiag->update(['value' => json_encode($value)]);
                $replicaDiag->updateMatchStandard();
            }  

            // 統計結算並更新目前指向的 AmtReplicaDiagGroup 及其隸屬之 AmtReplicaDiag 的 level,
            // 並設置 AmtReplicaDiag 指向所符合的 AmtDiagStandard
            // 
            // 這個動作必須每次提交答案都執行, 因為目前的評測系統是動態出題的, 每次挑選的題目都會和上次的作答有直接關係
            $replica->statisticsCurrentGroup();

            // 將動作添加至 AmtReplicaLog
            $replica->log->add($appendLog)->save();

            DB::commit();

            return redirect("/backend/amt_replica/{$replica->id}/edit")->with('success', '作答狀態更新');
        } catch (\Exception $e) {
            DB::rollback();

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

            $replica->groups()->get()->each(function ($group) {
                // 將 AmtDiagStandard 和 Child 的 default level 
                // ㄧ同考慮後的level更新至 AmtDiagGroup
                $group->updateLevel();
            });

            DB::commit();

            $request->session()->flash('success', "{$replica->child->name}{$replica->child->getSex()}評測完畢囉!");

            return view("/backend/amt_replica/finish", compact('replica'));
        } catch (\Exception $e) {
            DB::rollback();

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
                'status' => $record->s
            ]);

            // ~3
            $replicaDiags->each(function ($replicaDiag) use ($record) {
                $replicaDiag->update([
                    'value' => NULL, 
                    'standard_id' => NULL,
                    'level' => 0
                ]);
            });

            // ~4
            $replica->log->pop()->save();

            DB::commit();

            return redirect("/backend/amt_replica/{$replica->id}/edit")->with('success', "返回上一題");
        } catch (\Exception $e) {
            DB::rollback();

            Log::info($e);

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
}
