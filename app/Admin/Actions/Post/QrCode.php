<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class QrCode extends RowAction
{
    public $name = '选择车辆';

    public function handle(Model $model, Request $request)
    {
        $request->get('car');
        return $this->response()->success('二维码生成成功')->refresh();
    }

    public function form()
    {
        $car = [
            '训练车01',
        ];

        $this->checkbox('car', '车辆')->options($car);
    }

}