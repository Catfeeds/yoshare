@extends('themes.default.layouts.master')

@section('title', $category->name . ' - ' . $site->name)

@section('head')
    <link href="{{ url('themes/mobile/css/list.css') }}" rel="stylesheet">
    <script src="{{ url('themes/mobile/js/list.js') }}"></script>
@endsection

@section('body')
    @include('themes.mobile.layouts.header')

    <h2>移动端</h2>
    <ul>
        @foreach($articles as $article)
            <li><a href="{{ url('articles/detail-' . $article->id) . 'html' }}">{{ $article->title }}</a></li>
        @endforeach
    </ul>

    @include('themes.mobile.layouts.footer')
@endsection

@section('js')

@endsection