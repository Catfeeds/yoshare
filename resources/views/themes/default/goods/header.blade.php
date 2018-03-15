<div class="h-nav-bg">
    <div class="h-wrapper">
        <a herf="#" onclick="javascript:history.back(-1);" class="back"></a>
        <a herf="/cart" onclick="ahref('cart')" class="cart"></a>
        <a herf="#" onclick="" class="m-nav"></a>
    </div>
</div>
<div class="h-fixed">
    <a herf="#" onclick="javascript:history.back(-1);" class="g-back"></a>
    <ul>
        <li><a href="#">商品</a></li>
        <li><a href="#">详情</a></li>
        <li><a href="#">评价</a></li>
    </ul>
    <a class="g-nav" herf="#" ></a>
    <div class="clear"></div>
</div>
<script>
    var $goTop = $('.h-fixed');
    $(window).scroll(function(){
        var $this = $(this);
        if($this.scrollTop() > 140){
            $goTop.fadeIn();
        } else {
            $goTop.fadeOut();
        }
    });
</script>