@extends('themes.mobile.master')
@section('title', '关于我们-游享')
@section('css')
    <link href="{{ url('css/member.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/order.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ url('css/cart.css') }}" type="text/css" rel="stylesheet">
@endsection
@section('content')
    <div class="u-wrapper" style="padding-bottom: 0px">

        @include('themes.mobile.members.header')

        <div class="vip-text">
            <h3 style="padding-top: 30px;">一：为什么租赁游戏光盘</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                YO享平台为有效解决玩家购买游戏盘价格贵、买来游戏不喜欢、玩通关后二手平台交易有风险等等困扰。
            </div>

            <h3 style="padding-top: 30px;">二：关于支付</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                平台作为和微信支付签约的企业，目前仅支持微信支付。
            </div>

            <h3 style="padding-top: 30px;">三：关于押金</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                租赁游戏押金为300元，每次只能租赁一本光盘。在没有待归还的光盘情况下，押金可随时申请退还。
            </div>

            <h3 style="padding-top: 30px;">四：关于归还光盘</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                不限时活动期间内，平台不限制用户归还时间。非活动期间，您需要在您选择时长内归还光盘，最多可逾期三天，三天后将按照光盘现价额外收取超出时间的费用，1-30天按一月处理，请您在选购时
                妥善选择体验时长：1月、2月、3月...温馨提醒您及时归还。
            </div>

            <h3 style="padding-top: 30px;">五：租盘时间</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                即日起至2018年7月1日推出不限时还盘活动，您不必担忧还未玩尽兴就归还光盘。活动过后，将恢复至按月收费，您可根据游戏所需要的通关时间以及您玩游戏的时间合理选择租赁时长,可选择租赁:1月、
                2月、3月...
            </div>

            <h3 style="padding-top: 30px;">六：关于光盘</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                游戏均为中文实体光盘。
            </div>

            <h3 style="padding-top: 30px;">七：快递费怎么算</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                寄出的运费永久由平台承担，寄回的运费需要玩家自理。
            </div>

            <h3 style="padding-top: 30px;">八：我收到货物后应该做些什么</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                核对货物是否齐全，在待收货订单中确认收货即可。
            </div>

            <h3 style="padding-top: 30px;">九：如何更换游戏盘</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                双向退换，您直接把退换寄回的的单号提交，重新下单即可发碟。
            </div>

            <h3 style="padding-top: 30px;">十：光碟损坏怎么办</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                游戏光盘经由专门检测人员寄出，确保到达您手里的光盘是无损的，若是人为损坏，您需要购买一张相同的
                游戏盘归还。
            </div>

            <h3 style="padding-top: 30px;">十一：发货时间</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                每日18:00发货，节假日照常发货。
            </div>
        </div>
@endsection
@section('js')
<script>

</script>
@endsection