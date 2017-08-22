@extends('themes.default.layouts.master')

@section('title', $site->name)

@section('head')
    <link href="{{ url('themes/mobile/css/list.css') }}" rel="stylesheet">
    <script src="{{ url('themes/mobile/js/list.js') }}"></script>
@endsection

@section('body')
    @include('themes.mobile.layouts.header')

    <h2>移动端</h2>
    <ul>
        @foreach($pages as $page)
            <li><a href="{{ url('pages/detail-' . $page->id) . 'html' }}">{{ $page->title }}</a></li>
        @endforeach
    </ul>

    @include('themes.mobile.layouts.footer')
@endsection

@section('js')

@endsection