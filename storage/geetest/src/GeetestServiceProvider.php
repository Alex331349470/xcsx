<?php

namespace Encore\Admin\Geetest;

use Encore\Admin\Geetest\Facade\Geetest as Facade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class GeetestServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Geetest $extension)
    {
        if (!Geetest::boot()) {
            return;
        }

        $this->loadViewsFrom($extension->views(), 'geetest');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->registerPublishing();

        $this->extendValidator();
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton('geetest', function () {
            return new GeetestLib(config('geetest.geetest_id'), config('geetest.geetest_key'));
        });
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [__DIR__ . '/../config/geetest.php' => config_path('geetest.php'),],
                'geetest'
            );
        }
    }

    /**
     * Extend a `geetest` validator.
     */
    protected function extendValidator()
    {
        Validator::extend('geetest', function (Request $request) {

            $challenge = $request->get('geetest_challenge');
            $validate  = $request->get('geetest_validate');
            $seccode   = $request->get('geetest_seccode');

            $data = [
                'user_id'     => session('geetest_user_id'),
                'client_type' => 'web',
                'ip_address'  => $request->ip(),
            ];

            if (session()->get('gtserver') == 1) {
                return Facade::successValidate($challenge, $validate, $seccode, $data);
            }

            return Facade::failValidate($challenge, $validate, $seccode);
        });
    }
}