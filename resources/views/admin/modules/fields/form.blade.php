<div class="modal fade common" id="modal_form" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="width:640px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;</button>
                <h4 class="modal-title">请输入字段信息</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-info">
                            <form id="form" action="/admin/modules" method="post" class="form-horizontal">
                                {{ csrf_field() }}
                                <input id="method" name="_method" type="hidden" value="POST">
                                <input type="hidden" name="module_id" value="{{ $module->id }}" />
                                <div class="box-body">
                                    <div class="form-group">
                                        {!! Form::label('name', '名称:', ['class' => 'control-label col-sm-2']) !!}
                                        <div class="col-sm-4">
                                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        </div>
                                        {!! Form::label('title', '标题:', ['class' => 'control-label col-sm-2']) !!}
                                        <div class="col-sm-4">
                                            {!! Form::text('title', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('type', '类型', ['class' => 'control-label col-sm-2']) !!}
                                        <div class="col-sm-4">
                                            {!! Form::select('type', \App\Models\ModuleField::TYPES, null, ['class' => 'form-control']) !!}
                                        </div>
                                        {!! Form::label('default', '默认值:', ['class' => 'control-label col-sm-2']) !!}
                                        <div class="col-sm-4">
                                            {!! Form::text('default', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('required', '必须:', ['class' => 'control-label col-sm-2']) !!}
                                        <div class="col-sm-4">
                                            {!! Form::checkbox('required') !!}
                                        </div>
                                        {!! Form::label('system', '系统字段:', ['class' => 'control-label col-sm-2']) !!}
                                        <div class="col-sm-4">
                                            {!! Form::checkbox('system') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button class="btn btn-default" data-dismiss="modal">取消</button>
                                    <button type="submit" class="btn btn-info pull-right">提交</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
