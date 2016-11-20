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

Route::get('/backend/login', array('as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm'));
Route::post('/backend/login', array('as' => 'login', 'uses' => 'Auth\LoginController@login'));
Route::get('/backend/logout', 'Auth\LoginController@logout');
Auth::routes();
Route::get('/login', function (){abort(404);});

Route::get('/', 'HomeController@index');