@extends('themes.default.layouts.master')
@section('title', $content->title . '－北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/detail.css') }}" rel="stylesheet">
    <link href="{{ url('css/swiper.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <div  class="product">
        <div class="banner swiper-container">
            <div class="swiper-wrapper" id="banner_swiper">
                <div class="swiper-slide">
                    <img src="/images/detail.png" alt="">
                </div>
                <div class="swiper-slide">
                    <img src="/images/detail.png" alt="">
                </div>
                <div class="swiper-slide">
                    <img src="/images/detail.png" alt="">
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <div class="content">
        <div class="summary">
            <p class="subtitle">索尼（sony）ps4游戏光盘租赁正版盒装</p>
            <h3 class="title">铁拳7 Tekken7 限定版</h3>
            <p class="price">￥0.7/天<span class="time">30日起租</span><span class="rent">月租20元</span></p>
        </div>
        <div class="procedure">
            <h4>租赁流程</h4>
            <img src="" alt="租赁流程">
        </div>
        <div class="tips">
            <h4>租期计算方式</h4>
            <p>收到设备第二天租期开始，设备到期后3日内发出截止， 设备到期3日内未发货则按续租30日收费，此时您可继续使用30日。</p>
        </div>
        <div class="detail">
            <div class="header">
                <div class=""></div>
            </div>
        </div>
        <div class="video">

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
        function getItems(page_size, page) {
            $.ajax({
                url: '/api/contents/recommend',
                data: {
                    'site_id': 1,
                    'page_size': page_size,
                    'page': page,
                    'category_id' : 61
                },
                success: function (data) {
                    var html = '';
                    if (data.status_code == 200) {
                        $(data.data).each(function (k, obj) {
                            var url = '/contents/' + obj.id;
                            if (obj.link_type == 1)
                            {
                                url = obj.link;
                            }

                            html +='<div class="news_item">' +
                                '<a href="' + url + '" target="_blank"><img src="' + obj.image_url + '"></a>' +
                                '<div class="text">' +
                                '<div class="article_title">' + obj.title + '</div>' +
                                '<p class="explain">' + obj.summary + '</p> ' +
                                '<div class="col"></div>' +
                                '<div class="text_bot">' +
                                '<div class="date">' +
                                '<p>' + obj.time.substr(0, 4) + '<span>' + obj.time.substr(5, 5) + '</span></p></div>' +
                                '<p class="read_more"><a href="' + url + '" target="_blank">阅读详情></a> </p></div></div></div>';
                        });
                        $('#news').html(html);
                    } else {
                        alert(data);
                    }
                },
                error: function () {
                    alert('系统繁忙');
                },
            });
        }
        getItems(4, 1);
    </script>
@endsection