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
     * Determine whether the user can view the organization.
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
     * Determine whether the user can update the organization.
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
     * Determine whether the user can create users.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isSuper();
    }

    /**
     * Determine whether the user can delete the organization.
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
