@extends('themes.default.layouts.master')

@section('title', $category->name . ' - ' . $site->name)

@section('head')
    <link href="{{ asset('themes/default/css/category.css') }}" rel="stylesheet">
    <script src="{{ asset('themes/default/js/category.js') }}"></script>
@endsection

@section('body')
    @include('themes.default.layouts.header')

    <ul>
        @foreach($articles as $article)
            <li><a href="{{ url('articles/detail-' . $article->id . '.html') }}">{{ $article->title }}</a></li>
        @endforeach
    </ul>

    @include('themes.default.layouts.footer')
@endsection

@section('js')
<script>
    
</script>
@endsection