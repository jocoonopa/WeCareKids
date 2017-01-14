<?php

Route::get('/analysis/r/i/channel/{als_rpt_ib_channel}/cxt', 'Frontend\AlsRptIbCxtController@index')->where('als_rpt_ib_channel', '[0-9]+');

Route::get('/analysis/r/i/channel/{als_rpt_ib_channel}/cxt/login', 'Frontend\AlsRptIbCxtController@login')->where('als_rpt_ib_channel', '[0-9]+');
Route::get('/analysis/r/i/channel/{als_rpt_ib_channel}/cxt/logout', 'Frontend\AlsRptIbCxtController@logout')->where('als_rpt_ib_channel', '[0-9]+');
Route::post('/analysis/r/i/channel/{als_rpt_ib_channel}/cxt', 'Frontend\AlsRptIbCxtController@auth')->where('als_rpt_ib_channel', '[0-9]+');

Route::put('/analysis/r/i/cxt/{als_rpt_ib_cxt}', 'Frontend\AlsRptIbCxtController@update')->where('als_rpt_ib_cxt', '[0-9]+');

Route::get('/analysis/r/i/cxt/{als_rpt_ib_cxt}', 'Frontend\AlsRptIbCxtController@show')->where('als_rpt_ib_cxt', '[0-9]+');

Route::get('/analysis/r/i/cxt/{als_rpt_ib_cxt}/finish', 'Frontend\AlsRptIbCxtController@finish')->where('als_rpt_ib_cxt', '[0-9]+');