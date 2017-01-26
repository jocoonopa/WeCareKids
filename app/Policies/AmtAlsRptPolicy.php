<?php

namespace App\Policies;

use App\Model\AmtAlsRpt;
use App\Model\Organization;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AmtAlsRptPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuper()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the report.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\AmtAlsRpt  $report
     * @return mixed
     */
    public function view(User $user, AmtAlsRpt $report)
    {
        return Organization::isSameOrganization($user, $report->owner);
    }

    /**
     * Determine whether the user can update the report.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\AmtAlsRpt  $report
     * @return mixed
     */
    public function update(User $user, AmtAlsRpt $report)
    {
        return Organization::isSameOrganization($user, $report->owner);
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user){}

    /**
     * Determine whether the user can delete the report.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\AmtAlsRpt  $report
     * @return mixed
     */
    public function delete(User $user, AmtAlsRpt $report)
    {
        return Organization::isSameOrganization($user, $report->owner);
    }
}
