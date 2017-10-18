@extends('themes.default.master')
@section('title', '北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/news02.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="nav_min_bg"></div>

    <div class="banner">
        <img src="{{ url($category->parent->cover_url) }}">
    </div>
    <div class="content clear">
        <div class="tabs tabs01">
            @foreach($category->parent->children()->where('state', \App\Models\Category::STATE_ENABLED)->orderBy('sort')->get() as $item)
                <a href="{{ url('cn/contents/list/' . $item->id) }}"
                   class="{{ $item->id == $category->id ? 'ac' : '' }}">{{ $item->name }}</a>
            @endforeach
        </div>
        <div class="tab_item">
            <div class="item1">
                <div class="title">
                    <h4>{{ $category->name }}</h4>
                </div>
                <div class="all_news clear">
                    <div class="all_news_right">
                        <input type="text" id="keywords">
                        <button id="search">搜索</button>
                    </div>
                </div>
                <div class="news_list" id="list">
                </div>
                <p class="load_more" id="load_more"><a href="javascript:;">加载更多新闻</a><img
                            src="{{ url('images/load_icon.png') }}">
                </p>
                <div class="loading" id="loading"></div>
            </div>
        </div>
        <div class="tabs tabs02">
        </div>
    </div>
@endsection
@section('js')
    <script>
        var mq;
        var isMinStage = false;
        $(function () {
            $(".r_bot p").click(function () {
                $(".r_bot .year").slideToggle(200);
            })

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

        var num = 0;
        var count = 0;
        function getItems(category_id, page_size, keywords) {
            if (num == 0) {
                $('#list').html('');
            }
            num++;

            $.ajax({
                url: '/api/contents/list',
                data: {
                    'site_id': 1,
                    'word': keywords,
                    'category_id': category_id,
                    'page_size': page_size,
                    'page': num,
                },
                success: function (data) {
                    var html = '';
                    if (data.status_code == 200 && data.data.length > 0) {
                        html += '<div class="list_item01">';
                        $(data.data).each(function (k, obj) {
                            k++;

                            var url = '/contents/show/' + obj.id;
                            if (obj.link_type == 1) {
                                url = obj.link;
                            }

                            html += '<div class="news_item">' +
                                    '<a href="' + url + '" target="_blank"><img src="' + obj.image_url + '"></a>' +
                                    '<div class="text">' +
                                    '<div class="article_title">' + obj.title + '</div>' +
                                    '<p class="explain">' + obj.summary + '</p> ' +
                                    '<div class="col"></div>' +
                                    '<div class="text_bot">' +
                                    '<div class="date">' +
                                    '<p>' + obj.time.substr(0, 4) + '<span>' + obj.time.substr(5, 5) + '</span></p>' +
                                    '</div><p class="read_more"><a href="' + url + '" target="_blank">阅读详情></a></p></div></div></div>';

                            if (k == 4) {
                                html += '</div><div class="list_item01">';
                            }

                            count++;
                        });
                        html += '</div>';
                        $('#list').append(html);
                    }
                },
                error: function () {
                    alert('系统繁忙');
                },
                complete: function () {
                    $("#loading").css("display", "none");
                    $('#load_more').css("display", "block");
                }
            });
        }

        getItems('{{ $category->id }}', 8, '');

        $('#load_more').click(function () {
            $('#load_more').css("display", "none");
            $("#loading").css("display", "block");

            var keywords = $.trim($('#keywords').val());
            getItems('{{ $category->id }}', 8, keywords);
        });

        $('#search').click(function () {
            $('#load_more').css("display", "none");
            $("#loading").css("display", "block");
            
            var keywords = $.trim($('#keywords').val());

            num = 0;
            getItems('{{ $category->id }}', 8, keywords);
        });
    </script>
@endsection