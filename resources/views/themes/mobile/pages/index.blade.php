@extends('themes.mobile.layouts.master')

@section('title', $site->title)

@section('head')
    <link href="{{ asset('themes/default/css/index.css') }}" rel="stylesheet">
    <script src="{{ asset('themes/default/js/index.js') }}"></script>
@endsection

@section('body')
    @include('themes.default.layouts.header')

    <h2>移动端</h2>
    <ul>
        @foreach($pages as $page)
            <li><a href="{{ url('pages/detail-' . $page->id . '.html') }}">{{ $page->title }}</a></li>
        @endforeach
    </ul>

    @include('themes.mobile.layouts.footer')
@endsection

@section('js')

@endsection