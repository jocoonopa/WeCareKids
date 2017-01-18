<?php

namespace App\Model\Observers;

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
        $owner = $organization->owner;
        $contacter = $organization->contacter;

        if (!is_null($owner) && is_null($owner->organization)) {
            $owner->organization()->associate($organization);
            $owner->save();
        }

        if (!is_null($contacter) && $contacter->id !== (is_null($owner) ? null : $owner->id) && is_null($contacter->organization)) {
            $contacter->organization()->associate($organization);
            $contacter->save();
        }
    }
}