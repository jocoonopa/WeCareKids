<?php

namespace App\Providers;

use App\Model\Observers\OrganizationObserver;
use App\Model\Organization;
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
        Organization::observe(OrganizationObserver::class);
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
