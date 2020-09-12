<?php

use Encore\Admin\Geetest\Http\Controllers\GeetestController;
use Illuminate\Support\Facades\Route;

$attributes = [
    'prefix'     => config('admin.route.prefix'),
    'middleware' => config('admin.route.middleware'),
];

Route::group($attributes, function () {
    Route::get('auth/login', GeetestController::class.'@getLogin');
    Route::post('auth/login', GeetestController::class.'@postLogin');
});

Route::get('geetest', GeetestController::class.'@index');