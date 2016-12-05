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
        //
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
