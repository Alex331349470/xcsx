<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TestsController extends Controller
{
    public function test()
    {


//        $serial_num = '32094';
//        $ws = new \WebSocket\Client('wss://mobi.ydsyb123.com:8282/?dev_id='.$serial_num.'&member_id=319');
//
//        $client = new Client();
//
//        $client->get('https://mobi.ydsyb123.com/api/send2sb.php',[
//            'query' => [
//                'us_id' => env('CAR_US_ID'),
//                'openid' => env('CAR_OPEN_ID'),
//                'dev_id' => $serial_num,
//                'msg' => 'd100'
//            ]
//        ]);
//
//        $msg = json_decode($ws->receive(),true);
//        $ws->close();
//
//        $time = substr($msg['msg'],4,4);
//
//        $change = hexdec($time);
//
//        return $change;
    }
}
