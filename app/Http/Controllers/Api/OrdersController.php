<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Car;
use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::paginate(10);
        return new OrderResource($orders);
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function stop(Order $order)
    {
        if ($order->left_time == 0) {
            abort(403, '该订单已经结束');
        }

        $serial_num = Car::query()->where('id', $order->car_id)->first();

        dd($serial_num);
        $ws = new \WebSocket\Client('wss://mobi.ydsyb123.com:8282/?dev_id=' . $serial_num . '&member_id=319');

        $client = new Client();

        $client->get('https://mobi.ydsyb123.com/api/send2sb.php', [
            'query' => [
                'us_id' => env('CAR_US_ID'),
                'openid' => env('CAR_OPEN_ID'),
                'dev_id' => $serial_num,
                'msg' => 'd100'
            ]
        ]);
        $message = $ws->receive();

        $ws->close();
        dd($message);

        $msg = json_decode($message, true);

        $change = substr($msg['msg'], 4, 4);

        $time = hexdec($change);

        $order->update([
            'left_time' => $time,
            'status' => 2,
        ]);

        return response(null, 200);
    }

    public function start()
    {

    }
}
