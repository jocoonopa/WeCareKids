<?php

namespace App\Policies;

use App\Model\Organization;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Session;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the organizations.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function all(User $user)
    {
        return $user->isSuper();
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
        if (!$organization->isAllowedAccess($user)) {
            Session::flash('error', '您不是系統管理員或加盟商拥有人');

            return false;
        }

        return true;
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
        return $user->isSuper();
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
        if (!$user->isSuper()) {
            return false;
        }

        if (0 < $organization->reports()->count()) {
            Session::flash('error', "{$organization->name} 已经有问卷资料，不可删除!");

            return false;
        }

        return true;
    }
}
