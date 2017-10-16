@extends('default.layouts.master')

@section('title', $site->title)

@section('head')
    <link href="{{ asset('themes/default/css/index.css') }}" rel="stylesheet">
    <script src="{{ asset('themes/default/js/index.js') }}"></script>
@endsection

@section('body')
    @include('default.layouts.header')

    <h2>移动端</h2>
    <ul>
        @foreach($pages as $page)
            <li><a href="{{ url('pages/detail-' . $page->id . '.html') }}">{{ $page->title }}</a></li>
        @endforeach
    </ul>

    @include('default.layouts.footer')
@endsection

@section('js')

@endsection