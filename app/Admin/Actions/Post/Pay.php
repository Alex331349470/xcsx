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

        $data = curl_exec($ch);
        $wcdata = json_decode($data, true);
        curl_close($ch);

        Item::create([
            'adminId' => \Auth::guard('admin')->user()->id,
            'appId' => $wcdata['appId'],
            'timeStamp' => $wcdata['timeStamp'],
            'nonceStr' => $wcdata['nonceStr'],
            'package' => $wcdata['package'],
            'signType' => $wcdata['signType'],
            'paySign' => $wcdata['paySign']
        ]);

        return $this->response()->success('支付')->timeout(10000)->refresh();
    }
}