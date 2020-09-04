<?php

namespace App\Http\Resources;

use App\Models\DriverSchool;
use App\Models\UserInfo;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        $driver_school_id = UserInfo::query()->where('user_id',$data['id'])->first()->driver_school_id;
        $data['school'] = DriverSchool::query()->where('id',$driver_school_id)->first();

        return $data;
    }
}
