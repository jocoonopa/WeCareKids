<?php

namespace App\Http\Controllers\Backend;

use AmtAlsRpt as AAR;
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
        $replicas = AmtReplica::latest()->paginate(env('PERPAGE_COUNT', 50));

        return view('backend/amt_replica/index', compact('replicas'));
    }

    /**
     * 显示题目
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
         * 此 AmtReplica 的 Child 之预设 Level
         * 
         * @var integer
         */
        $level = $replica->getLevel();

        /**
         * 保留的答案
         * 
         * @var array
         */
        $answer = json_decode($request->get('answer'), true);

        return view('backend/amt_replica/edit', compact('replica', 'replicaDiags', 'level', 'answer'));
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
     * @todo 新增的过程之后透过 Observer 模式处理
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* 
        |--------------------------------------------------------------------------
        | 初始化评测所需实体
        |--------------------------------------------------------------------------
        |
        | 1. 新增 AmtReplica(此Model可当作Amt的复印品, Amt 为评测的模板, AmtReplica 可想像为印制出来的考卷, 
        |    受测者填写的是印制出来的考卷)
        |
        | 2. 产生对应的 AmtReplicaLog, 此实体用来记录答题过程 
        |    @example [{"d": [1,2,3], "l": 7, "s": 0},{...}] 
        |    
        |    d: AmtReplicaDiag 的 id, l 为 level, s为 AmtReplica 指向之 AmtReplicaDiagGroup 的状态
        | 
        | 3. 将已经完成的 AmtDiagGroup 选取出来(目前做到 19), 将 groups 和隶属之 AmtDiag 建立
        |    对应之 AmtReplicaDiagGroup 和  AmtReplicaDiag
        |
        | 4. 将新增之 AmtReplica.currentGroup 指向第一个 AmtReplicaDiagGroup,
        |    AmtReplicaDiagGroup.currentCell 指向 AmtReplicaDiagGroup::findEntryMapCell 
        |    找到的 AmtCell
        |
        |    AmtReplica(考卷)的第一个 AmtReplicaDiagGroup(大题)
        |
        | 5. 新增 AmtAlsReport, AmtReplica.report 指向此AmtAlsReport
        | 
        | 6. 检查目前指向的 AmtCell 是否需要作答, 若否进行 switch(), 让 AmtReplica 指到需要作答的 AmtCell
        | 
        */
        DB::beginTransaction();

        /**
         * 此 AmtReplica 是否尚未结束
         * 
         * @var boolean
         */
        $isNotFinish = true;

        /**
         * 欲绑定之 Child 
         * 
         * @var \App\Model\Child
         */
        $child = Child::find($request->get('child_id'));

        /**
         * 欲用来复制的评测模板
         * 
         * @var \App\Model\Amt
         */
        $amt = Amt::find($request->get('amt_id'));

        if (is_null($child) || is_null($amt)) {
            abort(Response::HTTP_NOT_FOUND, '受测者或评测模板为空!');
        }

        try {
            /**
             * 新增之 AmtReplica 实体, 即问卷
             * 
             * @var \App\Model\AmtReplica
             */
            $replica = new AmtReplica();
            $replica->creater()->associate(Auth::user());
            $replica->amt()->associate($amt);
            $replica->child()->associate($child);
            $replica->status = AmtReplica::STATUS_ORIGIN_ID;
            $replica->save();

            /**
             * 新增之 AmtReplicaLog 实体, 记录作答过程, 回到上一题功能需要透过此实体实现
             * 
             * @var \App\Model\AmtReplicaLog
             */
            $log = new AmtReplicaLog();
            $log->replica()->associate($replica);
            $log->save();

            $amt->groups()->each(function ($group) use ($replica) {
                /**
                 * 新增之 AmtReplicaDiagGroup 实体, 可想像为建立初始化问卷的大题/题组
                 * 
                 * @var \App\Model\AmtReplicaDiagGroup
                 */
                $replicaGroup = new AmtReplicaDiagGroup();
                $replicaGroup->replica()->associate($replica);
                $replicaGroup->group()->associate($group);
                $replicaGroup->save();

                /**
                 * 新增之 AmtReplicaDiag 实体, 可想像为建立初始化大题中的题目
                 *
                 * @var \App\Model\AmtReplicaDiag
                 */
                $group->diags()->get()->each(function ($diag) use ($replicaGroup) {
                    $replicaDiag = new AmtReplicaDiag();
                    $replicaDiag->diag()->associate($diag);
                    $replicaDiag->group()->associate($replicaGroup);
                    $replicaDiag->save();
                });
            });

            /**
             * 取得目前应该给使用者作答之 group
             * 
             * @var \App\Model\AmtReplicaDiagGroup
             */
            $replicaCurrentDiagGroup = $replica->groups()->first();

            /**
             * 进入 AmtGroup 透过的 AmtCell
             * 
             * @var \App\Model\AmtCell 
             */
            $entryCell = $replicaCurrentDiagGroup->findEntryMapCell();

            if (is_null($entryCell)) {
                abort(Response::HTTP_FORBIDDEN, '小孩年龄过小, 目前没有评测必要');
            }

            //  绑定指向的 Cell
            $replicaCurrentDiagGroup->currentCell()->associate($entryCell);
            $replicaCurrentDiagGroup->save();

            // 更新 replica group 指标
            $replica->currentGroup()->associate($replicaCurrentDiagGroup);
            $replica->save();

            // 新增关联报告实体 AmtAlsRpt
            $report = new AmtAlsRpt();
            $report->owner()->associate(Auth::user());
            $report->replica()->associate($replica);
            $report->save();

            // AmtReplica 同时也绑定 AmtAlsRpt, 形成 One To One replation ship
            $replica->reportBelong()->associate($report);
            $replica->save();

            if ($replicaCurrentDiagGroup->currentCell->isEmpty()) {
                $isNotFinish = $this->switchGroup($replica);
            }

            // 扣钱
            AAR::genUsageRecord($report);

            DB::commit();

            return true === $isNotFinish 
                ? redirect('/backend/child')->with('success', "{$replica->child->name}{$replica->child->getSex()}的评测新增啰!")
                : redirect('/backend/child')->with('warning', "{$replica->child->name}{$replica->child->getSex()} 此问卷没有作答必要");
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect('backend/child')->with('error', "{$e->getMessage()}");
        }
    }

    /**
     * 答题
     *
     * @param  App\Model\AmtReplica  $replica
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(AmtReplica $replica, Request $request)
    {
        /* 
        |--------------------------------------------------------------------------
        | 更新作答结果并导向 @edit
        |--------------------------------------------------------------------------
        |
        | 1. 根据 post 过来的 replica_diag_id 和 值, 更新 AmtReplicaDiag.value
        |
        | 2. 记录答题 AmtReplicaLog
        |
        | 3. 检核答案
        |
        | 4. switchCell or switchGroup or finish
        */
        
        /**
         * 取得目前应该给使用者作答之 group
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
        }, ARRAY_FILTER_USE_KEY);

        try {   
            // 更新 AmtReplicaDiag 之值
            foreach ($pairs as $diagId => $value) {
                AmtReplicaDiag::find($diagId)->update(['value' => json_encode($value)]);
            }  

            // 将动作添加至 AmtReplicaLog
            $replica->log->add([
                'd' => array_keys($pairs), 
                'l' => $request->get('level'), 
                's' => $currentReplicaGroup->status,
                'cc' => $currentReplicaGroup->currentCell->id,
                'rc' => is_null($currentReplicaGroup->resultCell) ? NULL : $currentReplicaGroup->resultCell->id
            ])->save();

            /**
             * 是否有成功切换 Cell 或是 Group, 如果没有表示该 AmtReplica 已经作答完毕,
             * 应导向使用者至 @finish
             * 
             * @var bool
             */
            $hasSwitched = $this->_switch($replica, $replica->currentGroup->currentCell->isPass($replica->currentGroup));

            DB::commit();

            return true === $hasSwitched
                ? redirect("/backend/amt_replica/{$replica->id}/edit")->with('success', '作答状态更新')
                : redirect("/backend/amt_replica/{$replica->id}/finish")->with('success', '评测完成!')
            ;
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect("/backend/amt_replica/{$replica->id}/edit")->with('error', "{$e->getMessage()}");
        }
    }

    /**
     * AmtReplica 答题结束的处理
     * 
     * @param  App\Model\AmtReplica  $replica
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function finish(AmtReplica $replica, Request $request)
    {
        if (!is_null($replica->findPendingDiagGroups()->first())) {
            return redirect("/backend/amt_replica/{$replica->id}/edit")->with('error', "评测{$replica}尚未作答完毕!");
        } 

        DB::beginTransaction();

        try {
            $replica->update(['status' => AmtReplica::STATUS_DONE_ID]);

            DB::commit();

            $request->session()->flash('success', "{$replica->child->name}{$replica->child->getSex()}评测完毕啰!");

            return view("/backend/amt_replica/finish", compact('replica'));
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            return redirect("/backend/amt_replica")->with('error', "{$e->getMessage()}");
        }
    }

    /**
     * 回到上一题
     * 
     * @param  App\Model\AmtReplica $replica
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function prev(AmtReplica $replica)
    {
        /*
        |--------------------------------------------------------------------------
        | 上一步的动作
        |--------------------------------------------------------------------------
        |
        | 1. AmtReplica: AmtReplica.currentGroup 设为 AmtReplicaDiag.group
        | 2. AmtReplicaGroup: AmtReplicaGroup.level 重设为 log['l'], AmtReplicaGroup.status 重设为 log['s']
        | 3. AmtReplicaDiag: 把log['d']指到的 AmtReplicaDiag之[value,standard_id] 设为 NULL, level 改为 0
        | 4. AmtReplicaLog: 最后的纪录物件将其删除
        */
       
        DB::beginTransaction();

        try {
            /**
             * 最后的动作纪录
             * 
             * @var array
             */
            $record = $replica->log->getLast();

            /**
             * 前一次答题时的出题
             * 
             * @var \App\Model\AmtReplicaDiag
             */
            $replicaDiags = AmtReplicaDiag::whereIn('id', $record->d)->get();
            
            /**
             * 前一次答题时 AmtReplica 的 currentGroup
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

            /**
             * 答案之键值对
             * 
             * @var array
             */
            $answer = [];

            // ~3
            $replicaDiags->each(function ($replicaDiag) use ($record, &$answer) {
                $answer[$replicaDiag->id] = json_decode($replicaDiag->value);

                $replicaDiag->update(['value' => NULL]);
            });

            // ~4
            $replica->log->pop()->save();

            DB::commit();

            return redirect("/backend/amt_replica/{$replica->id}/edit?answer=" . urlencode(json_encode($answer)))->with('success', "返回上一题");
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

        return redirect('/backend/amt_replica')->with('success', "{$replica->id}删除完成!");
    }

    /**
     * 有成功切换 AmtReplicaDiagGroup 的指向 Cell, return true
     *
     * 若没有成功切换, 表示该 AmtReplicaDiagGroup 已经完成, AmtReplicaDiagGroup 呼叫 finish(),
     * 将该 AmtReplicaDiagGroup 状态更新为完成.
     * 
     * 并且让 AmtReplica $this 呼叫 switchGroup() 切换 AmtReplicaDiagGroup.
     *
     * 若切换 AmtReplicaGroup 失败, 表示此 AmtReplica 已经完成,
     * AmtReplica 呼叫 finish(), 将 AmtReplica 状态更新为完成
     *
     * 因此, 回传 true 时, 要将使用者导向 @edit 继续作答.
     * 若回传 false, 则将使用者导向完成页面 @finish
     *
     * @todo  此 function 日后应该移动至 Service 处理
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
     * 切换 AmtReplica 指向的 AmtReplicaDiagGroup,
     * @todo 此 function 日后应该移动至 Service 处理
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
