<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Models\Car;
use App\Models\Order;
use App\Models\SellItem;
use App\Models\User;
use Carbon\Carbon;
use EasyWeChat\Factory;
use Illuminate\Http\Request;

class ReturnsController extends Controller
{
    public function wechatNotify()
    {
        // 校验回调参数是否正确
        $data = app('wechat_pay')->verify();
        $verifyNo = substr($data->out_trade_no,4);
        //找到对应的订单
        $order = Order::where('no', $verifyNo)->first();
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
            'pay_man' => '教练员',
            'status' => 1,
        ]);

        $officialAccount = \EasyWeChat::officialAccount();

        $name = SellItem::query()->where('id', $order->sell_item_id)->first()->name;
        $car = Car::query()->where('id', $order->car_id)->first()->name;
        $openId = User::query()->first()->openId;

        $sub_data = [
//            'touser' => 'otSh7szfR7tBPNcNzk45CgZUgdW4',
            'touser' => $openId,
            'template_id' => 'MUCyGRRr07-qwAGD08KxfxtIhdlbZ4y1wGQO70yjREk',
            'data' => [
                'productType' => '套餐名称',
                'name' => $name,
                'number' => 1,
                'expDate' => Carbon::now()->toDateString(),
                'remark' => '套餐已购买，训练车为' . $car . ',金额为' . $order->income . '元，训练时间为' . $order->left_time . '秒，请学员立即上车训练'
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
