 /*************************************************************************
 * --------------
 * 愛上主播台
 * --------------
 * 
 *  [2016] - [2017] Central Weather Bureau 
 *  All Rights Reserved.
 * 
 * NOTICE:  All information contained herein is, and remains
 * the property of Central Weather Bureau and its suppliers,
 * if any.  
 *
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 *
 * from Central Weather Bureau
 *
 *************************************************************************/
let systemURL=window.location.href.replace(/https?:\/\//i,"");
let videoSendURL="/i_reporter/newVideo"

/*=========================Global variable here=====================*/
var g_ScriptFontSize=25; //font size for text in Script div in index.html
var g_UserName; //fetch name by g_UserName.value
var g_SlideStep=0;
var g_Repeat; // slideshow repeat
var g_mediaRecorder;
var g_recordedChunks; //media transmission
var g_TotalRecordingTime=30;//default recording time is 30 seconds
let g_recordedSecs=0;
var g_fileName;
var g_currentDate=new Date();
//==================media source====================
var g_mediaSource=new MediaSource();
g_mediaSource.addEventListener('sourceopen',handleSourceOpen,false);
//==================media source====================

var g_sourceBuffer; // Video source buffer
var g_bFontResizing=false;
var g_bWebcamOn=false;
var g_nPageState=0; //0 in intro page, 1 in pre-recording page, 2 in recording page,3 in finish page

//==============timer variable===============
var g_bCountDownTimer=false;
var g_bElapseTimer=false;
var g_timerCountDownTimer; //Countdown Timer
var g_timerElapseTimer; //Total recording time left
var g_reloadTimer; //page reload timer
//==========================================

var accessToken; // facebook access token, in index.html
var CallJQuery; //FB upload : js calls jquery
var g_EffectButton=0;//Temporary use
var g_bFbPost=0; //Whether the user upload video to facebook
var g_fbResponseId;
//=====================
var g_UploadFB=1; //File name modification: Upload to fb or not
//=====================
var RemoteController;
/*=========================Global variable ===========================*/


/*==============================funcitions==================================*/

// main page loaded
function loadPage(){
    //slideshow
    g_Repeat=setInterval(function(){startSlideshow();},10000);
    let name=document.getElementById("name");
    name.value="";
    document.onmousedown=ReCalculate;
    document.onmousemove=ReCalculate;
    ReCalculate();
}

function Timeout(){
    // Reload page if being idle for 5 minutes
    location.reload();
}

function ReCalculate() // Reload time recalculated
{
    clearTimeout(g_reloadTimer);
    g_reloadTimer = setTimeout('Timeout()', 5 * 60 * 1000); // 5 minutes
}

function loadWeatherHelperTxt() // read txt file and show content in Script area
{
    const xhttp=new XMLHttpRequest();
    xhttp.onreadystatechange=function(){
        if(this.readyState==4&&this.status==200){
            document.getElementById("Script").innerHTML ="<p style='text-align:center;margin:10px 0;color:red;font-weight:bold'>低於5秒的錄影不會存檔，請留意</p>"+
            this.responseText;      
            g_ScriptFontSize=42;
            document.getElementById("Script").style.fontSize=g_ScriptFontSize+'px';
            document.getElementById("Script").style.fontWeight='bolder';
            document.getElementById("Script").style.fontFamily="KaiTi";
            document.getElementById("Script").style.lineHeight=2;
        } 
  };
  xhttp.open("GET","/i_reporter/loadWeatherTxt",true);
  xhttp.send();
}

function validateTextbox() //checking user's name
{
    g_UserName=document.getElementById("name");
    if(g_UserName.value=="")
    {
        alert("請輸入暱稱");
        g_UserName.focus();
        g_UserName.style.border="solid 3px red";
        return false;
    }
    else{
        g_recordedSecs=0;
        g_nPageState=1;
        g_bFontResizing=true;
        clearInterval(g_Repeat);
        loadWeatherHelperTxt();
        //remove slideshow first
        const elementSlide=document.getElementById("slide");
        const parent=elementSlide.parentNode;
        parent.removeChild(elementSlide);
        loadVideoRecorder();
    }
}
function setRecordingTime()
{ 
    let select=document.getElementById("select");
    g_TotalRecordingTime=select.value;//選擇的值放入a 
} 
function uploadToFB()
{
    var UploadOrNot = document.getElementById("UploadFB");
    g_UploadFB=UploadOrNot.value;
}

function loadVideoRecorder() // initializing video recorder
{
    //add video element
    let screen=document.getElementById("Screen");
    var elementCanvas = document.createElement("canvas");
    screen.appendChild(elementCanvas); 
    
    var elementVideo = document.createElement("video");
    screen.appendChild(elementVideo);  
    
    var canvasAttributeId = document.createAttribute("id");
    canvasAttributeId.value = "recordCanvas";  
    elementCanvas.setAttributeNode(canvasAttributeId);
        
    var videoAttributeId = document.createAttribute("id");
    videoAttributeId.value = "recorder";  
    elementVideo.setAttributeNode(videoAttributeId);
    
    var videoAttributePlay = document.createAttribute("autoplay");
    videoAttributePlay.value = true;  
    elementVideo.setAttributeNode(videoAttributePlay);
    
    //load in video recorder 
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia;
    if(navigator.getUserMedia)
    {
        navigator.getUserMedia({
            audio:true,
            video:{
                width:{ ideal: 1280, max: 1280 },
                height: { ideal: 720, max: 720 }
            }
        },
        handleVideo,videoError);
    }
    
}
 
function handleVideo(stream) 
{
    window.stream=stream;
    var video=document.querySelector("#recorder");
    video.addEventListener('loadedmetadata',function(){
        initCanvas(video);
    });
    video.srcObject = stream;
    // we need to play the video to trigger the loadedmetadata event
    video.play();
    
}

function initCanvas(video)
{
  var width = video.videoWidth;
  var height = video.videoHeight;

  // NOTE: In order to make the example simpler, we have opted to use a 2D
  // context. In a real application, you should use WebGL to render the
  // video and shaders to make filters, since it will be much faster.
  // You can see an example of this in Boo! https://github.com/mozdevs/boo
  var canvas = document.getElementById('recordCanvas');
  canvas.width = width;
  canvas.height = height;

  // use requestAnimationFrame to render the video as often as possible
    var ctx = canvas.getContext('2d');
    var draw = function () {
        var image = document.createElement('img');
        image.src="../../images/i_reporter/3-ol.png";
        // create a renderAnimationFrame loop
        requestAnimationFrame(draw);
        // draw the video data into the canvas
        ctx.drawImage(video, 0, 0, width, height);
        ctx.drawImage(image, 0, 580);

        if(g_EffectButton==1)
        RemoveBkground(ctx, width, height);
        else if(g_EffectButton==2)
        LightScene(ctx, width, height);

        var txt=g_UserName.value;
        //drawTextBG(ctx, txt, '26px arial', 53, 915);
        ctx.font='bold 23px arial';
        ctx.fillStyle='rgb(100,45,45)';
        ctx.fillText(txt,400,705);
    };
    draw();
    initRecorderWithCanvas(canvas);
}
function RemoveBkground(ctx, width, height) // special effect
{  
    const frame=ctx.getImageData(0, 0, width, height);
    let l=frame.data.length/4;

    for (var i = 0; i < l; i++) {
        let r = frame.data[i * 4 + 0];
        let g = frame.data[i * 4 + 1];
        let b = frame.data[i * 4 + 2];
        if (g > 150 && r > 150 && b > 150)
        frame.data[i * 4 + 3] = 0;
        
    }
      ctx.putImageData(frame, 0, 0);
}
function LightScene(ctx, width, height) // special effect
{
     var frame = ctx.getImageData(0, 0, width, height);
    var l = frame.data.length / 4;
    
    for (var i = 0; i < l; i++) {
        frame.data[i * 4 + 0]=frame.data[i * 4 + 0]+50;
        frame.data[i * 4 + 1]=frame.data[i * 4 + 1]+50;
        frame.data[i * 4 + 2]=frame.data[i * 4 + 2]+50;
    }
     ctx.putImageData(frame, 0, 0);
}

function drawTextBG(ctx, txt, font, x, y) //draw text background 
{ 
    
    ctx.save();
    ctx.font = font;
    ctx.textBaseline = 'top';
    ctx.fillStyle = 'rgb(35,35,80)';
    
    var width = ctx.measureText(txt).width;
    width+=40;
    ctx.fillRect(x, y, width, 46);
    
    ctx.fillStyle = 'rgb(245,245,245)';
    ctx.fillText(txt, x+20, y+10);
    
    ctx.restore();
}

function initRecorderWithCanvas(canvas)
{
    var options={mimeType:'video/webm',bitsPerSecond:4096000}; //bits per second
    g_recordedChunks=[];
    var canvas=document.getElementById('recordCanvas');
   
    var audioStream = new MediaStream(window.stream);
    var audioTrack = audioStream.getAudioTracks()[0];
    var videoStream = new MediaStream(canvas.captureStream(30)); 
    var videoTrack = videoStream.getVideoTracks()[0];
    
    var stream = new MediaStream([audioTrack,videoTrack]);
    try {
        g_mediaRecorder = new MediaRecorder(stream, options);
    } catch (e0) {
        console.log('Unable to create MediaRecorder with options Object: ', e0);
        try {
            options = {mimeType: 'video/webm,codecs=vp9', bitsPerSecond: 100000};
            g_mediaRecorder = new MediaRecorder(window.stream, options);
        } catch (e1) {
            console.log('Unable to create MediaRecorder with options Object: ', e1);
            try {
                options = 'video/vp8'; // Chrome 47
                g_mediaRecorder = new MediaRecorder(window.stream, options);
            }
            catch (e2) {
                alert('mimetype not supported');
                console.error('Exception while creating MediaRecorder:', e2);
                return;
            }
        }
    }
    
    

    g_mediaRecorder.onstop = handleStop;
    g_mediaRecorder.ondataavailable = handleDataAvailable;
}

function videoError(e) 
{
    // something error when loading recorder device
    alert("recording is not supported");
}


function fontSizeInScriptUp(add) //input parameter is valid
{
    if(g_bFontResizing==true && g_ScriptFontSize<=53)
    {
       g_ScriptFontSize+=add;
       document.getElementById("Script").style.fontSize=g_ScriptFontSize+'px';
    }
    
}

function fontSizeInScriptDown(minus) //input parameter is valid
{
    if(g_bFontResizing==true)
    {
    g_ScriptFontSize-=minus;
    document.getElementById("Script").style.fontSize=g_ScriptFontSize+'px';
    }
   
    
}

function startSlideshow()
{
    const images=["../../images/i_reporter/temp.jpg","../../images/i_reporter/temp3.jpg"];
    if(g_SlideStep<1){
        g_SlideStep++;
        document.getElementById("slide").src=images[g_SlideStep];
    }
    else{
        g_SlideStep=0;
        document.getElementById("slide").src=images[g_SlideStep];
    }
}

/*==============================functions: Manual buttons clicked==================================*/
function youtubeButtonClicked()
{
    var a = document.createElement('a');
        a.style.display = 'none';
        a.href = './youtube.html';
        a.target="_blank";
        document.body.appendChild(a);
        a.click();
    
    /*if(g_nPageState==3)
    {
        alert('上傳至Youtube');
    }
    else
    {
        alert('請先錄完影後，再來分享你的影片喔～');
    }*/
}

function prevButtonClicked()
{
    if(g_bCountDownTimer==true)
    {
        //code to be expand
    }
    
    else
    {
        if(g_nPageState==0)
        {
            alert("請先在下方輸入姓名,並按下GO鈕～");       
        }
        else if(g_nPageState==1)
        {
            g_nPageState=0;
            location.reload();  //Back to intro page
        }
        else if(g_nPageState==2)
        {       
            g_nPageState=0;
            location.reload();  //Back to intro page
        }
        else if(g_nPageState==3)
        {
            location.reload();  //Back to intro page
            // alert("錄影已結束");
        }
    }
}

function preparationCountDown(callback,val)
{
    var val = 10; //Countdown value
    g_bCountDownTimer=true;
    g_timerCountDownTimer = setInterval(function(){
        callback(val);
        if(val--<=0){
            clearInterval(g_timerCountDownTimer);
            g_bCountDownTimer=false;
            var screen =  document.getElementById("Screen");
            var countdown =  document.getElementById("preparationCountDownId");
            screen.removeChild(countdown);
            screen.style.backgroundColor="rgb(180,40,40)";//Tell user it start recording
            startRecording();//start recording
        }
    },1000);
}
function startRecording() //start recording
{
    //recording time elapse
    const screen=document.getElementById("Screen");
    const elementDiv=document.createElement("div");
    const DivAttributeId=document.createAttribute("id");
    DivAttributeId.value="ElapseTimeId";
    elementDiv.setAttributeNode(DivAttributeId);
    screen.appendChild(elementDiv);

    new ElapseTime(function(val){
        var timerMsg = (val >= 10 ? val : val);
        elementDiv.textContent=timerMsg;
    });
    g_mediaRecorder.start(10); // collect 10ms of data
    //alert(g_mediaRecorder.state);
}
                 
function ElapseTime(callback,val)
{
    g_bElapseTimer=true;
    var val=g_TotalRecordingTime-1; //Countdown value
    g_timerElapseTimer=setInterval(function(){
        console.log('still counting');
        callback(val);
        val--;
        g_recordedSecs++;
        if(val<=9){
            const screen=document.getElementById("Screen");
            const countdown=document.getElementById("ElapseTimeId");

            if(countdown){
                screen.removeChild(countdown);
            }
            const elementDiv=document.createElement("div");
            const DivAttributeId=document.createAttribute("id");
            DivAttributeId.value="preparationCountDownId";  
            elementDiv.setAttributeNode(DivAttributeId);
            screen.appendChild(elementDiv);
            new finalCountDown(function(val){
                var timerMsg = (val >= 10 ? val : val);
                elementDiv.textContent=timerMsg;
            });
            clearInterval(g_timerElapseTimer);
         } 
    },1000);
}
function finalCountDown(callback,val)
{
    val=9; //Countdown value
    g_timerCountDownTimer=setInterval(function(){
        callback(val);
        if(val--<=0){
            var screen=document.getElementById("Screen");
            var countdown=document.getElementById("preparationCountDownId");
            if(countdown){
                screen.removeChild(countdown);
            }
            screen.style.backgroundColor="rgb(100,100,180)";//Tell user it stops recording
            finishButtonClicked();//stop recording                    
            clearInterval(g_timerElapseTimer);
            clearInterval(g_timerCountDownTimer);
        }
    },1000);
}
function startButtonClicked()
{
    if(g_nPageState==1)
    {
        var month = g_currentDate.getMonth();
        var day = g_currentDate.getDate();
        var hour = g_currentDate.getHours();
        var minute = g_currentDate.getMinutes();
    
        var strMonth;
        var strDay;
        var strHour;
        month++;
    
        if(month<10)
            strMonth='0'+month;
        else
            strMonth=month;
    
        if(day<10)
            strDay='0'+day;
        else
            strDay=day;
    
    
        if(minute>=55)
        {
            minute=0;
            hour++;
        }
        if(hour<10)
            strHour='0'+hour;
        else
            strHour=hour;
        g_fileName=g_currentDate.getFullYear()+'_'+strMonth+'_'+strDay+'_'+strHour+"_"+minute+ "_"+g_currentDate.getSeconds();
        //alert(g_fileName);
        
        g_nPageState=2;
        var screen =  document.getElementById("Screen");
   
        var elementDiv =  document.createElement("div");
        var DivAttributeId = document.createAttribute("id");
        DivAttributeId.value = "preparationCountDownId";  
        elementDiv.setAttributeNode(DivAttributeId);
        screen.appendChild(elementDiv);
    
        new preparationCountDown(function(val){
            var timerMsg = (val >= 10 ? val : val);
            elementDiv.textContent=timerMsg;
        });
    }
    else if(g_nPageState==0)
    {
        alert('請先在下方輸入姓名,並按下GO鈕～');
    }
    else if(g_nPageState==2)
    {
        alert('已開始錄影！');
    }
    else
    {
        alert("錄影已結束！");
    }
}

function handleStop(event) 
{
    console.log('Recorder stopped: ', event);
}

function handleDataAvailable(event) 
{
    if (event.data && event.data.size > 0){
        g_recordedChunks.push(event.data);
    }
}
    
function finishButtonClicked()
{
    if(g_nPageState==2&&g_bCountDownTimer==false)
    {
        clearInterval(g_timerElapseTimer);
        g_nPageState=3;
        g_bFontResizing=false;
        g_mediaRecorder.stop();

        var elementVideo=document.getElementById("recorder");
        var parent=elementVideo.parentNode;
        parent.removeChild(elementVideo);

        g_ScriptFontSize=25;   
        document.getElementById("Script").style.fontSize=g_ScriptFontSize+'px';
        parent.style.fontWeight="bold";
        
        parent.style.backgroundColor="rgb(100,100,180)";

        if(g_recordedSecs>5){
            parent.innerHTML="<b>請依下方指示進行操作</b>";
            
            
            var blob=new Blob(g_recordedChunks,{type:'video/webm'});
            let fd=new FormData();
            let endRecord=new Date();
            let endRecord_Y=endRecord.getFullYear();
            let endRecord_m=("0"+(endRecord.getMonth()+1)).substr(-2);
            let endRecord_d=("0"+endRecord.getDate()).substr(-2);
            let endRecord_H=("0"+endRecord.getHours()).substr(-2);
            let endRecord_i=("0"+endRecord.getMinutes()).substr(-2);
            let endRecord_s=("0"+endRecord.getSeconds()).substr(-2);
            let recorderName=g_UserName.value;
            // recorderName=recorderName.substr(0,recorderName.length-1)+"X";
            let videoName=endRecord_Y+endRecord_m+endRecord_d+endRecord_H+endRecord_i+endRecord_s+recorderName;
            fd.append("_token",$('meta[name="csrf-token"]').attr('content'));
            fd.append('videoName',videoName);
            fd.append('data',blob);
            if(g_UploadFB==1){
                fd.append('newArticle',1);
            }
            else{
                fd.append('newArticle',0);
            }            
            $.ajax({
                type:'POST',
                url:videoSendURL,
                data:fd,
                processData:false,
                contentType:false
            }).done(function(ajxRes){
                if(ajxRes=='success'){
                    window.location="/i_reporter/qrcode";
                }
                else{
                    g_recordedSecs=0;
                    alert("很抱歉，上傳影片失敗。");
                }
            }).error(function(err){
                g_recordedSecs=0;
                alert("Oops...不知名錯誤。");
            });
        }
        else{
            const screen=document.getElementById("Screen");
            const countdown=document.getElementById("ElapseTimeId");
            if(countdown){
                screen.removeChild(countdown);
            }
            const recordCanvas=document.getElementById("recordCanvas");
            if(recordCanvas){
                screen.removeChild(recordCanvas);
            }

            g_recordedSecs=0;
            g_nPageState=1;
            const videoRecorder=document.getElementById('recorder');
            if(!videoRecorder)
                loadVideoRecorder();
        }
    }
    else if(g_nPageState==0)
    {
        alert('請先在下方輸入姓名,並按下GO鈕～');   
    }
    else if(g_nPageState==1)
    {
        alert('請按下錄影鈕～');
    }
    else if(g_nPageState==3)
    {
        alert("錄影已結束！");
    }
    else if(g_bCountDownTimer==true&&g_nPageState==2)
    {
        //alert("錄影尚未開始！");
    }
}

function handleSourceOpen(event) 
{
    g_sourceBuffer=g_mediaSource.addSourceBuffer('video/webm');
}

function specialEffect() //special effect for recording
{   
    if(g_nPageState==1)
    {
        if(g_EffectButton==0)
            g_EffectButton=1;
        else if(g_EffectButton==1)
            g_EffectButton=2;
        else 
            g_EffectButton=0;
    }
    else if(g_nPageState==2)
    {
        if(g_EffectButton==0)
            g_EffectButton=1;
        else if(g_EffectButton==1)
            g_EffectButton=2;
        else 
            g_EffectButton=0;
    }
    else
        alert("目前無法使用本功能！");
}