<?php

namespace App\Policies;

use App\Model\User;
use App\Model\AlsRptIbCxt;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlsRptIbCxtPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the alsRptIbCxt.
     *
     * @param  \App\User  $user
     * @param  \App\AlsRptIbCxt  $alsRptIbCxt
     * @return mixed
     */
    public function view(User $user, AlsRptIbCxt $alsRptIbCxt)
    {
    }

    /**
     * Determine whether the user can create alsRptIbCxts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        
    }

    /**
     * Determine whether the user can update the alsRptIbCxt.
     *
     * @param  \App\User  $user
     * @param  \App\AlsRptIbCxt  $alsRptIbCxt
     * @return mixed
     */
    public function update(User $user, AlsRptIbCxt $alsRptIbCxt)
    {
        //
    }

    /**
     * Determine whether the user can delete the alsRptIbCxt.
     *
     * @param  \App\User  $user
     * @param  \App\AlsRptIbCxt  $alsRptIbCxt
     * @return mixed
     */
    public function delete(User $user, AlsRptIbCxt $alsRptIbCxt)
    {
        //
    }
}
