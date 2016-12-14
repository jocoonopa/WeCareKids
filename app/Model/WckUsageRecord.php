<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WckUsageRecord extends Model
{
    public function usable()
    {
        return $this->morphTo();
    }

    public function organization()
    {
        return $this->belongsTo('App\Model\Organization', 'organization_id', 'id');
    }
}
