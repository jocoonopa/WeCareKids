<?php

namespace App\Policies;

use App\Model\User;
use App\Model\AlsRptIbChannel;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlsRptIbChannelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the alsRptIbChannel.
     *
     * @param  \App\User  $user
     * @param  \App\AlsRptIbChannel  $alsRptIbChannel
     * @return mixed
     */
    public function view(User $user, AlsRptIbChannel $alsRptIbChannel)
    {
        return $user->id === $alsRptIbChannel->creater_id;
    }

    /**
     * Determine whether the user can allow access the alsRptIbChannel.
     *
     * @param  \App\AlsRptIbChannel  $alsRptIbChannel
     * @return mixed
     */
    public function allow(User $user, AlsRptIbChannel $alsRptIbChannel)
    {
        return true === $alsRptIbChannel->is_open;
    }

    /**
     * Determine whether the user can create alsRptIbChannels.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the alsRptIbChannel.
     *
     * @param  \App\User  $user
     * @param  \App\AlsRptIbChannel  $alsRptIbChannel
     * @return mixed
     */
    public function update(User $user, AlsRptIbChannel $alsRptIbChannel)
    {
        //
    }

    /**
     * Determine whether the user can delete the alsRptIbChannel.
     *
     * @param  \App\User  $user
     * @param  \App\AlsRptIbChannel  $alsRptIbChannel
     * @return mixed
     */
    public function delete(User $user, AlsRptIbChannel $alsRptIbChannel)
    {
        //
    }
}
