<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $fillable = ['user_id', 'driver_school_id', 'total_account', 'left_money', 'is_vip'];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function driverSchool()
    {
        $this->hasOne(DriverSchool::class);
    }
}
