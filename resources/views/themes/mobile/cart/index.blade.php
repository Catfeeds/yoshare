@extends('themes.mobile.layouts.master')
@section('title', '购物车--北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/order.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ url('css/cart.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    @include('themes.mobile.cart.header')

    <div class="c-goods">
        <div class="all"><a href="javascript:void(0)" onclick="checkAll()" class="all-checked"></a><span class="words">全选</span></div>
        <ul>
            @foreach( $goodses as $goods )
            <li>
                <input type="radio" class="c-radio" >
                <input type="hidden" value="{{ $carts['ids'][$goods->id] }}" class="cart_id" >
                <div class="g-img"><img src='{{ $goods->image_url }}' alt="商品图片"></div>
                <div class="g-desc">
                    <h4 class="name">{{ $goods->name }}</h4>
                    <div class="subtitle">{{ $goods->subtitle }}</div>
                    <div class="price">￥{{ $goods->sale_price }}/月</div>
                </div>
                <div class="num">
                    <i class="i-sub"></i>
                    <input type="text" class="i-num" value="{{ $carts['numbers'][$goods->id] }}">
                    <i class="i-add"></i>
                    <input type="hidden" value="{{ $goods->id }}" class="goods_id">
                </div>
                <div class="action">
                    <a class="c-button" onclick="cartsDel({{ $goods->id }})">删除</a>
                </div>
                <div class="clear"></div>
            </li>
            @endforeach
        </ul>
    </div>
@endsection

@section('js')
<script src="{{ url('/js/layer.js') }}"></script>
<script type="text/javascript">

    $('.i-add').click(function(){
        var goods_id = $(this).siblings('.goods_id').val();
        var index = $(this).parents('li').index();

        $.ajax({
            url  : '/cart/add/'+goods_id,
            type : 'get',
            data : {
                'number'  : 1
            },
            success:function(data){
                msg = data.message;

                if(msg == 'success'){
                    msg = '购物车加入成功！';
                    //修改添加后的盘的数量
                    $(this).parent('.num').children('.i-num').val(data.data[index]['number']);
                    console.log($(this).parent('.num').children('.i-num').val());
                }

                statusCode = data.status_code;
                if (statusCode == 401){
                    layer.open({
                        content: msg,
                        btn: ['确认', '取消'],
                        yes: function(index, layero) {
                            window.location.href='/login';
                        }
                    });
                } else {
                    layer.open({
                        content: msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                    location.reload();
                }
            }
        })

    });

    $('.i-sub').click(function(){
        var goods_id = $(this).siblings('.goods_id').val();
        var index = $(this).parents('li').index();

        $.ajax({
            url  : '/cart/sub/'+goods_id,
            type : 'get',
            data : {
                'number'  : 1
            },
            success:function(data){
                msg = data.message;

                if(msg == 'success'){
                    msg = '购物车修改成功！';
                }

                statusCode = data.status_code;
                if (statusCode == 401){
                    layer.open({
                        content: msg,
                        btn: ['确认', '取消'],
                        yes: function(index, layero) {
                            window.location.href='/login';
                        }
                    });
                } else {
                    layer.open({
                        content: msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                    location.reload();
                }
            }
        })

    });
    
    function cartsDel(goods_id) {
        layer.open({
            content: '您确定从购物车删除此光盘吗？'
            ,btn: ['确定', '取消']
            ,yes: function(index){
                location.href = '/cart/'+goods_id+'/delete';
                layer.close(index);
            }
        });
    }
    
    function checkAll() {
        $("input[type='radio']").attr("checked","checked" );
    }

</script>
@endsection

