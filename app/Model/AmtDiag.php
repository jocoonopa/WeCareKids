<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtDiag extends Model
{
    public function group()
    {
        return $this->belongsTo('App\Model\AmtDiagGroup');
    }
}
