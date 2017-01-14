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
use App\Model\Child;
use App\Utility\Controllers\AmtReplicaTrait;
use Auth;
use DB;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response;
use Wck;

class AmtReplicaController extends Controller
{
    use AmtReplicaTrait;
    
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
         * 使用者
         * 
         * @var \App\Model\User
         */
        $user = Auth::user();

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
        $amt = Amt::find($request->get('amt_id', Amt::DEFAULT_AMT_ID));

        if (is_null($child) || is_null($amt)) {
            abort(Response::HTTP_NOT_FOUND, '受测者或评测模板为空!');
        }

        try {
            return $this->replicaFlow($user, $child, $amt);
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
                'g' => $currentReplicaGroup->id,
                'dir' => $currentReplicaGroup->dir,
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
            $replicaGroup = AmtReplicaDiagGroup::find($record->g);

            // ~1
            $replica->update(['current_group_id' => $replicaGroup->id]);
            
            // ~2
            $replicaGroup->update([
                'level' => $record->l,
                'status' => $record->s,
                'current_cell_id' => $record->cc,
                'result_cell_id' => $record->rc,
                'dir' => $record->dir
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
}
