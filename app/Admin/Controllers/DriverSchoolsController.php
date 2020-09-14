<?php

namespace App\Admin\Controllers;

use App\Models\DriverSchool;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverSchoolsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '驾校管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverSchool());

        $grid->column('id', __('Id值'));
        $grid->column('name', __('学校名称'));

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
        $show = new Show(DriverSchool::findOrFail($id));

        $show->field('name', __('学校名称'));
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
        $form = new Form(new DriverSchool());

        $form->text('name', __('学校名称'))->required();

        return $form;
    }
}
