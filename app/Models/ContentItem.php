<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentItem extends Model
{
    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;
    const TYPE_AUDIO = 3;
    const TYPE_LINK = 4;
    const TYPE_FILE = 5;

    protected $fillable = [
        'content_id',
        'type',
        'title',
        'url',
        'description',
        'sort',
    ];
}
