<?php

namespace App\Providers;

use App\Utility\Services\AmtAlsRptService;
use App\Utility\Services\AmtCellService;
use App\Utility\Services\AmtReplicaService;
use App\Utility\Services\SlackService;
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

        $this->app->singleton('amt_replica', function () {
            return new AmtReplicaService;
        });

        $this->app->singleton('slack', function ($app) {
            return new SlackService(new \Maknz\Slack\Client(env('SLACK_WEB_HOOK'), $this->getSlackSettings()));
        });
    }

    public function provides()
    {
        return ['amt_als_rpt', 'wck', 'amt_cell', 'amt_replica', 'slack'];
    }

    protected function getSlackSettings()
    {
        return [
            'username' => 'system_' . env('APP_ENV'),
            'channel' => env('SLACK_CHANNEL')
        ];
    }
}
