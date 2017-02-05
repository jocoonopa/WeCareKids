<?php

namespace App\Policies;

use App\Model\AlsRptIbCxt;
use App\Model\Organization;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlsRptIbCxtPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuper()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the cxt.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\AlsRptIbCxt  $cxt
     * @return mixed
     */
    public function view(User $user, AlsRptIbCxt $cxt)
    {
        return $this->isAllowToAccess($user, $cxt);
    }

    /**
     * Determine whether the user can update the cxt.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\AlsRptIbCxt  $cxt
     * @return mixed
     */
    public function update(User $user, AlsRptIbCxt $cxt)
    {
        return $this->isAllowToAccess($user, $cxt);
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user){}

    /**
     * Determine whether the user can delete the cxt.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\AlsRptIbCxt  $cxt
     * @return mixed
     */
    public function delete(User $user, AlsRptIbCxt $cxt)
    {
        return $this->isAllowToAccess($user, $cxt) && !$cxt->isMapped() && $user->isSuper();
    }

    /** 
     * Is the passed user is allowed to access the cxt.
     * 
     * @param  \App\Model\User  $user
     * @param  \App\Model\AlsRptIbCxt  $cxt
     * @return boolean          
     */
    protected function isAllowToAccess(User $user, AlsRptIbCxt $cxt)
    {
        return $cxt->channel->id === $user->channels->first()->id;
    }
}
