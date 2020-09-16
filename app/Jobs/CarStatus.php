<?php

namespace App\Jobs;

use App\Models\Car;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CarStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $car;
    protected $order;

    public function __construct(Car $car, Order $order, $delay)
    {
        $this->car = $car;
        $this->order = $order;
        $this->delay($delay);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now();
        $get = $this->car->end;
        if ($this->order->status !== 2) {
            if ($now->gte($get)) {
                $this->car->update([
                    'status' => false,
                ]);

                $this->order->update([
                    'left_time' => 0,
                    'status' => 0,
                ]);
            }
        }


    }
}
