<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function me(Request $request)
    {
        $user = User::query()->where('id',$request->user()->id)->first();

        UserResource::wrap('data');
        return new UserResource($user);
    }

}
