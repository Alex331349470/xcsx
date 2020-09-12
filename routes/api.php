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


Route::prefix('v1')
    ->namespace('Api')
    ->name('api.v1.')
    ->group(function () {

        Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function () {
                //api版本
                Route::get('version', function () {
                    return 'this is version 1';
                })->name('version');

                //微信登录
                Route::post('authorizations', 'AuthorizationsController@store')
                    ->name('authorization.store');

                //刷新token
                Route::put('authorizations/current', 'AuthorizationsController@update')
                    ->name('authorizations.update');

                //删除token
                Route::delete('authorizations/current', 'AuthorizationsController@destroy')
                    ->name('authorizations.destroy');
            });

        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {
                Route::get('/payment/wechat', 'PaymentsController@index');
                //非Authorization的api
                Route::middleware('auth:api')->group(function () {
                    //用户信息
                    Route::get('user', 'UsersController@me')
                        ->name('user.show');

                    Route::get('cars', 'CarsController@index');

                    Route::post('car/control', 'CarsController@controlCar')
                        ->name('car.control');
                });
            });
    });