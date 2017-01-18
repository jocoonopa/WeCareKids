<?php

Route::resource('/backend/user', 'Backend\UserController');
Route::put('/backend/user/{id}/restore', 'Backend\UserController@restore');