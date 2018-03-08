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
                    {{ $order['price'] }}元
                </div>
                <input type="hidden" value="{{ \App\Models\Payment::TYPE_ORDER }}" name="type" id="type">
            </li>
            <li>
                <h5>选择支付方式</h5>
                @foreach($payments as $payment)
                    <div class="payments">
                        <div class="payment">
                            <span class="pay-img">
                                <img src="{{ $payment->pic }}" alt="icon">
                            </span>
                            <span class="payname">{{ $payment->name.'支付' }}</span>
                        </div>
                        <label class="demo--label" style="float:right; display: inline-block">
                            <input name="payurl" type="hidden" id="payurl{{ $payment->id }}" value="{{ $payment->payurl }}">
                            <input class="demo--radio c-btn" type="radio" name="demo-radio" value="{{ $payment->id }}">
                            <span class="demo--checkbox demo--radioInput" style="margin-top: 0px"></span>
                        </label>
                        <div class="clear"></div>
                    </div>
                @endforeach
            </li>
        </ul>
        <div class="a-wrapper" style="padding-top: 220px"><a onclick="pay({{ $order['id'] }})" class="a-default">立即支付</a></div>
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
        function pay(oid){
            if(pid == 1){
            //微信支付
                if (typeof WeixinJSBridge == "undefined"){
                    if( document.addEventListener ){
                        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                    }else if (document.attachEvent){
                        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                    }
                }else{
                    jsApiCall();
                }
            }else{
            //支付宝支付

            }
        }

    </script>
    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                {{ $data['jsApiParameters'] }},
                function(res){
                    WeixinJSBridge.log(res.err_msg);
                    alert(res.err_code+res.err_desc+res.err_msg);
                }
            );
        }

    </script>
    <script type="text/javascript">
        //获取共享地址
        function editAddress()
        {
            WeixinJSBridge.invoke(
                'editAddress',
                {{ $data['editAddress'] }},
                function(res){
                    var value1 = res.proviceFirstStageName;
                    var value2 = res.addressCitySecondStageName;
                    var value3 = res.addressCountiesThirdStageName;
                    var value4 = res.addressDetailInfo;
                    var tel = res.telNumber;

                    alert(value1 + value2 + value3 + value4 + ":" + tel);
                }
            );
        }

        window.onload = function(){
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', editAddress, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', editAddress);
                    document.attachEvent('onWeixinJSBridgeReady', editAddress);
                }
            }else{
                editAddress();
            }
        };

    </script>
@endsection