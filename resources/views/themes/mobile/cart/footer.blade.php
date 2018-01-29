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
        var ids = '';
        $("input:radio").each(function () {
            if(this.checked){
                var ids = $this.val()+'+';
            }
        });
        console.log(ids);
        location.href = '/order/place';
    });

</script>