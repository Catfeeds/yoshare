@extends('themes.default.layouts.master')

@section('title', $page->title . ' - ' . $site->name)

@section('head')
    <link href="{{ url('themes/mobile/css/detail.css') }}" rel="stylesheet">
    <script src="{{ url('themes/mobile/js/detail.js') }}"></script>
@endsection

@section('body')
    @include('themes.mobile.layouts.header')

    <h2>移动端</h2>
    <h2>{{ $page->title }}</h2>
    <div>
        {!! $page->content !!}
    </div>

    @include('themes.mobile.layouts.footer')
@endsection

@section('js')

@endsection