<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>AMS</title>
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
    <meta name="description" content="">
    <link rel="stylesheet" href="{{ get_static_url('/css/templates/content.css') }}" type="text/css">
    <script src="{{ get_static_url('/plugins/jquery/2.2.4/jquery.min.js') }}"></script>
    <script src="{{ get_static_url('/js/swiper.jquery.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var title = '{{ $content->title }}';
            var subtitle = '{{ $content->subtitle }}';
            var url = '{{ url('/api/contents/share?id=' . $content->id) }}';
            var logo_url = '{{ empty($content->image_url) ? get_image_url('/images/logo.png') : get_image_url($content->image_url) }}';
            var app = navigator.userAgent.toLowerCase();
            $('#shares').click(function () {
                if (/iphone|ipad|ipod/.test(app)) {
                    share(url, title, logo_url, subtitle);
                } else if (/android/.test(app)) {
                    window.JSInterface.share(url, title, logo_url, subtitle);
                }
            })
        })
    </script>
    <style>
        .imgSwiper {
            position: fixed;
            left: 0px;
            top: 100%;
            width: 100%;
            height: 100%;
            background: #000;
            z-index: 100;
        }

        .imgSwiper .swiper-slide {
            display: -webkit-flex;
            align-items: center;
            justify-content: center;
        }

        .imgSwiper .swiper-slide img {
            max-width: 100%;
        }

        .closeSwiper {
            position: fixed;
            font-size: 30px;
            color: #fff;
            top: 0;
            right: 0px;
            z-index: 101;
            display: none;
        }
    </style>
</head>
<body>
<!--头部-->
@if (!empty($header))
<div class="hand_nanchang">
    <a href="javascript:history.go(-1)">
        <div class="back"><img src="{{ get_image_url('/images/back_zs.png') }}"/></div>
    </a>
    <div class="share"><img src="{{ get_image_url('/images/share.png') }}" id="shares"></div>
</div>
<div style="height:4rem"></div>
@endif
<!--头部 end-->
<!--内容主体部分-->
<div class="wrapper">
    <div class="wrapper_cont">
        <div class="content_title">
            {{ $content->title }}
        </div>
        <div class="content_time">
            <span class="author">编辑：{{ $content->author }}</span>
            <span class="time">　{{ empty($content->published_at) ?: $content->published_at->format('m-d H:i') }}</span>
            <span style="float:right;">{{ $content->clicks + $content->views }}</span>
            <span style="float:right;">阅读量:</span>
        </div>
        <!--内容详情部分-->
        <div class="content_mess">
            @if (!empty($content->video_url))
                <video id="video" autoplay="autoplay" controls="controls" width="100%"
                       poster="{{ get_image_url($content->image_url) }}" src="{{ get_video_url($content->video_url) }}">
                    不支持视频播放
                </video>
            @endif
            {!! $content->content !!}
        </div>
        <!--内容详情部分  end-->
        <div class="edit_author">
            <span style="float:left;color:#000">来源：</span>{{ $content->source }}
            <span style="float:right">[责任编辑：{{ $content->user->name }}]</span>
        </div>
    </div>
</div>
<!--内容主体部分 end-->
<script>
    var imgsps;
    $(function () {
        $(document.body).append($("<div>").addClass("imgSwiper").addClass("swiper-container").append($("<div>").addClass("swiper-wrapper")));
        $(document.body).append($("<div>").addClass("closeSwiper").html("<img width='' src='/images/close.png'>"));
        $('.content_mess img').each(function (index, element) {
            $('.imgSwiper .swiper-wrapper').append($("<div>").addClass("swiper-slide").append($("<img>").attr("src", $(element).attr("src"))));
            $(element).attr("ids", index).click(function (e) {
                $('.imgSwiper').css("top", "0px");
                $('.closeSwiper').fadeIn('fast');
                imgsps.slideTo(Number($(this).attr('ids')), 10, false);
            });
        });
        imgsps = new Swiper('.imgSwiper.swiper-container', {});
        $('.closeSwiper').click(function (e) {
            $('.imgSwiper').css("top", "100%");
            $(this).fadeOut('fast');
        });
    });
</script>
</body>
</html>
