<?php

Route::resource('/backend/analysis/r/i/channel', 'Backend\AlsRptIbChannelController');
Route::get('/backend/analysis/r/i/channel/{als_rpt_ib_channel}/qrcode', 'Backend\AlsRptIbChannelController@qrcode')
    ->where('als_rpt_ib_channel', '[0-9]+');

Route::put('/backend/analysis/r/i/channel/{als_rpt_ib_channel}/is_open', 'Backend\AlsRptIbChannelController@toggleOpen')
    ->where('als_rpt_ib_channel', '[0-9]+');