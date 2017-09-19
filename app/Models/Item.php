<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    const TYPE_IMAGE = 1;
    const TYPE_AUDIO = 2;
    const TYPE_VIDEO = 3;

    protected $fillable = [
        'refer_id',
        'refer_type',
        'type',
        'title',
        'url',
        'summary',
        'sort',
    ];

    public function refer()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->morphMany(Item::class, 'refer');
    }

    public static function sync($type, $content, $urls, $summary = '')
    {
        if (!empty($urls)) {
            $urls = explode(',', trim($urls));

            $content->items()->where('type', $type)->delete();
            foreach ($urls as $key => $url) {
                $content->items()->create([
                    'type' => $type,
                    'title' => '',
                    'summary' => $summary,
                    'sort' => $key,
                    'url' => str_replace(url(''), '', $url),
                ]);
            }
        }
    }
}
