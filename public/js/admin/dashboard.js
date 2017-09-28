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
        // 基于准备好的dom，初始化echarts图表
        var lineChart = echarts.init(document.getElementById('lineChart'), 'macarons');

        var option = {
            timeline:{
                data:[
                    '2002-01-01','2003-01-01','2004-01-01','2005-01-01','2006-01-01',
                    '2007-01-01','2008-01-01','2009-01-01','2010-01-01','2011-01-01'
                ],
                label : {
                    formatter : function(s) {
                        return s.slice(0, 4);
                    }
                },
                autoPlay : true,
                playInterval : 1000
            },
            options:[
                {
                    tooltip : {'trigger':'axis'},
                    legend : {
                        x:'center',
                        'data':['PV','UV','VV','IP'],
                        'selected':{
                            'PV':true,
                            'UV':false,
                            'VV':true,
                            'IP':false
                        }
                    },
                    calculable : true,
                    grid : {'y':30,'y2':100},
                    xAxis : [{
                        'type':'category',
                        'axisLabel':{'interval':0},
                        'data':[
                            '00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','01:00',
                            '12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00'
                        ]
                    }],
                    yAxis : [
                        {
                            'type':'value',
                            'max':53500
                        },
                        {
                            'type':'value',
                        }
                    ],
                    series : [
                        {
                            'name':'PV',
                            'type':'bar',
                            'markLine':{
                                symbol : ['arrow','none'],
                                symbolSize : [4, 2],
                                itemStyle : {
                                    normal: {
                                        lineStyle: {color:'orange'},
                                        barBorderColor:'orange',
                                        label:{
                                            position:'left',
                                            formatter:function(params){
                                                return Math.round(params.value);
                                            },
                                            textStyle:{color:'orange'}
                                        }
                                    }
                                },
                                'data':[{'type':'average','name':'平均值'}]
                            },
                            'data': dataMap.dataGDP['2002']
                        },
                        {
                            'name':'UV','yAxisIndex':1,'type':'bar',
                            'data': dataMap.dataFinancial['2002']
                        },
                        {
                            'name':'VV','yAxisIndex':1,'type':'bar',
                            'data': dataMap.dataEstate['2002']
                        },
                        {
                            'name':'IP','yAxisIndex':1,'type':'bar',
                            'data': dataMap.dataPI['2002']
                        },
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2003']},
                        {'data': dataMap.dataFinancial['2003']},
                        {'data': dataMap.dataEstate['2003']},
                        {'data': dataMap.dataPI['2003']},
                        {'data': dataMap.dataSI['2003']},
                        {'data': dataMap.dataTI['2003']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2004']},
                        {'data': dataMap.dataFinancial['2004']},
                        {'data': dataMap.dataEstate['2004']},
                        {'data': dataMap.dataPI['2004']},
                        {'data': dataMap.dataSI['2004']},
                        {'data': dataMap.dataTI['2004']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2005']},
                        {'data': dataMap.dataFinancial['2005']},
                        {'data': dataMap.dataEstate['2005']},
                        {'data': dataMap.dataPI['2005']},
                        {'data': dataMap.dataSI['2005']},
                        {'data': dataMap.dataTI['2005']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2006']},
                        {'data': dataMap.dataFinancial['2006']},
                        {'data': dataMap.dataEstate['2006']},
                        {'data': dataMap.dataPI['2006']},
                        {'data': dataMap.dataSI['2006']},
                        {'data': dataMap.dataTI['2006']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2007']},
                        {'data': dataMap.dataFinancial['2007']},
                        {'data': dataMap.dataEstate['2007']},
                        {'data': dataMap.dataPI['2007']},
                        {'data': dataMap.dataSI['2007']},
                        {'data': dataMap.dataTI['2007']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2008']},
                        {'data': dataMap.dataFinancial['2008']},
                        {'data': dataMap.dataEstate['2008']},
                        {'data': dataMap.dataPI['2008']},
                        {'data': dataMap.dataSI['2008']},
                        {'data': dataMap.dataTI['2008']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2009']},
                        {'data': dataMap.dataFinancial['2009']},
                        {'data': dataMap.dataEstate['2009']},
                        {'data': dataMap.dataPI['2009']},
                        {'data': dataMap.dataSI['2009']},
                        {'data': dataMap.dataTI['2009']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2010']},
                        {'data': dataMap.dataFinancial['2010']},
                        {'data': dataMap.dataEstate['2010']},
                        {'data': dataMap.dataPI['2010']},
                        {'data': dataMap.dataSI['2010']},
                        {'data': dataMap.dataTI['2010']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2011']},
                        {'data': dataMap.dataFinancial['2011']},
                        {'data': dataMap.dataEstate['2011']},
                        {'data': dataMap.dataPI['2011']},
                        {'data': dataMap.dataSI['2011']},
                        {'data': dataMap.dataTI['2011']}
                    ]
                }
            ]
        };

        // 加载数据
        lineChart.setOption(option);

        var mapChart = echarts.init(document.getElementById('mapChart'), 'macarons');

        var option = {
            timeline:{
                data:[
                    '2002-01-01','2003-01-01','2004-01-01','2005-01-01','2006-01-01',
                    '2007-01-01','2008-01-01','2009-01-01','2010-01-01','2011-01-01'
                ],
                label : {
                    formatter : function(s) {
                        return s.slice(0, 4);
                    }
                },
                autoPlay : true,
                playInterval : 1000
            },
            options:[
                {
                    tooltip : {'trigger':'item'},
                    dataRange: {
                        min: 0,
                        max : 53000,
                        text:['高','低'],           // 文本，默认为数值文本
                        calculable : true,
                        x: 'left',
                        color: ['orangered','yellow','lightskyblue']
                    },
                    series : [
                        {
                            'name':'GDP',
                            'type':'map',
                            'data': dataMap.dataGDP['2002']
                        }
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2003']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2004']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2005']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2006']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2007']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2008']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2009']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2010']}
                    ]
                },
                {
                    series : [
                        {'data': dataMap.dataGDP['2011']}
                    ]
                }
            ]
        };

        // 加载数据
        mapChart.setOption(option);

        var pieChart = echarts.init(document.getElementById('pieChart'), 'macarons');

        var option = {
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient : 'vertical',
                x : 'right',
                data:['Chrome','Firefox','Safari','IE9+','IE8-']
            },
            calculable : true,
            series : [
                {
                    name:'浏览器',
                    type:'pie',
                    radius : [30, 110],
                    center : ['50%', 200],
                    roseType : 'area',
                    x: '50%',               // for funnel
                    max: 40,                // for funnel
                    sort : 'ascending',     // for funnel
                    data:[
                        {value:50, name:'Chrome'},
                        {value:10, name:'Firefox'},
                        {value:5, name:'Safari'},
                        {value:25, name:'IE9+'},
                        {value:10, name:'IE8-'},
                    ]
                }
            ]
        };

        // 加载数据
        pieChart.setOption(option);
    }
);