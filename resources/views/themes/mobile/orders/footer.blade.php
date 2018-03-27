<div class="c-fixed" style="padding-top: 0px">
    <button class="place-btn" id="place">提交订单</button>
    <div class="price" style="margin-top: 40px">
        <p>
            实付款：<span>￥{{ $carts['total_price'] }}</span><br />
        </p>
    </div>
</div>
<script src="{{ url('/js/layer.js') }}"></script>
<script type="text/javascript">
    $('#place').click(function(){
        var aid = $('#aid').val();

        var ids = $('#ids').val();
        var name = $('#name').text();
        var phone = $('#phone').text();
        var address = $('#address').text();
        var total_price = $('#total_price').text();
        var addr_back = window.location.href;

        if(aid == 0){
            layer.open({
                content: '您还没有添加收货地址，无法提交！',
                btn: ['去添加', '取消'],
                yes: function(index, layero) {
                    window.location.href='/address/create?addrBack='+addr_back;
                }
            });
        }else{
            $.ajax({
                url  : '/order/store/',
                type : 'get',
                data : {
                    'ids'           : ids,
                    'name'          : name,
                    'phone'         : phone,
                    'address'       : address,
                    'total_price'   : total_price
                },
                success:function(data){
                    msg = data.message;
                    statusCode = data.status_code;
                    order = data.data;

                    if(statusCode == 200){
                        msg = '成功提交订单！';
                    }

                    if (statusCode == 401){
                        layer.open({
                            content: msg,
                            btn: ['确认', '取消'],
                            yes: function(index, layero) {
                                window.location.href='/login';
                            }
                        });
                    }  else if (statusCode == 200) {
                        layer.open({
                            content: msg,
                            btn: ['去支付', '取消'],
                            yes: function(index, layero) {
                                window.location.href='/order/pay/'+order.id;
                            }
                        });
                    } else {
                        layer.open({
                            content: msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                        location.reload();
                    }
                }
            })
        }
    });

</script>