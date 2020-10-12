<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\Pay;
use App\Admin\Actions\Post\QrCode;
use App\Models\Car;
use App\Models\Item;
use App\Models\SellItem;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;

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
//        $wechat_data = '';
        Admin::style('.box-body{overflow: scroll;}');
        $grid = new Grid(new SellItem());
        $adminId = \Auth::guard('admin')->user()->id;

        if ($data = Item::query()->where('adminId',$adminId)->first()) {
            $this->resolveAction($data);
            $data->delete();
        } else {
            Admin::script('console.log("hello")');
        }

        $grid->column('id', __('支付码-ID'))->qrcode(function ($value) {
            $item = SellItem::query()->where('id', $value)->first();

            if ($item->car_id == null) {
                return '请选择好训练车辆';
            }

            if (Car::query()->where('id',$item->car_id)->first()->status == 1) {
                $item->car_id = null;
                $item->save();
                return '该车辆正在使用中';
            }

            $url = env('APP_URL').'/api/v1/cars/' . $item->car_id . '/sell_items/' . $value . '/payment ';
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

            $data = curl_exec($ch);
            curl_close($ch);

            $item->car_id = null;
            $item->save();


            return $data;
        });

        $grid->column('time', __('时间(秒)'));
        $grid->column('name', __('套餐名称'));
        $grid->column('price', __('价格'));

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name', '套餐名称');
        });
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->add(new QrCode);
            $actions->add(new Pay);
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

    public function resolveAction($data)
    {
        $script = <<<SCRIPT
    WeixinJSBridge.invoke(
                    'getBrandWCPayRequest', {
                        "appId": {$data->appId} ,     //公众号名称，由商户传入
                        "timeStamp": {$data->timeStamp},         //时间戳，自1970年以来的秒数
                        "nonceStr": {$data->nonceStr}, //随机串
                        "package":' {$data->package},
                        "signType":{$data->signType},         //微信签名方式：
                        "paySign":{$data->paySign} //微信签名
                    },
                    function (res) {
                        if (res.err_msg == "get_brand_wcpay_request:ok") {
                            console.log(res.err_msg)
                            // 使用以上方式判断前端返回,微信团队郑重提示：
                            //res.err_msg将在用户支付成功后返回ok，但并不保证它绝对可靠。
                        }
                    });

SCRIPT;

        Admin::script($script);
    }
}
