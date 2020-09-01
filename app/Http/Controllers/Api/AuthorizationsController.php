<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Auth\AuthenticationException;
use Overtrue\LaravelSocialite\Socialite;
use PhpParser\Node\Expr\Throw_;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
        $driver = Socialite::driver('wechat');

        try {
            $accessToken = $driver->getAccessToken($request->code);

            $oauthUser = $driver->user($accessToken);

        } catch (\Exception $e) {
            throw new AuthenticationException('参数错误，未获取用户信息！');
        }


        if (!$user = User::where('open_id', $oauthUser->getId())->first()) {
            $user = User::create([
                'name' => $oauthUser->getNickname(),
                'avatar' => $oauthUser->getAvatar(),
                'open_id' => $oauthUser->getId(),
                'phone' => $request->phone,
            ]);

            UserInfo::create([
                'user_id' => $user->id,
                'driver_school_id' => $request->driver_school_id,
            ]);
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
