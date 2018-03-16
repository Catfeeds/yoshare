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
        var ids = '';

        $('.demo--radio:checked').each(function () {
            ids = $(this).siblings('.cart_id').val()+'-'+ids;
        });
        ids = ids.trim("-");
        if(ids == ''){
            layer.open({
                content: '您还没有选择宝贝哦'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }else{
            location.href = '/order/place/'+ids;
        }
    });

</script>