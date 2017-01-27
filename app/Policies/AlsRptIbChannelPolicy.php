<?php

namespace App\Policies;

use App\Model\User;
use App\Model\AlsRptIbChannel;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlsRptIbChannelPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the alsRptIbChannel.
     *
     * @param  \App\User  $user
     * @param  \App\AlsRptIbChannel  $alsRptIbChannel
     * @return mixed
     */
    public function update(User $user, AlsRptIbChannel $alsRptIbChannel)
    {
        return $user->isSuper() || $user->isOwner() || $user->id === $alsRptIbChannel->creater_id;
    }
}
