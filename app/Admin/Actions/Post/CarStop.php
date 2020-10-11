<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class CarStop extends RowAction
{
    public $name = '停止';

    public function handle(Model $model)
    {
        $model->status = 0;
        $model->save();
        $serial_num = $model->serial_num;

        $this->controlCar($serial_num);
        return $this->response()->success('车辆停止成功')->refresh();
    }

    protected function controlCar($devId)
    {
        $client = new Client();

        $msg = 'e10401000000';

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