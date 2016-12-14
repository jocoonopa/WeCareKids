<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    public function users()
    {
        return $this->hasMany('App\Model\User');
    }

    public function childs()
    {
        return $this->hasMany('App\Model\Child');
    }

    public function usages()
    {
        return $this->hasMany('App\Model\WckUsageRecord', 'organization_id', 'id');
    }

    public function contacter()
    {
        return $this->belongsTo('App\Model\Organization', 'contacter_id', 'id');
    }
}
