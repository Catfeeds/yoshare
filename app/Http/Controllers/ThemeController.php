<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Dropbox\Exception;
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

    public function getAssetNodes($path, $extension)
    {
        $fullPath = theme_asset_path($path);

        $dir = dir($fullPath);
        $dirs = [];
        $files = [];
        while ($file = $dir->read()) {
            if ($file != '.' && $file != '..') {
                if (is_file($fullPath . DIRECTORY_SEPARATOR . $file)) {//当前为文件
                    $files[] = $file;
                } else {//当前为目录
                    $dirs[] = $file;
                }
            }
        }

        $nodes = [];
        foreach ($dirs as $dir) {
            $nodes[] = [
                'text' => $dir,
                'tags' => ['0'],
                'extension' => $extension,
                'path' => $path . DIRECTORY_SEPARATOR . $dir,
                'nodes' => $this->getAssetNodes($path . DIRECTORY_SEPARATOR . $dir),
            ];
        }

        foreach ($files as $file) {
            $nodes[] = [
                'text' => $file,
                'icon' => 'fa fa-file-code-o',
                'path' => $path . DIRECTORY_SEPARATOR . $file,
            ];
        }

        return $nodes;
    }

    public function getThemeNodes($theme)
    {
        $nodes = [
            [
                'text' => 'css',
                'color' => '#00a47a',
                'tags' => [0, '样式'],
                'extension' => '.css',
                'path' => $theme->name . '/css',
                'nodes' => $this->getAssetNodes($theme->name . DIRECTORY_SEPARATOR . 'css', '.css'),
            ],
            [
                'id' => 12,
                'text' => 'js',
                'color' => '#f60',
                'tags' => [0, '脚本'],
                'extension' => '.js',
                'path' => $theme->name . '/js',
                'nodes' => $this->getAssetNodes($theme->name . DIRECTORY_SEPARATOR . 'js', '.js'),
            ],
        ];

        for ($i = 0; $i < count($nodes); $i++) {
            $nodes[$i]['tags'][0] = count($nodes[$i]['nodes']);
        };

        return $nodes;
        $path = theme_view_path($theme->name);
        $dir = dir($path);
        $dictionary = [];
        $files = [];
        while ($file = $dir->read()) {
            if ($file != '.' && $file != '..') {
                if (is_file($path . $file)) {//当前为文件
                    $files[] = $file;
                } else {//当前为目录
                    $this->scanfiles($files[$file], $path . $file . DIRECTORY_SEPARATOR, $file);
                }
            }
        }

    }

    public function tree()
    {
        $themes = Theme::orderBy('name')
            ->get();

        $nodes = [];
        foreach ($themes as $theme) {
            $node = [
                'text' => $theme->name,
                'tags' => [0, $theme->title],
                'extension' => '.blade.php',
                'path' => 'default',
                'nodes' => $this->getThemeNodes($theme)
            ];
            $node['tags'][0] = count($node['nodes']);
            $nodes[] = $node;
        }
        return Response::json($nodes);

        $nodes = [
            [
                'id' => 1,
                'text' => 'default',
                'tags' => ['7', '默认主题'],
                'extension' => '.blade.php',
                'path' => 'default',
                'nodes' => [
                    [
                        'id' => 11,
                        'text' => 'css',
                        'color' => '#00a47a',
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
                        'color' => '#f60',
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
                        'color' => '#08c',
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
                    [
                        'id' => 17,
                        'text' => 'abcd.blade.php',
                        'icon' => 'fa fa-file-code-o',
                        'path' => 'default/abcd.blade.php',
                    ],
                ],
            ],

        ];
        return Response::json($nodes);
    }

    public function readFile()
    {
        $path = request('path');

        $extension = strtolower(pathinfo($path)['extension']);
        if ($extension == 'php') {
            $path = theme_view_path($path);
        } else {
            $path = theme_asset_path($path);
        }

        //判断文件是否存在
        if (file_exists($path)) {
            return $this->responseSuccess(file_get_contents($path));
        } else {
            return $this->responseError('此文件文件不存在');
        }
    }

    public function writeFile()
    {
        $path = request('path');
        $data = request('data');

        $extension = strtolower(pathinfo($path)['extension']);
        if ($extension == 'php') {
            $path = theme_view_path($path);
        } else {
            $path = theme_asset_path($path);
        }

        //判断文件是否存在
        if (!file_exists($path)) {
            return $this->responseError('此文件文件不存在');
        }

        try {
            file_put_contents($path, $data);
            return $this->responseSuccess();
        } catch (Exception $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function createFile()
    {
        $path = request('path');

        $extension = strtolower(pathinfo($path)['extension']);
        if ($extension == 'php') {
            $path = theme_view_path($path);
        } else {
            $path = theme_asset_path($path);
        }

        try {
            file_put_contents($path, '');

            \Session::flash('flash_success', '创建成功!');
            return $this->responseSuccess();
        } catch (Exception $e) {
            return $this->responseError($e->getMessage());
        }
    }

    public function removeFile()
    {
        $path = request('path');

        $extension = strtolower(pathinfo($path)['extension']);
        if ($extension == 'php') {
            $path = theme_view_path($path);
        } else {
            $path = theme_asset_path($path);
        }

        //判断文件是否存在
        if (!file_exists($path)) {
            return $this->responseError('此文件文件不存在');
        }

        try {
            unlink($path);

            \Session::flash('flash_success', '删除成功!');
            return $this->responseSuccess();
        } catch (Exception $e) {
            return $this->responseError($e->getMessage());
        }
    }
}
