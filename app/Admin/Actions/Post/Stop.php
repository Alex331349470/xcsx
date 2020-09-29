<?php

namespace App\Admin\Actions\Post;

use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Encore\Admin\Actions\RowAction;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class Stop extends RowAction
{
    public $name = '停止';

    public function handle(Model $model)
    {
        $car = Car::query()->where('id', $model->car_id)->first();

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

        $model->update([
            'left_time' => $time,
            'status' => 2,
        ]);

        $car->update([
            'status' => 0,
        ]);

        $officialAccount = \EasyWeChat::officialAccount();

        $users = User::all();

        foreach ($users as $user) {

            if ($openId = $user->openId) {
                $sub_data = [
                    'touser' => $openId,
//                    'touser' => 'otSh7szfR7tBPNcNzk45CgZUgdW4',
                    'template_id' => '28JqHbTcIMEHHS7JMkYyLp-zUQhWorLv1SADPcPVXJg',
                    'data' => [
                        'first' => '车辆故障暂停',
                        'event' => '由于' . $car->name . '训练车故障，暂时暂停',
                        'finish_time' => Carbon::now()->toDateTimeString(),
                        'remark' => '订单' . $model->no . '车辆故障，暂停计时，该车还剩下' . $model->left_time . '秒,请修复该车或变更该订单训练车辆',
                    ],
                ];

                $officialAccount->template_message->send($sub_data);
            }
        }

        return $this->response()->success('停止成功')->refresh();
    }

}