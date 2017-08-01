<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'refer_id',
        'refer_type',
        'title',
        'url',
        'summary',
        'sort',
    ];

    public function refer()
    {
        return $this->morphTo();
    }

    public static function sync($content, $urls, $summary = '')
    {
        if (!empty($urls)) {
            $urls = explode(',', trim($urls));

            $content->videos()->delete();
            foreach ($urls as $key => $url) {
                $content->videos()->create([
                    'title' => '',
                    'summary' => $summary,
                    'sort' => $key,
                    'url' => str_replace(url(''), '', $url),
                ]);
            }
        }
    }
}
