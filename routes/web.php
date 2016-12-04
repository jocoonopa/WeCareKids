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
Route::get('/login', function (){abort(404);});
Route::get('/backend/home', 'Backend\AlsRptIbChannelController@index');

Route::resource('/backend/analysis/r/i/channel', 'Backend\AlsRptIbChannelController');
Route::get('/backend/analysis/r/i/channel/{als_rpt_ib_channel}/qrcode', 'Backend\AlsRptIbChannelController@qrcode')
    ->where('als_rpt_ib_channel', '[0-9]+');

Route::resource('/backend/analysis/r/i/cxt', 'Backend\AlsRptIbCxtController');
Route::get('/analysis/r/i/channel/{als_rpt_ib_channel}/cxt', 'Frontend\AlsRptIbCxtController@index')->where('als_rpt_ib_channel', '[0-9]+');

Route::put('/analysis/r/i/cxt/{als_rpt_ib_cxt}', 'Frontend\AlsRptIbCxtController@update')->where('als_rpt_ib_cxt', '[0-9]+');

Route::resource('/backend/amt', 'Backend\AmtController');
Route::resource('/backend/amt_replica', 'Backend\AmtReplicaController');