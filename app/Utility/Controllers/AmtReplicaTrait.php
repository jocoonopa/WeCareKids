<?php

namespace App\Utility\Controllers;

use AmtAlsRpt as AAR;
use AmtReplica as ARA;
use App\Model\AmtReplica;
use DB;
use Symfony\Component\HttpFoundation\Response;

Trait AmtReplicaTrait 
{
    protected function replicaFlow($user, $child, $amt)
    {
        ARA::gen($user, $child, $amt);

        /**
         * @var \App\Model\AmtReplica
         */
        $replica = ARA::getReplica();

        if (is_null(ARA::getEntryCell())) {
            abort(Response::HTTP_FORBIDDEN, '小孩年龄过小, 目前没有评测必要');
        }

        /**
         * 取得目前应该给使用者作答之 group
         * 
         * @var \App\Model\AmtReplicaDiagGroup
         */
        $replicaCurrentDiagGroup = $replica->groups()->first();

        /**
         * 此 AmtReplica 是否尚未结束
         * 
         * @var boolean
         */
        $isNotFinish = true;

        if ($replicaCurrentDiagGroup->currentCell->isEmpty()) {
            $isNotFinish = $this->switchGroup($replica);
        }
        
        // 扣钱
        AAR::genUsageRecord(ARA::getReport());
        
        DB::commit();

        return true === $isNotFinish 
            ? redirect("/backend/amt_replica/{$replica->id}/edit")
            : redirect('/backend/child')->with('warning', "{$replica->child->name}{$replica->child->getSex()} 此问卷没有作答必要");
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
     * @param  \App\Model\AmtReplica $replica
     * @return bool
     */
    protected function _switch(AmtReplica $replica)
    {
        $isPass = $replica->currentGroup->currentCell->isPass($replica->currentGroup);

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