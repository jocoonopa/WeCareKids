<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Model\AlsRptIbChannel::class => \App\Policies\AlsRptIbChannelPolicy::class,
        \App\Model\AlsRptIbCxt::class => \App\Policies\AlsRptIbCxtPolicy::class,
        \App\Model\AmtAlsRpt::class => \App\Policies\AmtAlsRptPolicy::class,
        \App\Model\AmtReplica::class => \App\Policies\AmtReplicaPolicy::class,
        \App\Model\Child::class => \App\Policies\ChildPolicy::class,
        \App\Model\Organization::class => \App\Policies\OrganizationPolicy::class,
        \App\Model\WckUsageRecord::class => \App\Policies\WckUsageRecordPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
