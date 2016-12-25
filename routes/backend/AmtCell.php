<?php

Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_cell', 'Backend\AmtCellController@index');
Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_cell/create', 'Backend\AmtCellController@create');
Route::get('/backend/amt_diag_group/{amt_diag_group}/amt_cell/{amt_cell}/edit', 'Backend\AmtCellController@edit');
Route::post('/backend/amt_diag_group/{amt_diag_group}/amt_cell', 'Backend\AmtCellController@store');
Route::put('/backend/amt_diag_group/{amt_diag_group}/amt_cell/{amt_cell}', 'Backend\AmtCellController@update');