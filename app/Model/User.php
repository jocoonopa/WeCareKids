<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable;
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The childs that belong to the user.
     */
    public function childs()
    {
        return $this->belongsToMany('App\Model\Child');
    }

    /**
     * The childs that belong to the user.
     */
    public function reports()
    {
        return $this->hasMany('App\Model\AmtAlsRpt', 'owner_id', 'id');
    }

    public function amts()
    {
        return $this->hasMany('App\Model\Amt', 'creater_id', 'id');
    }

    public function replicas()
    {
        return $this->hasManyThrough('App\Model\AmtReplica', 'App\Model\Amt', 'creater_id', 'amt_id', 'id');
    }
}
