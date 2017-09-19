<?php

namespace App\Models;

use Auth;
use Gate;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vote extends Item
{
    use SoftDeletes;

    const STATE_DELETED = 0;
    const STATE_NORMAL = 1;

    const MULTIPLE_FALSE = 0;
    const MULTIPLE_TRUE = 1;

    const TOP_TRUE = 1;
    const TOP_FALSE = 0;

    const LINK_TYPE_NONE = 0;
    const LINK_TYPE_WEB = 1;

    const STATE_PERMISSIONS = [
        0 => '@vote-delete',
    ];

    const STATES = [
        0 => '已删除',
        1 => '正常',
    ];

    protected $fillable = [
        'site_id',
        'title',
        'multiple',
        'image_url',
        'link_type',
        'link',
        'content',
        'begin_date',
        'end_date',
        'amount',
        'state',
        'member_id',
        'user_id',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'refer');
    }

    public function likes()
    {
        return $this->morphOne(Like::class, 'refer');
    }

    public function data()
    {
        return $this->hasMany(VoteData::class);
    }

    public function stateName()
    {
        return array_key_exists($this->state, static::STATES) ? static::STATES[$this->state] : '';
    }

    public static function getLinkTypes()
    {
        return [
            static::LINK_TYPE_NONE => '无',
            static::LINK_TYPE_WEB => '网址',
        ];
    }

    public function scopeOwns($query)
    {
        $query->where('site_id', Auth::user()->site_id);
    }

    /**
     * 条件过滤
     *
     * @param $query
     * @param $filters
     */
    public function scopeFilter($query, $filters)
    {
        $query->where(function ($query) use ($filters) {
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
        }
    }

}
