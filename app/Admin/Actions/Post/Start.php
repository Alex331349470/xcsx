<?php

namespace App\Admin\Actions\Post;

use App\Jobs\CarStatus;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Encore\Admin\Actions\RowAction;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class Start extends RowAction
{
    public $name = '继续';

    public function handle(Model $model)
    {
        $time = $model->left_time;
        $car = Car::query()->where('id', $model->car_id)->first();
        $serial_num = $car->serial_num;
        $model->status = 1;
        $model->save();

        $this->controlCar($time, $serial_num);


        $car->update([
            'status' => true,
            'start' => Carbon::now(),
            'end' => Carbon::now()->addSeconds($model->left_time),
        ]);

        CarStatus::dispatch($car, $model, $model->left_time);

        $officialAccount = \EasyWeChat::officialAccount();

        $users = User::all();

        foreach ($users as $user) {

            if ($openId = $user->openId) {
                $sub_data = [
                    'touser' => $openId,
//                    'touser' => 'otSh7szfR7tBPNcNzk45CgZUgdW4',
                    'template_id' => '28JqHbTcIMEHHS7JMkYyLp-zUQhWorLv1SADPcPVXJg',
                    'data' => [
                        'first' => ['value' => '车辆计时继续运行','color' => '#FF0000'],
                        'event' => ['value' => '由' . $car->name . '训练车计时运行', 'color' => '#FF0000'],
                        'finish_time' => Carbon::now()->toDateTimeString(),
                        'remark' => '订单' . $model->no . '由' . $car->name . '训练车继续计时运行,该订单还剩余' . $model->left_time.'秒',
                    ],
                ];

                $officialAccount->template_message->send($sub_data);
            }
        }

        return $this->response()->success('续单成功')->refresh();
    }

    protected function controlCar($time, $devId)
    {
        $client = new Client();
        $dechexTime = str_pad(dechex($time), 4, 0, STR_PAD_LEFT);

        $msg = 'e10401' . '01' . $dechexTime;

        $client->get('https://mobi.ydsyb123.com/api/send2sb.php', [
            'query' => [
                'us_id' => env('CAR_US_ID'),
                'openid' => env('CAR_OPEN_ID'),
                'dev_id' => $devId,
                'msg' => $msg
            ]
        ]);
    }

}