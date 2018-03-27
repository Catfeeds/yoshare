<ul class="content form">
    <li><span>收 货 人 ：</span>{!! Form::text('name', null, ['class' => 'a-form']) !!}</li>
    <li><span>联系电话：</span>{!! Form::text('phone', null, ['class' => 'a-form']) !!}</li>
    <li><span>省：</span>
        {!! Form::select('province', $provinces, isset($address) ? $address->province: '',['id' => 'province', 'onchange' => "loadRegion('province', 'city')"]) !!}
        <span>市：</span>
        {!! Form::select('city', $cities, isset($address) ? $address->city: '',['id' => 'city', 'onchange' => "loadRegion('city', 'town')"]) !!}
    </li>
    <li>
        <span>区：</span>
        {!! Form::select('town', $towns, isset($address) ? $address->town: '',['id' => 'town']) !!}
    </li>
    <li><span>详细地址：</span>{!! Form::textarea('detail', null) !!}</li>
</ul>
<script>
    function loadRegion(sel, subset){
        $("#"+subset+" option").each(function(){
            jQuery(this).remove();
        });
        $("<option value=0>请选择</option>").appendTo($("#"+subset));
        if(jQuery("#"+sel).val()==0){
            return;
        }
        $.getJSON('/address/region',{parent_id:$("#"+sel).val(),code:subset},
            function(data){
                if(data){
                    $.each(data,function(idx,item){
                        $("<option value="+item.id+">"+item.name+"</option>").appendTo($("#"+subset));
                    });
                }else{
                    $("<option value='0'>请选择</option>").appendTo($("#"+subset));
                }
            }
        );
    }
</script>