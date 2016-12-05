<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtReplica extends Model
{
    const STATUS_ORIGIN_ID = 0;
    const STATUS_DONE_ID = 2;
    const STATUS_SKIP_ID = 10;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function child()
    {
        return $this->belongsTo('App\Model\Child');
    }

    public function currentGroup()
    {
        return $this->belongsTo('App\Model\AmtReplicaDiagGroup', 'current_group_id', 'id');
    }

    public function groups()
    {
        return $this->hasMany('App\Model\AmtReplicaDiagGroup', 'replica_id', 'id');
    }

    public function log()
    {
        return $this->hasOne('App\Model\AmtReplicaLog', 'replica_id', 'id');
    }

    public function scopeFindPendingDiagGroups($query)
    {
        return $this->groups()->where('status', static::STATUS_ORIGIN_ID);
    }

    public function calculateCurrentGroupLevel()
    {
        $validReplicaDiags = [];
        $invalidReplicaDiags = [];
        $maxLevel = AmtDiagStandard::MIN_LEVEL;
        $upperLimit = NULL;
        
        // 收集通過和未通過的 diags
        foreach ($this->currentGroup->diags()->whereNotNull('value')->get() as $replicaDiag) {
            // 暫存通過和未通過的 diags
            if ($replicaDiag->isInvalidDiag()) {
                $invalidReplicaDiags[] = $replicaDiag;
            } else {
                $validReplicaDiags[] = $replicaDiag;
            }
        }

        // iterate 未通過的 diags, 找出上限
        foreach ($invalidReplicaDiags as $invalidDiag) {
            if ($upperLimit > $invalidDiag->min_level - 1) {
                $step = $invalidDiag->standard->step;

                $upperLimit = (0 === $step) ? $invalidDiag->min_level - 1 : $invalidDiag->min_level - $step;
            }

            $invalidDiag->update(['level' => $upperLimit]);
        }

        // iterate 通過的 diags, 和上限取交集作為測定之level值
        foreach ($validReplicaDiags as $validDiag) {
            if (!is_null($upperLimit) && 
                ($upperLimit < $validDiag->getMaxLevel() || $maxLevel > $validDiag->getMaxLevel())
            ) {
                continue;
            }
            $maxLevel = $validDiag->getMaxLevel();

            $validDiag->update(['level' => $maxLevel]);
        }

        // 更新目前作答 group 之 level
        $this->currentGroup->update([
            'level' => $maxLevel < $upperLimit ? $upperLimit : $maxLevel,
            'status' => AmtReplicaDiagGroup::STATUS_DONE_ID
        ]);

        return $this;
    }

    public function swtichGroup(AmtReplicaDiagGroup $group = NULL)
    {
        if (is_null($group)) {
            $group = $this->findPendingDiagGroups()->first();
        }

        if (!is_null($group)) {
            $this->update(['current_group_id' => $group->id]);

            $group->update(['level' => $this->child->getLevel($this->created_at)]);
        } else {
            $this->update(['current_group_id' => NULL]);
        }

        return $this;
    }

    public function isDone()
    {
        return $this->status === AmtReplica::STATUS_DONE_ID;
    }
}
