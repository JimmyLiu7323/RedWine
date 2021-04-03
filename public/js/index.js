var chartArray_time = new Array();
var chartArray_temp = new Array();
var chartArray_wxstr = new Array();
var chartArray_wsicon = new Array();

function page_init() {
    //單頁獨立設定，loading結束後執行
    //執行順序3
    console.log("page_init");
    //設定banner
    if ($(".main-banner div").length > 1) {
        $('.slider').slick({
            arrows: false,
            dots: true,
            autoplay: true,
            autoplaySpeed: 5000,
        });
    }
    //變更圖表大小
    set_chartSize();
    
    //取得氣象資料
    get_data();
}

function page_resize() {
    //單頁獨立設定，resize時執行
    console.log("page_resize");
}
window.onbeforeprint = function(event) {
    for (var id in Chart.instances) {
        Chart.instances[id].resize();
    }
};
//---------------------------------
//變更圖表大小
function set_chartSize(){
    if (devicePc){
        //$("#locationWeather-chart").height((190 * $("#locationWeather-chart").width()) / 473);
        $("#locationWeather-chart").height(220);
    }else if($("#wrap").hasClass("landscape")){
        $("#locationWeather-chart").css("height","70vh");
    }else {
        $("#locationWeather-chart").css("height","250px");
    }
}
function get_data() {
    var wx, humd, wd, ws, rain, temp;
    var wxStr;
    //天氣現象、濕度、風向、風速、累積雨量、溫度
    //https://opendata.cwb.gov.tw/opendatadoc/DIV2/A0003-001.pdf
    $.ajax({
        dataType: "json",
        type: "get",
        url: "https://opendata.cwb.gov.tw/api/v1/rest/datastore/O-A0003-001?locationName=南區中心&Authorization=CWB-67791895-5415-48AA-927D-31C51AC21207",
        success: function(DATA) {
            //console.log(DATA.records.location[0]);
            wx = DATA.records.location[0].weatherElement[20].elementValue;
            humd = DATA.records.location[0].weatherElement[4].elementValue;
            wd = DATA.records.location[0].weatherElement[1].elementValue;
            ws = DATA.records.location[0].weatherElement[2].elementValue;
            rain = DATA.records.location[0].weatherElement[6].elementValue;
            temp = DATA.records.location[0].weatherElement[3].elementValue;
            //console.log(JSON.stringify(["天氣現象:" + wxStr + ">icon" + wx, "濕度:" + humd, "風向:" + wd, "風速:" + ws, "累積雨量:" + rain, "溫度:" + temp], null, 1));
            //資料修正
            humd = parseInt(humd * 100);
            wd = parseFloat(wd);
            console.log("風向度數："+wd);
            if (wd == 0) {
                wd = "無風";
            } else {
                switch (true) {
                    case (wd >= 348.75) || (wd < 11.25):
                        wd = "北風";
                        break;
                    case (wd >= 11.25) && (wd < 33.75):
                        wd = "北東北";
                        break;
                    case (wd >= 33.75) && (wd < 56.25):
                        wd = "東北";
                        break;
                    case (wd >= 56.25) && (wd < 78.75):
                        wd = "東東北";
                        break;
                    case (wd >= 78.75) && (wd < 101.25):
                        wd = "東";
                        break;
                    case (wd >= 101.25) && (wd < 123.75):
                        wd = "東東南";
                        break;
                    case (wd >= 123.75) && (wd < 146.25):
                        wd = "東南";
                        break;
                    case (wd >= 146.25) && (wd < 168.75):
                        wd = "南東南";
                        break;
                    case (wd >= 168.75) && (wd < 191.25):
                        wd = "南";
                        break;
                    case (wd >= 191.25) && (wd < 213.75):
                        wd = "南西南";
                        break;
                    case (wd >= 213.75) && (wd < 236.25):
                        wd = "西南";
                        break;
                    case (wd >= 236.25) && (wd < 258.75):
                        wd = "西西南";
                        break;
                    case (wd >= 258.75) && (wd < 281.25):
                        wd = "西";
                        break;
                    case (wd >= 281.25) && (wd < 303.75):
                        wd = "西西北";
                        break;
                    case (wd >= 303.75) && (wd < 326.25):
                        wd = "西北";
                        break;
                    case (wd >= 326.25) && (wd < 348.75):
                        wd = "北西北";
                        break;
                }
            }

            ws = Number(ws).toFixed(1);
            temp = Number(temp).toFixed(1);
            if (wx == -99 || wx == "null") {
                wx = 99;
                wxStr = "無觀測";
            }
            //console.log(JSON.stringify(["天氣現象:" + wxStr + ">icon" + wx, "濕度:" + humd, "風向:" + wd, "風速:" + ws, "累積雨量:" + rain, "溫度:" + temp], null, 1));

            //顯示資料
            $(".locationWeather-info-wxIcon img").attr("alt", wxStr);
            var nowType = new Date().format("h");
            if (nowType >= 6 && nowType < 18) {
                nowType = "day";
            } else {
                nowType = "night";
            }
            if (wx == 99) {
                $(".locationWeather-info-wxIcon img").hide();
            } else {
                $(".locationWeather-info-wxIcon img").attr("src", "images/weather_icons/" + nowType + "/" + wx + ".svg");
            }
            $(".locationWeather-info-temp").html(temp + "°C");
            $("#locationWeather-info-humd").html(humd + "％");
            $("#locationWeather-info-wd").html(wd);
            $("#locationWeather-info-ws").html(ws + "公尺/秒");
            $("#locationWeather-info-rain").html(rain + "毫米");
        },
        error: function() {
            console.log("無法取得資料");
        }
    });

    //https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-077?locationName=中西區
    // 天氣預報因子中文說明表：https://opendata.cwb.gov.tw/opendatadoc/MFC/A0012-001.pdf
    var nowTime = new Date().format("yyyy-MM-ddThh:mm:ss");
    $.ajax({
        dataType: "json",
        type: "get",
        url: "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-077?locationName=中西區&Authorization=CWB-67791895-5415-48AA-927D-31C51AC21207",
        data: {
            "timeFrom": nowTime,
            "elementName": "Wx,T"
        },
        success: function(DATA) {
            //console.log(DATA.records.locations[0].location[0]);
            for (var i = 0; i < 8; i++) {
                //Wx:weatherElement[0].time[i].startTime
                //Wx現象文字:weatherElement[0].time[i].elementValue[0].value
                //Wx現象編號:weatherElement[0].time[i].elementValue[1].value
                //T:weatherElement[1]
                _str = DATA.records.locations[0].location[0].weatherElement[0].time[i].startTime;
                _index = _str.indexOf(":");
                _str = _str.substring(_index - 2, _index);
                if(_str == "00"){
                    _time = (new Date().getDate()+1)+"日0時";
                }else{
                    _time = _str;
                }                
                _wxstr = DATA.records.locations[0].location[0].weatherElement[0].time[i].elementValue[0].value;
                _wsicon = DATA.records.locations[0].location[0].weatherElement[0].time[i].elementValue[1].value;
                var nowType = Number(_str);
                if (nowType >= 6 && nowType < 18) {
                    nowType = "day";
                } else {
                    nowType = "night";
                }
                if (_wsicon == -99 || wx == "-99" || wx == "null") {
                    _wsicon = "circle";
                } else {
                    var chartimg = new Image(30,30);
                    chartimg.src = "images/weather_icons/" + nowType + "/" + _wsicon + ".svg";
                    _wsicon = chartimg;
                    //$(".locationWeather-info-wxIcon img").attr("src", "images/weather_icons/" + nowType + "/" + wx + ".svg");
                }
                _t = DATA.records.locations[0].location[0].weatherElement[1].time[i].elementValue[0].value;

                chartArray_time.push(_time);
                chartArray_temp.push(_t);
                chartArray_wxstr.push(_wxstr);
                chartArray_wsicon.push(_wsicon);
            }
            set_chart();
        },
        error: function() {
            console.log("無法取得資料");
        }
    });
}

