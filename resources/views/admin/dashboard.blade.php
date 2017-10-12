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
                            <span class="info-box-number text-aqua sel-font">{{ $pv }}</span>
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
                            <span class="info-box-number text-red sel-font">{{ $uv }}</span>
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
                            <span class="info-box-number text-yellow sel-font">{{ $ip }}</span>
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
                            <span class="info-box-number text-green sel-font">{{ $rm }}</span>
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
                                    @foreach($pages as $page)
                                        <tr>
                                            <td><a href="{{ $page->url }}" target="_blank">{{ $page->title }}</a></td>
                                            <td>{{ $page->clicks }}</td>
                                            <td><span class="badge {{ $page->badge }}">{{ $page->percent }}</span></td>
                                        </tr>
                                    @endforeach
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
                                @foreach($members as $member)
                                    <li>
                                        <img src="{{ $member->avatar_url }}" alt="User Image">
                                        <span class="users-list-name">{{ $member->nick_name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <!-- /.users-list -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-center">
                            <a href="/admin/members">查看所有会员</a>
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
        //小时统计
        var lineDates = [];
        var lineOptions = [];
        $.ajax({
            type: "get",
            url: "/admin/hours",
            async: false,
            success: function (res) {
                lineDates = res.dates;
                var lineMax = res.max;
                var lineHour = res.hours;
                var pvData = res.pvs;
                var uvData = res.uvs;
                var ipData = res.ips;
                var rmData = res.rms;

                lineOptions = [
                    {
                        tooltip: {'trigger': 'axis'},
                        legend: {
                            x: 'center',
                            'data': ['PV', 'UV', 'IP', 'RM'],
                            'selected': {
                                'PV': true,
                                'UV': true,
                                'IP': false,
                                'RM': false
                            }
                        },
                        calculable: true,
                        grid: {'y': 30, 'y2': 100},
                        xAxis: [{
                            'type': 'category',
                            'axisLabel': {'interval': 0},
                            'data': lineHour
                        }],
                        yAxis: [
                            {
                                'type': 'value',
                                'max': Math.floor(lineMax / 100 + 1) * 100,
                            },
                            {
                                'type': 'value',
                            }
                        ],
                        series: [
                            {
                                'name': 'PV', 'type': 'bar',
                                'data': pvData[lineDates[0]]
                            },
                            {
                                'name': 'UV', 'yAxisIndex': 1, 'type': 'bar',
                                'data': uvData[lineDates[0]]
                            },
                            {
                                'name': 'IP', 'yAxisIndex': 1, 'type': 'bar',
                                'data': ipData[lineDates[0]]
                            },
                            {
                                'name': 'RM', 'yAxisIndex': 1, 'type': 'bar',
                                'data': rmData[lineDates[0]]
                            },
                        ]
                    }
                ];

                for (var i = 1; i < lineDates.length; i++) {
                    lineOptions[i] = {
                        series: [
                            {'data': pvData[lineDates[i]]},
                            {'data': uvData[lineDates[i]]},
                            {'data': ipData[lineDates[i]]},
                            {'data': rmData[lineDates[i]]},
                        ]
                    };
                }
            }
        });

        //地区统计
        var mapDates = [];
        var mapOptions = [];
        $.ajax({
            type: "get",
            url: "/admin/areas",
            async: false,
            success: function (res) {
                mapDates = res.date;
                var mapMax = res.max;
                var mapData = res.data;

                mapOptions = [
                    {
                        tooltip: {'trigger': 'item'},
                        dataRange: {
                            min: 0,
                            max: Math.floor(mapMax / 100 + 1) * 100,
                            text: ['高', '低'],
                            calculable: true,
                            x: 'left',
                            color: ['orangered', 'yellow', 'lightskyblue']
                        },
                        series: [
                            {
                                'name': 'PV',
                                'type': 'map',
                                'data': mapData[mapDates[0]]
                            }
                        ]
                    }
                ];

                for (var i = 1; i < mapDates.length; i++) {
                    mapOptions[i] = {
                        series: [
                            {'data': mapData[mapDates[i]]}
                        ]
                    };
                }
            }
        });

        //浏览器统计
        var browserLegend = [];
        var browserData = [];
        $.ajax({
            type: "get",
            url: "/admin/browsers",
            async: false,
            success: function (res) {
                browserLegend = res.browsers;
                browserData = res.data;
            }
        });
    </script>
    <script src="/js/admin/dashboard.js"></script>
@endsection