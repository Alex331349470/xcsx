<?php

namespace App\Models;

use App\Traits\dateTrait;
use Illuminate\Database\Eloquent\Model;

class SellItem extends Model
{
    use dateTrait;

    protected $fillable = [ 'time', 'name', 'price','car_id'];
}
