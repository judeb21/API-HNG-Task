<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1'], function () {

    /*Get all Plan*/
    Route::get('plan', 'UserController@plan');

    /*Get all Users*/
    Route::get('user', 'UserController@user');

    /*Run subscriptions for users*/
    Route::post('subscribe', 'UserController@subscribe');

    /*Create Users*/
    Route::post('create', 'UserController@create');

    Route::post('login', 'AuthController@login');

    Route::post('logout', 'AuthController@logout');

    Route::post('refresh', 'AuthController@refresh');
    
    Route::post('me', 'AuthController@me');

});
