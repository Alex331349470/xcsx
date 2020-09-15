<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use App\Models\Car;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ControlCar
{

    /**
     * Handle the event.
     *
     * @param OrderPaid $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        $order = $event->getOrder();

        $car = Car::query()->where('id',$order->car_id)->first();
        $this->controlCar($order->left_time, $car->serial_num);
        $car->update([
            'status' => true,
            'start' => Carbon::now(),
            'end' => Carbon::now()->addSeconds($order->left_time),
        ]);
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
