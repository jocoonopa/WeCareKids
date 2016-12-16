<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtAlsRpt extends Model
{
    const ABILITY_COMPARE_THREAD_ID = 2;
    const TOTAL_DEEP_STEP = 5;

    protected $table = 'amt_als_rpts';

    protected $fillable = [
        'cxt_id', 
        'replica_id',
        'owner_id'
    ];

    public function owner()
    {
        return $this->belongsTo('App\Model\User', 'owner_id', 'id');
    }

    public function usages()
    {
        return $this->morphMany('App\Model\WckUsageRecord', 'usage');
    }

    public function cxtBelongs()
    {
        return $this->belongsTo('App\Model\AlsRptIbCxt', 'cxt_id', 'id');
    }
    
    public function cxt()
    {
        return $this->hasOne('App\Model\AlsRptIbCxt', 'report_id', 'id');
    }

    public function replica()
    {
        return $this->belongsTo('App\Model\AmtReplica', 'replica_id', 'id');
    }

    public function getUsageDesc()
    {
        return "評估({$this->replica->child->name})";
    }
}
