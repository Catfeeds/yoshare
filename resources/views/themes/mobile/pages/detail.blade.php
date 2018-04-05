@extends('themes.mobile.master')
@section('title', '活动中心-游享')
@section('css')
    <link href="{{ url('css/member.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/cart.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')

    @include('themes.mobile.layouts.header')

    <div class="u-wrapper">
        <div class="vip-text">
            <h3>{{ $page->subtitle }}</h3>
            <div class="content" style="padding-top: 40px;">
                {!! $page->content !!}
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>

    </script>
@endsection