<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
Route::any('wechat', 'WechatController@serve');

Route::post('payment/wechat/notify', 'ReturnsController@wechatNotify')
    ->name('payment.wechat.notify');

Route::get('wechat/menu','WechatController@menu');

Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
    Route::get('/user', 'UsersController@show')
        ->name('user.openid.show');
});
