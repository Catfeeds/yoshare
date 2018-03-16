<div class="header">
    <div class="search"><input class="s-input" id="search" type="text" placeholder="请输入游戏名"></div>
</div>
<script src="{{ url('/js/layer.js') }}"></script>
<script>
    $('#search').bind('keypress',function(event){
        if(event.keyCode == 13)
        {
            var name = $(this).val();

            $.ajax({
                url  : '/goods/search',
                type : 'post',
                data : {
                    name    : name,
                    _token  : '{{ csrf_token() }}'
                },
                success:function(data){
                    msg = data.message;
                    statusCode = data.status_code;
                    res = data.data;

                    if (statusCode == 200){
                        window.location.href='/goods/detail-'+res+'.html';
                    } else {
                        layer.open({
                            content: msg
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });
                    }
                }
            })
        }

    });
</script>