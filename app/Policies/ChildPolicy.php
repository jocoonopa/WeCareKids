<?php

namespace App\Policies;

use App\Model\Child;
use App\Model\User;
use App\Model\Organization;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChildPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuper()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the user.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Child  $child
     * @return mixed
     */
    public function view(User $user, Child $child)
    {
        return Organization::isSameOrganization($user, $child);
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Child  $child
     * @return mixed
     */
    public function update(User $user, Child $child)
    {
        return Organization::isSameOrganization($user, $child);
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user){}

    /**
     * Determine whether the user can delete the user.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\Child  $child
     * @return mixed
     */
    public function delete(User $user, Child $child)
    {
        return Organization::isSameOrganization($user, $child);
    }
}
