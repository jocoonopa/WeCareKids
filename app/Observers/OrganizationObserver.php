<?php

namespace App\Observers;

use App\Model\Organization;

class OrganizationObserver
{
    public function created(Organization $organization)
    {
        $organization->account = $organization->genAccount();
        $organization->save();
    }

    public function saved(Organization $organization)
    {
        /*
        |--------------------------------------------------------------------------
        | 歸屬使用者群組
        |--------------------------------------------------------------------------
        | owner 或是 contacter, 若尚未屬於任何 Organization,
        | 則自動歸屬到 Organization
        |
        */
       
       /**
        * 組織的擁有人
        * 
        * @var \App\Model\User||NULL
        */
        $owner = $organization->owner;

        /**
         * 組織的聯絡人
         * 
         * @var \App\Model\User||NULL
         */
        $contacter = $organization->contacter;

        // 若存在擁有人且擁有人沒有歸屬組織，將其關聯到組織
        if (!is_null($owner) && is_null($owner->organization)) {
            $owner->organization()->associate($organization);
            $owner->save();
        }

        // 若存在聯絡人且聯絡人沒有歸屬組織，將其關聯到組織
        if (
            !is_null($contacter) 
            && $contacter->id !== (is_null($owner) ? null : $owner->id) 
            && is_null($contacter->organization)
        ) {
            $contacter->organization()->associate($organization);
            $contacter->save();
        }
    }
}