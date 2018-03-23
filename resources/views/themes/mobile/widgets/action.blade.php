<div class="action">
    <ul>
        <li><a href="javascript:void(0)" id="collect">加入收藏</a></li>
        <li><a href="javascript:void(0)" id="addcart">加入购物车</a></li>
        <li><a href="javascript:void(0)" id="buynow">立即购买</a></li>
        <div class="clear"></div>
    </ul>
</div>

<script src="{{ url('/js/layer.js') }}"></script>
<script type="text/javascript">
    function ahref(mark) {
        location.href = '/'+mark;
    }
    $('#addcart').click(function(){
        var goods_id = $('#goods_id').val();
        var sale_price = $('#sale_price').val();

        $.ajax({
            url  : '/cart/add/'+goods_id,
            type : 'get',
            data : {
                'price' : sale_price,
                'number'  : 1
            },
            success:function(data){
                msg = data.message;
                statusCode = data.status_code;

                if(statusCode == 200){
                    msg = '购物车加入成功！';
                }else if(statusCode == 401){
                    url = '/login';
                }else{
                    url = '/member/vip';
                }

                if (statusCode == 401 || statusCode == 407){
                    layer.open({
                        content: msg,
                        btn: ['确认', '取消'],
                        yes: function(index, layero) {
                            window.location.href = url;
                        }
                    });
                } else {
                    layer.open({
                        content: msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            }
        })

    });

    $('#buynow').click(function(){
        var goods_id = $('#goods_id').val();
        var sale_price = $('#sale_price').val();

        $.ajax({
            url  : '/cart/add/'+goods_id,
            type : 'get',
            data : {
                'price' : sale_price,
                'number'  : 1
            },
            success:function(data){
                msg = data.message;
                statusCode = data.status_code;

                if (statusCode == 401){
                    layer.open({
                        content: msg,
                        btn: ['确认', '取消'],
                        yes: function(index, layero) {
                            window.location.href='/login';
                        }
                    });
                } else {
                    location.href = '/cart';
                }
            }
        })

    });

    $('#collect').click(function(){
        var goods_id = $('#goods_id').val();

        $.ajax({
            url  : '/member/collect/',
            type : 'get',
            data : {
                'goods_id' : goods_id,
            },
            success:function(data){
                msg = data.message;
                statusCode = data.status_code;

                if(statusCode == 200){
                    msg = '收藏成功！';
                }

                if (statusCode == 401){
                    layer.open({
                        content: msg,
                        btn: ['确认', '取消'],
                        yes: function(index, layero) {
                            window.location.href='/login';
                        }
                    });
                } else {
                    layer.open({
                        content: msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            }
        })

    });
</script>