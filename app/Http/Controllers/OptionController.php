<?php

namespace App\Http\Controllers;

use App\DataSource;
use App\Models\Option;
use App\Models\Site;
use Request;
use Response;
use Gate;

class OptionController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        if (Gate::denies('@option')) {
            $this->middleware('deny403');
        }

        return view('options.index');
    }

    public function update($id)
    {
        $option = Option::find($id);

        if ($option == null) {
            return;
        }

        $option->update(Request::all());
    }

    public function table()
    {
        $options = Option::owns()
                    ->get();

        $names = Site::getNames();

        $options->transform(function ($option) use($names) {
            return [
                'id' => $option->id,
                'code' => $option->code,
                'name' => $option->name,
                'value' => $option->value,
                'site_name' => $names[$option->site_id],
            ];
        });

        $ds = new DataSource();
        $ds->data = $options;

        return Response::json($ds);
    }
}
