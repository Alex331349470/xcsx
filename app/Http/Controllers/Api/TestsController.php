<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use Illuminate\Http\Request;


class TestsController extends Controller
{
    public function test()
    {
//        $userModel = new \App\Models\User();
//        $userModel->setConnection('mysql');
//
//        $officialAccount = \EasyWeChat::officialAccount();
//
//        $sub_data = [
//            'touser' => 'otSh7szfR7tBPNcNzk45CgZUgdW4',
//            'template_id' => '28JqHbTcIMEHHS7JMkYyLp-zUQhWorLv1SADPcPVXJg',
//            'data' => [
//                'first' => 'value',
//                'event' => 'value',
//                'finish_time' => 'value',
//                'remark' => 'value',
//            ],
//        ];
//
//        $officialAccount->template_message->send($sub_data);

        $serial_num = '32148';

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
//        return $msg;

        try {
            $serial_num = $serial_num;

            $ws = new \WebSocket\Client('wss://mobi.ydsyb123.com:8282/?dev_id=' . $serial_num . '&member_id=319');

//            $client = new Client();
//
//            $client->get('https://mobi.ydsyb123.com/api/send2sb.php', [
//                'query' => [
//                    'us_id' => env('CAR_US_ID'),
//                    'openid' => env('CAR_OPEN_ID'),
//                    'dev_id' => $serial_num,
//                    'msg' => 'd100'
//                ]
//            ]);
//            $message = $ws->receive();

            $ws->close();

//            $msg = json_decode($message, true);
//
//            if ($msg['msg']) {
//                return $this->response()->success('设备在线')->refresh();
//            }
        } catch (\Exception $exception) {
            return $this->response()->error('设备未在线')->refresh();
        }


//        $time = substr($msg['msg'],4,4);
//
//        $change = hexdec($time);
//
//        return $change;
    }

    public function pay(Request $request)
    {
        $order = [
            'out_trade_no' => time(),
            'body' => 'subject-test',
            'total_fee' => '1',
            'openid' => 'otSh7szfR7tBPNcNzk45CgZUgdW4'
        ];

       return $pay = app('wechat_pay')->mp($order);
    }
}
