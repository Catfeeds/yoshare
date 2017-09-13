@extends('layouts.master')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                编辑投票
            </h1>
            <ol class="breadcrumb">
                <li><a href="/index"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">投票管理</li>
            </ol>
        </section>

        <section class="content">
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box box-info">
                            <div class="box-body">

                                @include('errors.list')

                                {!! Form::model($vote,['method' => 'PUT', 'action' => ['\App\Http\Controllers\VoteController@update', $vote->id],
                                'class' => 'form-horizontal']) !!}

                                @include('votes._form')


                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </section>
    </div>

@endsection
