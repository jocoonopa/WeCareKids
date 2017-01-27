<?php

namespace App\Policies;

use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WckUsageRecordPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create WckUsageRecord.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuper();
    }
}
