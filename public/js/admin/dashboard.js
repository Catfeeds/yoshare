// 路径配置
require.config({
    paths: {
        echarts: 'http://echarts.baidu.com/build/dist'
    }
});

// 使用
require(
    [
        'echarts',
        'echarts/chart/line',
        'echarts/chart/bar',
        'echarts/chart/pie',
        'echarts/chart/map'
    ],
    function (echarts) {
        var lineChart = echarts.init(document.getElementById('lineChart'), 'macarons');

        var option = {
            timeline: {
                data: lineDate,
                label: {
                    formatter: function (s) {
                        return s.slice(0, 10);
                    }
                },
                autoPlay: true,
                playInterval: 1000
            },
            options: [
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
                            'max': 53500
                        },
                        {
                            'type': 'value',
                        }
                    ],
                    series: [
                        {
                            'name': 'PV',
                            'type': 'bar',
                            'data': pvData[0]
                        },
                        {
                            'name': 'UV', 'yAxisIndex': 1, 'type': 'bar',
                            'data': dataMap.dataFinancial['2002']
                        },
                        {
                            'name': 'RM', 'yAxisIndex': 1, 'type': 'bar',
                            'data': dataMap.dataEstate['2002']
                        },
                        {
                            'name': 'IP', 'yAxisIndex': 1, 'type': 'bar',
                            'data': dataMap.dataPI['2002']
                        },
                    ]
                },
                {
                    series: [
                        {'data': pvData[1]},
                        {'data': dataMap.dataFinancial['2003']},
                        {'data': dataMap.dataEstate['2003']},
                        {'data': dataMap.dataPI['2003']},
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2004']},
                        {'data': dataMap.dataFinancial['2004']},
                        {'data': dataMap.dataEstate['2004']},
                        {'data': dataMap.dataPI['2004']},
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2005']},
                        {'data': dataMap.dataFinancial['2005']},
                        {'data': dataMap.dataEstate['2005']},
                        {'data': dataMap.dataPI['2005']},
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2006']},
                        {'data': dataMap.dataFinancial['2006']},
                        {'data': dataMap.dataEstate['2006']},
                        {'data': dataMap.dataPI['2006']},
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2007']},
                        {'data': dataMap.dataFinancial['2007']},
                        {'data': dataMap.dataEstate['2007']},
                        {'data': dataMap.dataPI['2007']},
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2008']},
                        {'data': dataMap.dataFinancial['2008']},
                        {'data': dataMap.dataEstate['2008']},
                        {'data': dataMap.dataPI['2008']},
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2009']},
                        {'data': dataMap.dataFinancial['2009']},
                        {'data': dataMap.dataEstate['2009']},
                        {'data': dataMap.dataPI['2009']},
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2010']},
                        {'data': dataMap.dataFinancial['2010']},
                        {'data': dataMap.dataEstate['2010']},
                        {'data': dataMap.dataPI['2010']},
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2011']},
                        {'data': dataMap.dataFinancial['2011']},
                        {'data': dataMap.dataEstate['2011']},
                        {'data': dataMap.dataPI['2011']},
                    ]
                }
            ]
        };

        // 加载数据
        lineChart.setOption(option);

        var mapChart = echarts.init(document.getElementById('mapChart'), 'macarons');

        var option = {
            timeline: {
                data: mapDate,
                label: {
                    formatter: function (s) {
                        return s.slice(0, 10);
                    }
                },
                autoPlay: true,
                playInterval: 1000
            },
            options: [
                {
                    tooltip: {'trigger': 'item'},
                    dataRange: {
                        min: 0,
                        max: 53000,
                        text: ['高', '低'],
                        calculable: true,
                        x: 'left',
                        color: ['orangered', 'yellow', 'lightskyblue']
                    },
                    series: [
                        {
                            'name': 'GDP',
                            'type': 'map',
                            'data': mapData['2017-10-01']
                        }
                    ]
                },
                {
                    series: [
                        {'data': mapData['2017-10-02']}
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2004']}
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2005']}
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2006']}
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2007']}
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2008']}
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2009']}
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2010']}
                    ]
                },
                {
                    series: [
                        {'data': dataMap.dataGDP['2011']}
                    ]
                }
            ]
        };

        // 加载数据
        mapChart.setOption(option);

        var pieChart = echarts.init(document.getElementById('pieChart'), 'macarons');

        var option = {
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                x: 'right',
                data: browserLegend
            },
            calculable: true,
            series: [
                {
                    name: '浏览器',
                    type: 'pie',
                    radius: [30, 110],
                    center: ['50%', 200],
                    roseType: 'area',
                    x: '50%',               // for funnel
                    max: 40,                // for funnel
                    sort: 'ascending',     // for funnel
                    data: browserData
                }
            ]
        };

        // 加载数据
        pieChart.setOption(option);
    }
);