<div class="form-group">
    {!! Form::label('name', '站点名称:',['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('company', '单位名称:',['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
    {!! Form::text('company', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('app_key', '推送KEY:',['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::text('app_key', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('master_secret', '推送密钥:',['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::text('master_secret', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="box-footer">
    <button type="button" class="btn btn-default" onclick="window.history.back();">取　消</button>
    <button type="submit" class="btn btn-info pull-right" id="submit">确　定</button>
</div>