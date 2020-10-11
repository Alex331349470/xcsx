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
                //扫码支付
                Route::get('cars/{car}/sell_items/{sell_item}/payment', 'PaymentsController@payByWechat')
                    ->name('car.sell_item.payment');
                //测试
                Route::get('test', 'TestsController@pay')
                    ->name('test');

                //非Authorization的api
                Route::middleware('auth:api')->group(function () {
                    //用户信息
                    Route::get('user', 'UsersController@me')
                        ->name('user.show');
                    //套餐列表
                    Route::get('sell_items', 'SellItemsController@index')
                        ->name('sell_items.index');
                    //套餐详情
                    Route::get('sell_items/{sell_item}','SellItemsController@show')
                        ->name('sell_items.show');
                    //套餐修改
                    Route::put('sell_items/{sell_item}','SellItemsController@update')
                        ->name('sell_items.update');
                    //套餐删除
                    Route::delete('sell_items/{sell_item}','SellItemsController@destroy')
                        ->name('sell_items.destroy');
                    //车辆列表
                    Route::get('cars', 'CarsController@index')
                        ->name('cars.index');
                    //车辆详情
                    Route::get('cars/{car}', 'CarsController@show')
                        ->name('cars.show');
                    //车辆修改
                    Route::put('cars/{car}','CarsController@update')
                        ->name('cars.update');
                    //车辆删除
                    Route::delete('cars/{car}','CarsController@destroy')
                        ->name('cars.destroy');
                    //车辆控制
                    Route::post('car/control', 'CarsController@controlCar')
                        ->name('car.control');
                    //订单列表
                    Route::get('orders', 'OrdersController@index')
                        ->name('orders.index');
                    //订单详情
                    Route::get('orders/{order}', 'OrdersController@show')
                        ->name('orders.show');
                    //订单停止
                    Route::post('orders/{order}/stop', 'OrdersController@stop')
                        ->name('order.stop');
                    //订单续签
                    Route::post('orders/{order}/cars/{car}/start', 'OrdersController@start')
                        ->name('order.start');
                });
            });
    });