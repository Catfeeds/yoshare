<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Site extends Model
{
    const ID_DEFAULT = 1;

    protected $fillable = [
        'name',
        'title',
        'domain',
        'directory',
        'default_theme_id',
        'mobile_theme_id',
        'jpush_app_key',
        'jpush_app_secret',
        'wechat_app_id',
        'wechat_secret',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function default_theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function mobile_theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function getThemeAttribute()
    {
        if (is_mobile() && !empty($this->mobile_theme)) {
            return $this->mobile_theme;
        } else {
            return $this->default_theme;
        }
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
    public static function stores($input)
    {
        $site_id = Auth::user()->site_id;
        $input['user_id'] = Auth::user()->id;

        $site = self::create($input);

        //添加站点时，添加默认菜单
        $menu_content = $site->menus()->create([
            'site_id' => $site_id,
            'parent_id' => Menu::ID_ROOT,
            'name' => '内容管理',
            'url' => '#',
            'icon' => 'fa-edit',
            'sort' => 0
        ]);

        $site->menus()->create([
            'site_id' => $site_id,
            'parent_id' => $menu_content->id,
            'name' => '文章管理',
            'url' => '/admin/articles',
            'permission' => '@article',
            'icon' => 'fa-file-o',
            'sort' => 1
        ]);

        $site->menus()->create([
            'site_id' => $site_id,
            'parent_id' => $menu_content->id,
            'name' => '单页管理',
            'url' => '/admin/pages',
            'permission' => '@page',
            'icon' => 'fa-file-o',
            'sort' => 2
        ]);

        $site->menus()->create([
            'site_id' => $site_id,
            'parent_id' => $menu_content->id,
            'name' => '问答管理',
            'url' => '/admin/questions',
            'permission' => '@question',
            'icon' => 'fa-question-circle',
            'sort' => 3
        ]);

        $site->menus()->create([
            'site_id' => $site_id,
            'parent_id' => $menu_content->id,
            'name' => '问卷管理',
            'url' => '/admin/surveies',
            'icon' => 'fa-file-circle',
            'sort' => 4
        ]);

        $site->menus()->create([
            'site_id' => $site_id,
            'parent_id' => $menu_content->id,
            'name' => '投票管理',
            'url' => '/admin/votes',
            'permission' => '@vote',
            'icon' => 'fa-file-o',
            'sort' => 5
        ]);

        $menu_member = $site->menus()->create([
            'site_id' => $site_id,
            'parent_id' => self::ID_ROOT,
            'name' => '会员管理',
            'url' => '#',
            'icon' => 'fa-user',
            'sort' => 0
        ]);

        $site->menus()->create([
            'site_id' => $site_id,
            'parent_id' => $menu_member->id,
            'name' => '会员管理',
            'url' => '/admin/members',
            'permission' => '@member',
            'icon' => 'fa-user-o',
            'sort' => 1
        ]);

        $menu_log = $site->menus()->create([
            'site_id' => $site_id,
            'parent_id' => self::ID_ROOT,
            'name' => '日志查询',
            'url' => '#',
            'icon' => 'fa-calendar',
            'sort' => 0
        ]);

        $site->menus()->create([
            'site_id' => $site_id,
            'parent_id' => $menu_log->id,
            'name' => '推送日志',
            'url' => '/admin/members',
            'permission' => '@member',
            'icon' => 'fa-envelope-o',
            'sort' => 1
        ]);

        $site->menus()->create([
            'site_id' => $site_id,
            'parent_id' => $menu_log->id,
            'name' => '短信日志',
            'url' => '/admin/members',
            'permission' => '@member',
            'icon' => 'fa-envelope-o',
            'sort' => 2
        ]);

        \Session::flash('flash_success', '添加成功');
        return true;
    }

    public static function getNames()
    {
        $sites = Site::all();
        $names = [];
        foreach ($sites as $site) {
            $names[$site->id] = $site->title;
        }
        return $names;
    }

    public static function getThemes()
    {
        $theme = Theme::all();
        $themes = [];
        foreach ($theme as $row) {
            $themes[$row->id] = $row->title;
        }
        return $themes;
    }

    public function publish($theme, $device = '')
    {
        $modules = Module::where('state', Module::STATE_ENABLE)->get();

        //创建站点目录
        $path = public_path("$this->directory/$theme->name");
        if (!is_dir($path)) {
            //创建模块目录
            @mkdir($path, 0755, true);
        }

        //生成首页
        $html = curl_get("http://$this->domain/index.html", [CURLOPT_USERAGENT => $device]);
        $file_html = "$path/index.html";
        file_put_contents($file_html, $html);

        foreach ($modules as $module) {
            $rows = call_user_func([$module->model_class, 'all']);
            $categories = Category::where('module_id', $module->id)->get();

            $path = public_path("$this->directory/$theme->name/$module->plural");
            if (!is_dir($path)) {
                //创建模块目录
                @mkdir($path, 0755, true);
            }

            //生成列表页
            $html = curl_get("http://$this->domain/$module->plural/index.html", [CURLOPT_USERAGENT => $device]);
            $file_html = "$path/index.html";
            file_put_contents($file_html, $html);

            //生成栏目页
            if ($module->fields()->where('name', 'category_id')->count()) {
                foreach ($categories as $category) {
                    $html = curl_get("http://$this->domain/$module->plural/category-$category->id.html", [CURLOPT_USERAGENT => $device]);
                    $file_html = "$path/category-$category->id.html";
                    file_put_contents($file_html, $html);
                }
            }

            //生成详情页
            foreach ($rows as $row) {
                $html = curl_get("http://$this->domain/$module->plural/detail-$row->id.html", [CURLOPT_USERAGENT => $device]);
                $file_html = "$path/detail-$row->id.html";
                file_put_contents($file_html, $html);
            }
        }
    }
}
