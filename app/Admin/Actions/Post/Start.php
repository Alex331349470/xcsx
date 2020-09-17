<?php

namespace App\Admin\Actions\Post;

use App\Jobs\CarStatus;
use App\Models\Car;
use Carbon\Carbon;
use Encore\Admin\Actions\RowAction;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class Start extends RowAction
{
    public $name = '继续';

    public function handle(Model $model)
    {
        $time = $model->left_time;
        $car = Car::query()->where('id', $model->car_id)->first();
        $serial_num = $car->serial_num;
        $model->status = 1;
        $model->save();

        $this->controlCar($time, $serial_num);



        $car->update([
            'status' => true,
            'start' => Carbon::now(),
            'end' => Carbon::now()->addSeconds($model->left_time),
        ]);

        CarStatus::dispatch($car, $model, $model->left_time);

        return $this->response()->success('续单成功')->refresh();
    }

    protected function controlCar($time, $devId)
    {
        $client = new Client();
        $dechexTime = str_pad(dechex($time), 4, 0, STR_PAD_LEFT);

        $msg = 'e10401' . '01' . $dechexTime;

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