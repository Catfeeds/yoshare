@extends('themes.mobile.master')
@section('title', '支付页-游享')
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
                    {{ $result['price'] }}元
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
            }else if(pid == 3){
                var price = {{ $result['price'] }};
                $.ajax({
                    url  : '/wallets/get/balance/',
                    type : 'get',
                    data : {
                        'price'         : price,
                    },
                    success:function(data){
                        msg = data.message;
                        statusCode = data.status_code;
                        balance = data.data;
                        var type = 'balance';
                        var source = 'order';

                        if (parseInt(balance) < parseInt(price)) {
                            layer.open({
                                content: '当前余额为'+balance+',无法使用余额！',
                                btn: ['去充值', '其他支付'],
                                yes: function (index, layero) {
                                    window.location.href = '/wallets/balance/price';
                                }
                            });
                        }else{
                            layer.open({
                                content: '当前余额为'+balance,
                                btn: ['支付', '取消'],
                                yes: function (index, layero) {
                                    pay(type, price, source);
                                }
                            });
                        }

                        if (statusCode == 401){
                            layer.open({
                                content: msg,
                                btn: ['确认', '取消'],
                                yes: function(index, layero) {
                                    window.location.href='/login';
                                }
                            });
                        }
                    }
                })
            }else if(pid == 4){
                var price = {{ $result['price'] }};
                $.ajax({
                    url  : '/wallets/get/coupon/',
                    type : 'get',
                    data : {
                        'price'         : price,
                    },
                    success:function(data){
                        msg = data.message;
                        statusCode = data.status_code;
                        coupon = data.data;
                        var type = 'coupon';

                        if (parseInt(coupon) < parseInt(price)) {
                            layer.open({
                                content: '您的优惠券为'+coupon+'元,无法使用！',
                                btn: ['去充值', '其他支付'],
                                yes: function (index, layero) {
                                    window.location.href = '/wallets/balance/price';
                                }
                            });
                        }else{
                            layer.open({
                                content: '当前优惠券为'+coupon,
                                btn: ['支付', '取消'],
                                yes: function (index, layero) {
                                    pay(type, price);
                                }
                            });
                        }

                        if (statusCode == 401){
                            layer.open({
                                content: msg,
                                btn: ['确认', '取消'],
                                yes: function(index, layero) {
                                    window.location.href='/login';
                                }
                            });
                        }
                    }
                })
            } else{
                //TODO 支付宝支付

            }

        }

        function pay(type, price, source) {

            $.ajax({
                url  : '/wallets/pay',
                type : 'get',
                data : {
                    'order_id'      : {{ $result['id'] }},
                    'price'         : price,
                    'type'          : type,
                    'source': source,
                },
                success:function(data){
                    msg = data.message;
                    statusCode = data.status_code;
                    url = data.url;

                    if (statusCode == 200){
                        layer.open({
                            content: '支付成功',
                            btn: ['确认', '取消'],
                            yes: function (index, layero) {
                                window.location.href = url;
                            }
                        });
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