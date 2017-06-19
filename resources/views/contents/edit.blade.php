@extends('layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                编辑资讯
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li><a href="/contents">资讯管理</a></li>
                <li class="active">编辑</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <!-- right column -->
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-body">

                            @include('errors.list')
                            @include('layouts.flash')

                            {!! Form::model($content,['id' => 'form', 'method' => 'PATCH', 'action' => ['ContentController@update', $content->id],'class' => 'form-horizontal']) !!}

                            <input type="hidden" name="page" value="{{ $page }}">

                            @include('contents.form')

                            {!! Form::close() !!}

                        </div>
                    </div><!-- /.box -->
                </div><!--/.col (right) -->
            </div>   <!-- /.row -->
        </section>
    </div>
@endsection
