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
     * Determine whether the cxt can view the cxt.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\AlsRptIbCxt  $cxt
     * @return mixed
     */
    public function view(User $user, AlsRptIbCxt $cxt)
    {
        return $cxt->status === AlsRptIbCxt::STATUS_HAS_MAP ? false : Organization::isSameOrganization($user, $cxt->report->owner);
    }

    /**
     * Determine whether the cxt can update the cxt.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\AlsRptIbCxt  $cxt
     * @return mixed
     */
    public function update(User $user, AlsRptIbCxt $cxt)
    {
        return $cxt->status === AlsRptIbCxt::STATUS_HAS_MAP ? false : Organization::isSameOrganization($user, $cxt->report->owner);
    }

    /**
     * Determine whether the cxt can create users.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user){}

    /**
     * Determine whether the cxt can delete the cxt.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\AlsRptIbCxt  $cxt
     * @return mixed
     */
    public function delete(User $user, AlsRptIbCxt $cxt)
    {
        return $cxt->status === AlsRptIbCxt::STATUS_HAS_MAP ? false : Organization::isSameOrganization($user, $cxt->report->owner);
    }
}
