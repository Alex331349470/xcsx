<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\CarStart;
use App\Admin\Actions\Post\CarStop;
use App\Admin\Actions\Post\Replicate;
use App\Models\Car;
use App\Models\DriverSchool;
use Doctrine\DBAL\Driver;
use Encore\Admin\Admin;
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
    protected $title = '车辆管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Car());
        Admin::style('.box-body{overflow:scroll;}');
        $grid->column('id', __('Id'));
//        $grid->column('driver_school', __('所属驾校'))->display(function () {
//            $driverSchool = DriverSchool::query()->whereId($this->driver_school_id)->first();
//            return $driverSchool->name;
//        });
//
//        $grid->column('serial_num', __('车辆标识码'));
        $grid->column('name', __('车辆名称'));
        $grid->column('status', __('车辆状态'))->display(function ($value) {
            if ($value == 1) {
                return '运行';
            } else {
                return '停止';
            }
        });
        $grid->column('start', __('开始时间'));
        $grid->column('end', __('结束时间'));

        $grid->disableExport();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name', '车辆名称');
        });

        $grid->actions(function ($actions) {
            $actions->add(new CarStart);
            $actions->add(new CarStop);
            $actions->add(new Replicate);
        });

        $grid->disableExport();

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

        $show->field('id', __('Id值'));
        $show->field('driver_school', __('所属驾校'))->as(function () {
            $name = DriverSchool::query()->where('id', $this->driver_school_id)->first()->name;
            return $name;
        });
        $show->field('name', __('车辆名称'));
        $show->field('status', __('车辆状态'));
        $show->field('start', __('开始时间'));
        $show->field('end', __('结束时间'));
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
        $form = new Form(new Car());

        $driver_school_lv1 = DriverSchool::query()->get(['id', 'name'])->pluck('name', 'id');
        $form->select('driver_school_id', __('驾校名称'))->options($driver_school_lv1)->required();
        $form->text('serial_num', __('车辆标识码'))->rules('required');
        $form->text('name', __('车辆名称'))->rules('required|string|min:3');

        return $form;
    }
}
