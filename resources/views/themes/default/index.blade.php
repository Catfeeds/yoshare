@extends('themes.default.layouts.master')

@section('title', $site->title)

@section('head')
    <link href="{{ asset('themes/default/css/index.css') }}" rel="stylesheet">
    <script src="{{ asset('themes/default/js/index.js') }}"></script>
@endsection

@section('body')
    @include('themes.default.layouts.header')

    <ul>
        <li><a href="articles/index.html">文章</a></li>
        <li><a href="pages/index.html">页面</a></li>
    </ul>

    @include('themes.default.layouts.footer')
@endsection

@section('js')

@endsection