<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
        dd($user = User::query()->where('phone',$request->phone)->first());
        if (!$user = User::query()->where('phone', $request->phone)->where('password', bcrypt($request->password))->first()) {
            throw new AuthenticationException('用户异常');
        }

        $token = auth('api')->login($user);

        return $this->responseWithToken($token);
    }


    public function update()
    {
        $token = auth('api')->refresh();

        return $this->responseWithToken($token);
    }

    public function destroy()
    {
        auth('api')->logout();

        return response(null, 204);
    }

    protected function responseWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ])->setStatusCode(201);
    }
}
