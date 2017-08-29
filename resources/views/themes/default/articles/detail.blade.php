@extends('themes.default.layouts.master')

@section('title', $article->title . ' - ' . $site->title)

@section('head')
    <link href="{{ asset('themes/default/css/detail.css') }}" rel="stylesheet">
    <script src="{{ asset('themes/default/js/detail.js') }}"></script>
@endsection

@section('body')
    @include('themes.default.layouts.header')

    <h2>{{ $article->title }}</h2>
    <div>
        {!! $article->content !!}
    </div>

    @include('themes.default.layouts.footer')
@endsection

@section('js')
<script>
    
</script>
@endsection