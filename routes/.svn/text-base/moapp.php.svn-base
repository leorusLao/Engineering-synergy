<?php

use Illuminate\Http\Request;

/*
 |--------------------------------------------------------------------------
 | API Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register API routes for your application. These
 | routes are loaded by the RouteServiceProvider within a group which
 | is assigned the "api" middleware group. Enjoy building your API!
 |
 */

Route::get('/testing', function () {
	return response()->json(['name' => 'Alban Afmeti', 'state' => 'AL']);
});

Route::any('/login','Api\ApiFrontController@login');
Route::any('/signup','Api\ApiFrontController@signup');

Route::get('/dashboard','Api\ApiMbDailyController@dashboard');
Route::get('/dashboard/change-password','Api\ApiMbDailyController@changePassword');