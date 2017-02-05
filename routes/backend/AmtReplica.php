<?php

Route::resource('/backend/amt', 'Backend\AmtController');
Route::get('/backend/amt/{amt}/map', 'Backend\AmtController@map');
Route::resource('/backend/amt_replica', 'Backend\AmtReplicaController');
Route::get('/backend/amt_replica/{amt_replica}/finish', 'Backend\AmtReplicaController@finish');
Route::get('/backend/amt_replica/{amt_replica}/prev', 'Backend\AmtReplicaController@prev');
Route::get('/backend/amt_replica/{amt_replica}/prepare', 'Backend\AmtReplicaController@prepare');