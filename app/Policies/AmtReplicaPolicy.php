<?php

namespace App\Policies;

use App\Model\AmtReplica;
use App\Model\Organization;
use App\Model\User;
use App\Model\WckUsageRecord;
use Illuminate\Auth\Access\HandlesAuthorization;
use Session;

class AmtReplicaPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuper()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the replica.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\AmtReplica  $replica
     * @return mixed
     */
    public function view(User $user, AmtReplica $replica)
    {
        return Organization::isSameOrganization($user, $replica->creater);
    }

    /**
     * Determine whether the user can update the replica.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\AmtReplica  $replica
     * @return mixed
     */
    public function update(User $user, AmtReplica $replica)
    {
        return Organization::isSameOrganization($user, $replica->creater);
    }

    /**
     * Determine whether the user can create users.
     *
     * 组织剩余点数不足 - WckUsageRecord::COST_PER_REPLICA 时不可新增评测
     * 
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if (-(WckUsageRecord::COST_PER_REPLICA) > $user->organization->points) {
            Session::flash('error', '组织剩余金额不足，无法新增评测!');

            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete the replica.
     *
     * @param  \App\Model\User  $user
     * @param  \App\Model\AmtReplica  $replica
     * @return mixed
     */
    public function delete(User $user, AmtReplica $replica)
    {
        if (AmtReplica::STATUS_DONE_ID === $replica->status) {
            Session::flash('error', '此评测已经为完成状态，不可删除!');

            return false;
        }

        return Organization::isSameOrganization($user, $replica->creater);
    }
}
