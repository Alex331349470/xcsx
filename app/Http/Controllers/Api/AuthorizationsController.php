<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {

        $credentials['phone'] = $request->phone;
        $credentials['password'] = $request->password;

//        $oauthUser = session('wechat.oauth_user.default'); // 拿到授权用户
//        dd($user);
        if (!\Auth::attempt($credentials)) {
            throw new AuthenticationException('用户异常');
        } else {
            //查询后台用户
            $user = User::query()->where('phone', $request->phone)->first();
            //生成token
            $token = auth('api')->login($user);

            return $this->responseWithToken($token);
        }
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
