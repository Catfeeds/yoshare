@extends('themes.default.layouts.master')

@section('title', $site->name)

@section('head')
    <link href="{{ url('themes/default/css/index.css') }}" rel="stylesheet">
    <script src="{{ url('themes/default/js/index.js') }}"></script>
@endsection

@section('body')
    @include('themes.default.layouts.header')

    <ul>
        @foreach($site->categories as $category)
            <li><a href="{{ url('articles/list-' . $category->id . '.html') }}">{{ $category->name }}</a></li>
        @endforeach
    </ul>

    @include('themes.default.layouts.footer')
@endsection

@section('js')

@endsection