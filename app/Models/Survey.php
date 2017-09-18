<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Request;
use App\Models\DataSource;
use Response;

class Survey extends Model
{
    const STATE_DELETED = 0;

    const STATE_NORMAL = 1;

    const MULTIPLE_FALSE = 0;
    const MULTIPLE_TRUE = 1;

    const TOP_FALSE = 0;
    const TOP_TRUE = 1;

    const MULTIPLE = [
        0 => '单选',
        1 => '多选',
    ];

    protected $fillable = [
        'site_id',
        'title',
        'type',
        'image_url',
        'description',
        'ip',
        'state',
        'amount',
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
        if (isset($filters['state']) && $filters['state'] != '') {
            $query->where('state', $filters['state']);
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
            ->orderBy('is_top','desc')
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

    public function items()
    {
        return $this->hasMany(SurveyItem::class);
    }

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
}