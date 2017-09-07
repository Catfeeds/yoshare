<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyItem extends Model
{
    protected $fillable = [
        'survey_id',
        'title',
        'image_url',
        'description',
        'amount',
        'percent',
        'amount',
        'state',
        'username',
        'survey_title_id'
    ];

    public static function sum()
    {
        $amount = SurveyItem::sum('amount');
        return $amount;
    }

    public static function getList($survey_id)
    {
        $survey_items = SurveyItem::where('survey_id', $survey_id)
            ->orderBy('created_at')
            ->get();

        return $survey_items;
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function survey_title()
    {
        return $this->belongsTo(SurveyTitle::class, 'survey_title_id');
    }
}
