<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class CarStart extends RowAction
{
    public $name = '开启';

    public function handle(Model $model)
    {
        $model->status = 1;
        $model->save();
        $serial_num = $model->serial_num;

        $this->controlCar($serial_num);


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