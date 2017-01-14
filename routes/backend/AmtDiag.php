<?php

Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_diag', 'Backend\AmtDiagController@index');
Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_diag/create', 'Backend\AmtDiagController@create');
Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_diag/{amt_diag}/edit', 'Backend\AmtDiagController@edit');
Route::post('/backend/amt_diag_group/{amt_diag_group}/amt_diag', 'Backend\AmtDiagController@store');
Route::put('/backend/amt_diag_group/{amt_diag_group}/amt_diag/{amt_diag}', 'Backend\AmtDiagController@update');