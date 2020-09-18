<?php

namespace App\Admin\Actions\Post;

use App\Models\Car;
use Encore\Admin\Actions\RowAction;
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

        return $this->response()->success('二维码生成成功')->refresh();
    }

    public function form()
    {
//        $car = [];
//        $cars = Car::all();
//        foreach ($cars as $ca) {
//            array_push($car, $ca->name);
//        }
        $driver_school_lv1 = Car::query()->get(['id','name'])->pluck('name','id');
//        $form->select('driver_school_id', __('驾校名称'))->options($driver_school_lv1)->required();
        $this->select('car_id', __('训练车名称'))->options($driver_school_lv1);
//        $this->select()

//        $this->checkbox('car', '车辆')->options($car);
    }

}