<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function show()
    {
        $user = session('wechat.oauth_user.default'); // 拿到授权用户
        return "<h1>".$user['id']."</h1>";
    }

    public function fake()
    {
        return view('fake');
    }

}
