@extends('admin.layouts.master')
@section('content')
    {{--<script>--}}
    {{--window.location="/videos"--}}
    {{--</script>--}}

    <!-- import Ionicons -->
    <!-- @author sel -->
    <style>
        body{
            font-size: 14px;
        }
        .info-box-content{
            padding-top: 18px;
        }
        .font-16{
            font-size: 16px;
        }
        .progress-number{
            font-size: 16px;
        }
        .progress-number .num{
            margin-right: 40px;
            margin-left: 50px;
            position: relative;
            top: -6px;
        }
        .progress-number .ion-arrow-up-c{
            position: relative;
            top: -4px
        }
        .chart-legend {
            line-height: 25px;
        }
        .sel-font{
            font-size: 27px;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height: 294px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <p>数据统计</p>
            <ol class="breadcrumb">
                <li><a href="#"><i class="ion-ios-home-outline"></i> 首页</a></li>
                <li class="active">数据统计</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="glyphicon glyphicon-signal"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><span class="font-16">总访问量</span></span>
                            <span class="info-box-number text-aqua sel-font">144829</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div><!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="glyphicon glyphicon-time"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text font-16"><span class="font-16">平均浏览时长 [分钟]</span></span>
                            <span class="info-box-number text-red sel-font" id="average">13</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div><!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green">IP</span>
                        <div class="info-box-content">
                            <span class="info-box-text"><span class="font-16">总ip数</span></span>
                            <span class="info-box-number text-green sel-font">23869</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div><!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="glyphicon glyphicon-calendar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><span class="font-16">总统计天数</span></span>
                            <span class="info-box-number text-yellow sel-font">193</span>
                        </div><!-- /.info-box-content -->
                    </div><!-- /.info-box -->
                </div><!-- /.col -->
            </div><!-- /.row -->

            <!-- 今天 昨天 最近7天 最近30天 -->
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered" id="tend" style="background: #fff">
                        <tbody><tr>
                            <td data-id="1" class="bg-aqua" style="cursor:pointer">今天</td>
                            <td data-id="2" style="cursor:pointer">昨天</td>
                            <td data-id="3" style="cursor:pointer">最近7天</td>
                            <td data-id="4" style="cursor:pointer">最近30天</td>
                        </tr>
                        </tbody></table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered" id="progress" style="background: #fff">
                        <tbody><tr>
                            <td class="bg-aqua" data-id="1" style="cursor:pointer">今天</td>
                            <td data-id="2" style="cursor:pointer">昨天</td>
                            <td data-id="3" style="cursor:pointer">最近7天</td>
                            <td data-id="4" style="cursor:pointer">最近30天</td>
                        </tr>
                        </tbody></table>
                </div>
            </div>
            <!-- 第一层 -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h4 class="box-title">趋势图</h4>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- 按钮组 -->
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-default bg-light-blue-active">浏览量（PV）</button>
                                        <!-- <button type="button" class="btn btn-default">访客数（UV）</button>
                                        <button type="button" class="btn btn-default">其他 <span class="caret"></span></button> -->
                                    </div>
                                    <!-- 复选框 -->
                                    <!-- <span style="font-size: 12px;margin-left: 50px;">
                                        <label class="checkbox-inline">
                                            对比
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="option1"> 前一日
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" value="option2"> 上周同时期
                                        </label>
                                    </span> -->
                                    <div class="chart">
                                        <canvas id="line1" height="0"></canvas>
                                        <canvas id="line2" height="0"></canvas>
                                        <canvas id="line3" height="0"></canvas>
                                        <canvas id="line4" height="0"></canvas>
                                        <!-- Sales Chart Canvas -->
                                        <!-- <canvas id="salesChart" style="height: 180px;"></canvas> -->
                                    </div><!-- /.chart-responsive -->
                                </div><!-- /.col -->

                                <div class="col-md-6" id="line-progress">
                                    <div class="progress-group">
                                        <p>网站浏览量（PV）</p>
                                        <span class="progress-number">
                                        <span class="num numPv"></span>
                                    </span>
                                        <div class="progress sm">
                                            <div class="progress-bar progress-bar-aqua pv" style="width: 50%"></div>
                                        </div>
                                    </div><!-- /.progress-group -->
                                    <div class="progress-group">
                                        <p>ip数</p>
                                        <span class="progress-number">
                                        <span class="num numIp"></span>
                                    </span>
                                        <div class="progress sm">
                                            <div class="progress-bar progress-bar-red ip" style="width: 50%"></div>
                                        </div>
                                    </div><!-- /.progress-group -->
                                    <div class="progress-group">
                                        <p>访问时长 [分钟]</p>
                                        <span class="progress-number">
                                        <span class="num numTime" style='margin-left:28px'></span>
                                    </span>
                                        <div class="progress sm">
                                            <div class="progress-bar progress-bar-green staytime" style="width: 50%"></div>
                                        </div>
                                    </div>
                                </div>


                            </div><!-- /.row -->
                        </div><!-- ./box-body -->
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-blue"><i class="ion-ios-circle-filled"></i></span>
                                        <span id="today"></span>浏览量（pv）
                                    </div><!-- /.description-block -->
                                </div><!-- /.col -->
                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-light-blue"><i class="ion-ios-circle-filled"></i></span>
                                    </div><!-- /.description-block -->
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- /.box-footer -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div><!-- /.row -->

            <!-- 第二块 - 访问地图 - 饼状图 -->
            <div class="row">
                <div class="col-md-6">
                    <p>CMS访问地区统计图</p>
                </div>
                <div class="col-md-6">
                    <p>浏览器访问页面排行</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box box-solid">
                                        <div class="box-body">
                                            <div id="china-map" style="height: 500px; width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="box-default">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="chart-responsive">
                                                        <canvas id="pieChart" height="505" width="258" style="width: 258px; height: 505px;"></canvas>
                                                    </div><!-- ./chart-responsive -->
                                                </div><!-- /.col -->
                                                <div class="col-md-4">
                                                    <ul class="chart-legend clearfix" style="margin-top: 150px;margin-left: 50px;">
                                                        <li><i class="fa fa-circle-o text-red"></i> 谷歌浏览器</li>
                                                        <li><i class="fa fa-circle-o text-green"></i> 火狐浏览器</li>
                                                        <li><i class="fa fa-circle-o text-yellow"></i> 苹果浏览器</li>
                                                        <li><i class="fa fa-circle-o text-aqua"></i> 欧朋浏览器</li>
                                                        <li><i class="fa fa-circle-o text-light-blue"></i> 微软浏览器</li>
                                                        <li><i class="fa fa-circle-o text-gray"></i> 其他浏览器</li>
                                                    </ul>
                                                </div><!-- /.col -->
                                            </div><!-- /.row -->
                                        </div><!-- /.box-body -->
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                        </div><!-- ./box-body -->
                    </div><!-- /.box1 -->
                </div>
            </div>

            <!-- 第三块 - 受访页面排行 -->
            <div class="row">
                <div class="col-md-12">
                    <p>今天受访页面排行</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <table class="table table-striped">
                            <tbody><tr>
                                <td style="width: 10px">#</td>
                                <td>访问地址</td>
                                <td>浏览量（PV）</td>
                                <td style="width: 80px">占比</td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>http://www.cms.com.cn</td>
                                <td>1254</td>
                                <td>
                                    <span class="badge bg-red">
                                    60.03
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>http://suv.cms.com.cn</td>
                                <td>123</td>
                                <td>
                                    <span class="badge bg-yellow">
                                    5.89
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>http://lighttruck.cms.com.cn</td>
                                <td>113</td>
                                <td>
                                    <span class="badge bg-light-blue">
                                    5.41
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>http://cms.zhiye.com/</td>
                                <td>112</td>
                                <td>
                                    <span class="badge bg-green">
                                    5.36
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>http://pickup.cms.com.cn</td>
                                <td>67</td>
                                <td>
                                    <span class="badge bg-green">
                                    3.21
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>http://everest.cms.com.cn</td>
                                <td>63</td>
                                <td>
                                    <span class="badge bg-green">
                                    3.02
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>http://transit.cms.com.cn</td>
                                <td>62</td>
                                <td>
                                    <span class="badge bg-green">
                                    2.97
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>http://lightbus.cms.com.cn/index.shtml</td>
                                <td>39</td>
                                <td>
                                    <span class="badge bg-green">
                                    1.87
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>http://tourneo.cms.com.cn</td>
                                <td>26</td>
                                <td>
                                    <span class="badge bg-green">
                                    1.24
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>http://www.cms.com.cn/summary.html</td>
                                <td>24</td>
                                <td>
                                    <span class="badge bg-green">
                                    1.15
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>http://heavytruck.cms.com.cn/</td>
                                <td>21</td>
                                <td>
                                    <span class="badge bg-green">
                                    1.01
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>http://heavytruck.cms.com.cn</td>
                                <td>20</td>
                                <td>
                                    <span class="badge bg-green">
                                    0.96
                                    </span>
                                </td>
                            </tr>
                            </tbody>
                            </tbody></table>
                    </div>
                </div>
            </div>


        </section><!-- /.content -->
    </div>

    <!-- 加载Charts - 用于饼状图 -->
    <script src="/plugins/chartjs/Chart.min.js"></script>
    <!-- 加载Echarts - 用于地图 -->
    <script src="/plugins/echarts/3.3.2/echarts.min.js"></script>
    <script src="/plugins/echarts/3.3.2/china.js"></script>
    <!-- @author: sel -->
    <script src="/js/access.js"></script>
@endsection
