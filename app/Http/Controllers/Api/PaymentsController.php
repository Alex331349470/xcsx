<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use App\Models\Order;
use App\Models\SellItem;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function payByWechat(Car $car, SellItem $sellItem)
    {
        $order = new Order([
            'car_id' => $car->id,
            'sell_item_id' => $sellItem->id,
            'left_time' => $sellItem->time,
            'income' => $sellItem->price,
        ]);

        dd($order);
        $wechatOrder = app('wechat_pay')->mp([
            'out_trade_no' => $order->no,
            'total_fee' => $sellItem->price * 100,
            'body' => '支付订单：'. $order->no,
        ]);

//        $wechatOrder = app('wechat_pay')->scan([
//            'out_trade_no' => time(),
//            'total_fee' => 1,
//            'body' => '支付订单:' . time()
//        ]);

//        $qrCode = new QrCode($wechatOrder->code_url);

//        return response($qrCode->writeDataUri(), 200);
        return response(null,200);
    }

}
