@extends('themes.mobile.master')
@section('title', '支付页-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/address.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')
    <div class="u-wrapper">

        @include('themes.mobile.layouts.header')

        <ul class="pay">
            <li>
                <h5>支付金额</h5>
                <div class="price">
                    {{ $price }}元
                </div>
            </li>
            <li>
                <h5>选择支付方式</h5>
                @foreach($payments as $payment)
                    <div>
                        <img src="{{ $payment->pic }}" alt="icon">{{ $payment->name }}支付

                        <input class="demo--radio c-btn" type="checkbox" name="demo-checkbox{{ $payment->id }}}">
                        <span class="demo--checkbox demo--radioInput"></span>
                    </div>
                @endforeach
            </li>
        </ul>
        <div class="a-wrapper"><a href="/address/create" class="a-default">立即支付</a></div>
    </div>
@endsection
@section('js')
    <script src="{{ url('/js/layer.js') }}"></script>
    <script>
        function ask(id) {
            layer.open({
                content: '您确定要删除此地址吗？'
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    location.href = '/address/'+id+'delete';
                    layer.close(index);
                }
            });
        }

        function setAddr(id) {
            location.href = '/address/default/'+id;
        }
    </script>
@endsection