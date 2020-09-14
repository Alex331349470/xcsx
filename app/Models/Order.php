<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['car_id', 'sell_item_id', 'no', 'income', 'left_time', 'paid_at', 'payment_no', 'status','pay_man'];
}
