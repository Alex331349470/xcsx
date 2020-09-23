<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\QrCode;
use App\Models\SellItem;
use Encore\Admin\Actions\Response;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;
use GuzzleHttp\Client;

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
        Admin::style('.box-body{overflow: scroll;}');

        $grid = new Grid(new SellItem());

        $grid->column('id', __('支付码-ID'))->qrcode(function ($value)  {
            $car = SellItem::query()->where('id', $value)->first();

            $url = 'http://car.agelove.cn/api/v1/cars/' . $car->car_id . '/sell_items/' . $value . '/payment ';
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

            $data = curl_exec($ch);
            curl_close($ch);

            return $data;
        });

        $grid->column('time', __('时间(秒)'));
        $grid->column('name', __('套餐名称'));
        $grid->column('price', __('价格'));


        $grid->actions(function ($actions) {
            $actions->add(new QrCode);
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
