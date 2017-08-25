@extends('themes.default.layouts.master')

@section('title', $site->name)

@section('head')
    <link href="{{ asset('themes/default/css/index.css') }}" rel="stylesheet">
    <script src="{{ asset('themes/default/js/index.js') }}"></script>
@endsection

@section('body')
    @include('themes.default.layouts.header')

    <ul>
        @foreach($site->categories as $category)
            <li><a href="{{ url('articles/category-' . $category->id . '.html') }}">{{ $category->name }}</a></li>
        @endforeach
    </ul>

    @include('themes.default.layouts.footer')
@endsection

@section('js')
<script>
    
</script>
@endsection