@extends('themes.default.layouts.master')

@section('title', $article->title . ' - ' . $site->name)

@section('head')
    <link href="{{ url('themes/default/css/detail.css') }}" rel="stylesheet">
    <script src="{{ url('themes/default/js/detail.js') }}"></script>
@endsection

@section('body')
    @include('themes.default.layouts.header')

    <h2>移动端</h2>
    <h2>{{ $article->title }}</h2>
    <div>
        {!! $article->content !!}
    </div>

    @include('themes.default.layouts.footer')
@endsection

@section('js')

@endsection