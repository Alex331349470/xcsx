<?php

namespace App\Models;

use App\Traits\dateTrait;
use Illuminate\Database\Eloquent\Model;

class DriverSchool extends Model
{
    use dateTrait;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

}
