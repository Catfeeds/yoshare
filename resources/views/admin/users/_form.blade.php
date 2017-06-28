<div class="form-group">
    {!! Form::label('username', '用户名:', ['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::text('username', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('name', '姓名:',['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label($password, '密码:', ['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::password($password, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    <label for="role" class="control-label col-sm-2">角色选择</label>
    <div class="col-sm-10">
        @if(isset($roleUsers))
            <div class="checkbox">
                @foreach($roles as $role)
                    <label>
                        <input type="radio" {{ in_array($role->id, $roleUsers) ? 'checked' : '' }} name="role_id[]"
                               value="{{$role->id}}"> {{$role->name}}
                    </label>
                @endforeach
            </div>

        @else
            <div class="checkbox">
                @foreach($roles as $role)
                    <label>
                        <input type="radio" name="role_id[]" value="{{$role->id}}"> {{$role->name}}
                    </label>
                @endforeach
            </div>
        @endif
    </div>

</div>

<div class="form-group">
    {!! Form::label('site_id', '站点:',['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::select('site_id', $sites, !isset($user->site_id) ? '1':$user->site_id ,['class' => 'form-control col-sm-2']) !!}
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