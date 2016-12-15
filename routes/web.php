<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('/auth/login', 'Auth\LoginController@showLoginForm');
Route::post('/auth/login', 'Auth\LoginController@login');
Route::get('/auth/logout', 'Auth\LoginController@logout');
Auth::routes();

Route::get('/', function () {
    return redirect('/auth/login');
});
Route::get('/login', function (){abort(404);});
Route::get('/backend/home', 'Backend\AlsRptIbChannelController@index');

Route::resource('/backend/analysis/r/i/channel', 'Backend\AlsRptIbChannelController');
Route::get('/backend/analysis/r/i/channel/{als_rpt_ib_channel}/qrcode', 'Backend\AlsRptIbChannelController@qrcode')
    ->where('als_rpt_ib_channel', '[0-9]+');

Route::resource('/backend/analysis/r/i/cxt', 'Backend\AlsRptIbCxtController');
Route::get('/analysis/r/i/channel/{als_rpt_ib_channel}/cxt', 'Frontend\AlsRptIbCxtController@index')->where('als_rpt_ib_channel', '[0-9]+');

Route::get('/analysis/r/i/channel/{als_rpt_ib_channel}/cxt/login', 'Frontend\AlsRptIbCxtController@login')->where('als_rpt_ib_channel', '[0-9]+');
Route::get('/analysis/r/i/channel/{als_rpt_ib_channel}/cxt/logout', 'Frontend\AlsRptIbCxtController@logout')->where('als_rpt_ib_channel', '[0-9]+');
Route::post('/analysis/r/i/channel/{als_rpt_ib_channel}/cxt', 'Frontend\AlsRptIbCxtController@auth')->where('als_rpt_ib_channel', '[0-9]+');

Route::put('/analysis/r/i/cxt/{als_rpt_ib_cxt}', 'Frontend\AlsRptIbCxtController@update')->where('als_rpt_ib_cxt', '[0-9]+');

Route::resource('/backend/amt', 'Backend\AmtController');
Route::get('/backend/amt/{amt}/map', 'Backend\AmtController@map');
Route::resource('/backend/amt_replica', 'Backend\AmtReplicaController');
Route::get('/backend/amt_replica/{amt_replica}/finish', 'Backend\AmtReplicaController@finish');
Route::get('/backend/amt_replica/{amt_replica}/prev', 'Backend\AmtReplicaController@prev');

Route::resource('/backend/child', 'Backend\ChildController');
Route::resource('/backend/amt_als_rpt', 'Backend\AmtAlsRptController');

Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_diag', 'Backend\AmtDiagController@index');
Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_diag/create', 'Backend\AmtDiagController@create');
Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_diag/{amt_diag}/edit', 'Backend\AmtDiagController@edit');
Route::post('/backend/amt_diag_group/{amt_diag_group}/amt_diag', 'Backend\AmtDiagController@store');
Route::put('/backend/amt_diag_group/{amt_diag_group}/amt_diag/{amt_diag}', 'Backend\AmtDiagController@update');

Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_cell', 'Backend\AmtCellController@index');
Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_cell/create', 'Backend\AmtCellController@create');
Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_cell/{amt_cell}/edit', 'Backend\AmtCellController@edit');
Route::post('/backend/amt_diag_group/{amt_diag_group}/amt_cell', 'Backend\AmtCellController@store');
Route::put('/backend/amt_diag_group/{amt_diag_group}/amt_cell/{amt_cell}', 'Backend\AmtCellController@update');

Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_diag_standard', 'Backend\AmtDiagStandardController@index');
Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_diag_standard/create', 'Backend\AmtDiagStandardController@create');
Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_diag_standard/{amt_diag_standard}/edit', 'Backend\AmtDiagStandardController@edit');
Route::post('/backend/amt_diag_group/{amt_diag_group}/amt_diag_standard', 'Backend\AmtDiagStandardController@store');
Route::put('/backend/amt_diag_group/{amt_diag_group}/amt_diag_standard/{amt_diag_standard}', 'Backend\AmtDiagStandardController@update');

Route::resource('/backend/organization', 'Backend\OrganizationController');

Route::get('/api/datetime/age', 'Api\DateTimeController@getYMAge');



