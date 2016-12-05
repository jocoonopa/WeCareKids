<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Child extends Model
{
    protected $table = 'childs';

    protected $fillable = ['sex', 'name', 'birthday', 'school_name'];

    public static $levelMap = [
        [0, 213], //0
        [214, 274],//1
        [275, 366],//2
        [367, 549],//3
        [550, 732],//4
        [733, 915],//5
        [916, 1098],//6
        [1099, 1281],//7
        [1282, 1464],//8
        [1465, 1647],//9
        [1648, 1830],//10
        [1831, 2013],//11
        [2014, 2196],//12
        [2197, 2379],//13
        [2380, 2562],//14
        [2563, 2928],//15
        [2929, 3294],//16
        [3295, 3660],//17
        [3661, 4026],//18
        [4027, 4392],//19
        [4392, 4758]//20
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'birthday',
        'created_at',
        'updated_at'
    ];

    public function replicas()
    {
        return $this->hasMany('App\Model\AmtReplica', 'replica_id', 'id');
    }

    public function getLevel(Carbon $dateTime)
    {
        $dayCounts = $this->birthday->diffInDays($dateTime);

        foreach (static::$levelMap as $level => $range) {
            if ($range[0] <= $dayCounts && $range[1] >= $dayCounts) {
                return $level;
            }
        }
    }
}
