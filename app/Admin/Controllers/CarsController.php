<?php

namespace App\Admin\Controllers;

use App\Models\Car;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CarsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Models\Car';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Car());

        $grid->column('id', __('Id'));
        $grid->column('driver_school_id', __('Driver school id'));
        $grid->column('serial_num', __('Serial num'));
        $grid->column('sell_item_id', __('Sell item id'));
        $grid->column('name', __('Name'));
        $grid->column('status', __('Status'));
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
        $show = new Show(Car::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('driver_school_id', __('Driver school id'));
        $show->field('serial_num', __('Serial num'));
        $show->field('sell_item_id', __('Sell item id'));
        $show->field('name', __('Name'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Car());

        $form->number('driver_school_id', __('Driver school id'));
        $form->text('serial_num', __('Serial num'));
        $form->number('sell_item_id', __('Sell item id'));
        $form->text('name', __('Name'));
        $form->switch('status', __('Status'));

        return $form;
    }
}
