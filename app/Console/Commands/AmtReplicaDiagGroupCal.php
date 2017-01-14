<?php

namespace App\Console\Commands;

use App\Model\AmtReplicaDiagGroup;
use Illuminate\Console\Command;

class AmtReplicaDiagGroupCal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ardg:cal {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '計算指定 AmtReplicaDiagGroup 的分數, 並列出過程';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');

        $group = AmtReplicaDiagGroup::find($id);

        if (is_null($group)) {
            return $this->error("id:{$id}  查無資料!");
        }

        $this->info("------------------------------------------------------\n|    AmtReplicaGroup: {$group->id} [{$group->group->content}] 跑分開始~        |\n------------------------------------------------------\n");

        $this->resetCurrentGroup($group);

        // 判斷 cell is pass, then do switch
        $isPass = $group->currentCell->isPass($group);

        $this->switchProc($group, $isPass);

        $group->resultCell()->associate($group->currentCell);
        $group->status = AmtReplicaDiagGroup::STATUS_DONE_ID;
        $group->save();
        
        $this->info("------------------------------------------------------\n|    AmtReplicaGroup: {$group->id} [{$group->group->content}] 跑分完畢!        |\n------------------------------------------------------");
    }

    protected function resetCurrentGroup(AmtReplicaDiagGroup $group)
    {
        $group->currentCell()->associate($group->findEntryMapCell());
        $group->resultCell()->associate(NULL);
        $group->status = AmtReplicaDiagGroup::STATUS_INIT;
        $group->dir = NULL;
        $group->save();

        return $this;
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
     * @param  \App\Model\AmtReplicaDiagGroup $group
     * @param  bool $isPass
     * @return bool
     */
    protected function switchProc(AmtReplicaDiagGroup $group, $isPass)
    {
        if (true === $group->switchCellProxy($isPass, $this)) {
            return true;
        }

        return false;
    }
}















