<div class="c-fixed">
    <button class="payment" id="place">去结算</button>
    <div class="price">
        <p>
            合计：￥<span id="total_price">0</span><br />
            <i>（不含运费）</i>
        </p>
    </div>
</div>
<script type="text/javascript">
    $('.demo--radio').click(function () {
        var p = 0;
        $('.demo--radio:checked').each(function () {
            p = parseInt($(this).siblings('.h-price').val())+p;
        });
        $('#total_price').text(p);
    });

    $('#place').click(function(){
        var id_num = 0;

        $('.demo--radio:checked').each(function () {
            id_num++;
        });
        if(id_num > 1){
            layer.open({
                content: '您只能租赁一本光盘'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }
        id = $('.demo--radio:checked').siblings('.cart_id').val();

        if(id == '' || typeof id == 'undefined'){
            layer.open({
                content: '您还没有选择宝贝哦'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
            return false;
        }else{
            location.href = '/order/place/'+id;
        }
    });

</script>