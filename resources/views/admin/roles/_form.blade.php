<div class="form-group">
    {!! Form::label('name', '名称:',['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('description', '描述:', ['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
</div>


<div class="form-group">
    <label for="role" class="control-label col-sm-2">关联权限</label>
    <div class="col-sm-10">
        @if(isset($perms))
            <div class="checkbox">
                @foreach($permissions as $permission)
                    <label>
                        <input type="checkbox" {{ in_array($permission->id, $perms) ? 'checked' : '' }} name="permission_id[]"
                               value="{{$permission->id}}">{{$permission->description}}
                    </label>
                @endforeach
            </div>

        @else
            <div class="checkbox">
                @foreach($permissions as $permission)
                    <label>
                        <input type="checkbox" name="permission_id[]" value="{{$permission->id}}">{{$permission->description}}
                    </label>
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
    });
</script>