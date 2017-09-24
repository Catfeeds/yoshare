/**
 *  data progress
 *
 *  @author: sel
 */
var lineChartData = {
    labels : ["0","6","12","18"],
    datasets : [
        {
            fillColor : "rgba(151,187,205,0.5)",
            strokeColor : "rgba(151,187,205,1)",
            pointColor : "rgba(151,187,205,1)",
            pointStrokeColor : "#fff",
        }
    ]
};
var lineChartOptions = {
    showScale: true,
    scaleShowGridLines: false,
    scaleGridLineColor: "rgba(0,0,0,.05)",
    scaleGridLineWidth: 1,
    scaleShowHorizontalLines: true,
    scaleShowVerticalLines: true,
    bezierCurve: true,
    bezierCurveTension: 0.3,
    pointDot: true,
    pointDotRadius: 4,
    pointDotStrokeWidth: 1,
    pointHitDetectionRadius: 20,
    datasetStroke: true,
    datasetStrokeWidth: 2,
    datasetFill: true,
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%=datasets[i].label%></li><%}%></ul>",
    maintainAspectRatio: true,
    responsive: true
};

$('#line1').height(0);
$('#line2').height(0);
$('#line3').height(0);
$('#line4').height(0);
$('#line' + 1).height(250);
var lineChartCanvas = $('#line' + 1).get(0).getContext("2d");
var lineChart = new Chart(lineChartCanvas);
lineChartOptions.datasetFill = false;
var assests = ["0", "1", "10", "2"];
lineChartData.datasets[0].data = eval(assests);
lineChart.Line(lineChartData, lineChartOptions);

// 初始化今天和昨天

// 修改地方文字
var date = new Date();
var today = date.getFullYear()+'年'+(date.getMonth()+1)+'月'+date.getDate()+'日';
var yesterday = date.getFullYear()+'年'+(date.getMonth()+1)+'月'+(date.getDate() - 1)+'日';
$("#today").text(today);
// 时间点击切换
$("#tend").find("td").on("click", function(){
    $(this).addClass('bg-aqua').siblings().removeClass('bg-aqua');
    var id = $(this).attr("data-id");
    if(id == '1'){
        $("#today").text(today);
    }else if(id == '2'){
        $("#today").text(yesterday);
    }else if(id == '3'){
        $("#today").text('最近7天');
        lineChartData.datasets[0].data = 0;
    }else if(id == '4'){
        $("#today").text('最近30天');
    }
    tend(id);
});



/**
 *  progress data line
 *
 *  @author: sel
 */
function progress(id){
    $("#line-progress").find('.pv').css('width', 1 + '%');
    $("#line-progress").find('.ip').css('width', 1 + '%');
    $("#line-progress").find('.staytime').css('width', 10 + '%');
    $("#line-progress").find('.numPv').text(20);
    $("#line-progress").find('.numIp').text(11);
    $("#line-progress").find('.numTime').text(Math.ceil(3000 / 60));
}
// 统计日期点击切换
$("#progress").find("td").on("click", function(){
    $(this).addClass('bg-aqua').siblings().removeClass('bg-aqua');
    var id = $(this).attr('data-id');
    progress(id);
});


/**
 *  data for china-map
 *
 *  @author: sel
 */
