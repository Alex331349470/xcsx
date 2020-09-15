<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function me(Request $request)
    {
        $user = User::query()->whereId($request->user()->id)->first();

        return new UserResource($user);
    }

}