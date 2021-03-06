@extends('themes.default.layouts.master')

@section('title', $module->title . ' - ' . $site->title)

@section('head')
    <link href="{{ asset('themes/default/css/index.css') }}" rel="stylesheet">
    <script src="{{ asset('themes/default/js/index.js') }}"></script>
@endsection

@section('body')
    @include('themes.default.layouts.header')

    <h2>移动端</h2>
    <ul>
        @foreach($site->categories as $category)
            <li><a href="{{ "category-$category->id.html" }}">{{ $category->name }}</a></li>
        @endforeach
    </ul>

    @include('themes.default.layouts.footer')
@endsection

@section('js')

@endsection