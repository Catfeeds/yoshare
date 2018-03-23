@extends('themes.mobile.layouts.master')
@section('title', '商品详情页－北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/detail.css') }}" rel="stylesheet">
    <link href="{{ url('css/swiper.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
    @include('themes.mobile.goods.header')
    <div  class="product">
        <div class="banner swiper-container">
            <div class="swiper-wrapper" id="banner_swiper">
                @foreach($goods->images() as $img)
                <div class="swiper-slide">
                    <img src="{{ $img->url }}" alt="">
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <div class="content">
        <div class="summary">
            <p class="subtitle">{{ $goods->subtitle }}</p>
            <h3 class="title">{{ $goods->name }}</h3>
            <p class="price">￥{{ $goods->sale_price }}/月<span class="time">畅玩无期限</span><span class="rent">原价{{ $goods->price }}元/月</span></p>
        </div>
        <div class="procedure">
            <h4>租赁流程</h4>
            <img src="{{ url('images/goods/process.png') }}" alt="租赁流程">
        </div>
        <div>
            <input type="hidden" id="goods_id" value="{{ $goods->id }}">
            <input type="hidden" id="sale_price" value="{{ $goods->sale_price }}">
        </div>
        <div class="tips" style="display: none">
            <h4>租期计算方式</h4>
            <p>{{ $goods->summary }}</p>
        </div>
        <div class="detail">
            <div class="title">
                <div class="img"><img src="{{ url('images/goods/detail_bg.png') }}" alt=""></div>
            </div>
            <div class="text">{!! $goods->content !!}</div>
        </div>
        <div class="video" style="display: none">
            <video src="{{ $goods->video_url }}" controls="controls">
                您的浏览器不支持 video 标签。
            </video>
        </div>

    </div>
@endsection
@section('js')
    <script src="{{ url('js/swiper.jquery.min.js') }}"></script>
    <script>
        banners = new Swiper('.banner.swiper-container', {
            autoplay: 3000,
            loop: true,
            autoplayDisableOnInteraction: false,
            pagination: '.banner .swiper-pagination',
            paginationClickable: true
        });
    </script>
@endsection