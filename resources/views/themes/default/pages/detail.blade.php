@extends('themes.default.layouts.master')

@section('title', $page->title . ' - ' . $site->name)

@section('head')
    <link href="{{ url('themes/default/css/detail.css') }}" rel="stylesheet">
    <script src="{{ url('themes/default/js/detail.js') }}"></script>
@endsection

@section('body')
    @include('themes.default.layouts.header')

    <h2>{{ $page->title }}</h2>
    <div>
        {!! $page->content !!}
    </div>

    @include('themes.default.layouts.footer')
@endsection

@section('js')
<script>
    
</script>
@endsection