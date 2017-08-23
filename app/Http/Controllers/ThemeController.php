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
                'extension' => '.blade.php',
                'nodes' => [
                    [
                        'id' => 11,
                        'text' => 'css',
                        'tags' => ['3', '样式'],
                        'extension' => '.css',
                        'path' => 'default/css',
                        'nodes' => [
                            [
                                'id' => 111,
                                'text' => 'index.css',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/css/index.css',
                            ],
                            [
                                'id' => 112,
                                'text' => 'detail.css',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/css/detail.css',
                            ],
                            [
                                'id' => 113,
                                'text' => 'page.css',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/css/page.css',
                            ],
                        ],
                    ],
                    [
                        'id' => 12,
                        'text' => 'js',
                        'tags' => ['3', '脚本'],
                        'extension' => '.js',
                        'path' => 'default/js',
                        'nodes' => [
                            [
                                'id' => 121,
                                'text' => 'index.js',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/js/index.js',
                            ],
                            [
                                'id' => 122,
                                'text' => 'detail.js',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/js/detail.js',
                            ],
                            [
                                'id' => 123,
                                'text' => 'page.js',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/js/page.js',
                            ],
                        ],
                    ],
                    [
                        'id' => 13,
                        'text' => 'layouts',
                        'tags' => ['1', '布局'],
                        'extension' => '.blade.php',
                        'path' => 'default/layouts',
                        'nodes' => [
                            [
                                'id' => 131,
                                'text' => 'master.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/layouts/master.blade.php',
                            ],
                            [
                                'id' => 132,
                                'text' => 'header.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/layouts/header.blade.php',
                            ],
                            [
                                'id' => 133,
                                'text' => 'footer.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/layouts/footer.blade.php',
                            ],
                        ],
                    ],
                    [
                        'id' => 14,
                        'text' => 'articles',
                        'tags' => ['2', '文章'],
                        'extension' => '.blade.php',
                        'path' => 'default/articles',
                        'nodes' => [
                            [
                                'id' => 141,
                                'text' => 'index.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/articles/index.blade.php',
                            ],
                            [
                                'id' => 142,
                                'text' => 'category.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/articles/category.blade.php',
                            ],
                            [
                                'id' => 143,
                                'text' => 'detail.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/articles/detail.blade.php',
                            ],
                        ],
                    ],
                    [
                        'id' => 15,
                        'text' => 'pages',
                        'tags' => ['2', '单页'],
                        'extension' => '.blade.php',
                        'path' => 'default/pages',
                        'nodes' => [
                            [
                                'id' => 151,
                                'text' => 'index.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/pages/index.blade.php',
                            ],
                            [
                                'id' => 151,
                                'text' => 'detail.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/pages/detail.blade.php',
                            ],
                        ],
                    ],
                    [
                        'id' => 16,
                        'text' => 'videos',
                        'tags' => ['2', '视频'],
                        'extension' => '.blade.php',
                        'path' => 'default/videos',
                        'nodes' => [
                            [
                                'id' => 161,
                                'text' => 'index.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/videos/index.blade.php',
                            ],
                            [
                                'id' => 162,
                                'text' => 'detail.blade.php',
                                'icon' => 'fa fa-file-code-o',
                                'path' => 'default/videos/detail.blade.php',
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

        $extension = strtolower(pathinfo($path)['extension']);
        if ($extension == 'php') {
            $path = theme_view_path($path);
        }
        else{
            $path = theme_asset_path($path);
        }

        file_put_contents($path, '');

        return $this->responseSuccess();
    }

    public function readFile()
    {
        $path = request('path');

        $extension = strtolower(pathinfo($path)['extension']);
        if ($extension == 'php') {
            $path = theme_view_path($path);
        }
        else{
            $path = theme_asset_path($path);
        }

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

        $extension = strtolower(pathinfo($path)['extension']);
        if ($extension == 'php') {
            $path = theme_view_path($path);
        }
        else{
            $path = theme_asset_path($path);
        }

        //判断文件是否存在
        if (!file_exists($path)) {
            return $this->responseError('此文件文件不存在');
        }

        file_put_contents($path, $data);

        return $this->responseSuccess();
    }
}
