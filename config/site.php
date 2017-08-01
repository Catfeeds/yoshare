<?php

return [
    '1' => [
        'jpush' => [
            'app_key' => '5891f2f9549ba634488f9a74',
            'master_secret' => '9376fda52b4dd8525800cb16',
        ],
        'watermark' => [
            'enabled' => true,
            'path' => 'images/watermark.png'
        ],
        'sms' => [
            'template' => [
                '0' => '',
                '1' => '',
                '2' => '',
                '3' => '',
                '4' => '',
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
        'other_path' => '/uploads/others',
    ],
];