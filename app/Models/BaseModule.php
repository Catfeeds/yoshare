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

    protected $dates = ['deleted_at'];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'refer');
    }

    public function videos()
    {
        return $this->morphMany(Video::class, 'refer');
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

        $contents = static::withTrashed()
            ->whereIn('id', $ids)
            ->get();
        foreach ($contents as $content) {
            $content->state = $state;
            $content->save();
            if ($state == static::STATE_DELETED) {
                $content->delete();
            } else if ($content->trashed()) {
                $content->restore();
            }
            if ($state == static::STATE_PUBLISHED) {
                $content->published_at = Carbon::now();
                $content->save();
            }
        }
    }
}