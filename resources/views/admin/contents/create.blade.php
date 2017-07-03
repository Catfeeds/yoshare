@extends('admin.layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                新增{{ $model->title }}
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li><a href="/contents">内容管理</a></li>
                <li class="active">新增</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <!-- right column -->
                <div class="col-md-12">
                    <!-- Horizontal Form -->
                    <div class="box box-info">
                        <div class="box-body">

                            @include('admin.errors.list')
                            @include('admin.layouts.flash')

                            {!! Form::open(['url' => '/admin/contents', 'class' => 'form-horizontal']) !!}

                            <input type="hidden" name="category_id" value="{{ $category_id }}">

                            @include('admin.contents.form')

                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

