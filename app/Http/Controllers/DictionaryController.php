<?php

namespace App\Http\Controllers;

use App\Models\DataSource;
use App\Http\Requests\DictionaryRequest;
use App\Models\Dictionary;
use App\Models\User;
use App\Models\Site;
use Gate;
use Auth;
use Request;
use Response;

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

        return view('admin.dictionaries.show');
    }

    public function edit($id)
    {
        $dictionary = Dictionary::find($id);

        if ($dictionary == null) {

            \Session::flash('flash_warning', '无此记录');
            return redirect('/admin/dictionaries');
        }

        $user = User::find($id);

        return view('admin.dictionaries.edit', compact('dictionary','user'));
    }

    public function update($id, Request $request)
    {
        $dictionaries = Dictionary::find($id);

        if ($dictionaries == null) {
            \Session::flash('flash_warning', '无此记录');
            return redirect()->to($this->getRedirectUrl())
                ->withInput($request->input());
        }
        $dictionaries->update(Request::all());

        \Session::flash('flash_success', '修改成功!');
        return redirect('/admin/dictionaries');
    }

    public function destroy($id)
    {
        $dictionary = Dictionary::find($id);
        if ($dictionary == null) {
            \Session::flash('flash_warning', '无此记录');
            return;
        }
        $dictionary->delete();
        \Session::flash('flash_success', '删除成功');
    }

    public function create()
    {
        return view('admin.dictionaries.create');
    }

    public function store(DictionaryRequest $request)
    {
        $input = Request::all();
        $input['site_id'] = Auth::user()->site_id;
        Dictionary::create($input);
        \Session::flash('flash_success', '添加成功');
        return redirect('/admin/dictionaries');
    }

    public function table()
    {
        $dictionaries = Dictionary::owns()
                        ->get();

        $names = Site::getNames();

        $dictionaries->transform(function ($dictionary) use ($names) {
            return [
                'id' => $dictionary->id,
                'code' => $dictionary->code,
                'name' => $dictionary->name,
                'value' => $dictionary->value,
                'site_id' => $names[$dictionary->site_id],
                'created_at' => $dictionary->created_at->format('Y-m-d H:i:s'),
            ];
        });

        $ds = new DataSource();
        $ds->data = $dictionaries;

        return Response::json($ds);
    }
}
