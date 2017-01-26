<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * @param  \App\Model\User  $srcUser
     * @return mixed
     */
    public function view(User $user, User $srcUser)
    {
        return $this->isAllowedToView($user, $srcUser);
    }

    /**
     * Determine whether the user can update the organization.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\User  $srcUser
     * @return mixed
     */
    public function update(User $user, User $srcUser)
    {
        return $this->isAllowedToEdit($user, $srcUser);
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isOwner();
    }

    /**
     * Determine whether the user can delete the organization.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\User  $srcUser
     * @return mixed
     */
    public function delete(User $user, User $srcUser)
    {
        return false;
    }

    protected function isAllowedToView(User $user, User $srcUser)
    {
        return $user->organization->id === $srcUser->organization->id;
    }

    protected function isAllowedToEdit(User $user, User $srcUser)
    {
        if ($user->isOwner()) {
            return $user->organization->id === $srcUser->organization->id;
        }

        return $user->id === $srcUser->id;
    }
}
