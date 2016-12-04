<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AmtDiagGroup extends Model
{
    public function diags()
    {
        return $this->hasMany('App\Model\AmtDiag', 'group_id', 'id');
    }

    public function scopeFindValid($query)
    {
        return $query->where('id', '<', 20);
    }
}
