@extends('themes.mobile.master')
@section('title', '我的收货地址-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/address.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')

    @include('themes.mobile.layouts.header')

    <div class="u-wrapper">
        <ul class="list">
            @foreach($addresses as $address)
            <li>
                <div class="w-addr clear">
                    <span class="receiver">收货人：{{ $address->name }}</span>
                    <span class="tel">电　话：{{ $address->phone }}</span>
                </div>
                <div class="addr-detail">
                    <span>{{ $address->detail }}</span>
                </div>
                <div class="addr-set clear">
                    @if($address->is_default == \App\Models\Address::IS_DEFAULT)
                        <a class="a-active" href="#">默认地址</a>
                    @else
                        <a class="a-no" href="javascript:void(0)" onclick="setAddr({{ $address->id }})">设为默认</a>
                    @endif
                    <a href="javascript:void(0)" onclick="ask({{ $address->id }})" class="a-right">删除</a>
                    <a href="/address/{{ $address->id }}/edit" class="a-right" style="margin-right: 143px;">编辑</a>
                </div>
            </li>
            @endforeach
        </ul>
        <div class="a-wrapper"><a href="#" onclick="addAddr()" class="a-default">添加收货地址</a></div>
    </div>
@endsection
@section('js')
<script src="{{ url('/js/layer.js') }}"></script>
<script>
    var addr_back = window.location.href;

    function addAddr() {
        window.location.href='/address/create?addrBack='+addr_back;
    }

    function ask(id) {
        layer.open({
            content: '您确定要删除此地址吗？'
            ,btn: ['确定', '取消']
            ,yes: function(index){
                location.href = '/address/'+id+'/delete';
                layer.close(index);
            }
        });
    }

    function setAddr(id) {
        location.href = '/address/default/'+id;
    }
</script>
@endsection