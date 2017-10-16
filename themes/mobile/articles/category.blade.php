@extends('default.layouts.master')

@section('title', $category->name . ' - ' . $site->title)

@section('head')
    <link href="{{ asset('themes/default/css/category.css') }}" rel="stylesheet">
    <script src="{{ asset('themes/default/js/category.js') }}"></script>
@endsection

@section('body')
    @include('default.layouts.header')

    <h2>移动端</h2>
    <ul>
        @foreach($articles as $article)
            <li><a href="{{ "detail-$article->id.html" }}">{{ $article->title }}</a></li>
        @endforeach
    </ul>

    @include('default.layouts.footer')
@endsection

@section('js')

@endsection