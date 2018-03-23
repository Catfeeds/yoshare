<div class="o-fixed"
    @if($system['title'] == '提交订单')
        style = "height: 168px;"
    @elseif($system['title'] == '订单页')
        style = "height: 445px;"
     @endif
>
    <div class="o-header">
        <a herf="#" onclick="javascript:history.back(-1);" class="f-back"></a>
        <div class="title">{{ $system['title'] }}</div>
        <a class="h-nav" herf="#"></a>
        <div class="clear"></div>
    </div>
    @if(isset($state))
        <ul class="order">
            <li style="width: 100%"
                @if($state == '')
                    class = "active";
                @endif ><a href="/order/lists">全部</a></li>
            <li @if($state == 'nopay')
                    class = "active";
                @endif><a href="/order/lists/nopay">待付款</a></li>
            <li @if($state == 'nosend')
                    class = "active";
                @endif><a href="/order/lists/nosend">待发货</a></li>
            <li @if($state == 'sended')
                    class = "active";
                @endif><a href="/order/lists/sended">待收货</a></li>
            <li @if($state == 'return')
                class = "active";
                    @endif><a href="/order/lists/return">待归还</a></li>
            <li @if($state == 'success')
                class = "active";
                    @endif><a href="/order/lists/success">已完成</a></li>
        </ul>
    @endif
</div>
