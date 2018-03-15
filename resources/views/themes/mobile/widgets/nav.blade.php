<div class="nav-wrapper">
    <div class="nav"
        @if($system['mark'] == 'member')
            style="left: 11.5%"
        @endif
    >
        <ul>
            <li onclick="ahref('')" class="{{$system['mark'] == 'index'? 'active' : ''}}"><div class="words">首页</div></li>
            <li onclick="ahref('goods/category-2.html')" class="{{$system['mark'] == 'goods'? 'active' : ''}}"><div class="words">分类</div></li>
            <li onclick="ahref('cart')" class="{{$system['mark'] == 'cart'? 'active' : ''}}"><div class="words">购物车</div></li>
            <li onclick="ahref('member')" class="{{$system['mark'] == 'member'? 'active' : ''}}"><div class="words">我的</div></li>
            <div class="clear"></div>
        </ul>
    </div>
</div>

<script type="text/javascript">
    function ahref(mark) {
        location.href = '/'+mark;
    }
</script>