<?php

namespace App\Policies;

use App\Model\Organization;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuper()) {
            return true;
        }
    }

    /**
     * Determine whether the replica can view the replica.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Organization  $organization
     * @return mixed
     */
    public function view(User $user, Organization $organization)
    {
        return $organization->isAllowedAccess($user);
    }

    /**
     * Determine whether the replica can update the replica.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Organization  $organization
     * @return mixed
     */
    public function update(User $user, Organization $organization)
    {
        return $organization->isAllowedAccess($user);
    }

    /**
     * Determine whether the replica can create users.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuper();
    }

    /**
     * Determine whether the replica can delete the replica.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Organization  $organization
     * @return mixed
     */
    public function delete(User $user, Organization $organization)
    {
        return false;
    }
}
