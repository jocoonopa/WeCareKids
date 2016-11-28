<?php

namespace App\Providers;

use App\Model\AlsRptIbChannel;
use App\Model\AlsRptIbCxt;
use App\Policies\AlsRptIbChannelPolicy;
use App\Policies\AlsRptIbCxtPolicy;
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
        AlsRptIbChannel::class => AlsRptIbChannelPolicy::class
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
