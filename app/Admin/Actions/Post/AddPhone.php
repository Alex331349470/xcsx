<?php

namespace App\Admin\Actions\Post;

use App\Models\Car;
use Encore\Admin\Actions\RowAction;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AddPhone extends RowAction
{
    public $name = '手机号';

    public function handle(Model $model, Request $request)
    {
        $phone = $request->get('phone');

        $model->phone = $phone;
        $model->save();

        $devId = Car::query()->where('id',$model->car_id)->first()->serial_num;
        $this->controlCar($devId);
        
        return $this->response()->success('手机号保存成功')->refresh();
    }

    public function form()
    {

        $this->text('phone', __('手机号'));
    }

    protected function controlCar($devId)
    {
        $client = new Client();

        $msg = 'e10401000000';

        $client->get('https://mobi.ydsyb123.com/api/send2sb.php', [
            'query' => [
                'us_id' => env('CAR_US_ID'),
                'openid' => env('CAR_OPEN_ID'),
                'dev_id' => $devId,
                'msg' => $msg
            ]
        ]);
    }

}