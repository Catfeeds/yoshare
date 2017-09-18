<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    const STATE_DELETED = 0;
    const STATE_NORMAL = 1;

    const MULTIPLE_FALSE = 0;
    const MULTIPLE_TRUE = 1;

    const TOP_TRUE = 1;
    const TOP_FALSE = 0;

    const LINK_TYPE_NONE = 0;
    const LINK_TYPE_WEB = 1;

    protected $fillable = [
        'site_id',
        'title',
        'multiple',
        'image_url',
        'link',
        'description',
        'begin_date',
        'end_date',
        'amount',
        'comments',
        'state',
        'username',
        'is_top',
        'likes'
    ];

    public function stateName()
    {
        switch ($this->state) {
            case static::STATE_NORMAL:
                return '正常';
                break;
            case static::STATE_DELETED:
                return '已删除';
                break;
        }
    }

    public static function getLinkTypes()
    {
        return [
            static::LINK_TYPE_NONE => '无',
            static::LINK_TYPE_WEB => '网址',
        ];
    }

    public function items()
    {
        return $this->hasMany(VoteItem::class);
    }

    public function data()
    {
        return $this->hasMany(VoteData::class);
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
            empty($filters['recommend']) ?: $query->where('is_top', 'like', '%' . $filters['recommend'] . '%');
            empty($filters['start_date']) ?: $query->where('created_at', '>=', $filters['start_date']);
            empty($filters['end_date']) ?: $query->where('created_at', '<=', $filters['end_date']);
        });
        if (isset($filters['state']) && $filters['state'] != '') {
            $query->where('state', $filters['state']);
        }
    }

}
