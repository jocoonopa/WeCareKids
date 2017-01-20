<?php

Route::resource('/backend/user', 'Backend\UserController');
Route::put('/backend/user/{id}/restore', 'Backend\UserController@restore');
Route::get('/backend/user/{user}/reset', 'Backend\UserController@showResetForm');
Route::put('/backend/user/{user}/reset', 'Backend\UserController@reset');