<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellItem extends Model
{
    protected $fillable = ['user_id', 'time', 'name', 'price'];
}
