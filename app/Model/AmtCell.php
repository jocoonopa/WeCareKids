<?php

namespace App\Model;

use AmtCell as AC;
use App\Model\AmtDiag;
use App\Model\Child;
use DB;
use Illuminate\Database\Eloquent\Model;

class AmtCell extends Model
{
    public static $threadMap = ['l' => -1, 'e' => 0, 'h' => 1];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The standards that belong to the cell
     */
    public function standards()
    {
        return $this->belongsToMany('App\Model\AmtDiagStandard', 'cells_standards', 'cell_id', 'standard_id');
    }

    /**
     * The group that cell belong to 
     */
    public function group()
    {
        return $this->belonsTo('App\Model\AmtDiagGroup', 'group_id', 'id');
    }

    /**
     * The nextCell that cell belong to 
     */
    public function next()
    {
        return $this->belongsTo('App\Model\AmtCell', 'next_id', 'id');
    }

    /**
     * The prevCell that cell belong to 
     */
    public function prev()
    {
        return $this->belongsTo('App\Model\AmtCell', 'prev_id', 'id');
    }

    /**
     * The leagueCell that cell belong to 
     */
    public function league()
    {
        return $this->belongsTo('App\Model\AmtCell', 'league_id', 'id');
    }

    public function currentRpaGroups()
    {
        return $this->hasMany('App\Model\AmtReplicaDiagGroup', 'current_cell_id', 'id');
    }

    public function resultRpaGroups()
    {
        return $this->hasMany('App\Model\AmtReplicaDiagGroup', 'result_cell_id', 'id');
    }

    /**
     * 可讓 AmtReplicaDiag::isPass 呼叫, 用來尋找 AmtReplicaDiag 在該 AmtCell 中對應到的
     * AmtDiagStandard
     * 
     * @param  AmtReplicaDiag $replicaDiag
     * @return \App\Model\AmtDiagStandard
     */
    public function getDiagMapStandard(AmtReplicaDiag $replicaDiag)
    {
        return $this->standards()->get()->first(function ($standard) use ($replicaDiag) {
            return $standard->diag_id === $replicaDiag->diag->id;
        });
    }

    /**
     * 最後跑分時會用到.
     *
     * 根據小朋友年齡整合 League 回傳對應之 AmtCell
     * 
     * @param  \App\Model\AmtReplica $replica
     * @return \App\Model\AmtCell
     */
    public function findMatchedByReplica(AmtReplica $replica)
    {
        if (is_null($this->league)) {
            return $this;
        }

        return $this->league()->first(function ($cell) use ($replica) {
            return $cell->level === $replica->getLevel();
        }); 
    }

    /**
     * 取得 league 中的實質 cell
     * 
     * @return \App\Model\AmtCell
     */
    public function getChief()
    {
        return is_null($this->league) ? $this : $this->league;
    }

    /**
     * 找到 league 中 Level 最高的
     * 
     * @return \App\Model\AmtCell
     */
    public function findHighest()
    {
        if (is_null($this->league)) {
            return $this;
        }

        return AmtCell::where('league_id', $this->league_id)->orderBy('level', 'desc')->first();
    }

    /**
     * 找到 league 中 Level 最低的
     * 
     * @return \App\Model\AmtCell
     */
    public function findLowest()
    {
        if (is_null($this->league)) {
            return $this;
        }

        return AmtCell::where('league_id', $this->league_id)->orderBy('level', 'asc')->first();
    }

    /**
     * League 是否沒有包含任何 standard
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return 0 === $this->getChief()->standards()->count();
    }

    /**
     * AmtReplicaDiagGroup 在該 Cell 中對應到的 AmtReplicaDiags,
     * 和 Cell 原本所包含的 AmtDiagStandards 配對驗證,
     * 搭配 Cell 定義的檢查邏輯, 最後回傳 boolean
     * 
     * @param  AmtReplicaDiagGroup $replicaGroup [description]
     * @return boolean                       [description]
     */
    public function isPass(AmtReplicaDiagGroup $replicaGroup)
    {
        $replicaDiags = static::findDoneDiags($replicaGroup);
        
        $statementWouldBeExecuted = AC::setStr($this->statement)->convertToStatment();

        return eval($statementWouldBeExecuted);
    }

    /**
     * 回傳閾值題最後應該呈現的 level
     * 
     * @param  \App\Mpdel\AmtReplicaDiagGroup $replicaGroup
     * @return integer                           
     */
    public function getThreadResultLevel(AmtReplicaDiagGroup $replicaGroup)
    {
        $replicaDiag = static::findDoneDiags($replicaGroup)->first(function ($replicaDiag) {
            return $replicaDiag->diag->type === AmtDiag::TYPE_THREAD_ID;
        });
        
        $child = $replicaGroup->replica->child;

        return $replicaGroup->replica->getLevel() + (array_get(static::$threadMap, $replicaDiag->value) * $this->step);
    }

    /**
     * 尋找此 Cell 需要回答且尚未回答的 diags
     * 
     * @param  AmtReplicaDiagGroup $replicaGroup
     * @return Collection [\App\Model\AmtReplicaDiag]
     */
    public static function findFreshDiags(AmtReplicaDiagGroup $replicaGroup) 
    {
        $query = static::genFindDiagsQuery($replicaGroup);

        if (is_null($query)) {
            return NULL;
        }
        
        return $query->whereNull('amt_replica_diags.value')->get();
    }

    /**
     * 尋找此 Cell 需要回答且已經回答的 diags
     * 
     * @param  AmtReplicaDiagGroup $replicaGroup
     * @return Collection [\App\Model\AmtReplicaDiag]
     */
    public static function findDoneDiags(AmtReplicaDiagGroup $replicaGroup)
    {
        $query = static::genFindDiagsQuery($replicaGroup);
        
        if (is_null($query)) {
            return NULL;
        }

        return $query->whereNotNull('amt_replica_diags.value')->get();
    }

    /**
     * 確認有無為作答的 AmtReplicaDiagGroup
     * 
     * @param  AmtReplicaDiagGroup $replicaGroup [description]
     * @return boolean                       [description]
     */
    public static function hasFreshDiag(AmtReplicaDiagGroup $replicaGroup)
    {
        $query = static::findFreshDiags($replicaGroup);
        
        if (is_null($query)) {
            return NULL;
        }

        return 0 < static::findFreshDiags($replicaGroup)->count();
    }

    protected static function genFindDiagsQuery(AmtReplicaDiagGroup $replicaGroup)
    {
        if (is_null($replicaGroup->currentCell)) {
            return NULL;
        }

        $leagueCell = $replicaGroup->currentCell->getChief();

        $standards = $leagueCell->standards;

        if (is_null($standards)) {
            return NULL;
        }

        return DB::table('amt_replica_diags')
            ->leftJoin('amt_diags', 'amt_replica_diags.diag_id', '=', 'amt_diags.id')
            ->leftJoin('amt_diag_standards', 'amt_diag_standards.diag_id', '=', 'amt_diags.id')
            ->leftJoin('amt_replica_diag_groups', 'amt_replica_diag_groups.group_id', '=', 'amt_diag_standards.id')
            ->leftJoin('cells_standards', 'cells_standards.cell_id', '=', 'amt_diag_standards.id')
            ->leftJoin('amt_cells', 'amt_cells.id', '=', 'cells_standards.cell_id')
            ->where('amt_replica_diags.group_id', $replicaGroup->id)
            ->where('amt_cells.id', $leagueCell->id)
        ;
    }
}