var myChart = echarts.init(document.getElementById('china-map'));
var geoCoordMap = {
   '北京':[116.41,39.9],
   '上海':[121.43,31.5],
   '天津':[117.2,39.1],
   '重庆':[106.45,29.56],
   '深圳':[114.06,22.61],
   '香港':[114.1,22.2],
   '澳门':[113.5,22.2],
   '西藏':[91.11,29.97],
   '福建':[119.3,26.08],
   '广西':[108.33,22.84],
   '广东':[113.23,23.16],
   '山西':[112.53,37.87],
   '云南':[102.73,25.04],
   '海南':[110.35,20.02],
   '辽宁':[123.38,41.8],
   '吉林':[125.35,43.88],
   '宁夏':[106.27,38.47],
   '江西':[115.89,28.68],
   '青海':[101.74,36.56],
   '内蒙古':[111.65,40.82],
   '四川':[104.06,30.67],
   '陕西':[108.95,34.27],
   '台湾':[121.420757,28.656386],
   '江苏':[118.78,32.04],
   '贵州':[106.71,26.57],
   '新疆':[87.68,43.77],
   '浙江':[120.19,30.26],
   '山东':[117,36.65],
   '甘肃':[103.73,36.03],
   '河南':[113.65,34.76],
   '黑龙江':[126.63,45.75],
   '河北':[114.48,38.03],
   '湖南':[113,28.21],
   '安徽':[117.27,31.86],
   '湖北':[114.31,30.52],
};
function convertData(data) {
	var res = [];
	for (var i = 0; i < data.length; i++) {
        var geoCoord = geoCoordMap[data[i].name];
        if (geoCoord) {
            res.push({
        		name: data[i].name,
        		value: geoCoord.concat(data[i].value)
            });
        }
	}
	return res;
}
var option = {
	title: {
		text: '访问地区统计图',
		left: 'center'
	},
    tooltip: {},
    visualMap: {
    	max : "",
        left: 'left',
        top: 'bottom',
        text: ['高','低'],
        seriesIndex: [1],
        inRange: {
            color: ['#e0ffff', '#006edd']
        },
        calculable : true
    },
    geo: {
        map: 'china',
        roam: true,
        label: {
            normal: {
                show: true,
                textStyle: {
                    color: 'rgba(0,0,0,0.4)'
                }
            }
        },
        itemStyle: {
            normal:{
                borderColor: 'rgba(0, 0, 0, 0.2)'
            },
            emphasis:{
                areaColor: null,
                shadowOffsetX: 0,
                shadowOffsetY: 0,
                shadowBlur: 20,
                borderWidth: 0,
                shadowColor: 'rgba(0, 0, 0, 0.5)'
            }
        }
    },
    series : [
        {
           type: 'scatter',
           coordinateSystem: 'geo',
           symbolSize: 10,
           symbol: 'circle',
           symbolRotate: 35,
           label: {
               normal: {
                   formatter: '{b}',
                   position: 'right',
                   show: false
               },
               emphasis: {
                   show: true
               }
           },
           itemStyle: {
               normal: {
                    color: '#DC291E'
               }
           }
        },
        {
            name: '全省',
            type: 'map',
            geoIndex: 0,
            data:[]
        }
    ]
};

/**
$.ajax({
    type : "get",
    url : "/access/area/5",
    async : false,
    success : function(e){
        option.series[0].data = convertData(eval(e));
    }
});
 */
