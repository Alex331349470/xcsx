<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Jobs\CarStatus;
use App\Models\Car;
use App\Models\Order;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\New_;

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

        $car = Car::query()->where('id', $order->car_id)->first();

        $serial_num = $car->serial_num;

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

        $msg = json_decode($message, true);

        $change = substr($msg['msg'], 4, 4);

        $time = hexdec($change);

        $order->update([
            'left_time' => $time,
            'status' => 2,
        ]);

        $car->update([
            'status' => 0,
        ]);

        return response(null, 200);
    }

    public function start(Order $order,Car $car)
    {
        $time = $order->left_time;
        $serial_num = $car->serial_num;

        $this->controlCar($time, $serial_num);

        $order->update([
            $order->status => true,
        ]);

        $car->update([
            'status' => true,
            'start' => Carbon::now(),
            'end' => Carbon::now()->addSeconds($order->left_time),
        ]);

        CarStatus::dispatch($car, $order, $order->left_time);
    }

    protected function controlCar($time, $devId)
    {
        $client = new Client();
        $dechexTime = str_pad(dechex($time), 4, 0, STR_PAD_LEFT);

        $msg = 'e10401' . '01' . $dechexTime;

        $client->get('https://mobi.ydsyb123.com/api/send2sb.php', [
            'query' => [
                'us_id' => env('CAR_US_ID'),
                'openid' => env('CAR_OPEN_ID'),
                'dev_id' => $devId,
                'msg' => $msg
            ]
        ]);
    }
}
