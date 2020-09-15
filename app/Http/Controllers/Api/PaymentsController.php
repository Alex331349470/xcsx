<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderPaid;
use App\Models\Car;
use App\Models\Order;
use App\Models\SellItem;

class PaymentsController extends Controller
{
    public function payByWechat(Car $car, SellItem $sellItem)
    {
        if ($car->status == true) {
            abort(403,'车辆正在使用中');
        }

        $order = new Order([
            'car_id' => $car->id,
            'sell_item_id' => $sellItem->id,
            'left_time' => $sellItem->time,
            'income' => $sellItem->price,
        ]);

        $order->save();

        app('wechat_pay')->mp([
            'out_trade_no' => $order->no,
            'total_fee' => $sellItem->price * 100,
            'body' => '支付订单：'. $order->no,
        ]);

        return response(null,200);
    }



}
