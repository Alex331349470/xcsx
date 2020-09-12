<?php

return [
    'wechat' => [
        'app_id'      => 'wx060853c6aa6cdaee',   // 公众号 app id
        'mch_id'      => '1602211774',  // 第一步获取到的商户号
        'key'         => 'xE8ZJjbNkC1ioUYUjqUK7rFYSrPyGuqX', // 刚刚设置的 API 密钥
        'cert_client' => resource_path('wechat_pay/apiclient_cert.pem'),
        'cert_key'    => resource_path('wechat_pay/apiclient_key.pem'),
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];
