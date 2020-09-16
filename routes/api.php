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

                //登录
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
                Route::get('cars/{car}/sell_items/{sell_item}/payment', 'PaymentsController@payByWechat')
                    ->name('car.sell_item.payment');

                Route::get('sell_items', 'SellItemsController@index')
                    ->name('sell_items.index');

                Route::get('cars', 'CarsController@index')
                    ->name('cars.index');

                Route::get('cars/{car}', 'CarsController@show')
                    ->name('cars.show');

                Route::post('car/control', 'CarsController@controlCar')
                    ->name('car.control');

                Route::get('test', 'TestsController@test')
                    ->name('test');

                Route::post('orders/{order}/stop', 'OrdersController@stop')
                    ->name('order.stop');

                Route::post('orders/{order}/cars/{car}/start', 'OrdersController@start')
                    ->name('order.start');
                //非Authorization的api
                Route::middleware('auth:api')->group(function () {
                    //用户信息
                    Route::get('user', 'UsersController@me')
                        ->name('user.show');


//                    Route::post('car/control', 'CarsController@controlCar')
//                        ->name('car.control');

//                    Route::get('payment/{order}/wechat', 'PaymentsController@payBywechat')
//                        ->name('payment.wechat');
                });
            });
    });