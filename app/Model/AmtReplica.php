<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Log;

class AmtReplica extends Model
{
    const STATUS_ORIGIN_ID = 0;
    const STATUS_DONE_ID = 2;

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

    public function report()
    {
        return $this->belongsTo('App\Model\AmtAlsRpt', 'report_id', 'id');
    }

    public function scopeFindPendingDiagGroups($query)
    {
        return $this->groups()->where('status', static::STATUS_ORIGIN_ID);
    }

    public function scopeFindGroupsByCategory($query, AmtCategory $category)
    {
        return $this->groups()
            ->leftJoin('amt_diag_groups', 'amt_replica_diag_groups.group_id', '=', 'amt_diag_groups.id')
            ->where('amt_diag_groups.category_id', $category->id)
        ;
    }

    /**
     * 根據傳入的 AmtCategory, 取得此 AmtReplica 所有關聯隸屬的 AmtReplicaDiagGroup,
     * 加總 group 的 level後返回平均 level
     *
     * @param \App\Model\AmtCategory $category
     * @return integer
     */
    public function getLevelByCategory(AmtCategory $category)
    {
        $count = 0;

        /**
         * 計算的 level
         * 
         * @var integer
         */
        $level = 0;

        /**
         * 存放取得的最末 AmtCategory 
         * 
         * @var array [\App\Model\AmtCategory]
         */
        $finals = [];

        // 找尋並儲存最末分類
        $category->findFinals($finals);
        
        foreach ($finals as $final) {
            $groups = static::findGroupsByCategory($final)->get();

            $groups->each(function ($group) use (&$level, &$count) {
                $count ++;
                $level += $group->getLevel();
            });
        }
        
        return 0 === $count ? 0 : floor($level/$groups->count());
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
     * 此 AmtReplica 是否已經完成
     * 
     * @return boolean
     */
    public function isDone()
    {
        return $this->status === AmtReplica::STATUS_DONE_ID;
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
}
