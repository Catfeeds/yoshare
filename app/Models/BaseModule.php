<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class BaseModule extends Model
{
    use SoftDeletes;

    const STATE_DELETED = 0;
    const STATE_PUBLISHED = 9;

    const STATES = [];

    const STATE_PERMISSIONS = [];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'refer');
    }

    public function images()
    {
        return $this->files()->where('type', File::TYPE_IMAGE)->orderBy('sort')->get();
    }

    public function audios()
    {
        return $this->files()->where('type', File::TYPE_AUDIO)->orderBy('sort')->get();
    }

    public function videos()
    {
        return $this->files()->where('type', File::TYPE_VIDEO)->orderBy('sort')->get();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'refer');
    }

    public function getCommentCountAttribute()
    {
        return cache_remember($this->getMorphClass() . "-comment-$this->id", 1, function () {
            return $this->comments()->where('state', Comment::STATE_PASSED)->count();
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'refer');
    }

    public function getFavoriteCountAttribute()
    {
        return cache_remember($this->getMorphClass() . "-favorite-$this->id", 1, function () {
            return $this->favorites()->count();
        });
    }

    public function setCreatedAt($value)
    {
        $this->attributes['sort'] = strtotime($value);
        return parent::setCreatedAt($value);
    }

    public function stateName()
    {
        return array_key_exists($this->state, static::STATES) ? static::STATES[$this->state] : '';
    }

    public function scopeOwns($query)
    {
        $query->where('site_id', Auth::user()->site_id);
    }

    public function scopeFilter($query, $filters)
    {
        $query->where(function ($query) use ($filters) {
            empty($filters['id']) ?: $query->where('id', $filters['id']);
            empty($filters['category_id']) ?: $query->where('category_id', $filters['category_id']);
            empty($filters['title']) ?: $query->where('title', 'like', '%' . $filters['title'] . '%');
            empty($filters['start_date']) ?: $query->where('created_at', '>=', $filters['start_date']);
            empty($filters['end_date']) ?: $query->where('created_at', '<=', $filters['end_date']);
        });
        if (isset($filters['state'])) {
            if (!empty($filters['state'])) {
                $query->where('state', $filters['state']);
            } else if ($filters['state'] === strval(static::STATE_DELETED)) {
                $query->onlyTrashed();
            }
        }
    }

    public static function state($input)
    {
        $ids = $input['ids'];
        $state = $input['state'];

        //判断是否有操作权限
        $permission = array_key_exists($state, static::STATE_PERMISSIONS) ? static::STATE_PERMISSIONS[$state] : '';
        if (!empty($permission) && Gate::denies($permission)) {
            return;
        }

        $items = static::withTrashed()
            ->whereIn('id', $ids)
            ->get();
        foreach ($items as $item) {
            $item->state = $state;
            $item->save();
            if ($state == static::STATE_DELETED) {
                $item->delete();
            } else if ($item->trashed()) {
                $item->restore();
            }
            if ($state == static::STATE_PUBLISHED) {
                $item->published_at = Carbon::now();
                $item->save();
            }
        }
    }
}