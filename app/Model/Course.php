<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function replicas()
    {
        return $this->belongsToMany('App\Model\AmtReplica', 'courses_replicas', 'id', 'replica_id');
    }

    public function getSomething()
    {

    }
}
