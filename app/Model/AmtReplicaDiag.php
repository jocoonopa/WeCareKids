<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Wck;

class AmtReplicaDiag extends Model
{    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function standard()
    {
        return $this->belongsTo('App\Model\AmtDiagStandard', 'standard_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo('App\Model\AmtReplicaDiagGroup', 'group_id', 'id');
    }

    public function diag()
    {
        return $this->belongsTo('App\Model\AmtDiag', 'diag_id', 'id');
    }

    /**
     * 此段程式碼非常非常容易發生錯誤, 關聯的 table 相當多, 修改前請務必再三檢查
     *
     * 可用 DB::enableQueryLog(); dd(DB::getQueryLog()); 確認 query 是否正確
     * 
     * @param  AmtReplicaDiagGroup $replicaGroup
     * @return \Illuminate\Database\Query\Builder                           
     */
    public function scopeFindFreshDiags($query, AmtReplicaDiagGroup $replicaGroup)
    {
        if (is_null($replicaGroup->currentCell)) {
            return NULL;
        }

        /**
         * Chief cell
         * 
         * @var \App\Model\AmtCell
         */
        $leagueCell = $replicaGroup->currentCell->getChief();

        /**
         * Chief cell 包含的 AmtDiagStandard
         * 
         * @var \App\Model\AmtDiagStandard
         */
        $standards = $leagueCell->standards;

        if (is_null($standards)) {
            return NULL;
        }

        /*
        |--------------------------------------------------------------------------
        | 關聯說明
        |--------------------------------------------------------------------------
        | 
        | # Join
        | AmtReplicaDiag 找到其所隸屬的 AmtDiag,
        | AmtDiag 找到其所包含的 AmtDiagStandard,
        | AmtReplicaDiag 找到其所隸屬的 AmtReplicaDiagGroup,  
        | AmtDiagStandard 透過 cells_standards 與 AmtCell 連結,
        | 
        | # Where
        | - 指定 AmtReplicaDiagGroup, 可以讓我們找到包含的 AmtReplicaDiag 和 對應的 AmtDiagGroup
        | - AmtDiagGroup 確定後再來需要確定是在哪個 AmtCell, 因此指定 AmtCell
        | 
         */
        return $query
            ->select('amt_replica_diags.*')
            ->leftJoin('amt_diags', 'amt_replica_diags.diag_id', '=', 'amt_diags.id')
            ->leftJoin('amt_diag_standards', 'amt_diag_standards.diag_id', '=', 'amt_diags.id')
            ->leftJoin('amt_replica_diag_groups', 'amt_replica_diag_groups.id', '=', 'amt_replica_diags.group_id')
            ->leftJoin('cells_standards', 'cells_standards.standard_id', '=', 'amt_diag_standards.id')
            ->leftJoin('amt_cells', 'amt_cells.id', '=', 'cells_standards.cell_id')
            ->where('amt_replica_diag_groups.replica_id', $replicaGroup->replica->id)
            ->where('amt_cells.id', $leagueCell->id)
            ->groupBy('amt_replica_diags.id')
        ;
    }

    public function getUTF8value()
    {
        return Wck::convertJson2Utf8($this->value);
    }
}
