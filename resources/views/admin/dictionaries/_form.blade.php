<div class="form-group">
    {!! Form::label('code', '编码:', ['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::text('code', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('name', '名称:',['class' => 'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('value', '值:',['class'=>'control-label col-sm-2']) !!}
    <div class="col-sm-10">
        {!! Form::text('value', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="box-footer">
    <a href="/admin/dictionaries">
        <button type="button" class="btn btn-default">取　消</button>
    </a>
    <button type="submit" class="btn btn-info pull-right">确　定</button>
</div>