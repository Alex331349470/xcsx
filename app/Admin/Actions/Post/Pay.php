<?php

namespace App\Admin\Actions\Post;

use App\Models\Car;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Encore\Admin\Actions\RowAction;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Pay extends RowAction
{
    public $name = '支付';

    public function handle(Model $model, Request $request)
    {
        $car_id = $request->get('car_id');
        $car = Car::query()->where('id', $car_id)->first();

        if ($car->status == true) {
            return $this->response()->warning('车辆正在使用中')->refresh();
        }
        try {
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

            if ($msg['msg']) {
                $admin_id = \Auth::guard('admin')->user()->id;

                $url = $url = env('APP_URL') . '/api/v1/cars/' . $car_id . '/sell_items/' . $model->id . '/payment/' . $admin_id;

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

                $data = curl_exec($ch);
                $wcdata = json_decode($data, true);
                curl_close($ch);

                Item::create([
                    'adminId' => \Auth::guard('admin')->user()->id,
                    'appId' => $wcdata['appId'],
                    'timeStamp' => $wcdata['timeStamp'],
                    'nonceStr' => $wcdata['nonceStr'],
                    'package' => $wcdata['package'],
                    'signType' => $wcdata['signType'],
                    'paySign' => $wcdata['paySign']
                ]);

                return $this->response()->success('支付')->refresh();
            }
        } catch (\Exception $exception) {
            $officialAccount = \EasyWeChat::officialAccount();

            $users = User::all();

            foreach ($users as $user) {

                if ($openId = $user->openId) {
                    $sub_data = [
                        'touser' => $openId,
//                    'touser' => 'otSh7szfR7tBPNcNzk45CgZUgdW4',
                        'template_id' => '28JqHbTcIMEHHS7JMkYyLp-zUQhWorLv1SADPcPVXJg',
                        'data' => [
                            'first' => '车辆状态',
                            'event' => ['value' => $car->name . '未在线', 'color' => '#FF0000'],
                            'finish_time' => Carbon::now()->toDateTimeString(),
                            'remark' => '该车辆处于未在线状态，请及时修复！',
                        ],
                    ];

                    $officialAccount->template_message->send($sub_data);
                }
            }
            return $this->response()->error('设备未在线')->refresh();
        }
//        $admin_id = \Auth::guard('admin')->user()->id;
//
//        $url = $url = env('APP_URL') . '/api/v1/cars/' . $car_id . '/sell_items/' . $model->id . '/payment/' . $admin_id;
//
//        $ch = curl_init();
//
//        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
//
//        $data = curl_exec($ch);
//        $wcdata = json_decode($data, true);
//        curl_close($ch);
//
//        Item::create([
//            'adminId' => \Auth::guard('admin')->user()->id,
//            'appId' => $wcdata['appId'],
//            'timeStamp' => $wcdata['timeStamp'],
//            'nonceStr' => $wcdata['nonceStr'],
//            'package' => $wcdata['package'],
//            'signType' => $wcdata['signType'],
//            'paySign' => $wcdata['paySign']
//        ]);
//
//        return $this->response()->success('支付')->refresh();
    }

    public function form()
    {
        $car_lv1 = Car::query()->get(['id', 'name'])->pluck('name', 'id');

        $this->select('car_id', __('训练车名称'))->options($car_lv1);
    }
}