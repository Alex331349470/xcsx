<?php

namespace App\Admin\Controllers;

use App\Models\DriverSchool;
use App\Models\User;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use GuzzleHttp\Client;

class UsersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '用户管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        Admin::style('.box-body{overflow: scroll;}');

        $grid = new Grid(new User());

        $grid->column('id', __('Id值'));
        $grid->column('driver_school', __('所属驾校'))->display(function () {
            return $name = DriverSchool::query()->where('id', $this->driver_school_id)->first()->name;
        });
        $grid->column('name', __('姓名'));
        $grid->column('phone', __('手机号'));
        $grid->column('email', __('邮箱地址'));
        $grid->column('address', __('运营地址'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id值'));
        $show->field('driver_school', __('所属学校'))->as(function (){
            return $name = DriverSchool::query()->where('id',$this->driver_school_id)->first()->name;
        });
        $show->field('name', __('姓名'));
        $show->field('phone', __('手机号'));
        $show->field('email', __('邮箱地址'));
        $show->field('address', __('运营地址'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $driver_school_lv1 = DriverSchool::query()->get(['id','name'])->pluck('name','id');
        $form->select('driver_school_id', __('驾校名称'))->options($driver_school_lv1)->required();
        $form->text('name', __('姓名'));
        $form->mobile('phone', __('手机号'));
        $form->email('email', __('邮箱地址'));
        $form->text('address', __('运营地址'));
        $form->password('password', __('密码'));

        $form->saving(function (Form $form){
            $client = new Client();
            $openid = $client->get('http://car.agelove.cn/user');
            dd($openid);
            $form->password = bcrypt($form->password);
            $form->openId = $openid;
        });

        return $form;
    }
}
