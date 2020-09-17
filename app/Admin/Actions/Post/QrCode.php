<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class QrCode extends RowAction
{
    public $name = '二维码';

    public function handle(Model $model)
    {
        $client = new Client();

        $client->get('http://car.agelove.cn/api/v1/cars/1/sell_items/'.$model->id.'/payment');

        return $this->response()->success('二维码生成成功')->refresh();
    }

}