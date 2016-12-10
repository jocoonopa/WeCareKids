<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Amt extends Model
{
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
}
