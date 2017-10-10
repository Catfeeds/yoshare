<?php

namespace App\Http\Controllers;

use App\Models\DataSource;
use App\Models\Survey;
use App\Models\SurveyItem;
use Request;
use Response;

class SurveyItemController extends Controller
{
    public function update($id)
    {
        $input = Request::all();
        $item = SurveyItem::find($id);
        $item->count = $input['count'];

        if ($item == null) {
            return;
        }
        $item->save();
    }

    //TODO tongji unfinished
    public function table($survey_id)
    {
        $survey = Survey::with('subjects')->find($survey_id);
        $subjects = $survey->subjects()->orderBy('id', 'desc')->get();


        foreach ($subjects as $subject) {

            $amount = $subject->items->sum('count');

            $subjects->transform(function ($item) use ($amount) {

                return [
                    'id' => $item->id,
                    'survey_id' => $item->survey_id,
                    'subject' => $item->title,
                    'title' => $item->title,
                    'percent' => $amount == 0 ? 0 : round(($item->count / $amount) * 100) . '%',
                    'count' => $item->count,
                    'sort' => $item->sort,
                ];

            });
        }

        $ds = new DataSource();
        $ds->data = $subjects;

        return Response::json($ds);
    }

    public function show($survey_id)
    {
        return view('admin.surveys.items', compact('survey_id'));
    }
}
