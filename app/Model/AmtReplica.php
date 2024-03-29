<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Log;

class AmtReplica extends Model
{
    const STATUS_ORIGIN_ID = 0;
    const STATUS_DONE_ID = 2;
    const AGE_THREAD = 3;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 從傳入的 Collection 取得最新未過期的 AmtReplica
     * 
     * @param  \Illuminate\Database\Eloquent\Collection $replicas
     * @return \Illuminate\Database\Eloquent\Collection           
     */
    public static function fetchWithoutExpiredFromCollection(Collection $replicas)
    {
        return $replicas->filter(function ($replica) {
            return !$replica->isExpired();
        });
    }

    /**
     * 從傳入的 Collection 取得擁有對應 Cxt 的 AmtReplica
     * 
     * @param  \Illuminate\Database\Eloquent\Collection $replicas
     * @return \Illuminate\Database\Eloquent\Collection           
     */
    public static function fetchWithCxtFromCollection(Collection $replicas)
    {
        return $replicas->filter(function ($replica) {
            return !is_null($replica->report->cxt);
        });
    }

    public function amt()
    {
        return $this->belongsTo('App\Model\Amt');
    }

    public function creater()
    {
        return $this->belongsTo('App\Model\User', 'creater_id', 'id');
    }

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

    public function report()
    {
        return $this->hasOne('App\Model\AmtAlsRpt', 'replica_id', 'id');
    }

    public function reportBelong()
    {
        return $this->belongsTo('App\Model\AmtAlsRpt', 'report_id', 'id');
    }

    public function courses()
    {
        return $this->hasMany('App\Model\Course', 'courses_replicas', 'id', 'course_id');
    }

    public function findPendingDiagGroups()
    {
        return $this->groups()->where('status', static::STATUS_ORIGIN_ID);
    }

    public function findGroupsByCategory($final)
    {
        return $this->groups()
            ->leftJoin('amt_diag_groups', 'amt_diag_groups.id', '=', 'amt_replica_diag_groups.group_id')
            ->where('category_id', $final->id)
            ->get()
        ;
    }

    /**
     * 將目前的 AmtReplica 設定為終止狀態
     * 
     * @return \App\Model\AmtReplica
     */
    public function finish()
    {
        $this->update(['status' => static::STATUS_DONE_ID]);

        return $this;
    }

    /**
     * 切換目前指向的 AmtReplicaGroup,
     * 同時處理 Entry AmtCell 的問題 
     * 
     * @return \App\Model\AmtReplicaDiagGroup
     */
    public function swtichGroup()
    {
        $groups = $this->findPendingDiagGroups()->get();

        if (0 === $groups->count()) {
            return $this->finish();
        }

        $group = $groups->first();
        $cell = $group->findEntryMapCell();

        //  綁定指向的 Cell
        $group->update(['current_cell_id' => $cell->id]);

        if ($cell->isEmpty()) { 
            $group->skip();

            return $this->swtichGroup();
        }

        if (is_null($group)) {
            return false;
        }

        return $this->update(['current_group_id' => $group->id]);        
    }

    /**
     * 此 AmtReplica 是否為初始狀態(尚未完成)
     * 
     * @return boolean
     */
    public function isOrigin()
    {
        return AmtReplica::STATUS_ORIGIN_ID === $this->status;
    }

    /**
     * 此 AmtReplica 是否已經完成
     * 
     * @return boolean
     */
    public function isDone()
    {
        return AmtReplica::STATUS_DONE_ID === $this->status;
    }

    /**
     * 此 AmtReplica 是否已經過期
     * 
     * @return boolean
     */
    public function isExpired()
    {
        return is_null($this->report->cxt) && \Carbon\Carbon::now()->modify(AlsRptIbCxt::BEFORE_DAYS . ' days')->gt($this->created_at);
    }

    /**
     * Alias of AmtChild::getLevel
     * 
     * @return integer
     */
    public function getLevel()
    {
        return $this->child->getLevel($this->created_at);
    }

    public function getExpiredCountdown()
    {
        return \Carbon\Carbon::now()->modify(AlsRptIbCxt::BEFORE_DAYS . ' days')->diffInDays($this->created_at);
    }
}
