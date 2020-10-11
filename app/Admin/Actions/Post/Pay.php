<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Encore\Admin\Admin;
use Illuminate\Database\Eloquent\Model;

class Pay extends RowAction
{
    public $name = '支付';

    public function handle(Model $model)
    {
        // $model ...

        $openid = (new \Encore\Admin\Admin)->user()->openId;
        $url = env('APP_URL') . '/api/v1/test';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = json_decode(curl_exec($ch), true);
        curl_close($ch);


        return $this->response()->info('支付');
    }

}