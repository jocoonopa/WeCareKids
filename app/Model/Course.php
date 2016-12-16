<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    const COURSE_ONEBYONE = 5;
    const COURSE_AGILE = 3;
    const COURSE_MUSCLE = 2;
    const COURSE_BRAND = 1;
    const COURSE_PLAN = 4;

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
}
