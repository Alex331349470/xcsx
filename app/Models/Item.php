<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'adminId', 'appId', 'timeStamp', 'nonceStr', 'package', 'signType', 'paySign'
    ];
}
