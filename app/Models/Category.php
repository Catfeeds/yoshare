<?php

namespace App\Models;

use App\Node;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;

    const LINK_TYPE_NONE = 0;
    const LINK_TYPE_WEB = 1;

    protected $fillable = [
        'site_id',
        'code',
        'name',
        'title',
        'subtitle',
        'likes',
        'parent_id',
        'slug',
        'link_type',
        'link',
        'image_url',
        'cover_url',
        'author',
        'description',
        'content',
        'template',
        'state',
        'sort',
    ];

    public function stateName()
    {
        switch ($this->state) {
            case static::STATE_DISABLED:
                return '已禁用';
                break;
            case static::STATE_ENABLED:
                return '已启用';
                break;
        }
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    public function scopeOwns($query)
    {
        $query->where('site_id', Auth::user()->site_id);
    }

    public function scopeNodes($query)
    {
        if (Auth::user()->roles()->where('id', Role::ID_ADMIN)->exists()) {
            $query->where('site_id', Auth::user()->site_id);
        }
        else {
            $category_ids = Auth::user()->categories->pluck('id')->toArray();
            $query->where('site_id', Auth::user()->site_id)->whereIn('id', $category_ids);
        }
    }

    public static function getSiteTree()
    {
        $categories = Category::owns()->orderBy('sort')->get();

        $root = new Node();
        $root->id = 0;

        static::getNodes($root, $categories);

        return $root->nodes;
    }

    public static function tree($state = '', $parent_id = 0, $show_parent = false)
    {
        if (empty($state)) {
            $categories = Category::nodes()->orderBy('sort')->get();
        } else {
            $categories = Category::nodes()->where('state', $state)->orderBy('sort')->get();
        }

        $parent = Category::find($parent_id);
        if (empty($parent)) {
            $root = new Node();
            $root->id = $parent_id;
            $root->text = '所有栏目';
        }
        else {
            $root = new Node();
            $root->id = $parent->id;
            $root->text = $parent->name;
        }

        static::getNodes($root, $categories);

        if ($show_parent) {
            return [$root];
        }
        else {
            return $root->nodes;
        }
    }

    public static function getNodes($parent, $categories)
    {
        foreach ($categories as $category) {
            if ($category->parent_id == $parent->id) {
                $node = new Node();
                $node->id = $category->id;
                $node->text = $category->name;

                $parent->nodes[] = $node;
                static::getNodes($node, $categories);
            }
        }
    }
}
