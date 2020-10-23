<?php

namespace App\Admin\Actions\Post;

use App\Models\User;
use Carbon\Carbon;
use Encore\Admin\Actions\RowAction;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class CarStart extends RowAction
{
    public $name = '开启';

    public function handle(Model $model)
    {
        if ($model->status == 1) {
            return $this->response()->warning('车辆正在开启中')->refresh();
        }

        $model->status = 1;
        $model->save();
        $serial_num = $model->serial_num;

        $this->controlCar($serial_num);

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
                        'event' => ['value' => $model->name . '处于常开状态', 'color' => '#FF0000'],
                        'finish_time' => Carbon::now()->toDateTimeString(),
                        'remark' => '该车处于常开状态，请知悉！',
                    ],
                ];

                $officialAccount->template_message->send($sub_data);
            }
        }

        return $this->response()->success('车辆开启成功')->refresh();
    }

    protected function controlCar($devId)
    {
        $client = new Client();

        $msg = 'e10401010000';

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