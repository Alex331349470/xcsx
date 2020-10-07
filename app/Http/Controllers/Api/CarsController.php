<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CarResource;
use App\Models\Car;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CarsController extends Controller
{
    //为 Guzzle http 创建新的实例变量
    protected $client;

    public function __construct(Client $client)
    {
        //返回 guzzle http 对象实例
        return $this->client = $client;
    }

    public function index()
    {
        $cars = Car::paginate(10);

        CarResource::wrap('data');
        return new CarResource($cars);
    }

    public function show(Car $car)
    {
        return new CarResource($car);
    }

    public function update(Car $car,Request $request)
    {
        $data = $request->all();

        $car->update($data);
        $car->save();

        return new CarResource($car);
    }

    public function destroy(Car $car)
    {
        $car->delete();

        return response(null,204);
    }
    //控制车辆继电器
    public function controlCar(Request $request)
    {
        //车辆唯一标识码
        $devId = $request->serialNum;
        //继电器号码
        $controllerNum = str_pad($request->controllerNum, 2, 0, STR_PAD_LEFT);

        //根据type值进行继电器功能
        switch ($request->type) {
            case 'start':
                $this->sendStartSignal($devId, $controllerNum);

            default:
                $this->sendDelaySignal($request->time, $devId, $controllerNum);
        }

        return response()->json([
            'message' => '发送指令成功！',
        ])->setStatusCode(200);
    }

    //发送继电器启动控制信号
    protected function sendStartSignal($devId, $controllerNum)
    {
        $msg = 'e104' . $controllerNum . '000001';

        $this->client->get('https://mobi.ydsyb123.com/api/send2sb.php', [
            'query' => [
                'us_id' => env('CAR_US_ID'),
                'openid' => env('CAR_OPEN_ID'),
                'dev_id' => $devId,
                'msg' => $msg
            ]
        ]);
    }

    //发送继电器延时关闭信号
    protected function sendDelaySignal($time, $devId, $controllerNum)
    {
        $dechexTime = str_pad(dechex($time), 4, 0, STR_PAD_LEFT);

        $msg = 'e104' . $controllerNum . '00' . $dechexTime;

        $this->client->get('https://mobi.ydsyb123.com/api/send2sb.php', [
            'query' => [
                'us_id' => env('CAR_US_ID'),
                'openid' => env('CAR_OPEN_ID'),
                'dev_id' => $devId,
                'msg' => $msg
            ]
        ]);
    }
}
