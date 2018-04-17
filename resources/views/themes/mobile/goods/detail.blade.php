@extends('themes.mobile.layouts.master')
@section('title', '商品详情页－游享')
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
            <p class="price">￥{{ $goods->sale_price }}/月 <span class="rent">原价{{ $goods->price }}元/月</span><span
                        class="time" style="display: none"></span></p>
        </div>
        <div class="procedure">
            <h4>租赁流程</h4>
        </div>
        <div><img src="{{ url('images/goods/process.png') }}" alt="租赁流程" width="980"></div>
        <div>
            <input type="hidden" id="goods_id" value="{{ $goods->id }}">
            <input type="hidden" id="sale_price" value="{{ $goods->sale_price }}">
        </div>
        <div class="tips">
            <h4>租期计算方式</h4>
            <p>按月收费，周期最多为37天，从您付费后开始计算，一般游戏会在三天左右到您手中，平台额外给出您四天左右时间归还光盘；请您及时确认收货，查看归还时间；
                如您超过此时间，我们将按超出时间的长短来收取逾期费用，超出1-30天收取该盘的每月单价，以此类推。</p>
        </div>
        <div class="detail" name="detail">
            <div class="title">
                <div class="img"><img src="{{ url('images/goods/detail_bg.png') }}" alt=""></div>
            </div>
            <div class="text" style="font-size: 35px;">{!! $goods->content !!}</div>
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