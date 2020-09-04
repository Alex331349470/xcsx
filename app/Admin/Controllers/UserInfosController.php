<?php

namespace App\Admin\Controllers;

use App\Models\UserInfo;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserInfosController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Models\UserInfo';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserInfo());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('driver_school_id', __('Driver school id'));
        $grid->column('total_account', __('Total account'));
        $grid->column('left_money', __('Left money'));
        $grid->column('is_vip', __('Is vip'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(UserInfo::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('driver_school_id', __('Driver school id'));
        $show->field('total_account', __('Total account'));
        $show->field('left_money', __('Left money'));
        $show->field('is_vip', __('Is vip'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new UserInfo());

        $form->number('user_id', __('User id'));
        $form->number('driver_school_id', __('Driver school id'));
        $form->decimal('total_account', __('Total account'))->default(0.00);
        $form->decimal('left_money', __('Left money'))->default(0.00);
        $form->switch('is_vip', __('Is vip'));

        return $form;
    }
}
