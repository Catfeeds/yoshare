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
                        <span class="info-box-icon bg-yellow">IP</span>

                        <div class="info-box-content">
                            <span class="info-box-text">独立IP</span>
                            <span class="info-box-number text-yellow sel-font">88197</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="glyphicon glyphicon-grain"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">注册会员(RM)</span>
                            <span class="info-box-number text-green sel-font">5409</span>
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
    <script>
        //小时
        var lineDate = [
            '2017-10-01', '2017-10-02', '2017-10-03', '2017-10-04', '2017-10-05',
            '2017-10-06', '2017-10-07', '2017-10-08', '2017-10-09', '2017-10-10'
        ];

        var lineHour = [
            '00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '01:00',
            '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'
        ];

        var pvData = [];
        pvData[0] = [{name: "00:00", value: 4315},
            {name: '01:00', value: 2150.76},
            {name: '02:00', value: 6018.28},
            {name: '03:00', value: 2324.8},
            {name: '04:00', value: 1940.94},
            {name: '05:00', value: 5458.22},
            {name: '06:00', value: 2348.54},
            {name: '07:00', value: 3637.2},
            {name: '08:00', value: 5741.03},
            {name: '09:00', value: 10606.85},
            {name: '10:00', value: 8003.67},
            {name: '11:00', value: 3519.72},
            {name: '12:00', value: 4467.55},
            {name: '13:00', value: 2450.48},
            {name: '14:00', value: 10275.5},
            {name: '15:00', value: 6035.48},
            {name: '16:00', value: 4212.82},
            {name: '17:00', value: 4151.54},
            {name: '18:00', value: 13502.42},
            {name: '19:00', value: 2523.73},
            {name: '20:00', value: 642.73},
            {name: '21:00', value: 2232.86},
            {name: '22:00', value: 4725.01},
            {name: '23:00', value: 1243.43},
        ];
        pvData[1] = [
            {name: '00:00', value: 5007.21},
            {name: '01:00', value: 2578.03},
            {name: '02:00', value: 6921.29},
            {name: '03:00', value: 2855.23},
            {name: '04:00', value: 2388.38},
            {name: '05:00', value: 6002.54},
            {name: '06:00', value: 2662.08},
            {name: '07:00', value: 4057.4},
            {name: '08:00', value: 6694.23},
            {name: '09:00', value: 12442.87},
            {name: '10:00', value: 9705.02},
            {name: '11:00', value: 3923.11},
            {name: '12:00', value: 4983.67},
            {name: '13:00', value: 2807.41},
            {name: '14:00', value: 12078.15},
            {name: '15:00', value: 6867.7},
            {name: '16:00', value: 4757.45},
            {name: '17:00', value: 4659.99},
            {name: '18:00', value: 15844.64},
            {name: '19:00', value: 2821.11},
            {name: '20:00', value: 713.96},
            {name: '21:00', value: 2555.72},
            {name: '22:00', value: 5333.09},
            {name: '23:00', value: 1777.34},
        ];
        //地区
        var mapDate = [
            '2017-10-01', '2017-10-02', '2017-10-03', '2017-10-04', '2017-10-05',
            '2017-10-06', '2017-10-07', '2017-10-08', '2017-10-09', '2017-10-10'
        ];

        var mapData = [];
        mapData['2017-10-01'] = [{name: "北京", value: 4315},
            {name: "天津", value: 2150.76},
            {name: "河北", value: 6018.28},
            {name: "山西", value: 2324.8},
            {name: "内蒙古", value: 1940.94},
            {name: "辽宁", value: 5458.22},
            {name: "吉林", value: 2348.54},
            {name: "黑龙江", value: 3637.2},
            {name: "上海", value: 5741.03},
            {name: "江苏", value: 10606.85},
            {name: "浙江", value: 8003.67},
            {name: "安徽", value: 3519.72},
            {name: "福建", value: 4467.55},
            {name: "江西", value: 2450.48},
            {name: "山东", value: 10275.5},
            {name: "河南", value: 6035.48},
            {name: "湖北", value: 4212.82},
            {name: "湖南", value: 4151.54},
            {name: "广东", value: 13502.42},
            {name: "广西", value: 2523.73},
            {name: "海南", value: 642.73},
            {name: "重庆", value: 2232.86},
            {name: "四川", value: 4725.01},
            {name: "贵州", value: 1243.43},
            {name: "云南", value: 2312.82},
            {name: "西藏", value: 162.04},
            {name: "陕西", value: 2253.39},
            {name: "甘肃", value: 1232.03},
            {name: "青海", value: 340.65},
            {name: "宁夏", value: 377.16},
            {name: "新疆", value: 1612.6}
        ];
        mapData['2017-10-02'] = [
            {name: "北京", value: 5007.21},
            {name: "天津", value: 2578.03},
            {name: "河北", value: 6921.29},
            {name: "山西", value: 2855.23},
            {name: "内蒙古", value: 2388.38},
            {name: "辽宁", value: 6002.54},
            {name: "吉林", value: 2662.08},
            {name: "黑龙江", value: 4057.4},
            {name: "上海", value: 6694.23},
            {name: "江苏", value: 12442.87},
            {name: "浙江", value: 9705.02},
            {name: "安徽", value: 3923.11},
            {name: "福建", value: 4983.67},
            {name: "江西", value: 2807.41},
            {name: "山东", value: 12078.15},
            {name: "河南", value: 6867.7},
            {name: "湖北", value: 4757.45},
            {name: "湖南", value: 4659.99},
            {name: "广东", value: 15844.64},
            {name: "广西", value: 2821.11},
            {name: "海南", value: 713.96},
            {name: "重庆", value: 2555.72},
            {name: "四川", value: 5333.09},
            {name: "贵州", value: 1426.34},
            {name: "云南", value: 2556.02},
            {name: "西藏", value: 185.09},
            {name: "陕西", value: 2587.72},
            {name: "甘肃", value: 1399.83},
            {name: "青海", value: 390.2},
            {name: "宁夏", value: 445.36},
            {name: "新疆", value: 1886.35},
        ];

        //浏览器
        var browserLegend = [];
        var browserData = [];
        $.ajax({
            type : "get",
            url : "/admin/browsers",
            async : false,
            success : function(res){
                browserLegend = res.browsers;
                browserData = res.data;
            }
        });
    </script>
    <script src="/js/admin/dashboard.js"></script>
@endsection