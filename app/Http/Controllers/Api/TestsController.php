<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TestsController extends Controller
{
    public function test()
    {
        $ws = new \WebSocket\Client('wss://mobi.ydsyb123.com:8282/?dev_id=32094&member_id=319');

        $client = new Client();

        $client->get('https://mobi.ydsyb123.com/api/send2sb.php',[
            'query' => [
                'us_id' => env('CAR_US_ID'),
                'openid' => env('CAR_OPEN_ID'),
                'dev_id' => '32094',
                'msg' => 'd100'
            ]
        ]);

        $msg = json_decode($ws->receive(),true);

        $time = substr($msg['msg'],4,4);

        $change = hexdec($time);

        return $change;
    }
}
