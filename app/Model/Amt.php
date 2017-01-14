<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Amt extends Model
{
    const DEFAULT_AMT_ID = 2;
    
    /**
     * The amt that belong to the creater.
     */
    public function creater()
    {
        return $this->belongsTo('App\Model\User', 'creater_id', 'id');
    }
    
    public function groups()
    {
        return $this->hasMany('App\Model\AmtDiagGroup', 'amt_id', 'id');
    }

    public function diags()
    {
        return $this->hasManyThrough('App\Model\AmtDiag', 'App\Model\AmtDiagGroup', 'amt_id', 'group_id', 'id');
    }

    public function cells()
    {
        return $this->hasManyThrough('App\Model\AmtCell', 'App\Model\AmtDiagGroup', 'amt_id', 'group_id', 'id');
    }
}
