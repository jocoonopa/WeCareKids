<?php

/**
 * @file
 *
 * This file contain routes related to users or user authentications.
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