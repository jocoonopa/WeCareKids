<?php

Route::resource('/frontend/amt_als_rpt', 'Frontend\AmtAlsRptController');

Route::get('/frontend/amt_als_rpt/course/{courseId}', 'Frontend\AmtAlsRptController@course');
