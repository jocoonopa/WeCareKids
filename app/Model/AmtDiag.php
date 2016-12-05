<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtDiag extends Model
{
    const TYPE_SWITCH_ID = 0;
    const TYPE_CHECKBOX_ID = 1;
    const TYPE_SLIDER_ID = 2;
    const TYPE_RADIO_ID = 3;

    public function group()
    {
        return $this->belongsTo('App\Model\AmtDiagGroup');
    }

    public function standards()
    {
        return $this->hasMany('App\Model\AmtDiagStandard', 'diag_id', 'id');
    }

    public function scopeFindMatchStandards($query, $level, $isDefaultLevel = false)
    {
        return $this->standards()
            ->where('min_level', '<=', $level + (true === $isDefaultLevel ? 0 : 1) )
            ->where('max_level', '>=', $level)
        ;
    }
}
