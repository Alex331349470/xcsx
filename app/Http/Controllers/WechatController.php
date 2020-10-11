<?php

namespace App\Http\Controllers;

use EasyWeChat\Factory;
use Illuminate\Http\Request;

class WechatController extends Controller
{
    public function serve()
    {
        $app = Factory::officialAccount(config('wechat.official_account.default'));

        return $app->server->serve();
    }

    public function menu()

    {
        $app = Factory::officialAccount(config('wechat.official_account.default'));
        $menu = [

            [
                "type" => "view",
                "name" => "训练车系统",
                "url" => "http://car.agelove.cn/admin"
            ]
        ];

//        $app->menu->create($menu);
        $app->menu->delete();
        return $app->menu->list();
    }

}
