<div class="c-fixed">
    <button class="payment" id="place">去结算</button>
    <div class="price">
        <p>
            合计：<span>￥{{ $carts['total_price'] }}</span><br />
            <i>（不含运费）</i>
        </p>
    </div>
</div>
<script src="{{ url('/js/layer.js') }}"></script>
<script type="text/javascript">
    $('#place').click(function(){
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
                if(msg == 'success'){
                    msg = '购物车加入成功！';
                }
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