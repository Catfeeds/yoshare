@extends('themes.mobile.layouts.master')
@section('title', '收藏页--北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/order.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('css/cart.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    @include('themes.mobile.members.header')

    <div class="c-goods">
        <ul>
            @foreach( $goodses as $goods )
                <li class="cart_li">
                    <div class="g-img" onclick="cjump({{ $goods->id }})"><img src='{{ $goods->image_url }}' alt="商品图片"></div>
                    <div class="g-desc" style="width: 60%">
                        <h4 class="name" onclick="cjump({{ $goods->id }})">{{ $goods->name }}</h4>
                        <div class="subtitle" style="height: 75px;">
                            <span class="cancle">取消收藏</span>
                            <input type="hidden" value="{{ $goods->id }}" name="goods_id" class="goods_id">
                        </div>
                        <div class="price">￥{{ $goods->sale_price }}/月</div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endsection

@section('js')
    <script src="{{ url('/js/layer.js') }}"></script>
    <script>
        function cjump(id) {
            location.href = '/goods/detail-'+id+'.html';
        }
        
        $('.cancle').click(function () {
            var goods_id = $(this).siblings('.goods_id').val();
            layer.open({
                content: '您确定取消收藏此光盘吗？',
                btn: ['确定', '取消'],
                yes: function (index, layero) {
                    cancle(goods_id);
                }
            });
        })

        function cancle(goods_id) {
            $.ajax({
                url  : '/collect/cancle',
                type : 'get',
                data : {
                    'goods_id'      : goods_id,
                },
                success:function(data){
                    msg = data.message;
                    statusCode = data.status_code;

                    if (statusCode == 200){
                        layer.open({
                            content: '取消成功'
                            ,skin: 'msg'
                            ,time: 3 //2秒后自动关闭
                        });
                        location.reload();
                    }else{
                        layer.open({
                            content: msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    }
                }
            })
        }

    </script>
@endsection

