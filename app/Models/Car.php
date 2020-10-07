<?php

namespace App\Models;

use App\Traits\dateTrait;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use dateTrait;

    protected $fillable = ['driver_school_id', 'serial_num', 'name', 'status', 'start', 'end'];

}
