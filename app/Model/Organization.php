<?php

namespace App\Model;

use App\Model\Child;
use App\Model\User;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    const INIT_BENEFIT = 30000;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public static function _create(User $owner = NULL, User $contacter = NULL, User $creater, $name)
    {
        $organization = new Organization;

        $organization->name = $name;
        $organization->points = static::INIT_BENEFIT;
        $organization->owner()->associate($owner);
        $organization->contacter()->associate($contacter);
        $organization->creater()->associate($creater);
        $organization->save();

        return $organization;
    }

    /**
     * 判斷使用者和小孩是否屬於同一個組織
     * 
     * @param  \App\Model\User||\App\Model\Child   $user1  教師或是組織擁有者或是小孩
     * @param  \App\Model\User||\App\Model\Child   $user2  教師或是組織擁有者或是小孩
     * @return boolean       
     */
    public static function isSameOrganization($user1, $user2)
    {
        if (is_null($user2->organization) || is_null($user1->organization)) {
            return false;
        }

        return $user1->organization->id === $user2->organization->id;
    }
    
    public function users()
    {
        return $this->hasMany('App\Model\User');
    }

    public function childs()
    {
        return $this->hasMany('App\Model\Child');
    }

    public function reports()
    {
        return $this->hasManyThrough(
            'App\Model\AmtAlsRpt', 'App\Model\User',
            'organization_id', 'owner_id', 'id'
        );
    }

    public function usages()
    {
        return $this->hasMany('App\Model\WckUsageRecord', 'organization_id', 'id');
    }

    public function contacter()
    {
        return $this->belongsTo('App\Model\User', 'contacter_id', 'id');
    }

    public function creater()
    {
        return $this->belongsTo('App\Model\User', 'creater_id', 'id');
    }

    public function owner()
    {
        return $this->belongsTo('App\Model\User', 'owner_id', 'id');
    }

    /**
     * 判斷使用者是否為該 Organization 的擁有者
     * 
     * @param  \App\Model\User    $user 
     * @return boolean      
     */
    public function isOwner(User $user)
    {
        if (is_null($this->owner)) {
            return false;
        }
        
        return $user->id === $this->owner->id;
    }

    /**
     * 判斷該使用者是否為 Organization 的聯絡人
     * 
     * @param  \App\Model\User    $user
     * @return boolean      
     */
    public function isContacter(User $user)
    {
        if (is_null($this->contacter)) {
            return false;
        }
        
        return $user->id === $this->contacter->id;
    }

    /**
     * 判斷使用者是否可以訪問該 Organization
     * 
     * @param  \App\Model\User    $user
     * @return boolean     
     */
    public function isAllowedAccess(User $user)
    {
        return !(!$user->isSuper() && !$this->isOwner($user));
    }

    /**
     * 新增建立 Organization 後，根據 id 產生 account.
     *
     * 此方法會在 Organization 的 Observer 被呼叫
     * 
     * @return string
     */
    public function genAccount()
    {
        return 'W' . str_pad(6, 0, $this->id, STR_PAD_LEFT);
    }
}
