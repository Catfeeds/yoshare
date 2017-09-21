<div class="form-group">
    {!! Form::label('name', '名称:',['class' => 'control-label col-sm-1']) !!}
    <div class="col-sm-5">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
    {!! Form::label('description', '备注:', ['class' => 'control-label col-sm-1']) !!}
    <div class="col-sm-5">
        {!! Form::text('description', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label for="role" class="control-label col-sm-1">关联权限:</label>
    <div class="col-sm-11">
        @if(isset($perms))
            <div class="checkbox">
                @foreach($permissions as $key=>$permission)
                    <br class="{{$loop->index}}-perm" style="display: none"/>
                    <label>
                        <input type="checkbox" {{ in_array($permission['id'], $perms) ? 'checked' : '' }} name="permission_id[]"
                               value="{{$permission['id']}}">{{$permission['description']}}
                    </label>
                    <input type="hidden" value="{{$permission['group']}}" class="{{$loop->index}}-group">
                @endforeach
            </div>
        @else
            <div class="checkbox">
                @foreach($permissions as $key=>$permission)
                    <br class="{{$loop->index}}-perm" style="display: none" />
                    <label>
                        <input type="checkbox" name="permission_id[]" value="{{$permission['id']}}">{{$permission['description']}}
                    </label>
                    <input type="hidden" value="{{$permission['group']}}" class="{{$loop->index}}-group">
                @endforeach
            </div>
        @endif
    </div>

</div>

<div class="box-footer">
    <button type="button" class="btn btn-default" onclick="window.history.back();">取　消</button>
    <button type="submit" class="btn btn-info pull-right">确　定</button>
</div>

<script>
    $(function () {
        $('#begin_time').datetimepicker({
            format: 'YYYY/MM/DD HH:mm',
            locale: 'zh-cn'
        });
        $('#end_time').datetimepicker({
            format: 'YYYY/MM/DD HH:mm',
            locale: 'zh-cn'
        });
        var count = '{{$count}}';
        for(i=0; i<parseInt(count); i++){
            var p = $('.'+i+'-group').val();
            var n = $('.'+(i+1)+'-group').val();
            console.log(p+'-'+n);
            if( parseInt(p) != parseInt(n)){
                $('.'+(i+1)+'-perm').css('display', 'block');
            }else{
                $('.'+(i+1)+'-perm').css('display', 'none');

            }
        }
    });
</script>