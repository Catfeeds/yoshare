@extends('themes.mobile.layouts.master')
@section('title', '高级用户中心-游享')
@section('css')
    <link href="{{ url('css/member.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')

    @include('themes.mobile.members.header')

    <div class="u-wrapper">
        <div class="vip-text">
            <div class="content" style="text-align: center">
                <p><b style="color: #ffcc42;font-size: 80px">{{ App\Models\Member::TYPES[$system['vip_level']] }}</b> </p>
                <p>(每次可租盘上限：<b>{{ $system['vip_level'] }}本</b>)</p>
                <p style="font-size: 60px;padding: 100px;">
                    {{ $wallet[$system['type']] }}
                    @if($system['type'] == 'deposit')
                        元
                    @elseif($system['type'] == 'balance')
                        U币
                    @else
                        元券
                    @endif
                </p>
             </div>
        </div>
        @if($system['title'] == '我的押金')
            <ul class="refund">
                @if($wallet['state'] == \App\Models\Wallet::STATE_NORMAL && $wallet[$system['type']] !== 0)
                    <li id="refund">退押金</li>
                @elseif($wallet['state'] == \App\Models\Wallet::STATE_REFUNDING)
                    <li style="color: red;">押金退还审核中...</li>
                @endif
            </ul>
        @endif
        <div class="a-wrapper" style="padding-top: 170px">
            @if($system['title'] == '我的余额' )
                <a href="/wallets/balance/price" class="a-default">立即充值</a>
            @elseif($system['vip_level'] == \App\Models\Member::TYPE_ORDINARY && $system['title'] == '我的押金')
                <a href="/wallets/recharge/{{ \App\Models\Member::DEPOSIT_MONEY }}" class="a-default">
                    立即成为VIP
                </a>
            @elseif($system['vip_level'] == \App\Models\Member::TYPE_GOLD && $system['title'] == '我的押金')
                <a href="#" class="a-default">
                    您已是VIP
                </a>
            @endif
        </div>
    </div>
@endsection
@section('js')
<script src="{{ url('/js/layer.js') }}"></script>
<script>
    $('#refund').click(function () {
        layer.open({
            content: '您确定要申请退还押金吗？退还后您不再享有租赁光盘特权哦'
            ,btn: ['确定', '取消']
            ,yes: function(index){
                $.ajax({
                    url: '/deposit/apply/'+{{ $wallet['id'] }},
                    type: 'POST',
                    data: {'_token': '{{ csrf_token() }}', 'state': {{ \App\Models\Wallet::STATE_REFUNDING }} },
                    success: function (data) {
                        msg = data.message;
                        statusCode = data.status_code;
                        if(statusCode == 200){
                            layer.open({
                                content: '申请成功'
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
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
        });
    });
</script>
@endsection