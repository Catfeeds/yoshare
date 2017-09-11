<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Http\Requests;
use App\Models\SurveyItem;
use App\DataSource;
use App\Record;
use App\Column;
use Response;
use Request;

class SurveyItemController extends Controller
{
    public function update($id)
    {
        $item = SurveyItem::find($id);

        if ($item == null) {
            return;
        }
        $item->update(Request::all());
    }

    public function table($survey_id)
    {
        $items = SurveyItem::with('survey', 'survey_title')->where('survey_id', $survey_id)->orderBy('id', 'desc')->get();
        $amount = SurveyItem::where('survey_id', $survey_id)->sum('amount');

        $items->transform(function ($item) use ($amount) {

            return [
                'id' => $item->id,
                'survey_id' => $item->survey_id,
                'title' => $item->title,
                'sub_title' => $item->survey_title->title,
                'master_title' => $item->survey->title,
                'percent' => $amount == 0 ? 0 : round(($item->amount / $amount) * 100) . '%',
                'amount' => $item->amount,
                'sort' => $item->sort,
            ];

        });
        $ds = new DataSource();
        $ds->data = $items;

        return Response::json($ds);
    }

    public function show($survey_id)
    {
        return view('admin.surveys.items', compact('survey_id'));
    }
}
