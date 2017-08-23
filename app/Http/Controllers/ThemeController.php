<?php

namespace App\Http\Controllers;

use Response;

class ThemeController extends BaseController
{
    public function __construct()
    {
    }

    public function index()
    {
        return view('admin.themes.index');
    }

    public function tree()
    {
        $nodes = [
            [
                'id' => 1,
                'text' => 'default',
                'tags' => ['7', '默认主题'],
                'nodes' => [
                    [
                        'id' => 11,
                        'text' => 'css',
                        'tags' => ['3', '样式'],
                        'nodes' => [
                            [
                                'id' => 111,
                                'text' => 'index.css',
                                'icon' => 'fa fa-file-code-o',
                                'path' => public_path('themes/default/css/index.css'),
                            ],
                            [
                                'id' => 112,
                                'text' => 'detail.css',
                                'icon' => 'fa fa-file-code-o',
                                'path' => public_path('themes/default/css/detail.css'),
                            ],
                            [
                                'id' => 113,
                                'text' => 'page.css',
                                'icon' => 'fa fa-file-code-o',
                                'path' => public_path('themes/default/css/page.css'),
                            ],
                        ],
                    ],
                    [
                        'id' => 12,
                        'text' => 'js',
                        'tags' => ['3', '脚本'],
                        'nodes' => [
                            [
                                'id' => 121,
                                'text' => 'index.js',
                                'icon' => 'fa fa-file-code-o',
                                'path' => public_path('themes/default/js/index.js'),
                            ],
                            [
                                'id' => 122,
                                'text' => 'detail.js',
                                'icon' => 'fa fa-file-code-o',
                                'path' => public_path('themes/default/js/detail.js'),
                            ],
                            [
                                'id' => 123,
                                'text' => 'page.js',
                                'icon' => 'fa fa-file-code-o',
                                'path' => public_path('themes/default/js/page.js'),
                            ],
                        ],
                    ],
                    [
                        'id' => 13,
                        'text' => 'layouts',
                        'tags' => ['1', '布局'],
                        'nodes' => [
                            [
                                'id' => 131,
                                'text' => 'master.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => resource_path('views/themes/default/layouts/master.blade.php'),
                            ],
                            [
                                'id' => 132,
                                'text' => 'header.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => resource_path('views/themes/default/layouts/header.blade.php'),
                            ],
                            [
                                'id' => 133,
                                'text' => 'footer.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => resource_path('views/themes/default/layouts/footer.blade.php'),
                            ],
                        ],
                    ],
                    [
                        'id' => 14,
                        'text' => 'articles',
                        'tags' => ['2', '文章'],
                        'nodes' => [
                            [
                                'id' => 141,
                                'text' => 'index.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => resource_path('views/themes/default/articles/index.blade.php'),
                            ],
                            [
                                'id' => 142,
                                'text' => 'category.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => resource_path('views/themes/default/articles/category.blade.php'),
                            ],
                            [
                                'id' => 143,
                                'text' => 'detail.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => resource_path('views/themes/default/articles/detail.blade.php'),
                            ],
                        ],
                    ],
                    [
                        'id' => 15,
                        'text' => 'pages',
                        'tags' => ['2', '单页'],
                        'nodes' => [
                            [
                                'id' => 151,
                                'text' => 'index.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => resource_path('views/themes/default/pages/index.blade.php'),
                            ],
                            [
                                'id' => 151,
                                'text' => 'detail.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => resource_path('views/themes/default/pages/detail.blade.php'),
                            ],
                        ],
                    ],
                    [
                        'id' => 16,
                        'text' => 'videos',
                        'tags' => ['2', '视频'],
                        'nodes' => [
                            [
                                'id' => 161,
                                'text' => 'index.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => resource_path('views/themes/default/videos/index.blade.php'),
                            ],
                            [
                                'id' => 162,
                                'text' => 'detail.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => resource_path('views/themes/default/videos/detail.blade.php'),
                            ],
                        ],
                    ],
                ],
            ],

        ];
        return Response::json($nodes);
    }

    public function createFile()
    {
        $path = request('path');
    }

    public function readFile()
    {
        $path = request('path');

        //TODO 判断文件是否在public/themes目录下 文件扩展名不能为.php
        //TODO 判断文件是否在resource/themes目录下

        //判断文件是否存在
        if (file_exists($path)) {
            return file_get_contents($path);
        } else {
            return '';
        }
    }

    public function writeFile()
    {
        $path = request('path');
        $data = request('data');

        //TODO 判断文件是否在public/themes目录下 文件扩展名不能为.php
        //TODO 判断文件是否在resource/themes目录下

        //判断目录是否存在，不存在则创建

        file_put_contents($path, $data);

        $this->responseSuccess();
    }
}
