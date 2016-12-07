<?php

namespace App\Providers;

use App\Utility\Services\AlsRptService;
use App\Utility\Services\AmtCellService;
use App\Utility\Services\WckService;
use Illuminate\Support\ServiceProvider;

class AlsRptProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('als_rpt', function ($app) {
            return new AlsRptService($app->make('App\Utility\Repositorys\AlsRptRepo'));
        });

        $this->app->singleton('wck', function () {
            return new WckService;
        });

        $this->app->singleton('amt_cell', function () {
            return new AmtCellService;
        });
    }

    public function provides()
    {
        return ['als_rpt', 'wck', 'amt_cell'];
    }
}
