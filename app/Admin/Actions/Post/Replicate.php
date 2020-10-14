<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class Replicate extends RowAction
{
    public $name = '检测';

    public function handle(Model $model)
    {
        $serial_num = $model->serial_num;

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

        if ($message->msg) {
            return $this->response()->success('设备在线')->refresh();

        } else {
            return $this->response()->error('设备未在线')->refresh();

        }
    }

}