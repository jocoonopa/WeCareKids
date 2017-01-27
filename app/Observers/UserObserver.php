<?php

namespace App\Observers;

use App\Model\AlsRptIbChannel;
use App\Model\User;

class UserObserver
{
    public function created(User $user)
    {
        $channel = AlsRptIbChannel::createPrototype($user);
        $channel->save();
    }
}