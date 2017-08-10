<div class="modal fade common" id="modal_form" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="width:640px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times;</button>
                <h4 class="modal-title">请输入模块信息</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-info">
                            <form id="form" action="/admin/modules" method="post" class="form-horizontal">
                                {{ csrf_field() }}
                                <input id="method" name="_method" type="hidden" value="POST">
                                <div class="box-body">
                                    <div class="form-group">
                                        {!! Form::label('name', '表名:', ['class' => 'control-label col-sm-2']) !!}
                                        <div class="col-sm-4">
                                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        </div>
                                        {!! Form::label('title', '模块名称', ['class' => 'control-label col-sm-2']) !!}
                                        <div class="col-sm-4">
                                            {!! Form::text('title', null, ['class' => 'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('model_class', '模块类', ['class' => 'control-label col-sm-2']) !!}
                                        <div class="col-sm-10">
                                            {!! Form::text('model_class', null, ['class' => 'form-control', 'placeholder' => 'App\Models\Article']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('controller_class', '控制器类', ['class' => 'control-label col-sm-2']) !!}
                                        <div class="col-sm-10">
                                            {!! Form::text('controller_class', null, ['class' => 'form-control', 'placeholder' => 'App\Http\Controllers\ArticleController']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('view_path', '视图路径', ['class' => 'control-label col-sm-2']) !!}
                                        <div class="col-sm-10">
                                            {!! Form::text('view_path', null, ['class' => 'form-control', 'placeholder' => 'admin.articles']) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('groups', '编辑器分组', ['class' => 'control-label col-sm-2']) !!}
                                        <div class="col-sm-10">
                                            {!! Form::text('groups', null, ['class' => 'form-control', 'placeholder' => '基本信息,正文']) !!}
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
