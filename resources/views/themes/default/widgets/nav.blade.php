<div class="nav-wrapper">
    <div class="nav">
        <ul>
            <li onclick="ahref('')" class="{{$mark == 'index'? 'active' : ''}}"><div class="words">首页</div></li>
            <li onclick="ahref('categories/list/7')" class="{{$mark == 'product'? 'active' : ''}}"><div class="words">分类</div></li>
            <li onclick="ahref('cart')" class="{{$mark == 'cart'? 'active' : ''}}"><div class="words">购物车</div></li>
            <li onclick="ahref('user')" class="{{$mark == 'user'? 'active' : ''}}"><div class="words">我的</div></li>
            <div class="clear"></div>
        </ul>
    </div>
</div>

<script type="text/javascript">
    function ahref(mark) {
        location.href = '/'+mark;
    }
</script>