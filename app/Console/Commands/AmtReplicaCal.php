<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Model\AmtReplica;
use App\Model\AmtReplicaDiagGroup;
use DB;

class AmtReplicaCal extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'replica:cal {--i|interactive=null} {amtReplicaId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重新計算Replica 分數';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ('all' === $this->argument('amtReplicaId')) {
            AmtReplica::where('created_at', '>=', '2016-12-20')->get()->each(function ($replica) {
                return $this->replicaCalProc($replica);
            });

            return $this->info("\n---------------------------\n所有 AmtReplica 處理完畢!\n\n");
        }

        $replica = AmtReplica::find($this->argument('amtReplicaId'));

        if (is_null($replica)) {
            return $this->info("\n AmtReplica:404 Not Found!\n\n");
        }

        return $this->replicaCalProc($replica);
    }

    protected function replicaCalProc(AmtReplica $replica)
    {
        $this->info("\n---------------------------\nAmtReplica:{$replica->id} 開始處理...\n---------------------------\n");

        // 找出所有 replica groups, 分別進行處理
        $replica->groups->each(function ($group) use ($replica) {
            if (!is_null($this->option('interactive'))) {
                if (false === $this->confirm('Continue ?', 'y')) {
                    return false;
                }
            }
            
            $this->info("------------------------------------------------------\n    AmtReplicaGroup: {$group->id} [{$group->group->content}] 跑分開始~        \n------------------------------------------------------\n");
            // AmtReplica.currentGroup 重新指向 
            // group 找出對應level 之 cell, update current cell
            $this->pointToTheGroup($replica, $group)->resetCurrentGroup($group);

            // 判斷 cell is pass, then do switch
            $isPass = $group->currentCell->isPass($group);

            $this->switchProc($replica, $isPass);

            $this->line("<yellow>------------------------------------------------------\n     等級: {$group->getLevel()} \n------------------------------------------------------</yellow>");
            $this->info("------------------------------------------------------\n    AmtReplicaGroup: {$group->id} [{$group->group->content}] 跑分完畢!        \n------------------------------------------------------");

            $this->line("||||||||||||||||||||||||||||||||||||||||||||||||||||||");            
        }); 

        $this->info("\n---------------------------\nAmtReplica:{$replica->id} 處理完畢!\n---------------------------\n");
    }

    protected function pointToTheGroup(AmtReplica $replica, AmtReplicaDiagGroup $group)
    {
        $replica->currentGroup()->associate($group);
        $replica->save();
        
        return $this;    
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
     * @param  \App\Model\AmtReplica $replica
     * @param  bool $isPass
     * @return bool
     */
    protected function switchProc(AmtReplica $replica, $isPass)
    {
        if (true === $replica->currentGroup->switchCellProxy($isPass, $this)) {
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
    }
}
