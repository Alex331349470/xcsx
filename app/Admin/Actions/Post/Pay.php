<?php

namespace App\Admin\Actions\Post;

use App\Models\Car;
use App\Models\Item;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Pay extends RowAction
{
    public $name = '支付';

    public function handle(Model $model, Request $request)
    {
        $car_id = $request->get('car_id');
        $car = Car::query()->where('id', $car_id)->first();

        if ($car->status == true) {
            return $this->response()->warning('车辆正在使用中')->refresh();
        }

        $admin_id = \Auth::guard('admin')->user()->id;

        $url = $url = env('APP_URL') . '/api/v1/cars/' . $car_id . '/sell_items/' . $model->id . '/payment/' . $admin_id;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = curl_exec($ch);
//        $wcdata = json_decode($data, true);
//        curl_close($ch);
//
//        Item::create([
//            'adminId' => \Auth::guard('admin')->user()->id,
//            'appId' => $wcdata['appId'],
//            'timeStamp' => $wcdata['timeStamp'],
//            'nonceStr' => $wcdata['nonceStr'],
//            'package' => $wcdata['package'],
//            'signType' => $wcdata['signType'],
//            'paySign' => $wcdata['paySign']
//        ]);

        return $this->response()->success($data)->refresh();
    }

    public function form()
    {
        $car_lv1 = Car::query()->get(['id', 'name'])->pluck('name', 'id');

        $this->select('car_id', __('训练车名称'))->options($car_lv1);
    }
}