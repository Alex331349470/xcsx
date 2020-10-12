<?php

namespace App\Http\Controllers\Api;

use App\Models\DriverSchool;
use App\Models\User;
use App\Models\Car;
use App\Models\Order;
use App\Models\SellItem;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function payByWechat(Car $car, SellItem $sellItem)
    {
        if ($car->status == true) {
            abort(403, '车辆正在使用中');
        }

        $school_name = DriverSchool::query()->where('id', $car->driver_school_id)->first()->name;
        $school_pinyin = pinyin_abbr($school_name);

        $order = Order::create([
            'car_id' => $car->id,
            'sell_item_id' => $sellItem->id,
            'left_time' => $sellItem->time,
            'income' => $sellItem->price,
        ]);

        $order->no = $school_pinyin . $order->no;
        $order->save();

        $wechatOrder = app('wechat_pay')->scan([
            'out_trade_no' => $order->no,
            'total_fee' => $sellItem->price * 100,
            'body' => '支付订单：' . $school_name . '-' . $order->no,
        ]);

        return response($wechatOrder->code_url, 200);
    }

    public function paySoon(Car $car, SellItem $sellItem, Request $request)
    {
        $school_name = DriverSchool::query()->where('id', $car->driver_school_id)->first()->name;
        $school_pinyin = pinyin_abbr($school_name);

        $order = Order::create([
            'car_id' => $car->id,
            'sell_item_id' => $sellItem->id,
            'left_time' => $sellItem->time,
            'income' => $sellItem->price,
        ]);

        $order->no = $school_pinyin . $order->no;
        $order->save();

        $openId = User::query()->where('adminId', $request->admin_id)->first();

        $wechatOrder = app('wechat_pay')->mp([
            'out_trade_no' => $order->no,
            'total_fee' => $sellItem->price * 100,
            'body' => '支付订单：' . $school_name . '-' . $order->no,
            'openid' => $openId,
        ]);

        return $wechatOrder;
    }


}
