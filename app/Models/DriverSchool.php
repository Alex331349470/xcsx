<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverSchool extends Model
{
    protected $fillable = ['name'];

    public function UserInfo()
    {
        return $this->belongsTo(UserInfo::class);
    }
}
