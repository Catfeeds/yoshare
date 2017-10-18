@extends('layouts.master')
@section('title', '北京优享科技有限公司')
@section('content')
    <!--
    <div class="banner swiper-container">
        <div class="swiper-wrapper" id="banner_swiper">
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="product-wrapper">
        <div class="product-title">产品展示</div>
        <div class="minbanner swiper-container">
            <div class="swiper-wrapper" id="minbanner_swiper">
            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
    <div class="middle-img"><img src="images/index/middle-banner.png" alt="middle-banner"/></div>
    <div class="product">
    @foreach(\App\Models\Category::find(7)->contents()->where('state', \App\Models\Content::STATE_PUBLISHED)->orderBy('id', 'asc')->get() as $content)
        @if($loop->index%3 == 0)
        <ul>
        @endif
            <li><a href="{{url('/contents/show/'.$content->id)}}"><div class="shadow"></div><div class="product-name">{{$content->title}}</div><img src="{{url($content->image_url)}}" alt="product-1"></a></li>
        @if($loop->index%3 == 2 || $loop->index == 4)
            <div class="clear"></div>
        </ul>
        @endif
    @endforeach
    </div>
    -->
@endsection
@section('js')
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