<?php

namespace App\Http\Controllers;

use App\Http\Requests\DictionaryRequest;
use App\Models\DataSource;
use App\Models\Dictionary;
use Auth;
use Gate;
use Request;
use Response;
use DB;

class DictionaryController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        if (Gate::denies('@dictionaries')) {
            $this->middleware('deny403');
        }

        $parent_id = Request::get('parent_id') ?: 0;

        return view('admin.dictionaries.index', compact('parent_id'));
    }

    public function create($parent_id)
    {
        return view('admin.dictionaries.create', compact('parent_id'));
    }

    public function store(DictionaryRequest $request)
    {
        $input = Request::all();
        $parent_id = $input['parent_id'];

        $sort = Dictionary::select(DB::raw('max(sort) as max'))
            ->where('parent_id', '=', $parent_id)
            ->first()->max;

        $sort += 1;

        $input['sort'] = $sort;
        $input['parent_id'] = $parent_id;
        $input['site_id'] = \Auth::user()->site_id;

        Dictionary::create($input);

        $url = '/admin/dictionaries?parent_id=' . $parent_id;
        \Session::flash('flash_success', '添加成功');
        return redirect($url);
    }


    public function edit($id)
    {

    }

    public function update($id, Request $request)
    {

    }

    public function save($id)
    {
        $dictionary = Dictionary::find($id);

        if ($dictionary == null) {
            return;
        }

        $dictionary->update(Request::all());
    }

    public function destroy($id)
    {
        $dictionary = Dictionary::find($id);
        $dictionary->delete();
    }

    public function tree()
    {
        return Response::json(Dictionary::tree('', 0, false));
    }

    public function table($parent_id)
    {
        $dictionaries = Dictionary::owns()
            ->where('parent_id', $parent_id)
            ->orderBy('sort')
            ->get();

        $dictionaries->transform(function ($dictionary) {
            return [
                'id' => $dictionary->id,
                'site_id' => $dictionary->site->title,
                'parent_id' => $dictionary->parent_id,
                'code' => $dictionary->code,
                'name' => $dictionary->name,
                'value' => $dictionary->value,
                'sort' => $dictionary->sort,
            ];
        });

        $ds = new DataSource();
        $ds->data = $dictionaries;

        return Response::json($ds);
    }

}
