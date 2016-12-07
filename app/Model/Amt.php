<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Amt extends Model
{
    public function groups()
    {
        return $this->hasMany('App\Model\AmtDiagGroup', 'amt_id', 'id');
    }
}
