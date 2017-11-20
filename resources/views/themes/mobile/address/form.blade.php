<ul class="content form">
    <li><span>收 货 人 ：</span><input name="name" type="text" value="" class="a-form"></li>
    <li><span>联系电话：</span><input name="phone" type="text" value="" class="a-form"></li>
    <li><span>邮　　编：</span><input name="postcode" type="text" value="" class="a-form"></li>
    <li><span>省：</span>
        <select name="province" id="province" onchange="loadRegion('province', 'city')">
            <option value="0" selected>省份/直辖市</option>
            @foreach($provinces as $province)
                <option value="{{ $province->id }}" >{{ $province->name }}</option>
            @endforeach
        </select>
        <span>市：</span>
        <select name="city" id="city" onchange="loadRegion('city', 'town');">
            <option value="0">市/县</option>
        </select>
    </li>
    <li><span>区：</span>
        <select name="town" id="town">
            <option value="0">镇/区</option>
        </select>
    </li>
    <li><span>详细地址：</span><textarea name="address"></textarea></li>
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
        $.getJSON('region',{parent_id:$("#"+sel).val(),code:subset},
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