@extends('admin.layouts.master')

@section('css')
    <style>
        .sel-font {
            font-size: 32px;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                访问统计
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">访问统计</li>
            </ol>
        </section>

        <section class="content">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="glyphicon glyphicon-signal"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">浏览量(PV)</span>
                            <span class="info-box-number text-aqua sel-font">526321</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="glyphicon glyphicon-user"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">独立用户(UV)</span>
                            <span class="info-box-number text-red sel-font">122110</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="glyphicon glyphicon-calendar"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">访问次数(VV)</span>
                            <span class="info-box-number text-green sel-font">85409</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow">IP</span>

                        <div class="info-box-content">
                            <span class="info-box-text">独立IP</span>
                            <span class="info-box-number text-yellow sel-font">197</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">小时统计</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="chart" id="lineChart" style="height:400px"></div>
                                    <!-- /.chart-responsive -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- ./box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-8">
                    <!-- MAP & BOX PANE -->
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">地区统计</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pad">
                                        <!-- Map will be created here -->
                                        <div id="mapChart" style="height: 400px;"></div>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                    <!-- TABLE: LATEST ORDERS -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">今天受访页面排行</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>页面</th>
                                        <th>浏览量(PV)</th>
                                        <th>比例</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><a href="#">南宁市例行新闻发布会：2017年南宁市创新驱动发展情况通报</a></td>
                                        <td>2638</td>
                                        <td><span class="label label-danger">25%</span></td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">中秋国庆将至！值班≠加班 调休≠补休！</a></td>
                                        <td>1367</td>
                                        <td><span class="label label-warning">13%</span></td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">第14届东博会对外交流成效显著</a></td>
                                        <td>712</td>
                                        <td><span class="label label-info">8%</span></td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">2017中国—东盟（南宁）孔子文化周开幕</a></td>
                                        <td>353</td>
                                        <td><span class="label label-default">4%</span></td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">直升机急救真的来了！不是演练，是真实伤员转运！</a></td>
                                        <td>261</td>
                                        <td><span class="label label-default">3%</span></td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">场馆应急演练 看球赛遇"突发"观众这样撤</a></td>
                                        <td>242</td>
                                        <td><span class="label label-default">1%</span></td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">这种南宁人都爱的水果竟然是“致富果”！</a></td>
                                        <td>120</td>
                                        <td><span class="label label-default">1%</span></td>
                                    </tr>
                                    <tr>
                                        <td><a href="#"> 百个中秋爱心礼包情暖困难群众</a></td>
                                        <td>119</td>
                                        <td><span class="label label-default">1%</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->

                <div class="col-md-4">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">浏览器</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="chart" id="pieChart" style="height:400px"></div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">最近注册会员</h3>

                            <div class="box-tools pull-right">
                                <span class="label label-danger">8 个新会员</span>
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <ul class="users-list clearfix">
                                <li>
                                    <img src="/plugins/admin-lte/2.3.7/img/user1-128x128.jpg" alt="User Image">
                                    <a class="users-list-name" href="#">Alexander Pierce</a>
                                    <span class="users-list-date">Today</span>
                                </li>
                                <li>
                                    <img src="/plugins/admin-lte/2.3.7/img/user8-128x128.jpg" alt="User Image">
                                    <a class="users-list-name" href="#">Norman</a>
                                    <span class="users-list-date">Yesterday</span>
                                </li>
                                <li>
                                    <img src="/plugins/admin-lte/2.3.7/img/user7-128x128.jpg" alt="User Image">
                                    <a class="users-list-name" href="#">Jane</a>
                                    <span class="users-list-date">12 Jan</span>
                                </li>
                                <li>
                                    <img src="/plugins/admin-lte/2.3.7/img/user6-128x128.jpg" alt="User Image">
                                    <a class="users-list-name" href="#">John</a>
                                    <span class="users-list-date">12 Jan</span>
                                </li>
                                <li>
                                    <img src="/plugins/admin-lte/2.3.7/img/user2-160x160.jpg" alt="User Image">
                                    <a class="users-list-name" href="#">Alexander</a>
                                    <span class="users-list-date">13 Jan</span>
                                </li>
                                <li>
                                    <img src="/plugins/admin-lte/2.3.7/img/user5-128x128.jpg" alt="User Image">
                                    <a class="users-list-name" href="#">Sarah</a>
                                    <span class="users-list-date">14 Jan</span>
                                </li>
                                <li>
                                    <img src="/plugins/admin-lte/2.3.7/img/user4-128x128.jpg" alt="User Image">
                                    <a class="users-list-name" href="#">Nora</a>
                                    <span class="users-list-date">15 Jan</span>
                                </li>
                                <li>
                                    <img src="/plugins/admin-lte/2.3.7/img/user3-128x128.jpg" alt="User Image">
                                    <a class="users-list-name" href="#">Nadia</a>
                                    <span class="users-list-date">15 Jan</span>
                                </li>
                            </ul>
                            <!-- /.users-list -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-center">
                            <a href="javascript:void(0)" class="uppercase">查看所有会员</a>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>

    </div><!-- /.content-wrapper -->
    <script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
    <script src="/js/admin/data.js"></script>
    <script src="/js/admin/dashboard.js"></script>
@endsection