var numArr = [];
var area = [
    {
        "name": "北京",
        "value": 6594
    },
    {
        "name": "广东",
        "value": 5110
    },
    {
        "name": "江西",
        "value": 4021
    },
    {
        "name": "上海",
        "value": 2829
    },
    {
        "name": "江苏",
        "value": 2329
    },
    {
        "name": "浙江",
        "value": 2220
    },
    {
        "name": "山东",
        "value": 2078
    },
    {
        "name": "河南",
        "value": 1670
    },
    {
        "name": "湖北",
        "value": 1669
    },
    {
        "name": "四川",
        "value": 1336
    },
    {
        "name": "河北",
        "value": 1226
    },
    {
        "name": "湖南",
        "value": 1081
    },
    {
        "name": "安徽",
        "value": 1076
    },
    {
        "name": "福建",
        "value": 915
    },
    {
        "name": "广西",
        "value": 777
    },
    {
        "name": "山西",
        "value": 754
    },
    {
        "name": "辽宁",
        "value": 745
    },
    {
        "name": "重庆",
        "value": 698
    },
    {
        "name": "天津",
        "value": 689
    },
    {
        "name": "陕西",
        "value": 668
    },
    {
        "name": "黑龙江",
        "value": 538
    },
    {
        "name": "云南",
        "value": 523
    },
    {
        "name": "吉林",
        "value": 489
    },
    {
        "name": "美国",
        "value": 470
    },
    {
        "name": "内蒙古",
        "value": 435
    },
    {
        "name": "贵州",
        "value": 373
    },
    {
        "name": "甘肃",
        "value": 317
    },
    {
        "name": "台湾",
        "value": 226
    },
    {
        "name": "新疆",
        "value": 194
    },
    {
        "name": "局域网",
        "value": 170
    },
    {
        "name": "中国",
        "value": 153
    },
    {
        "name": "日本",
        "value": 132
    },
    {
        "name": "/",
        "value": 115
    },
    {
        "name": "新加坡",
        "value": 99
    },
    {
        "name": "香港",
        "value": 95
    },
    {
        "name": "海南",
        "value": 84
    },
    {
        "name": "德国",
        "value": 69
    },
    {
        "name": "宁夏",
        "value": 64
    },
    {
        "name": "韩国",
        "value": 62
    },
    {
        "name": "西藏",
        "value": 60
    },
    {
        "name": "俄罗斯",
        "value": 57
    },
    {
        "name": "青海",
        "value": 48
    },
    {
        "name": "保留地址",
        "value": 47
    },
    {
        "name": "斯里兰卡",
        "value": 46
    },
    {
        "name": "哥伦比亚",
        "value": 32
    },
    {
        "name": "GOOGLE",
        "value": 29
    },
    {
        "name": "巴西",
        "value": 29
    },
    {
        "name": "英国",
        "value": 28
    },
    {
        "name": "共享地址",
        "value": 25
    },
    {
        "name": "土耳其",
        "value": 21
    },
    {
        "name": "法国",
        "value": 20
    },
    {
        "name": "印度",
        "value": 17
    },
    {
        "name": "菲律宾",
        "value": 17
    },
    {
        "name": "加拿大",
        "value": 17
    },
    {
        "name": "伊朗",
        "value": 16
    },
    {
        "name": "巴基斯坦",
        "value": 14
    },
    {
        "name": "乌克兰",
        "value": 14
    },
    {
        "name": "越南",
        "value": 13
    },
    {
        "name": "孟加拉",
        "value": 12
    },
    {
        "name": "荷兰",
        "value": 11
    },
    {
        "name": "西班牙",
        "value": 11
    },
    {
        "name": "阿根廷",
        "value": 9
    },
    {
        "name": "澳大利亚",
        "value": 9
    },
    {
        "name": "马来西亚",
        "value": 9
    },
    {
        "name": "印度尼西亚",
        "value": 7
    },
    {
        "name": "突尼斯",
        "value": 7
    },
    {
        "name": "南非",
        "value": 7
    },
    {
        "name": "泰国",
        "value": 6
    },
    {
        "name": "罗马尼亚",
        "value": 6
    },
    {
        "name": "厄瓜多尔",
        "value": 5
    },
    {
        "name": "洪都拉斯",
        "value": 5
    },
    {
        "name": "亚太地区",
        "value": 5
    },
    {
        "name": "秘鲁",
        "value": 5
    },
    {
        "name": "挪威",
        "value": 5
    },
    {
        "name": "阿尔及利亚",
        "value": 5
    },
    {
        "name": "斯洛伐克",
        "value": 4
    },
    {
        "name": "奥地利",
        "value": 4
    },
    {
        "name": "乌拉圭",
        "value": 4
    },
    {
        "name": "葡萄牙",
        "value": 4
    },
    {
        "name": "比利时",
        "value": 4
    },
    {
        "name": "瑞士",
        "value": 4
    },
    {
        "name": "意大利",
        "value": 4
    },
    {
        "name": "澳门",
        "value": 3
    },
    {
        "name": "乌干达",
        "value": 3
    },
    {
        "name": "巴拿马",
        "value": 3
    },
    {
        "name": "墨西哥",
        "value": 3
    },
    {
        "name": "智利",
        "value": 3
    },
    {
        "name": "摩洛哥",
        "value": 3
    },
    {
        "name": "瑞典",
        "value": 3
    },
    {
        "name": "新西兰",
        "value": 3
    },
    {
        "name": "尼日利亚",
        "value": 3
    },
    {
        "name": "克罗地亚",
        "value": 3
    },
    {
        "name": "沙特阿拉伯",
        "value": 3
    },
    {
        "name": "格鲁吉亚",
        "value": 2
    },
    {
        "name": "古巴",
        "value": 2
    },
    {
        "name": "埃及",
        "value": 2
    },
    {
        "name": "哈萨克斯坦",
        "value": 2
    },
    {
        "name": "安哥拉",
        "value": 2
    },
    {
        "name": "卡塔尔",
        "value": 2
    },
    {
        "name": "肯尼亚",
        "value": 2
    },
    {
        "name": "匈牙利",
        "value": 2
    },
    {
        "name": "爱沙尼亚",
        "value": 2
    },
    {
        "name": "也门",
        "value": 2
    },
    {
        "name": "加蓬",
        "value": 2
    },
    {
        "name": "老挝",
        "value": 2
    },
    {
        "name": "波兰",
        "value": 2
    },
    {
        "name": "塞尔维亚",
        "value": 1
    },
    {
        "name": "巴拉圭",
        "value": 1
    },
    {
        "name": "马达加斯加",
        "value": 1
    },
    {
        "name": "法属波利尼西亚",
        "value": 1
    },
    {
        "name": "阿鲁巴",
        "value": 1
    },
    {
        "name": "马尔代夫",
        "value": 1
    },
    {
        "name": "波斯尼亚和黑塞哥维那",
        "value": 1
    },
    {
        "name": "希腊",
        "value": 1
    },
    {
        "name": "毛里求斯",
        "value": 1
    },
    {
        "name": "多米尼加",
        "value": 1
    },
    {
        "name": "牙买加",
        "value": 1
    },
    {
        "name": "海地",
        "value": 1
    },
    {
        "name": "哥斯达黎加",
        "value": 1
    },
    {
        "name": "以色列",
        "value": 1
    },
    {
        "name": "立陶宛",
        "value": 1
    },
    {
        "name": "捷克",
        "value": 1
    },
    {
        "name": "爱尔兰",
        "value": 1
    },
    {
        "name": "尼泊尔",
        "value": 1
    },
    {
        "name": "纳米比亚",
        "value": 1
    },
    {
        "name": "黎巴嫩",
        "value": 1
    },
    {
        "name": "斐济",
        "value": 1
    },
    {
        "name": "约旦",
        "value": 1
    },
    {
        "name": "蒙古",
        "value": 1
    },
    {
        "name": "阿联酋",
        "value": 1
    },
    {
        "name": "塞内加尔",
        "value": 1
    },
    {
        "name": "尼加拉瓜",
        "value": 1
    },
    {
        "name": "叙利亚",
        "value": 1
    },
    {
        "name": "文莱",
        "value": 1
    },
    {
        "name": "莫桑比克",
        "value": 1
    },
    {
        "name": "多米尼克",
        "value": 1
    },
    {
        "name": "布基纳法索",
        "value": 1
    },
    {
        "name": "马里",
        "value": 1
    },
    {
        "name": "加纳",
        "value": 1
    },
    {
        "name": "玻利维亚",
        "value": 1
    },
    {
        "name": "苏丹",
        "value": 1
    },
    {
        "name": "危地马拉",
        "value": 1
    },
    {
        "name": "科威特",
        "value": 1
    },
    {
        "name": "萨尔瓦多",
        "value": 1
    }
];
option.series[1].data = eval(area);

