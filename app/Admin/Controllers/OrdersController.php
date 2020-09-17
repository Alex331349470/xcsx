<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\Stop;
use App\Models\Car;
use App\Models\Order;
use App\Models\SellItem;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrdersController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '订单管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('status', '运营状态', [
                0 => '完成',
                1 => '进行',
                2 => '停止',
            ]);
        });
        $grid->model()->whereNotNull('paid_at')->orderBy('paid_at','desc');
        $grid->column('id', __('Id值'));
        $grid->column('car', __('车辆名称'))->display(function () {
            $name = Car::query()->where('id', $this->car_id)->first()->name;
            return $name;
        });
//        $grid->column('driver_school_id', __('Driver school id'));
        $grid->column('sell_item', __('套餐名称'))->display(function () {
            $name = SellItem::query()->where('id', $this->sell_item_id)->first()->name;
            return $name;
        });
        $grid->column('no', __('订单号'));
        $grid->column('left_time', __('剩余时间'));
        $grid->column('income', __('收入'));
        $grid->column('paid_at', __('付款时间'));
        $grid->column('payment_no', __('付款流水号'));
        $grid->column('status',__('订单状态'))->display(function ($value){
            if ($value == 0) {
                return '完成';
            } elseif ($value == 2){
                return '停止';
            } else {
                return '进行';
            }
        });
        $grid->column('pay_man',__('付款人'));

        $grid->disableCreateButton();

        $grid->actions(function ($actions){
            $actions->add(new Stop);
        });
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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id值'));
        $show->field('car', __('所属车辆'))->as(function (){
            $name = Car::query()->where('id',$this->car_id)->first()->name;
            return $name;
        });
        $show->field('sell_item', __('所属套餐'))->as(function (){
            $name = SellItem::query()->where('id',$this->sell_item_id)->first()->name;
            return $name;
        });
        $show->field('no', __('订单号'));
        $show->field('left_time', __('剩余时间'));
        $show->field('income', __('收入'));
        $show->field('paid_at', __('付款时间'));
        $show->field('payment_no', __('付款流水号'));
        $show->field('status',__('订单状态'));
        $show->field('pay_man',__('付款人'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));

        return $show;
    }

}
