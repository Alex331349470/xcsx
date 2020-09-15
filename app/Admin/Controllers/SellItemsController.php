<?php

namespace App\Admin\Controllers;

use App\Models\SellItem;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SellItemsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '套餐管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SellItem());

        $grid->column('id', __('Id值'));
        $grid->column('time', __('时间(秒)'));
        $grid->column('name', __('套餐名称'));
        $grid->column('price', __('价格'));

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
        $show = new Show(SellItem::findOrFail($id));

        $show->field('time', __('时间(秒)'));
        $show->field('name', __('套餐名称'));
        $show->field('price', __('价格'));
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
        $form = new Form(new SellItem());

        $form->number('time', __('时间(秒)'));
        $form->text('name', __('套餐名称'));
        $form->decimal('price', __('价格'))->default(0.00);

        return $form;
    }
}