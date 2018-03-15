<div class="action">
    <ul>
        <li><a href="">加入收藏</a></li>
        <li><a href="javascript:void(0)" id="addcart">加入购物车</a></li>
        <li><a href="">立即购买</a></li>
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

                if(msg == 'success'){
                    msg = '购物车加入成功！';
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