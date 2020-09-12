<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use function EasyWeChat\Kernel\Support\str_random;

class PaymentsController extends Controller
{
    public function payByWechat(Order $order, Request $request)
    {
        $test_num = str_random(15);
        $wechatOrder = app('wechat_pay')->scan([
            'out_trade_no' => $test_num,
            'total_fee' => 0.01 * 100,
            'body' => '支付订单'.$test_num
        ]);

        $qrCode = new QrCode($wechatOrder->code_url);

        return response($qrCode->writeDataUri(),200);
    }
}
