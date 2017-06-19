@extends('layouts.master')

@section('content')
    {{--<script>--}}
    {{--window.location="/videos"--}}
    {{--</script>--}}

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                数据统计
            </h1>
            <ol class="breadcrumb">
                <li><a href="/"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">数据统计</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">新增视频数</span>
                            <span class="info-box-number">90<small>%</small></span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div><!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">累计视频数</span>
                            <span class="info-box-number">41,410</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div><!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">新增会员数</span>
                            <span class="info-box-number">760</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div><!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">注册会员数</span>
                            <span class="info-box-number">23,500</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div><!-- /.col -->
            </div><!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">本月访问统计 - PV/IP</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <div class="btn-group">
                                    <button class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-wrench"></i></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a></li>
                                    </ul>
                                </div>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <p class="text-center">
                                        <strong>2016年6月1日 - 2016年6月30日</strong>
                                    </p>
                                    <div class="chart">
                                        <!-- Sales Chart Canvas -->
                                        <canvas id="salesChart" style="height: 180px;"></canvas>
                                    </div><!-- /.chart-responsive -->
                                </div><!-- /.col -->
                                <div class="col-md-4">
                                    <p class="text-center">
                                        <strong>Goal Completion</strong>
                                    </p>
                                    <div class="progress-group">
                                        <span class="progress-text">发布的视频</span>
                                        <span class="progress-number"><b>160</b>/200</span>
                                        <div class="progress sm">
                                            <div class="progress-bar progress-bar-aqua" style="width: 80%"></div>
                                        </div>
                                    </div><!-- /.progress-group -->
                                    <div class="progress-group">
                                        <span class="progress-text">转码的视频</span>
                                        <span class="progress-number"><b>310</b>/400</span>
                                        <div class="progress sm">
                                            <div class="progress-bar progress-bar-red" style="width: 77.5%"></div>
                                        </div>
                                    </div><!-- /.progress-group -->
                                    <div class="progress-group">
                                        <span class="progress-text">活跃的会员</span>
                                        <span class="progress-number"><b>4800</b>/23,500</span>
                                        <div class="progress sm">
                                            <div class="progress-bar progress-bar-green" style="width: 24%"></div>
                                        </div>
                                    </div><!-- /.progress-group -->
                                    <div class="progress-group">
                                        <span class="progress-text">App访问次数</span>
                                        <span class="progress-number"><b>56080</b>/78,100</span>
                                        <div class="progress sm">
                                            <div class="progress-bar progress-bar-yellow" style="width: 71.8%"></div>
                                        </div>
                                    </div><!-- /.progress-group -->
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- ./box-body -->
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 17%</span>
                                        <h5 class="description-header">$35,210.43</h5>
                                        <span class="description-text">TOTAL REVENUE</span>
                                    </div><!-- /.description-block -->
                                </div><!-- /.col -->
                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0%</span>
                                        <h5 class="description-header">$10,390.90</h5>
                                        <span class="description-text">TOTAL COST</span>
                                    </div><!-- /.description-block -->
                                </div><!-- /.col -->
                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-green"><i class="fa fa-caret-up"></i> 20%</span>
                                        <h5 class="description-header">$24,813.53</h5>
                                        <span class="description-text">TOTAL PROFIT</span>
                                    </div><!-- /.description-block -->
                                </div><!-- /.col -->
                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block">
                                        <span class="description-percentage text-red"><i class="fa fa-caret-down"></i> 18%</span>
                                        <h5 class="description-header">1200</h5>
                                        <span class="description-text">GOAL COMPLETIONS</span>
                                    </div><!-- /.description-block -->
                                </div>
                            </div><!-- /.row -->
                        </div><!-- /.box-footer -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-8">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">最新发布的视频</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>视频标题</th>
                                        <th>栏目</th>
                                        <th>发布状态</th>
                                        <th>发布日期</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><a href="#">今年东博会将在会展中心新展馆开幕</a></td>
                                        <td>南宁新闻</td>
                                        <td><span class="label label-success">已发布</span></td>
                                        <td>
                                            <div class="sparkbar" data-color="#00a65a" data-height="20">2016-06-01</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">南宁市科协第八次代表大会召开</a></td>
                                        <td>南宁新闻</td>
                                        <td><span class="label label-primary">未发布</span></td>
                                        <td>
                                            <div class="sparkbar" data-color="#f39c12" data-height="20">2016-06-01</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">南宁市科协第八次代表大会召开</a></td>
                                        <td>南宁新闻</td>
                                        <td><span class="label label-success">已发布</span></td>
                                        <td>
                                            <div class="sparkbar" data-color="#f56954" data-height="20">2016-06-01</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">南宁市城市轨道交通乘客守则》正式发布</a></td>
                                        <td>南宁新闻</td>
                                        <td><span class="label label-success">已发布</span></td>
                                        <td>
                                            <div class="sparkbar" data-color="#00c0ef" data-height="20">2016-06-01</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">市本级公车处置正式启动 首批公车5月30日网络竞价</a></td>
                                        <td>南宁新闻</td>
                                        <td><span class="label label-success">已发布</span></td>
                                        <td>
                                            <div class="sparkbar" data-color="#f39c12" data-height="20">2016-06-01</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">市民参观民族大道修复整治工程 理解和支持项目建设</a></td>
                                        <td>南宁新闻</td>
                                        <td><span class="label label-danger">已删除</span></td>
                                        <td>
                                            <div class="sparkbar" data-color="#f56954" data-height="20">2016-06-01</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">幸福工程：让贫困母亲走上致富路</a></td>
                                        <td>南宁新闻</td>
                                        <td><span class="label label-success">已发布</span></td>
                                        <td>
                                            <div class="sparkbar" data-color="#00a65a" data-height="20">2016-06-01</div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div><!-- /.table-responsive -->
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->

                <div class="col-md-4">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">今日访问统计</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="chart-responsive">
                                        <canvas id="pieChart" height="150"></canvas>
                                    </div><!-- ./chart-responsive -->
                                </div><!-- /.col -->
                                <div class="col-md-4">
                                    <ul class="chart-legend clearfix">
                                        <li><i class="fa fa-circle-o text-red"></i> Chrome</li>
                                        <li><i class="fa fa-circle-o text-green"></i> IE</li>
                                        <li><i class="fa fa-circle-o text-yellow"></i> FireFox</li>
                                        <li><i class="fa fa-circle-o text-aqua"></i> Safari</li>
                                        <li><i class="fa fa-circle-o text-light-blue"></i> Opera</li>
                                        <li><i class="fa fa-circle-o text-gray"></i> 其他</li>
                                    </ul>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-body -->
                        <div class="box-footer no-padding">
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">Android
                                        <span class="pull-right text-red"><i class="fa fa-angle-down"></i> 12%</span></a>
                                </li>
                                <li><a href="#">iOS <span class="pull-right text-green"><i class="fa fa-angle-up"></i> 4%</span></a>
                                </li>
                                <li><a href="#">Other <span class="pull-right text-red"><i class="fa fa-angle-down"></i> 8%</span></a>
                                </li>
                            </ul>
                        </div><!-- /.footer -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@endsection
