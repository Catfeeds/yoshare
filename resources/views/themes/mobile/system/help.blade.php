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
                二手盘每次畅玩至少花费50-100元，一手盘购买更是达300多元，YO享平台为有效解决玩家购买游戏盘价格贵、买来游戏不喜欢
                感觉浪费了钱、买了游戏盘玩通关后又要想方设法在二手平台去交易等等困扰。
            </div>

            <h3 style="padding-top: 30px;">二：关于押金</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                每次租赁一个游戏盘，只需缴纳押金300元，成为黄金会员；如果您不打算继续在YO享平台租赁光盘，在我们收到您退还的光盘后检查
                无误后，您可随时申请退款，我们的工作人员审核后便会给您退款。作为一家注册的正规企业，我们本着薄利多销的企业宗旨，旨在
                搭建一个游戏体验极佳的租赁平台，用户完全不需要担心押金无法退还的问题。
            </div>

            <h3 style="padding-top: 30px;">三：租盘时间</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                为了最大限度提升用户的游戏体验，我们从平台上线即日起至2018年7月1日，隆重推出租盘不限时活动，让您不必担忧还未玩尽兴就
                被迫归还光盘。随着平台的扩大，后续我们还会退出更多更好、更诱人的活动，敬请期待。
             </div>

            <h3 style="padding-top: 30px;">四：快递费怎么算？</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                寄出的运费永久由平台承担，寄回的运费需要玩家自理。寄回时，所有的快递我们这里都能收到，您可选择自己方便又实惠的快递。
            </div>

            <h3 style="padding-top: 30px;">五：我收到货物后应该做些什么？</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                当您收到货物后，打开包装箱，根据发货清单核对货物是否齐全，并检查设备功 能是否完好，同时请保管好产品的包装箱及填充物。
                登录YO享商城点击待收货订单中的确认收货即可。
            </div>

            <h3 style="padding-top: 30px;">六：如何更换游戏盘</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                双向退换，您直接把退换寄回的的单号提交，重新下单即可发碟。
            </div>

            <h3 style="padding-top: 30px;">七：光碟损坏怎么办</h3>
            <div class="content" style="text-indent: 80px;padding-top: 40px;">
                游戏光盘经由专门检测人员寄出，确保到达您手里的光盘是无损的，若是人为损坏，您需要购买一张相同的游戏盘归还。
            </div>
        </div>
@endsection
@section('js')
<script>

</script>
@endsection