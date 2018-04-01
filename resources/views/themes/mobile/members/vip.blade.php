@extends('themes.mobile.layouts.master')
@section('title', '高级用户中心-游享')
@section('css')
    <link href="{{ url('css/member.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/cart.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')

    @include('themes.mobile.members.header')

    <div class="u-wrapper">
        <div class="vip-text">
            <h3>会员等级简介</h3>
            <div class="content" style="padding-top: 40px;">
                <p><b style="color: #ffcc42">黄金会员</b> </p><p style="text-indent: 80px">押金金额 300元 每次可租盘上限：<b>1本</b></p>
             </div>
        </div>
        <div class="a-wrapper" style="padding-top: 320px;">
            @if($type == \App\Models\Member::TYPE_ORDINARY)
                <a href="/wallets/recharge/{{ \App\Models\Member::DEPOSIT_MONEY }}" class="a-default">
                    立即成为VIP
                </a>
            @endif
        </div>
    </div>
@endsection
@section('js')
<script>

</script>
@endsection