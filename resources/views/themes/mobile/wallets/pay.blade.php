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
            <li>
                <h5>支付金额</h5>
                <div class="price">
                    {{ $data['price'] }}元
                </div>
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
        <div class="a-wrapper" style="padding-top: 170px"><button onclick="callpay()" class="a-default">立即支付</button></div>
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


    </script>
    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall()
        {

            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                <?php echo $data['jsApiParameters'];?>,
                function(res){
                    if(res.err_msg == "get_brand_wcpay_request:ok" ){
                        window.history.go(-2);
                        //支付失败
                    }else if(res.err_msg == "get_brand_wcpay_request:fail" ){
                        layer.open({
                            content: '支付失败'
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                        window.location.href="/order/pay/"+{{ $result['id'] }};
                    }else{
                        layer.open({
                            content: '取消支付'
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    }
                }
            );
        }

    </script>
    <script type="text/javascript">
        function callpay()
        {
            var pid = $('input[name="demo-radio"]:checked').val();
            //微信支付
            if(pid == 1){
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
                //TODO 支付宝支付

            }

        }

    </script>
@endsection