<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Model\AmtAlsRpt::observe(\App\Observers\AmtAlsRptObserver::class);
        \App\Model\Organization::observe(\App\Observers\OrganizationObserver::class);
        \App\Model\WckUsageRecord::observe(\App\Observers\WckUsageRecordObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ('local' === env('APP_ENV') && true === env('APP_DEBUG')) {
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        }
    }
}
