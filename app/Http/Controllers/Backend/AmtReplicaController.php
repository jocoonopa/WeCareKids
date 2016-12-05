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
     * Show the form for editing the specified resource.
     *
     * @param  App\Model\AmtReplica $replica
     * @return \Illuminate\Http\Response
     */
    public function edit(AmtReplica $replica, Request $request)
    {
        /**
         * 取得目前應該給使用者作答之 group
         * 
         * @var \App\Model\AmtReplicaDiagGroup
         */
        $replicaCurrentDiagGroup = $replica->currentGroup;

        $replicaCurrentDiagGroup = (is_null($replicaCurrentDiagGroup)) 
            ? $replica->findPendingDiagGroups()->first()
            : $replicaCurrentDiagGroup
        ;

        // 若沒有符合之 group, 表示該問卷已經作答完畢, 導向填寫完成頁@finish
        if (is_null($replicaCurrentDiagGroup)) {
            return redirect("/backend/amt_replica/{$replica->id}/finish")->with('success', '評測完成!');
        }

        $isDefaultLevel = (0 === $replicaCurrentDiagGroup->level);

        /**
         * 小朋友的預設進入等級
         * 
         * @var integer
         */
        $level = (0 === $replicaCurrentDiagGroup->level) 
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
            ->filter(function ($replicaDiag) use ($level, $isDefaultLevel) {
                $count = $replicaDiag->diag->findMatchStandards($level, $isDefaultLevel)->count();

                return 0 < $count;
            });

        if (0 === $replicaDiags->count()) {
            //  若也沒有存在值得 diag, 表示此 Group 應該被該 child 略過
            if (0 === $replicaCurrentDiagGroup->diags()->whereNotNull('value')->count()) {
                $replicaCurrentDiagGroup->update(['status' => AmtReplica::STATUS_SKIP_ID]);
            }

            $replica->swtichGroup();

            return redirect("/backend/amt_replica/{$replica->id}/edit")->with('success', "大題: {$replica->currentGroup->id }作答完畢");
        }

        return view('backend/amt_replica/edit', compact('replica', 'replicaDiags', 'level'));
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
        | 1. 接收傳過來的 replica_diag_ids(name key) 以及其值
        | 2. 比對 AmtReplicaDiag 對應的 AmtDiagStandard, AmtReplicaDiagGroup::calculateLevel() 取得此 diag 的 level,
        |    - 若全部通過,通過之 diags 中提供最高 max-level 之 standard 為該 AmtReplicaDiagGroup 之測定standard 
        |    
        |    - 有未通過的,則未通過之 diags 中提供最低 level 之 diag 之 (minLevel - 1) 為該 
        |      AmtReplicaDiagGroup之最高可能測定level
        |
        |    - 若其為範圍型 standard, 且 child->getLevel() 落在其區間, 則child->getLevel() 為該 AmtReplicaDiagGroup 之 level.
        |    
        |    - 若在範圍之外, 小於 min 以 min 計, 大於 max 以 max 計
        |    
        |    - 若 diags 沒有提供任何符合的 standard, 直接回傳 child->getLevel() 為該 AmtReplicaDiagGroup 之 level
        |
        | 3. 更新 AmtReplicaDiag.standard 和 AmtReplicaDiag.level 之值
        */
        $diagPairs = $request->all();
        
        /**
         * 取得目前應該給使用者作答之 group
         * 
         * @var \App\Model\AmtReplicaDiagGroup
         */
        $replicaCurrentDiagGroup = $replica->findPendingDiagGroups()->first();

        /**
         * 小朋友的預設進入等級
         * 
         * @var integer
         */
        $level = (0 === $replicaCurrentDiagGroup->level) 
            ? $replica->child->getLevel($replica->created_at)
            : $replicaCurrentDiagGroup->level
        ;

        /**
         * 新增的log
         * 
         * @var array
         */
        $appendLog = [
            'd' => [], 
            'l' => $level, 
            's' => $replicaCurrentDiagGroup->status
        ];

        DB::beginTransaction();

        try {
            foreach ($diagPairs as $diagId => $value) {
                if (!is_numeric($diagId)) {
                    continue;
                }

                // 添加作答log
                $appendLog['d'][] = $diagId;

                // 更新 replicaDiag 符合的 standard
                $replicaDiag = AmtReplicaDiag::find($diagId);

                $replicaDiag->update(['value' => json_encode($value)]);

                $standard = $replicaDiag->getResultStandard();

                if (is_null($standard)) {
                    continue;
                }

                // 這段看看不能不能Event 的 pattern 取代流水帳
                // diag之level 的更新置放在 AmtReplica.calculateCurrentGroupLevel()
                $replicaDiag->update(['standard_id' => $standard->id]);
            }  

            $replica->calculateCurrentGroupLevel();

            //Log
            // 上一步的動作就是把 AmtReplicaDiag.value 和 AmtReplicaDiag.standard_id 改為 NULL, 
            // AmtReplicaGroup.level 重設為 log['l'], AmtReplicaGroup.status 重設為 log['s']
            $logs = json_decode($replica->log->logs, true);
            $logs[] = $appendLog;
            $replica->log->logs = json_encode($logs);
            $replica->log->save();

            DB::commit();

            return redirect("/backend/amt_replica/{$replica->id}/edit")->with('success', '作答狀態更新');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect("/backend/amt_replica/{$replica->id}/edit")->with('error', "{$e->getMessage()}");
        }
    }

    /**
     * @param  App\Model\AmtReplica  $replica
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function finish(AmtReplica $replica, Request $request)
    {
        DB::beginTransaction();

        try {
            $replica->update(['status' => AmtReplica::STATUS_DONE_ID,]);

            DB::commit();

            $request->session()->flash('success', "{$replica->id}評測完畢!");

            return view("/backend/amt_replica/finish");
        } catch (\Exception $e) {
            DB::rollback();

            return redirect("/backend/amt_replica")->with('error', "{$e->getMessage()}");
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
        | 1. 新增 AmtReplica 實體, 並且指定目前指向的 \App\Model\AmtReplicaDiagGroup
        | 2. 新增 隸屬 AmtReplicaLog 實體　
        | 3. 迭代AmtDiagGroup, 建立 AmtReplicaDiagGroup 及 AmtReplicaDiag 實體, 初始化整份問卷
        |
        | PS: 這邊的動作之後應該透過 Observer 或是 Service 抽離出來
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
                'child_id' => $request->get('child_id', 1),
                'status' => AmtReplica::STATUS_ORIGIN_ID
            ]);

            /**
             * 新增之 AmtReplicaLog 實體, 記錄作答過程, 回到上一題功能需要透過此實體實現
             * 
             * @var \App\Model\AmtReplicaLog
             */
            $log = AmtReplicaLog::create([
                'replica_id' => $replica->id
            ]);

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
            $replicaCurrentDiagGroup = $replica->groups()->where('status', 0)->first();

            /* 
            |--------------------------------------------------------------------------
            | 更新 replica group 指標
            |--------------------------------------------------------------------------
            */
            $replica->update([
                'current_group_id' => $replicaCurrentDiagGroup->id
            ]);

            DB::commit();

            return redirect('/backend/amt_replica')->with('success', "{$replica->id}新增完成!");
        } catch (\Exception $e) {
            DB::rollback();

            return redirect('/backend/amt_replica')->with('error', "{$e->getMessage()}");
        }
    }

    public function destroy(AmtReplica $replica)
    {
        $replica->delete();

        return redirect('/backend/amt_replica')->with('success', "{$replica->id}刪除完成!");
    }
}
