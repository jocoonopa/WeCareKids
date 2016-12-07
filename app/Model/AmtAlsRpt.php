<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtAlsRpt extends Model
{
    protected $table = 'amt_als_rpts';

    protected $fillable = [
        'cxt_id', 
        'replica_id',
        'owner_id'
    ];

    public function owner()
    {
        return $this->belongsTo('App\Model\User', 'report_id', 'id');
    }
    
    public function cxt()
    {
        return $this->hasOne('App\Model\AlsRptIbCxt', 'report_id', 'id');
    }

    public function replica()
    {
        return $this->hasOne('App\Model\AmtReplica', 'report_id', 'id');
    }
}
