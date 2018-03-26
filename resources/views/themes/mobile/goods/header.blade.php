<div class="h-nav-bg">
    <div class="h-wrapper">
        <a herf="#" onclick="javascript:history.back(-1);" class="back"></a>
        <a herf="/cart" onclick="ahref('cart')" class="cart"></a>
        <a herf="#" onclick="" class="m-nav dis-nav"></a>
        <ul class="header-nav">
            <li onclick="jump('/')">首页</li>
            <li onclick="jump('/member')">我的</li>
            <li onclick="jump('/help')">帮助</li>
        </ul>
    </div>
</div>
<div class="h-fixed">
    <a herf="#" onclick="javascript:history.back(-1);" class="g-back"></a>
    <ul>
        <li><a href="#">商品</a></li>
        <li><a href="#detail">详情</a></li>
        <li><a href="#">评价</a></li>
    </ul>
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

    $('.dis-nav').click(function () {
        var mark = $(this).siblings('.header-nav').attr('style');

        if(typeof mark == 'undefined' || mark == 'display:none'){
            $(this).siblings('.header-nav').attr('style', 'display:block');
        }else{
            $(this).siblings('.header-nav').attr('style', 'display:none');
        }
    })
</script>