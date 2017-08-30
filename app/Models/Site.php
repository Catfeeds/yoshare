<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Theme;

class Site extends Model
{
    const ID_DEFAULT = 1;

    protected $fillable = [
        'name',
        'title',
        'default_theme',
        'mobile_theme',
        'jpush_app_key',
        'jpus_app_secret',
        'username',
        'directory',
        'domain',
        'wechat_app_id',
        'wechat_secret'

    ];

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

    public static function getNames()
    {
        $sites = Site::all();
        $names = [];
        foreach ($sites as $site) {
            $names[$site->id] = $site->name;
        }
        return $names;
    }

    public  static function getThemes(){
        $theme = Theme::all();
        $themes = [];
        foreach ($theme as  $row) {
            $themes[$row->id] = $row->title;
        }
        return $themes;
    }
}
