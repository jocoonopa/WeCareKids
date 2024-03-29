<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Collective\Html\Eloquent\FormAccessible;

class Child extends Model
{
    use FormAccessible;

    protected $table = 'childs';

    protected $fillable = ['sex', 'name', 'birthday', 'school_name', 'identifier'];

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

    /**
     * The users that belong to the child.
     */
    public function users()
    {
        return $this->belongsToMany('App\Model\User');
    }

    public function guardians()
    {
        return $this->belongsToMany('App\Model\Guardian')->withPivot('relation');
    }

    public function replicas()
    {
        return $this->hasMany('App\Model\AmtReplica', 'child_id', 'id');
    }

    public function usages()
    {
        return $this->hasMany('App\Model\WckUsageRecord');
    }

    public function organization()
    {
        return $this->belongsTo('App\Model\Organization');
    }

    public function scopeFindChildByOrganizationWithRelated($query, \App\Model\User $user)
    {
        return $user->isSuper() ? 
            $query->with('guardians', 'users', 'replicas', 'replicas.report.cxt') :
            $query->with('guardians', 'users', 'replicas', 'replicas.report.cxt')->where('organization_id', $user->organization->id)
        ;
    }

    /**
     * Get the user's date of birth for forms.
     *
     * @param  string  $value
     * @return string
     */
    public function formBirthdayAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getSex()
    {
        return 0 === (int) $this->sex ? '女' : '男';
    }

    public function getAge()
    {
        return $this->birthday->diffInYears(Carbon::now());
    }

    public static function getYMAge(\DateTime $dateTime)
    {
        $birthday = Carbon::instance($dateTime);

        $diff = Carbon::now()->diff($birthday);
        
        return $diff->format('%y岁%m个月');
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

    public static function getMonthFromMap($level)
    {
        $levels = array_get(static::$levelMap, (int) $level);

        $days = floor(($levels[0] + $levels[1])/2);
        
        return static::getYMAge(with(new \DateTime)->modify("-{$days} days"));
    }

    public static function getYearMonthRangeFromMap($level)
    {
        $levels = array_get(static::$levelMap, (int) $level);

        return [
            static::convertDaysToYearMonth(array_get($levels, 0)),
            static::convertDaysToYearMonth(array_get($levels, 1)),
        ];
    }

    protected static function convertDaysToYearMonth($days)
    {
        $years = floor($days / 365);
        $months = floor(($days % 365)/30);

        return $years > 0 ? "{$years}Y{$months}M" : "{$months}M";
    }
}
