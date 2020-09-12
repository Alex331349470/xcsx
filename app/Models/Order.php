<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['car_id', 'driver_school_id', 'sell_item_id', 'income', 'left_time'];
}
