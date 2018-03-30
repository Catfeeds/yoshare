<?php

return [
    '1' => [
        'watermark' => [
            'enabled' => true,
            'path' => 'images/watermark.png'
        ],
        'sms' => [
            'url' => 'http://sh2.cshxsp.com/sms.aspx',
            'action' => 'send',
            'account' => 'jkwl171',
            'password' => 'jkwl17166',
            'template' => [
                '0' => '找回密码手机验证码为@,该验证码五分钟内有效，打死也不要告诉别人哦。【优享科技】',
                '1' => '注册验证码为@,该验证码五分钟内有效，打死也不要告诉别人哦。【优享科技】',
                '2' => '验证码为@，仅用于重置密码，请尽快完成密码重置哦。【优享科技】',
                '3' => '绑定手机号验证码为@,该验证码五分钟内有效，打死也不要告诉别人哦。【优享科技】',
                '4' => '手机登录手机号验证码为@,仅用于手机登录，该验证码五分钟内有效，打死也不要告诉别人哦。【优享科技】',
            ],
        ],
        'smsAli' => [
            'accessKeyId' => 'LTAILEdTlZPvoHkW',
            'accessKeySecret' => 'fFMpEHBcMcobS8ma4Sb6UQNZwIRoBC',
            'sign'  => '优享科技',
            'template' => [
                '0' => 'SMS_113461511',
                '1' => 'SMS_113451445',
                '2' => 'SMS_113660681',
                '3' => 'SMS_113451438',
                '4' => 'SMS_129761972',
            ],
        ],

    ],

    'cdn' => [
        'image_url' => '',
        'video_url' => '',
        'static_url' => '',
    ],

    'upload' => [
        'url_prefix' => '',
        'avatar_path' => '/uploads/avatars',
        'video_path' => '/uploads/videos',
        'audio_path' => '/uploads/audios',
        'image_path' => '/uploads/images',
        'file_path' => '/uploads/files',
    ],
];