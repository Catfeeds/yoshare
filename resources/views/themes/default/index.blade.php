@extends('themes.default.layouts.master')
@section('title', '北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/swiper.min.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <div class="banner swiper-container">
        <div class="swiper-wrapper" id="banner_swiper">
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="main">
        <div class="category">
            <ul>
                <li><a href="#"><img src="{{ url('images/index/shoot.png') }}" alt=""/></a><h5>射击游戏</h5></li>
                <li><a href="#"><img src="{{ url('images/index/shoot.png') }}" alt=""/></a><h5>格斗游戏</h5></li>
                <li><a href="#"><img src="{{ url('images/index/shoot.png') }}" alt=""/></a><h5>赛车游戏</h5></li>
                <li><a href="#"><img src="{{ url('images/index/shoot.png') }}" alt=""/></a><h5>体育游戏</h5></li>
                <li><a href="#"><img src="{{ url('images/index/shoot.png') }}" alt=""/></a><h5>动作游戏</h5></li>
                <li><a href="#"><img src="{{ url('images/index/shoot.png') }}" alt=""/></a><h5>冒险游戏</h5></li>
                <li><a href="#"><img src="{{ url('images/index/shoot.png') }}" alt=""/></a><h5>角色扮演</h5></li>
                <li><a href="#"><img src="{{ url('images/index/shoot.png') }}" alt=""/></a><h5>沙盒游戏</h5></li>
                <div class="clear"></div>
            </ul>
        </div>
        <div class="activity wrapper">
            <div class="top"><h3>活动专区</h3> <a class="more" href="" alt="more">更多活动</a><div class="clear"></div></div>
            <ul>
                <li>
                    <h4>仁王</h4>
                    <span class="price">15元/月</span><span class="hot-tag"></span>
                    <div class="img"><a href="#"><img src="{{ url('images/index/activity.png') }}" alt="activity"/></a></div>
                </li>
                <li>
                    <h4>仁王</h4>
                    <span class="price">15元/月</span><span class="hot-tag"></span>
                    <div class="img"><a href="#"><img src="{{ url('images/index/activity.png') }}" alt="activity"/></a></div>
                </li>
                <li>
                    <h4>仁王</h4>
                    <span class="price">15元/月</span><span class="hot-tag"></span>
                    <div class="img"><a href="#"><img src="{{ url('images/index/activity.png') }}" alt="activity"/></a></div>
                </li>
                <li>
                    <h4>仁王</h4>
                    <span class="price">15元/月</span><span class="hot-tag"></span>
                    <div class="img"><a href="#"><img src="{{ url('images/index/activity.png') }}" alt="activity"/></a></div>
                </li>
                <div class="clear"></div>
            </ul>
        </div>
        <div class="hot wrapper">
            <div class="top"><h3>热租排行</h3> <a class="more" href="" alt="more">更多热租</a><div class="clear"></div></div>
            <ul>
                <li>
                    <div class="img"><img src="{{ url('images/index/hot.png') }}" alt="hot"/></div>
                    <div class="text">
                        <h4>街头斗士</h4>
                        <div class="intro">这是游戏介绍这是游戏介绍这是游戏介绍这是游戏介绍
                            这是游戏介绍这是游戏介绍这是游戏介绍这是游戏介绍
                            这是游戏介绍这是游戏介绍</div>
                        <span class="price">20元/月</span><a href="#" alt="buy">立即选购</a>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <div class="img"><img src="{{ url('images/index/hot.png') }}" alt="hot"/></div>
                    <div class="text">
                        <h4>街头斗士</h4>
                        <div class="intro">这是游戏介绍这是游戏介绍这是游戏介绍这是游戏介绍
                            这是游戏介绍这是游戏介绍这是游戏介绍这是游戏介绍
                            这是游戏介绍这是游戏介绍</div>
                        <span class="price">20元/月</span><a href="#" alt="buy">立即选购</a>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <div class="img"><img src="{{ url('images/index/hot.png') }}" alt="hot"/></div>
                    <div class="text">
                        <h4>街头斗士</h4>
                        <div class="intro">这是游戏介绍这是游戏介绍这是游戏介绍这是游戏介绍
                            这是游戏介绍这是游戏介绍这是游戏介绍这是游戏介绍
                            这是游戏介绍这是游戏介绍</div>
                        <span class="price">20元/月</span><a href="#" alt="buy">立即选购</a>
                    </div>
                    <div class="clear"></div>
                </li>
                <li>
                    <div class="img"><img src="{{ url('images/index/hot.png') }}" alt="hot"/></div>
                    <div class="text">
                        <h4>街头斗士</h4>
                        <div class="intro">这是游戏介绍这是游戏介绍这是游戏介绍这是游戏介绍
                            这是游戏介绍这是游戏介绍这是游戏介绍这是游戏介绍
                            这是游戏介绍这是游戏介绍</div>
                        <span class="price">20元/月</span><a href="#" alt="buy">立即选购</a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="news">
            <div class="top"><h3 style="margin-left: 56px;">新游推荐</h3> <a style="margin-right: 56px;" class="more" href="" alt="more">更多推荐</a><div class="clear"></div></div>
            <ul>
                <li><a href="#" alt="recommend"><img src="{{ url('images/index/new.png') }}" alt=""/></a></li>
                <li><a href="#" alt="recommend"><img src="{{ url('images/index/new.png') }}" alt=""/></a></li>
                <li><a href="#" alt="recommend"><img src="{{ url('images/index/new.png') }}" alt=""/></a></li>
                <li><a href="#" alt="recommend"><img src="{{ url('images/index/new.png') }}" alt=""/></a></li>
                <li><a href="#" alt="recommend"><img src="{{ url('images/index/new.png') }}" alt=""/></a></li>
            </ul>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ url('js/swiper.jquery.min.js') }}"></script>
    <script>
        function getBanners(page_size, page) {
            $.ajax({
                url: '/api/contents/list',
                data: {
                    'category_id': 5,
                    'page_size': page_size,
                    'page': page,
                },
                success: function (data) {
                    var html = '';
                    if (data.status_code == 200) {
                        $(data.data).each(function (k, obj) {
                            var url = '/contents/show/' + obj.id;
                            if (obj.link_type == 1) {
                                url = obj.link;
                            }

                            html += '<div class="swiper-slide">' +
                                '<img src="' + obj.image_url + '">' +
                                '</div>';
                        });
                        $('#banner_swiper').html(html);
                        banners = new Swiper('.banner.swiper-container', {
                            autoplay: 3000,
                            loop: true,
                            autoplayDisableOnInteraction: false,
                            pagination: '.banner .swiper-pagination',
                            paginationClickable: true
                        });
                    } else {
                        alert(data);
                    }
                },
            });
        }

        getBanners(20, 1);

    </script>
@endsection