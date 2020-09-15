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

}
