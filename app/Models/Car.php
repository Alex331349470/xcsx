<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Illuminate\Support\Carbon;

class Car extends Model
{
    protected $fillable = ['driver_school_id', 'serial_num', 'name', 'status', 'start', 'end'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}
