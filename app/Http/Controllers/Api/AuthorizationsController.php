<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use http\Client\Curl\User;
use Overtrue\Socialite\SocialiteManager;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request)
    {
       $driver = SocialiteManager::driver('wechat');

       $token = $driver->getAccessToken($request->code);

       $oauthUser = $driver->user($token);

       if (!$user = User::where('open_id', $oauthUser->getId())->first()) {
           $user = User::create([
               'name' => $oauthUser->getNickname(),
               'avatar' => $oauthUser->getAvatar(),
               'open_id' => $oauthUser->getId(),
           ]);

           $accessToken = auth('api')->login($user);

           return $this->responseWithToken($accessToken);
       }

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
