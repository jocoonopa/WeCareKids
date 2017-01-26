<?php

namespace App\Policies;

use App\Model\AmtReplica;
use App\Model\Organization;
use App\Model\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
     * 組織剩餘點數不足 - WckUsageRecord::COST_PER_REPLICA 時不可新增評測
     * 
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return -(WckUsageRecord::COST_PER_REPLICA) <= $user->organization->point;
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
        return Organization::isSameOrganization($user, $replica->creater);
    }
}
