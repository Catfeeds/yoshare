<div class="o-fixed" style="height: 166px;">
    <div class="o-header">
        <a herf="#" onclick="javascript:history.back(-1);" class="f-back"></a>
        <div class="c-title" style="width: 56.1%">购物车({{ $carts['number'] }})</div>
        <a class="h-nav dis-nav" herf="#" ></a>
        <a class="l-cart" herf="#" ></a>
        <ul class="header-nav">
            <li onclick="jump('/')">首页</li>
            <li onclick="jump('/member')">我的</li>
            <li onclick="jump('/help')">帮助</li>
        </ul>
        <div class="clear"></div>
    </div>
</div>
<script>
    $('.dis-nav').click(function () {
        var mark = $(this).siblings('.header-nav').attr('style');

        if(typeof mark == 'undefined' || mark == 'display:none'){
            $(this).siblings('.header-nav').attr('style', 'display:block');
        }else{
            $(this).siblings('.header-nav').attr('style', 'display:none');
        }
    })
</script>
