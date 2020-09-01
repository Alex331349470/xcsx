<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use App\Models\User;
use Overtrue\LaravelSocialite\Socialite;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
       $driver = Socialite::driver('wechat');

       $accessToken = $driver->getAccessToken($request->code);

       $oauthUser = $driver->user($accessToken);

       if (!$user = User::where('open_id', $oauthUser->getId())->first()) {
           $user = User::create([
               'name' => $oauthUser->getNickname(),
               'avatar' => $oauthUser->getAvatar(),
               'open_id' => $oauthUser->getId(),
           ]);
       }

        $token = auth('api')->login($user);

        return $this->responseWithToken($token);

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
