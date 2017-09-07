<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyTitle extends Model
{
    protected $table = 'survey_titles';

    protected $fillable = [
        'survey_id',
        'title',
    ];

    const OPTIONS_NUM = 4;

    const DEFAULT_OPTIONS = [
        0 => '满意',
        1 => '比较满意',
        2 => '不满意',
        3 => '非常不满意',
    ];

}
