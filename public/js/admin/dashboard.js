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
                data: lineDates,
                label: {
                    formatter: function (s) {
                        return s.slice(0, 10);
                    }
                },
                autoPlay: true,
                playInterval: 1000
            },
            options: lineOptions
        };

        // 加载数据
        lineChart.setOption(option);

        var mapChart = echarts.init(document.getElementById('mapChart'), 'macarons');

        var option = {
            timeline: {
                data: mapDates,
                label: {
                    formatter: function (s) {
                        return s.slice(0, 10);
                    }
                },
                autoPlay: true,
                playInterval: 1000
            },
            options: mapOptions
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