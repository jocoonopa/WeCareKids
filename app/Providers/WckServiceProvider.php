<?php

namespace App\Providers;

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
            return new \App\Utility\Services\AmtAlsRptService($app->make('App\Utility\Repositorys\AmtAlsRptRepo'));
        });

        $this->app->singleton('wck', function () {
            return new \App\Utility\Services\WckService;
        });

        $this->app->singleton('amt_cell', function () {
            return new \App\Utility\Services\AmtCellService;
        });

        $this->app->singleton('amt_replica', function () {
            return new \App\Utility\Services\AmtReplicaService;
        });

        $this->app->singleton('slack', function ($app) {
            return new \App\Utility\Services\SlackService(new \Maknz\Slack\Client(env('SLACK_WEB_HOOK'), $this->getSlackSettings()));
        });
    }

    public function provides()
    {
        return [
            'amt_als_rpt', 
            'wck', 
            'amt_cell', 
            'amt_replica', 
            'slack'
        ];
    }

    protected function getSlackSettings()
    {
        return [
            'username' => 'system_' . env('APP_ENV'),
            'channel' => env('SLACK_CHANNEL')
        ];
    }
}
