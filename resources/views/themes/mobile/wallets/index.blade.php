@extends('themes.mobile.layouts.master')
@section('title', '高级用户中心-北京优享科技有限公司')
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
                <p style="font-size: 60px;padding: 100px;">{{ $system['money'] }}元</p>
             </div>
        </div>
        @if($system['title'] == '我的押金')
            <ul class="refund">
                <li id="refund">退押金</li>
            </ul>
        @endif
        @if($system['title'] == '我的押金')
            <div class="a-wrapper" style="padding-top: 170px"><a href="/wallets/deposit/price" class="a-default">立即充值</a></div>
        @elseif($system['title'] == '我的余额')
            <div class="a-wrapper" style="padding-top: 270px"><a href="/wallets/balance/price" class="a-default">立即充值</a></div>
        @endif
    </div>
@endsection
@section('js')
<script>
    $('#refund').click(function () {
        layer.open({
            content: '您确定要申请退还押金吗？退还后您不再可以租赁光盘哦'
            ,btn: ['确定', '取消']
            ,yes: function(index){
                location.href = '/wallets/delete';
            }
        });
    });
</script>
@endsection