@extends('admin.layouts.master')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                编辑问卷
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">问卷管理</li>
            </ol>
        </section>

        <section class="content">
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-info">
                            <div class="box-body">
                                @include('admin.layouts.flash')
                                @include('admin.errors.list')

                                {!! Form::model($survey,['method' => 'PUT', 'action' => ['\App\Http\Controllers\SurveyController@update', $survey->id],
                                'class' => 'form-horizontal']) !!}

                                @include('admin.surveys._form')


                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </section>
    </div>

@endsection
