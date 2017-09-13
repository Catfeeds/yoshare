<?php

namespace App\Http\Controllers;

use App\Models\DataSource;
use App\Models\VoteItem;
use Request;
use Response;

class VoteItemController extends Controller
{
    public function update($id)
    {
        $item = VoteItem::find($id);

        if ($item == null) {
            return;
        }
        $item->update(Request::all());
    }

    public function table($vote_id)
    {
        $items = VoteItem::where('vote_id', $vote_id)->orderBy('id', 'desc')->get();
        $amount = VoteItem::where('vote_id', $vote_id)->sum('amount');

        $items->transform(function ($item) use ($amount) {

            return [
                'id' => $item->id,
                'vote_id' => $item->vote_id,
                'title' => $item->title,
                'percent' => $amount == 0 ? 0 : round(($item->amount / $amount) * 100) . '%',
                'amount' => $item->amount,
                'sort' => $item->sort,
            ];

        });
        $ds = new DataSource();
        $ds->data = $items;

        return Response::json($ds);
    }

    public function show($vote_id)
    {
        return view('votes.items', compact('vote_id'));
    }
}
