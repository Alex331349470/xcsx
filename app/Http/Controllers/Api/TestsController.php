<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use App\Models\DriverSchool;
use App\Models\Order;
use App\Models\SellItem;
use GuzzleHttp\Client;
use Illuminate\Http\Request;


class TestsController extends Controller
{
    public function test()
    {
        $userModel = new \App\Models\User();
        $userModel->setConnection('mysql');

        $officialAccount = \EasyWeChat::officialAccount();

        $sub_data = [
            'touser' => 'otSh7szfR7tBPNcNzk45CgZUgdW4',
            'template_id' => '28JqHbTcIMEHHS7JMkYyLp-zUQhWorLv1SADPcPVXJg',
            'data' => [
                'first' => 'value',
                'event' => 'value',
                'finish_time' => 'value',
                'remark' => 'value',
            ],
        ];

        $officialAccount->template_message->send($sub_data);

        $serial_num = '32094';
        $ws = new \WebSocket\Client('wss://mobi.ydsyb123.com:8282/?dev_id='.$serial_num.'&member_id=319');

        $client = new Client();

        $client->get('https://mobi.ydsyb123.com/api/send2sb.php',[
            'query' => [
                'us_id' => env('CAR_US_ID'),
                'openid' => env('CAR_OPEN_ID'),
                'dev_id' => $serial_num,
                'msg' => 'd100'
            ]
        ]);

        $msg = json_decode($ws->receive(),true);
        $ws->close();

        $time = substr($msg['msg'],4,4);

        $change = hexdec($time);

        return $change;
    }

    public function pay(Request $request)
    {
        $car_id = $request->car_id;
        $car = Car::query()->where('id',$car_id)->first();
        $sell_item_id = $request->sell_item_id;
        $sellItem = SellItem::query()->where('id',$sell_item_id)->first();

        $school_name = DriverSchool::query()->where('id', $car->driver_school_id)->first()->name;
        $school_pinyin = pinyin_abbr($school_name);

        if ($car->status == true) {
            abort(403, '车辆正在使用中');
        }

        $order = Order::create([
            'car_id' => $car->id,
            'sell_item_id' => $sellItem->id,
            'left_time' => $sellItem->time,
            'income' => $sellItem->price,
        ]);

        $order->no = $school_pinyin . $order->no;

        $order->save();

        $wechatOrder = [
            'out_trade_no' => $order->no,
            'body' => '支付订单：' . $school_name . '-' . $order->no,
            'total_fee' => $sellItem->price * 100,
            'openid' => 'otSh7szfR7tBPNcNzk45CgZUgdW4'
        ];

        $pay = app('wechat_pay')->mp($wechatOrder);
    }
}
