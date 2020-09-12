<?php

namespace Encore\Admin\Geetest\Http\Controllers;

use Encore\Admin\Controllers\AuthController;
use Encore\Admin\Geetest\Facade\Geetest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class GeetestController extends AuthController
{
    /**
     * @return mixed
     */
    public function index()
    {
        $data   = [
            'user_id'     => auth()->check() ? auth()->user()->id : 'UnLoginUser',
            'client_type' => 'web',
            'ip_address'  => request()->ip(),
        ];
        $status = Geetest::preProcess($data);

        session()->put('gtserver', $status);
        session()->put('user_id', $data['user_id']);

        return Geetest::getResponseStr();
    }

    /**
     * Show the login page.
     *
     * @return \Illuminate\Contracts\View\Factory|Redirect|\Illuminate\View\View
     */
    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect(config('admin.route.prefix'));
        }

        $product = config('geetest.product', 'float');

        return view('geetest::login', ['product'   => $product]);
    }

    /**
     * Handle a login request.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {
        $credentials = $request->only([$this->username(), 'password']);
        $remember = $request->get('remember', false);

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials, [
            $this->username()   => 'required',
            'password'          => 'required',
            'geetest_challenge' => 'geetest',
        ], [
            'geetest' => config('geetest.server_fail_alert')
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        if ($this->guard()->attempt($credentials, $remember)) {
            return $this->sendLoginResponse($request);
        }

        return back()->withInput()->withErrors([
            $this->username() => $this->getFailedLoginMessage(),
        ]);
    }
}