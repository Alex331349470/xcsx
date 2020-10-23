<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Post\Pay;
use App\Admin\Actions\Post\QrCode;
use App\Models\Car;
use App\Models\Item;
use App\Models\SellItem;
use App\Models\User;
use Carbon\Carbon;
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
        $adminId = \Auth::guard('admin')->user()->id;

        if ($data = Item::query()->where('adminId', $adminId)->first()) {
            Admin::script('WeixinJSBridge.invoke(
                    \'getBrandWCPayRequest\', {
                        "appId": "' . $data->appId . '",
                        "timeStamp":"' . $data->timeStamp . '",
                        "nonceStr": "' . $data->nonceStr . '",
                        "package": "' . $data->package . '",
                        "signType":"' . $data->signType . '",
                        "paySign":"' . $data->paySign . '"
                    },
                    function (res) {
                        if (res.err_msg == "get_brand_wcpay_request:ok") {

                        }
                    });');
            $data->delete();
        } else {
            Admin::script('console.log("hello")');
        }

        $grid->column('id', __('支付码-ID'))->qrcode(function ($value) {
            $item = SellItem::query()->where('id', $value)->first();

            if ($item->car_id == null) {
                return '请选择好训练车辆';
            }
            $car = Car::query()->where('id', $item->car_id)->first();

            if ($car->status == 1) {
                $item->car_id = null;
                $item->save();
                return '该车辆正在使用中';
            }
            if ($item->car_id !== null) {
                try {
                    $serial_num = $car->serial_num;

                    $ws = new \WebSocket\Client('wss://mobi.ydsyb123.com:8282/?dev_id=' . $serial_num . '&member_id=319');

                    $client = new Client();

                    $client->get('https://mobi.ydsyb123.com/api/send2sb.php', [
                        'query' => [
                            'us_id' => env('CAR_US_ID'),
                            'openid' => env('CAR_OPEN_ID'),
                            'dev_id' => $serial_num,
                            'msg' => 'd100'
                        ]
                    ]);
                    $message = $ws->receive();

                    $ws->close();

                    $msg = json_decode($message, true);

                    if ($msg['msg']) {
                        $url = env('APP_URL') . '/api/v1/cars/' . $item->car_id . '/sell_items/' . $value . '/payment ';
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
                    }
                } catch (\Exception $exception) {
                    $item->car_id = null;
                    $item->save();

                    $officialAccount = \EasyWeChat::officialAccount();

                    $users = User::all();

                    foreach ($users as $user) {

                        if ($openId = $user->openId) {
                            $sub_data = [
                                'touser' => $openId,
//                    'touser' => 'otSh7szfR7tBPNcNzk45CgZUgdW4',
                                'template_id' => '28JqHbTcIMEHHS7JMkYyLp-zUQhWorLv1SADPcPVXJg',
                                'data' => [
                                    'first' => '车辆状态',
                                    'event' => ['value' =>  $car->name . '未在线', 'color' => '#FF0000'],
                                    'finish_time' => Carbon::now()->toDateTimeString(),
                                    'remark' => '该车辆处于未在线状态,请及时检修!',
                                ],
                            ];

                            $officialAccount->template_message->send($sub_data);
                        }
                    }
                    return '设备未在线';
                }
            }
//            $url = env('APP_URL') . '/api/v1/cars/' . $item->car_id . '/sell_items/' . $value . '/payment ';
//            $ch = curl_init();
//
//            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
//            curl_setopt($ch, CURLOPT_HEADER, 0);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
//
//            $data = curl_exec($ch);
//            curl_close($ch);
//
//            $item->car_id = null;
//            $item->save();
//
//
//            return $data;
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
        $form->currency('price', __('价格'))->default(0.00);

        return $form;
    }


}
