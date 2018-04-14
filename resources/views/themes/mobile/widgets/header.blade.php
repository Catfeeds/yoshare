<div class="header">
    <div class="search"><input class="s-input" id="search" type="text" placeholder="请输入游戏名"></div>
    <ul class="results"></ul>
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
                    var html = '';
                    msg = data.message;
                    statusCode = data.status_code;
                    res = data.data;

                    if (statusCode == 200){
                        $(res).each(function (k, obj) {
                            url = "/goods/detail-"+obj.id+".html";
                            html += '<li>' +
                                '<a href="'+url+'">'+obj.name+'</a>'+
                                '</li>';
                        });
                        $('.results').html(html).attr('style', 'display: block;');
                    } else {
                        html = '<li>'+msg+'</li>';
                        $('.results').html(html).attr('style', 'display: block;');
                    }
                }
            })
        }

    });
</script>