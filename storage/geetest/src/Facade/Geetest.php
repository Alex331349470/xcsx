<?php

namespace Encore\Admin\Geetest\Facade;

use Illuminate\Support\Facades\Facade;

class Geetest extends Facade
{
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'geetest';
    }
}