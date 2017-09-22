<?php

namespace App\Models;

use Auth;
use Gate;
use Illuminate\Database\Eloquent\SoftDeletes;
use Request;
use Response;

class Survey extends BaseModule
{
    use SoftDeletes;

    const STATE_DELETED = 0;
    const STATE_NORMAL = 1;

    const MULTIPLE_FALSE = 0;
    const MULTIPLE_TRUE = 1;

    const TOP_FALSE = 0;
    const TOP_TRUE = 1;

    const STATES = [
        0 => '已删除',
        1 => '正常',
    ];

    const MULTIPLE = [
        0 => '单选',
        1 => '多选',
    ];

    const STATE_PERMISSIONS = [
        0 => '@survey-delete',
    ];


    protected $fillable = [
        'site_id',
        'title',
        'type',
        'image_url',
        'description',
        'ip',
        'state',
        'member_id',
        'begin_date',
        'end_date',
        'username',
        'is_top',
        'multiple',
        'link',
        'sort'
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

    /**
     * 条件过滤
     *
     * @param $query
     * @param $filters
     */
    public function scopeFilter($query, $filters)
    {
        $query->where(function ($query) use ($filters) {
            empty($filters['recommend']) ?: $query->where('is_top', $filters['recommend']);
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

    public static function table()
    {
        $filter = Request::all();
        $offset = Request::get('offset') ? Request::get('offset') : 0;
        $limit = Request::get('limit') ? Request::get('limit') : 20;

        $site_id = Auth::user()->site_id;

        $surveys = Survey::where('site_id', $site_id)
            ->filter($filter)
            ->orderBy('is_top', 'desc')
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->limit($limit)
            ->get();

        $count = Survey::where('site_id', $site_id)
            ->filter($filter)
            ->count();


        $surveys->transform(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'description' => $item->description,
                'image_url' => $item->image_url,
                'amount' => $item->amount,
                'state_name' => $item->stateName(),
                'ip' => $item->ip,
                'is_top' => $item->is_top,
                'begin_date' => $item->begin_date,
                'end_date' => $item->end_date,
                'created_at' => $item->created_at->toDateTimeString(),
                'updated_at' => $item->updated_at->toDateTimeString(),
            ];
        });

        $ds = new DataSource();
        $ds->rows = $surveys;
        $ds->total = $count;
        return Response::json($ds);

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
        \Session::flash('flash_success', static::STATE_DELETED . '成功!');
    }

//    public function items()
//    {
//        return $this->hasMany(SurveyItem::class);
//    }

    public function data()
    {
        return $this->hasMany(SurveyData::class);
    }

    public function titles()
    {
        return $this->hasMany(SurveyTitle::class);
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function items()
    {
        return $this->morphMany(Item::class, 'refer');

    }
}