function set_chart() {
    //換天氣圖示
    Chart.pluginService.register({
        afterUpdate: function(chart) {
            for (var i = 0; i < 8; i++) {
                chart.config.data.datasets[0]._meta[0].data[i]._model.pointStyle = chartArray_wsicon[i];
            }        
        }
    });
    //if (devicePc)
    var nowDate = new Date().format("yyyy-MM-dd");
    Chart.defaults.global.defaultFontSize = 16;
    Chart.defaults.global.defaultFontFamily = "'Noto Sans TC','Verdana','Helvetica Neue', 'Helvetica', 'Arial', sans-serif";
    var chart = new Chart($("#locationWeather-chart"), {
        type: "line",
        data: {
            labels: chartArray_time,
            datasets: [{
                data: chartArray_temp,
                label:"溫度",
                borderWidth: 1,
                fill: false,
                backgroundColor: "#ffaad2",
                borderColor: "#ffaad2",
                borderWidth: 2,
                pointRadius: 6,                
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: nowDate
            },
            maintainAspectRatio: false,
            tooltips: {
                position: 'nearest',
                mode: 'index',
                intersect: false,
                callbacks: {
                    title: function(tooltipItem, data) {
                        var label = tooltipItem[0].xLabel.replace(/時/, "") + "時";
                        return label;
                    },
                    label: function(tooltipItem, data) {
                        //溫度
                        var label = data.datasets[tooltipItem.datasetIndex].label || '';
                        if (label) {
                            label += '：';
                        }
                        label += Math.round(tooltipItem.yLabel * 100) / 100;
                        label += '°C';
                        return label;
                    },
                    footer: function(tooltipItems, data) {
                        var _str = "天氣：";
                        tooltipItems.forEach(function(tooltipItem) {
                            _str += chartArray_wxstr[tooltipItem.index];
                        });                        
                        return _str;
                    },
                },
                footerFontStyle: 'normal'
            },
            layout: {
                padding: {
                    left: 0,
                    right: 20,
                    top: 0,
                    bottom: 0
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        padding: 10
                    }
                }],
                xAxes: [{
                    ticks: {
                        padding: 10
                    }
                }]
            }
        }
    });
}
