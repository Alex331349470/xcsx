<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Models\Order;
use Carbon\Carbon;
use EasyWeChat\Factory;
use Illuminate\Http\Request;

class ReturnsController extends Controller
{
    public function wechatNotify()
    {
        // 校验回调参数是否正确
        $data = app('wechat_pay')->verify();
        //找到对应的订单
        $order = Order::where('no', $data->out_trade_no)->first();
        // 订单不存在则告知微信支付
        if (!$order) {
            return 'fail';
        }
        // 订单已支付
        if ($order->paid_at) {
            // 告知微信支付此订单已处理
            return app('wechat_pay')->success();
        }

        // 将订单标记为已支付
        $order->update([
            'paid_at' => Carbon::now(),
            'payment_no' => $data->transaction_id,
            'pay_man' => 'normal_man',
            'status' => 1,
        ]);

        $officialAccount = \EasyWeChat::officialAccount();

        $sub_data = [
            'touser' => 'otSh7szfR7tBPNcNzk45CgZUgdW4',
            'template_id' => 'MUCyGRRr07-qwAGD08KxfxtIhdlbZ4y1wGQO70yjREk
',
            'data' => [
                'name' => '测试',
                'number' => '1'
            ],
        ];

        $officialAccount->template_message->send($sub_data);

        $this->afterPaid($order);

        return app('wechat_pay')->success();
    }

    protected function afterPaid(Order $order)
    {
        event(new OrderPaid($order));
    }
}
