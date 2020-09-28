<?php

namespace App\Http\Controllers\Api;

use App\Models\DriverSchool;
use Endroid\QrCode\QrCode;
use App\Models\Car;
use App\Models\Order;
use App\Models\SellItem;

class PaymentsController extends Controller
{
    public function payByWechat(Car $car, SellItem $sellItem)
    {
        if ($car->status == true) {
            abort(403, '车辆正在使用中');
        }

        $school = DriverSchool::query()->where('id', $car->driver_school_id)->first()->name;

        $order = Order::create([
            'car_id' => $car->id,
            'sell_item_id' => $sellItem->id,
            'left_time' => $sellItem->time,
            'income' => $sellItem->price,
        ]);

        $wechatOrder = app('wechat_pay')->scan([
            'out_trade_no' => $order->no,
            'total_fee' => $sellItem->price * 100,
            'body' => '支付订单：' . $school . '-' . $order->no,
        ]);

//        $qrCode = new QrCode($wechatOrder->code_url);

//        return response($qrCode->writeDataUri(), 200);

        return response($wechatOrder->code_url, 200);
    }


}
