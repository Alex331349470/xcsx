<?php

namespace App\Admin\Actions\Post;

use App\Models\Car;
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

        return $this->response()->success('停止成功')->refresh();
    }

}