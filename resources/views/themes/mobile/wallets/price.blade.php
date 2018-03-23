@extends('themes.mobile.master')
@section('title', '支付页-北京优享科技有限公司')
@section('css')
    <link href="{{ url('css/address.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')

    @include('themes.mobile.layouts.header')

    <div class="u-wrapper">
        <ul class="pay">
            <li style="min-height: 660px">
                <h5>选择金额</h5>
                @foreach($chooses as $key => $val)
                    <div class="price" style="line-height: 100px">
                        充值<span class="val"> {{ $val }} </span>元<br/>
                            @if($system['type'] == 'deposit')
                                升级为
                            @else
                                赠送
                            @endif
                            <b>{{ $key }}</b>

                    </div>
                @endforeach
            </li>
        </ul>
        <div class="a-wrapper" style="padding-top: 170px"><button onclick="callpay()" class="a-default">去支付</button></div>
    </div>
@endsection
@section('js')
    <script src="{{ url('/js/layer.js') }}"></script>
    <script>
        $('.price').click(function () {
            $(this).addClass('active');
            $(this).siblings().removeClass('active');
        });
        
        function callpay() {
            var price = $('div.active').children('span.val').text();
            location.href = '/wallets/recharge/'+price;
        }


    </script>
@endsection