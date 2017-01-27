<?php

namespace App\Utility\Services;

use App\Model\AmtAlsRpt;
use App\Model\AmtReplica;
use App\Model\AmtReplicaDiag;
use App\Model\AmtReplicaDiagGroup;
use App\Model\AmtReplicaLog;

class AmtReplicaService
{
    protected $replica;
    protected $entryCell;
    protected $report;

    public function gen(\App\Model\User $user, \App\Model\Child $child, \App\Model\Amt $amt)
    {
        /**
         * 新增之 AmtReplica 实体, 即问卷
         * 
         * @var \App\Model\AmtReplica
         */
        $replica = new AmtReplica();
        $replica->creater()->associate($user);
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

        //  绑定指向的 Cell
        $replicaCurrentDiagGroup->currentCell()->associate($entryCell);
        $replicaCurrentDiagGroup->save();

        // 更新 replica group 指标
        $replica->currentGroup()->associate($replicaCurrentDiagGroup);
        $replica->save();

        // 新增关联报告实体 AmtAlsRpt
        $report = new AmtAlsRpt();
        $report->owner()->associate($user);
        $report->replica()->associate($replica);
        $report->save();

        // AmtReplica 同时也绑定 AmtAlsRpt, 形成 One To One replation ship
        $replica->reportBelong()->associate($report);
        $replica->save();

        $this
            ->setReplica($replica)
            ->setEntryCell($entryCell)
            ->setReport($report)
        ;        
    }

    /**
     * Gets the value of replica.
     *
     * @return mixed
     */
    public function getReplica()
    {
        return $this->replica;
    }

    /**
     * Sets the value of replica.
     *
     * @param mixed $replica the replica
     *
     * @return self
     */
    protected function setReplica($replica)
    {
        $this->replica = $replica;

        return $this;
    }

    /**
     * Gets the value of entryCell.
     *
     * @return mixed
     */
    public function getEntryCell()
    {
        return $this->entryCell;
    }

    /**
     * Sets the value of entryCell.
     *
     * @param mixed $entryCell the entry cell
     *
     * @return self
     */
    protected function setEntryCell($entryCell)
    {
        $this->entryCell = $entryCell;

        return $this;
    }

    /**
     * Gets the value of report.
     *
     * @return mixed
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Sets the value of report.
     *
     * @param mixed $report the report
     *
     * @return self
     */
    protected function setReport($report)
    {
        $this->report = $report;

        return $this;
    }
}