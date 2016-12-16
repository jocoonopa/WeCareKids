<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WckUsageRecord extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function usage()
    {
        return $this->morphTo();
    }

    public function organization()
    {
        return $this->belongsTo('App\Model\Organization', 'organization_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'user_id', 'id');
    }

    public function child()
    {
        return $this->belongsTo('App\Model\Child', 'child_id', 'id');
    }

    public function getVarietyDesc()
    {
        return $this->variety >= 0 ? "<span class=\"label label-success\">+{$this->variety}</span>" : "<span class=\"label label-danger\">{$this->variety}</span>";
    }
}
