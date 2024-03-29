<?php

namespace App\Model;

use App\Model\Organization;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_super' => 'boolean',
        'created_at' => 'date'
    ];

    protected $dates = ['deleted_at'];

    public static function _create(array $data)
    {
        $user = User::create([
            'name' => trim(array_get($data, 'name')),
            'email' => trim(array_get($data, 'email')),
            'phone' => trim(array_get($data, 'phone')),
            'password' => bcrypt(\Carbon\Carbon::now()->format('Ymd')),
            'remember_token' => str_random(10)
        ]);

        $user->organization()->associate(Organization::find(array_get($data, 'organization_id')));
        $user->save();

        return $user;
    }

    /**
     * The childs that belong to the user.
     */
    public function childs()
    {
        return $this->belongsToMany('App\Model\Child');
    }

    public function usages()
    {
        return $this->hasMany('App\Model\WckUsageRecord');
    }

    public function organization()
    {
        return $this->belongsTo('App\Model\Organization');
    }

    public function cxts()
    {
        return $this->hasManyThrough('App\Model\AlsRptIbCxt', 'App\Model\AlsRptIbChannel', 'creater_id', 'channel_id', 'id');
    }

    public function channels()
    {
        return $this->hasMany('App\Model\AlsRptIbChannel', 'creater_id', 'id');
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
        return $this->hasMany('App\Model\AmtReplica', 'creater_id', 'id');
    }

    public function getOwnChannel()
    {
        return $this->channels()->first();
    }

    public function createdOrgs()
    {
        return $this->hasMany('App\Model\Organization', 'creater_id', 'id');
    }

    public function contactedOrgs()
    {
        return $this->hasMany('App\Model\Organization', 'contacter_id', 'id');
    }

    public function ownedOrgs()
    {
        return $this->hasMany('App\Model\Organization', 'owner_id', 'id');
    }

    /**
     * 尋找可以用來作為傳入之 Organization 之擁有者或聯絡人的 User
     * (屬於此 Organization 或是 尚未歸屬任何 Organization)
     * 
     * @param  $query
     * @param  Organization $organization
     * @return $query
     */
    public function scopeFindOrgOptions($query, Organization $organization)
    {
        return $query
            ->where('organization_id', '=', $organization->id)
            ->orWhere('organization_id', '=', NULL)
        ;
    }

    public function scopeFetchUsersInValidScope($query, User $user)
    {
        $organizationId = is_null($user->organization) ? NULL : $user->organization->id;
        
        if ($user->isSuper()) {
            return $query->withTrashed()->latest();
        }

        if ($user->isOwner()) {
            return $query->where('organization_id', $organizationId)->withTrashed()->latest();
        }

        return $query->where('organization_id', $organizationId)->latest();
    }

    /**
     * 是否為系統管理員
     * 
     * @return boolean
     */
    public function isSuper()
    {
        return $this->is_super;
    }

    /**
     * 是否為組織擁有者
     * 
     * @return boolean
     */
    public function isOwner()
    {
        if (is_null($this->organization)) {
            return false;
        }

        return $this->organization->owner_id === $this->id;
    }

    /**
     * 取得權限名稱
     * 
     * @return string
     */
    public function getJobTitle()
    {
        if ($this->isSuper()) {
            return '系统管理员';
        }

        return $this->isOwner() ? '拥有人' : '教师';
    }
}
