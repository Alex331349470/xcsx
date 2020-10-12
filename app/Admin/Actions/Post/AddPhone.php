<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AddPhone extends RowAction
{
    public $name = '手机号';

    public function handle(Model $model, Request $request)
    {
        $phone = $request->get('phone');

        $model->phone = $phone;
        $model->save();

        return $this->response()->success('手机号保存成功')->refresh();
    }

    public function form()
    {

        $this->text('phone', __('手机号'));
    }

}