function IsPC() {
    var userAgentInfo = navigator.userAgent;
    var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");
    var flag = true;
    for (var v = 0; v < Agents.length; v++) {
        if (userAgentInfo.indexOf(Agents[v]) >= 0) { flag = false; break; }
    }
    return flag;
}
const devicePc = IsPC();

function onOrientationchange() {
    if (window.orientation === 180 || window.orientation === 0) {
        //直式.portrait
        $("#wrap").addClass("portrait").removeClass("landscape");
    }
    if (window.orientation === 90 || window.orientation === -90) {
        //橫式.landscape
        $("#wrap").addClass("landscape").removeClass("portrait");
    }
}

const $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');

//=======================================================
$(function() {
    //執行順序1
    console.log("ready");
    if (!devicePc){
        /* 橫向或直向 */
        onOrientationchange();
        window.addEventListener("orientationchange", onOrientationchange, false);
    }

    //header
    $("#languageBtn").click(function(){
        if($(this).html() == "EN") {
            $(this).html("中文");
            $(this).attr("title","切換至中文版網站");
        }else {
            $(this).html("EN");
            $(this).attr("title","切換至英文版網站");
        }
    });
    $("#socialBtn").click(function(){
        $(".menuBox-socialBox").toggle();
        //關閉搜尋盒
        $(".header-searchBox").removeClass("act");
    });
    $(".menuBox-hamburgerBtn").click(function(){
        $(".headerBox").toggleClass('act');
        $(this).toggleClass('act');
        $(".header-mobileMenuBox").toggle();
    });
    $(".mobileMenuBox-nav .typeList").click(function(){
        $(this).toggleClass("act");
        $(this).next().toggleClass("act");
    });
    $(".header-navBox-nav .typeList").hover(function(){
        $(this).addClass("act");
        $(this).find('ul').addClass("act");
    },function(){
        $(this).removeClass("act");
        $(this).find('ul').removeClass("act");
    });
    $(".header-navBox-nav .typeList a").focus(function(){
        if($(this).parent().hasClass("typeList")) {
            $(this).parent().addClass("act");
            $(this).parent().find('ul').addClass("act");
        }        
    });
    $(".header-navBox-nav .typeList ul a").focusout(function(){
        _total = $(this).parent().parent().find("li").length;
        if(($(this).parent().index()+1) == _total){
            $(this).parent().parent().parent().removeClass("act");
            $(this).parent().parent().removeClass("act");
        }
    });
    $("#searchBtn").click(function(){
        $(".header-searchBox").toggleClass("act");
        $("#header-searchBox-search").focus();
        //關閉社群盒
        $(".menuBox-socialBox").css("display","none");
    });
    //pc-focus離開line鈕時
    $(".socialBox-lineBtn").focusout(function(){
        //關閉社群盒
        $(".menuBox-socialBox").css("display","none");
    });
    $(window).scroll(function () {
         _obj = $(".headerBox");
         if($(this).scrollTop() > 0){
            _obj.addClass('roll');         
         }else{
            _obj.removeClass('roll');   
         }
    });
});
window.onload = function() {
    // 圖片下載後執行這裡，且$(document).ready() 必須在 window.onload 之前使用
    //執行順序2
    console.log("onload");
    page_init();
    //onresize
    window.onresize = function() {
        console.log("onresize");
        //程式↓
        if($(".menuBox-hamburgerBtn").hasClass("act")) {
            $(".menuBox-hamburgerBtn").click();
        }
        //程式↑
        page_resize();
    };
};
Date.prototype.format = function(fmt) { 
     var o = { 
        "M+" : this.getMonth()+1,                 //月份 
        "d+" : this.getDate(),                    //日 
        "h+" : this.getHours(),                   //小时 
        "m+" : this.getMinutes(),                 //分 
        "s+" : this.getSeconds(),                 //秒 
        "q+" : Math.floor((this.getMonth()+3)/3), //季度 
        "S"  : this.getMilliseconds()             //毫秒 
    }; 
    if(/(y+)/.test(fmt)) {
            fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length)); 
    }
     for(var k in o) {
        if(new RegExp("("+ k +")").test(fmt)){
             fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
         }
     }
    return fmt; 
}