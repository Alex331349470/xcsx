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
                "name" => "宏达科目二",
                "url" => "http://car2.agelove.cn/admin"
            ],

            [
                "type" => "view",
                "name" => "信息绑定码",
                "url" => "http://car.agelove.cn/user"
            ]
        ];

        $app->menu->create($menu);
//        $app->menu->delete();
        return $app->menu->list();
    }

}
