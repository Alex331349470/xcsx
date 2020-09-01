<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ['driver_school_id', 'sell_item_id', 'name', 'status'];
}
