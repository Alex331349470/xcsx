<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function me(Request $request)
    {
        UserResource::wrap('data');
        return new UserResource($request->user());
    }

}
