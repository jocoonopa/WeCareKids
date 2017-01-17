<?php

namespace App\Model\Observers;

use App\Model\Organization;

class OrganizationObserver
{
     /**
     * Listen to the User created event.
     *
     * @param  User  $user
     * @return void
     */
    public function created(Organization $organization)
    {
        $organization->account = $organization->genAccount();
        $organization->save();   
    }
}