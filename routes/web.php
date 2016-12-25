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
| Ps: When you use require_once instead of require, the application can only require the route during the first test 
| case, and simply ignores it on any subsequent tests. So please use require instead require_once to avoid broken 
| when running tests.
*/

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require(app_path() . '/../routes/auth/Login.php');

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
require(app_path() . '/../routes/Frontend/AlsRptIbCxt.php');
require(app_path() . '/../routes/Frontend/AmtAlsRpt.php');

/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
*/
require(app_path() . '/../routes/Backend/AlsRptIbChannel.php');
require(app_path() . '/../routes/Backend/AlsRptIbCxt.php');
require(app_path() . '/../routes/Backend/AmtReplica.php');
require(app_path() . '/../routes/Backend/Child.php');
require(app_path() . '/../routes/Backend/AmtAlsRpt.php');
require(app_path() . '/../routes/Backend/AmtDiag.php');
require(app_path() . '/../routes/Backend/AmtCell.php');
require(app_path() . '/../routes/Backend/AmtDiagStandard.php');
require(app_path() . '/../routes/Backend/Organization.php');
require(app_path() . '/../routes/Backend/RecommendCourse.php');

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
*/
require(app_path() . '/../routes/api/DateTime.php');