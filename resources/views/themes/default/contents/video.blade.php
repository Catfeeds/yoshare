@extends('templates.cn.master')
@section('title', '江铃汽车股份有限公司')
@section('css')
    <link href="{{ url('css/news_info.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="nav_min_bg"></div>

    <div class="banner">
        <img src="{{ url($content->category->parent->cover_url) }}">
    </div>


    <div class="content">
        <div class="detail_place"><a href="/">首页</a> >
            @if($content->category->parent->name != '首页')
                {{--<a href="{{ url('categories/list/' . $content->category->parent->id) }}"> {{ $content->category->parent->name }}</a> >--}}
                <a href="{{ url('contents/list/' . $content->category->id) }}"> {{ $content->category->name }}</a>
            @endif
        </div>
        <div class="detail_content">
            <div class="info">
                <time>{{ $content->published_at->format('Y') }}<small>-{{ $content->published_at->format('m') }}-{{ $content->published_at->format('d') }}</small></time>
                <abbr>
                    本篇文章的来源：<span>{{ empty($content->source) ? '江铃汽车股份有限公司' : $content->source }}</span>
                </abbr>
                <acronym>浏览次数（Views） / <span>{{ $content->clicks }}</span></acronym>
                <div class="share">
                    分享此文章
                    <style>
				.bdshare-button-style0-16 .bds_tsina{
					width:33px;
					height:33px;
					background:url(/images/share_w1.gif);
					border:1px solid #ededed;
					padding:0;
				}
				.bdshare-button-style0-16 .bds_tsina:hover{
					border:1px solid #999;
				}
				.bdshare-button-style0-16 .bds_tqq{
					width:33px;
					height:33px;
					background:url(/images/share_w2.gif);
					border:1px solid #ededed;
					padding:0;
				}
				.bdshare-button-style0-16 .bds_tqq:hover{
					border:1px solid #999;
				}
				.bdshare-button-style0-16 .bds_more{
					width:33px;
					height:33px;
					background:url(/images/share_w3.gif);
					border:1px solid #ededed;
					padding:0;
				}
				.bdshare-button-style0-16 .bds_more:hover{
					border:1px solid #999;
				}
				</style>
                <div class="bdsharebuttonbox"><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_more" data-cmd="more"></a></div>
                    <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"{{ $content->title }}","bdMini":"2","bdPic":"","bdStyle":"0","bdSize":"16"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
                </div>
                <a href="{{ url('contents/list/' . $content->category->id) }}" class="back">返回列表</a>
            </div>
            <div class="text_box">
                <div class="detail_text">
                    <h5>{{ $content->title }}</h5>
					
					<div class="font">
						字号调整：
						<span class="small" role="s">小</span>
						<span class="middle now" role="m">中</span>
						<span class="big" role="b">大</span>
					</div>
					
                    @if (!empty($content->video_url))
                        <div class="center-block">
                            <video id="video" width="100%" controls
                                   poster="{{ get_image_url($content->image_url) }}" src="{{ get_video_url(is_mobile() ? $content->m3u8_url : $content->video_url) }}">
                                不支持视频播放
                            </video>
                        </div>
                    @endif
                    {!! $content->content !!}

                    @if(!empty($content->next()))
                        <div class="next">
                            <a href="/contents/show/{{ $content->next()->id }}">{{ $content->next()->title }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="recommand">
        <h4>推荐新闻 （ Recommended News ）</h4>
        <hr>
        <div class="list_item01" id="news">

        </div>
    </div>
@endsection
@section('js')
    <script>
        var mq;
        var isMinStage = false;
        $(function () {
            $('.search_one input').focus(function (e) {
                $(this).addClass('long');
            });
            $('.search_one input').blur(function (e) {
                $(this).removeClass('long');
            });
            $(window).scroll(function () {
                var st = $(document.documentElement).scrollTop() || $(document.body).scrollTop();
                if (st > 58) {
                    $('.header').addClass('fix');
                    $('.nav').addClass('fix');
                } else {
                    $('.header').removeClass('fix');
                    $('.nav').removeClass('fix');
                }
            });
            /*min*/
            $('.nav_min_in').click(function (e) {
                if ($(this).hasClass('close')) {
                    $(this).removeClass('close');
                    $('.nav_min').slideUp(300);
                    $('.nav_min_bg').fadeOut('fast');
                } else {
                    $(this).addClass('close');
                    $('.nav_min').slideDown(300);
                    $('.nav_min_bg').fadeIn('fast');
                }
            });
            $('.nav_min_bg').click(function (e) {
                $('.nav_min_in').removeClass('close');
                $('.nav_min').slideUp(300);
                $(this).fadeOut('fast');
            });
            $('.nav_min .i').each(function (index, element) {
                $(element).click(function (e) {
                    var _fd = $(this).find('.si');
                    if (_fd.length > 0) {
                        $(_fd[0]).slideToggle('fast');
                    }
                });
            });
            //
            mq = window.matchMedia("(max-width:1100px)");
            mq.addListener(function (changed) {
                changeSize640(changed.matches);
            });
            changeSize640(mq.matches);
            //
            $('.about_top .item').each(function (index, element) {
                $($(element).find('p')[0]).click(function (e) {
                    if (isMinStage) {
                         var _ul=$($(this).parent().find('ul')[0]);
								if(!_ul.hasClass('minishow')){
									_ul.slideToggle('fast');
								}
                    }
                });
            });
        });
        function changeSize640(b) {
            isMinStage = b;
            if (b) {
                $('.about_top .item').each(function (index, element) {
                     var _ul=$($(element).find('ul')[0]);
					if(!_ul.hasClass('minishow')){
						_ul.hide();
					}
                });
            } else {
                $('.about_top .item').each(function (index, element) {
                    $($(element).find('ul')[0]).show();
                });
            }
        }

        function getItems(page_size, page) {
            $.ajax({
                url: '/api/contents/recommend',
                data: {
                    'site_id': 1,
                    'page_size': page_size,
                    'page': page,
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
