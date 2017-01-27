<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [    
        'created_at',
        'updated_at'
    ];

    protected $guarded = [];

    public function childs()
    {
        return $this->belongsToMany('App\Model\Child');
    }

    public static function _create($name, $mobile, $sex, $email)
    {
       return Guardian::create([
            'name' => $name,
            'mobile' => $mobile,
            'sex' => $sex,
            'email' => $email
        ]);
    }
}
