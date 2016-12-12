<?php

namespace App\Providers;

use App\Utility\Services\AmtAlsRptService;
use App\Utility\Services\AmtCellService;
use App\Utility\Services\WckService;
use Illuminate\Support\ServiceProvider;

class WckServiceProvider extends ServiceProvider
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
        $this->app->bind('amt_als_rpt', function ($app) {
            return new AmtAlsRptService($app->make('App\Utility\Repositorys\AmtAlsRptRepo'));
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
        return ['amt_als_rpt', 'wck', 'amt_cell'];
    }
}
