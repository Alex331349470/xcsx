<?php

namespace App\Jobs;

use App\Models\Car;
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

    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $now = Carbon::now() ;
       $get = $this->car->end;
       if ($now->gte($get)) {
           $this->car->update([
               'status' => false,
           ]);
       }
    }
}
