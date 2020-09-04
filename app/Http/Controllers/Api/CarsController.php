<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CarResource;
use App\Models\Car;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    public function show(Car $car)
    {
        return new CarResource($car->where('serial_num', $car->serial_num)->first());
    }

    public function controlCar(Request $request)
    {
        $devId = $request->serialNum;
        $time = $request->time;
        $type = str_pad($request->type, 2, 0, STR_PAD_LEFT);


        $this->sendSignal($time, $devId, $type);
        return response()->json([
            'message' => '发送指令成功！',
        ])->setStatusCode(200);
    }

    protected function sendSignal($time, $devId, $type)
    {
        $client = new Client();

        $get = str_pad(dechex($time), 4, 0, STR_PAD_LEFT);

        $msg = 'e104' . $type . '01' . $get;


        $promise = $client->requestAsync('get', 'https://mobi.ydsyb123.com/api/send2sb.php', [
            'query' => [
                'us_id' => env('CAR_US_ID'),
                'openid' => env('CAR_OPEN_ID'),
                'dev_id' => $devId,
                'msg' => 'e104'.$type.'000001'
            ]
        ])->then(function () use ($msg, $client, $devId) {
            $client->requestAsync('get', 'https://mobi.ydsyb123.com/api/send2sb.php', [
                'query' => [
                    'us_id' => env('CAR_US_ID'),
                    'openid' => env('CAR_OPEN_ID'),
                    'dev_id' => $devId,
                    'msg' => $msg
                ]
            ]);
        });


        $promise->wait();
    }
}
