<?php

namespace App\Admin\Actions\Post;

use App\Models\Car;
use Encore\Admin\Actions\RowAction;
use Encore\Admin\Admin;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class QrCode extends RowAction
{
    public $name = '选择车辆';

    public function handle(Model $model, Request $request)
    {

        $model->car_id = $request->get('car_id');
        $model->save();

        return $this->response()->warning('二维码生成中,请勿操作')->timeout(8000)->refresh();

    }

    public function form()
    {
        $car_lv1 = Car::query()->get(['id','name'])->pluck('name','id');

        $this->select('car_id', __('训练车名称'))->options($car_lv1);
    }

}