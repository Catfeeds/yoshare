@extends('layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                系统管理
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">系统设置</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-body">
                            <table id="table"
                                   data-toggle="table"
                                   data-url="/options/table"
                                   data-pagination="true"
                                   data-search="true"
                                   data-show-refresh="true"
                                   data-show-toggle="true"
                                   data-show-columns="true"
                                   data-toolbar="#toolbar">
                                <thead>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script type="text/javascript">
        (function () {
            $('#table').bootstrapTable({
                columns: [
                    {
                        field: 'name',
                        title: '名称'
                    }, {
                        field: 'value',
                        title: '值',
                        editable: {
                            type: 'select',
                            source: [{ value: 1, text: '是' }, { value: 0, text: "否" }]
                        }

                    }, {
                        field: 'site_name',
                        title: '站点'
                    }
                ],
                onEditableSave: function (field, row, old, $el) {
                    row._token = '{{ csrf_token() }}';
                    $.ajax({
                        type: "put",
                        url: "/options/" + row.id,
                        data: row,
                        success: function (data, status) {
                        },
                        error: function (data) {
                            alert('Error');
                        },
                    });
                }
            });
        })();
    </script>
@endsection