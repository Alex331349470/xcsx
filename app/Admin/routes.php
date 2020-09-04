<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('users','UsersController');
    $router->resource('cars','CarsController');
    $router->resource('orders','OrdersController');
    $router->resource('sell_items','SellItemsController');
    $router->resource('driver_schools','DriverSchoolsController');
    $router->resource('user_infos','UserInfosController');
});
