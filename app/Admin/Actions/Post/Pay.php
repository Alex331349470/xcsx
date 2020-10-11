<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Encore\Admin\Admin;
use Illuminate\Database\Eloquent\Model;

class Pay extends RowAction
{
    public $name = '支付';
    protected $wechat_data = [];

    public function handle(Model $model)
    {

        $openid = (new \Encore\Admin\Admin)->user()->openId;
        $url = env('APP_URL') . '/api/v1/test';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = json_decode(curl_exec($ch), true);
        $this->wechat_data = $data;

        curl_close($ch);

        $this->resolveAction($data);

        return $this->response()->info('支付');
    }

    protected function buildActionPromise()
    {
        return <<<SCRIPT
        var process = new Promise(function (resolve,reject) {

            Object.assign(data, {
                _token: $.admin.token,
                _action: '{$this->getCalledClass()}',
            });

            $.ajax({
                method: 'GET',
                url: '{$this->getHandleRoute()}',
                data: data,
                success: function (data) {
                    resolve([data, target]);
                },
                error:function(request){
                    reject(request);
                }
            });
        });

SCRIPT;
    }


    public function resolveAction($data)
    {
        $script = <<<SCRIPT
    WeixinJSBridge.invoke(
                    'getBrandWCPayRequest', {
                        "appId": {$data['appId']} ,     //公众号名称，由商户传入
                        "timeStamp": {$data['timeStamp']},         //时间戳，自1970年以来的秒数
                        "nonceStr": {$data['nonceStr']}, //随机串
                        "package":' {$data['package']},
                        "signType":{$data['signType']},         //微信签名方式：
                        "paySign":{$data['paySign']} //微信签名
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