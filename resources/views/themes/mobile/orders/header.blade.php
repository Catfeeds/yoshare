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
</div>
