<?php

namespace App\Models;

use Auth;
use Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    const STATE_DELETED = 0;
    const STATE_NORMAL = 1;
    const STATE_PASSED = 9;

    const TYPE_ARTICLE = 1;
    const TYPE_QUESTION = 2;

    const STATE_PERMISSIONS = [
        0 => '@comment-delete',
        9 => '@comment-pass',
    ];

    protected $fillable = [
        'site_id',
        'refer_id',
        'refer_type',
        'summary',
        'likes',
        'member_id',
        'ip',
        'user_id',
        'state',
    ];

    public function refer()
    {
        return $this->morphTo();
    }

    public function files()
    {
        return $this->morphMany(File::class, 'refer');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    public function stateName()
    {
        switch ($this->state) {
            case static::STATE_NORMAL:
                return '未审核';
                break;
            case static::STATE_PASSED:
                return '已审核';
                break;
            case static::STATE_DELETED:
                return '已删除';
                break;
        }
    }

    public function scopeOwns($query)
    {
        $query->where('site_id', Auth::user()->site_id);
    }

    public function scopeFilter($query, $filters)
    {
        $query->where(function ($query) use ($filters) {
            !empty($filters['id']) ? $query->where('refer_id', $filters['id']) : '';
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