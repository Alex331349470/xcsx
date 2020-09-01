<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $fillable = [];

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
