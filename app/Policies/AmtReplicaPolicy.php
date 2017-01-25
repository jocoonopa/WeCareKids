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
     * Determine whether the replica can view the replica.
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
     * Determine whether the replica can update the replica.
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
     * Determine whether the replica can create users.
     *
     * @param  \App\Model\User  $user
     * @return mixed
     */
    public function create(User $user){}

    /**
     * Determine whether the replica can delete the replica.
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
