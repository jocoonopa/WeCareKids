<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::model('als_rpt_ib_channel', 'App\Model\AlsRptIbChannel');
        Route::model('als_rpt_ib_cxt', 'App\Model\AlsRptIbCxt');
        Route::model('amt_replica', 'App\Model\AmtReplica');
        Route::model('amt_als_rpt', 'App\Model\AmtAlsRpt');
        Route::model('amt_cell', 'App\Model\AmtCell');
        Route::model('amt_diag_group', 'App\Model\AmtDiagGroup');
        Route::model('amt_diag_standard', 'App\Model\AmtDiagStandard');
        Route::model('amt_diag', 'App\Model\AmtDiag');
        Route::model('amt', 'App\Model\Amt');
        Route::model('organization', 'App\Model\Organization');
        Route::model('child', 'App\Model\Child');
        Route::model('user', 'App\Model\User');
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }
}
