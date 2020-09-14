<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use App\Models\Order;
use App\Models\SellItem;
use Endroid\QrCode\QrCode;

class PaymentsController extends Controller
{
    public function payByWechat(Order $order, SellItem $sellItem, Car $car)
    {

        if ($order->paid_at || $order->left_time > 0) {
            abort(403,'订单不正确');
        }

        $wechatOrder = app('wechat_pay')->mp([
            'out_trade_no' => $order->no,
            'total_fee' => $order->price * 100,
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
