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

    //TODO
    public function table($survey_id)
    {
        $survey = Survey::with('subjects')->find($survey_id);

        $subjects = $survey->subjects()->orderBy('id', 'desc')->get();

        $subjects->transform(function ($subject) {
            $titles = $subject->items()->get();

            $amount = $subject->items->sum('count');

            foreach ($titles as $title) {
                return [
                    'id' => $title->id,
                    'survey_id' => $title->refer_id,
                    'subject' => $subject->title,
                    'title' => $title->title,
                    'percent' => $amount == 0 ? 0 : round(($subject->count / $amount) * 100) . '%',
                    'count' => $subject->count,
                    'sort' => $title->sort,
                ];
            }
        });
        $ds = new DataSource();
        $ds->data = $subjects;
        return Response::json($ds);
    }


    public function show($survey_id)
    {
        return view('admin.surveys.items', compact('survey_id'));
    }
}
