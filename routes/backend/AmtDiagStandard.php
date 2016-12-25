<?php

Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_diag_standard', 'Backend\AmtDiagStandardController@index');
Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_diag_standard/create', 'Backend\AmtDiagStandardController@create');
Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_diag_standard/{amt_diag_standard}/edit', 'Backend\AmtDiagStandardController@edit');
Route::post('/backend/amt_diag_group/{amt_diag_group}/amt_diag_standard', 'Backend\AmtDiagStandardController@store');
Route::put('/backend/amt_diag_group/{amt_diag_group}/amt_diag_standard/{amt_diag_standard}', 'Backend\AmtDiagStandardController@update');