var num = eval(area);
$(num).each(function(ind,el){
    numArr.push(el.value);
    return numArr;
});
var maxNumber = Math.max.apply(null, numArr);
console.log(option.series[1].data);
option.visualMap.max = maxNumber;

myChart.setOption(option, true);



/**
 *  browser pie
 *
 *  @author sel
 */
var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
var pieChart = new Chart(pieChartCanvas);
var pieOptions = {
    segmentShowStroke: true,
    segmentStrokeColor: "#fff",
    segmentStrokeWidth: 1,
    percentageInnerCutout: 50,
    animationSteps: 100,
    animationEasing: "easeOutBounce",
    animateRotate: true,
    animateScale: false,
    responsive: true,
    maintainAspectRatio: false,
    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
    tooltipTemplate: "<%=value %> <%=label%>",
};
var dataBroser = [{label: "IE浏览器", value: 1150, color: "#3c8dbc", highlight: "#3c8dbc"}, {label: "其他浏览器", value: 6770, color: "#d2d6de", highlight: "#d2d6de"}
,{label: "欧朋浏览器", value: 14, color: "#00c0ef", highlight: "#00c0ef"},{label: "火狐浏览器", value: 1173, color: "#00a65a", highlight: "#00a65a"}
,{label: "苹果浏览器", value: 6120, color: "#f39c12", highlight: "#f39c12"},{label: "谷歌浏览器", value: 28665, color: "#dd4b39", highlight: "#dd4b39"}]
pieChart.Doughnut(eval(dataBroser), pieOptions);
