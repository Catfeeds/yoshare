<div class="o-fixed" @if($system['title'] == '提交订单')
    style = "height: 168px;"
    @endif
>
    <div class="o-header">
        <a herf="#" onclick="javascript:history.back(-1);" class="f-back"></a>
        <div class="title">{{ $system['title'] }}</div>
        <a class="h-nav" herf="#"></a>
        <div class="clear"></div>
    </div>
    <ul class="order">
        <li class="active"><a href="#">全部</a></li>
        <li><a href="#">待付款</a></li>
        <li><a href="#">待发货</a></li>
        <li><a href="#">待收货</a></li>
        <li><a href="#">待评价</a></li>
    </ul>
</div>
