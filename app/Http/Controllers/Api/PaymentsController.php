<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Endroid\QrCode\QrCode;
use Illuminate\Http\Request;
use Yansongda\Pay\Pay;
use function EasyWeChat\Kernel\Support\str_random;

class PaymentsController extends Controller
{
    protected $config = [
        'app_id' => 'wx060853c6aa6cdaee', // 公众号 APPID
        'mch_id' => '1602211774',
        'key' => 'xE8ZJjbNkC1ioUYUjqUK7rFYSrPyGuqX',
        'notify_url' => 'http://yanda.net.cn/notify.php',

        'log' => [ // optional
            'file' => './logs/wechat.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'single', // optional, 可选 daily.
            'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
        ]

    ];

    public function index()
    {
        $order = [
            'out_trade_no' => time(),
            'total_fee' => '1', // **单位：分**
            'body' => 'test body - 测试',
        ];

        $pay = Pay::wechat($this->config)->scan($order);

        dd($pay);
        // $pay->appId
        // $pay->timeStamp
        // $pay->nonceStr
        // $pay->package
        // $pay->signType
    }

    public function notify()
    {
        $pay = Pay::wechat($this->config);

        try{
            $data = $pay->verify(); // 是的，验签就这么简单！

            Log::debug('Wechat notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $pay->success();// laravel 框架中请直接 `return $pay->success()`
    }
}
