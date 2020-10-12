<?php

namespace App\Admin\Actions\Post;

use App\Models\Item;
use Encore\Admin\Actions\RowAction;
use Encore\Admin\Admin;
use Illuminate\Database\Eloquent\Model;

class Pay extends RowAction
{
    public $name = '支付';

    public function handle(Model $model)
    {
        $url = env('APP_URL') . '/api/v1/test';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = json_decode(curl_exec($ch), true);

        curl_close($ch);

        Item::create([
            'adminId' => \Auth::guard('admin')->user()->id,
            'appId' => $data['appId'],
            'timeStamp' => $data['timeStamp'],
            'nonceStr' => $data['nonceStr'],
            'package' => $data['package'],
            'signType' => $data['signType'],
            'paySign' => $data['paySign']
        ]);

        return $this->response()->info('微信支付')->refresh();
    }
}