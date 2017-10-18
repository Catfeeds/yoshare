@extends('templates.master')
@section('title', '优享简介-北京优享科技有限公司')
@section('css')
    <link href="/css/summary.css" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <div class="banner">
        <img src="{{url($cover_url) }}">
    </div>
    <div class="content">
        <div class="text-bg"></div>
        <div class="text">
            <h2> <div class="circle"></div><span class="num">1</span> 优享公司简介 <span>/</span></h2>
            {!! $content !!}
        </div>
    </div>
@endsection

@section('js')
    <script>
        var _hmt = _hmt || [];
        (function () {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?b3806a4f7376fbb6020ff31c0990726f";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
@endsection
