<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    const ID_DEFAULT = 1;

    protected $fillable = [
        'name',
        'company',
        'desktop_theme',
        'mobile_theme',
        'app_key',
        'master_secret',
        'username',
    ];

    public function getThemeAttribute()
    {
        if (is_mobile() && !empty($this->mobile_theme)) {
            return $this->mobile_theme;
        } else {
            return $this->desktop_theme;
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
            $names[$site->id] = $site->title;
        }
        return $names;
    }
}
