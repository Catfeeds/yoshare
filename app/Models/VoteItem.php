<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteItem extends Model
{
    protected $fillable = [
        'vote_id',
        'title',
        'image_url',
        'description',
        'amount',
        'percent',
        'amount',
        'state',
        'username',
    ];

    public static function sum()
    {
        $amount= VoteItem::sum('amount');
        return $amount;
    }

    public static function getList($vote_id)
    {
        $vote_items = VoteItem::where('vote_id', $vote_id)
            ->orderBy('sort')
            ->get();

        return $vote_items;
    }

    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }
}
