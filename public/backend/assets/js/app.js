var history_url = new Array();
var cururl = '';
var login_info = {};
var is_legacy = false;
var todo_func = false;
function detectMobile(){
    var check = false;
      (function(a,b){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))check = true})(navigator.userAgent||navigator.vendor||window.opera);
      return check;
}
function number_format(n) {
    n += "";
    var arr = n.split(".");
    var re = /(\d{1,3})(?=(\d{3})+$)/g;
    return arr[0].replace(re,"$1,") + (arr.length == 2 ? "."+arr[1] : "");
}

function proc_error(errors){
    $('.has-error .control-label').remove();
    $('.has-error').removeClass('has-error');
    if(errors){
        $.each(errors, function( col, errmsg ) {
          var label = '<label class="control-label" for="'+col+'">'+errmsg +'</label>';
            if($("[for='"+col+"']").length == 0){
            $('#' + col).parent().append(label);
            $('#' + col).addClass('has-error');
            }
          //console.log("col:"+col+" emsg:"+errmsg);
        });
    }
}
function load_page(url){
    location.href=url;
}
function reload_page(){
    location.reload(true);
}
function prev_page(){
    window.history.back();
}
function register_form(frm, func_callback, func_before){
    $(frm).ajaxForm({
        beforeSubmit: function(){
                    if(func_before){
                        var s = func_before();
                        if(s === false) return false;
                    }
                },
        success: function(ret){
            proc_ret(ret, func_callback, null, frm);
        },
        error: function(ret){
            if(ret.status == 422){
                var errors = ret.responseJSON;
                var err = {status:'err', errors:errors };
                proc_ret(JSON.stringify(err), func_callback, null, frm);
            }else{
                msgbox_show('系統錯誤，可能是連線中斷，請稍候再試！');
            }
        }
    });
}
function submit_form(frm, func_callback, func_before){
        $(frm).ajaxSubmit({
        beforeSubmit: function(){
                    if(func_before){
                        func_before();
                    }
                },
        success: function(ret){
            proc_ret(ret, func_callback);
        }
    });
}
function edit_form_submit(form_id){
    for ( instance in CKEDITOR.instances ) CKEDITOR.instances[instance].updateElement();
    $(form_id).submit();
}
function get_data(url, send_data, proc_handler, err_handler){
    send_data['_token'] = $('#csrf_token').val();
    $.ajax({
        url:url,
        type:'post',
        data:send_data,
        success:function(ret){
            proc_ret(ret, proc_handler, err_handler);
        },
        error:function(jq, err_status, err_string){
            console.log('get_data err_string: '+err_string);
        }
    });
}
function proc_ret(res, proc_handler, err_handler, frm){
    try{
        $('.has-error').removeClass('has-error');
        $('.error-icon').remove();
        if(res.status == 'ok'){
            if(res.msg){
                var title = res.title ? res.title : '';
                var after_proc = proc_handler ? function(){proc_handler(res)} : null;
				var alterDismissed = false;
                if(res.redirect){
                    if(!proc_handler) proc_handler = function(){};
                    if(res.redirect == 'backpage'){
                        after_proc = function(){ proc_handler(res); prev_page(); };
                    }else if(res.redirect == 'reload'){
                        after_proc = function(){ proc_handler(res); reload_page(); };
                    }else{
                        after_proc = function(){ proc_handler(res); load_page(res.redirect); };
                    }
                }
                msgbox_show(res.msg, title, alterDismissed, after_proc);
                return;
            }
            if(proc_handler){
                try{
                    proc_handler(res);
                    return;
                }catch(e){
                    alert('要接續呼叫的指令碼發生錯誤！(PROC_HANDLER ERROR)');
                    console.log(e);
                }
            }
            if(res.redirect){
                if(res.redirect == 'backpage'){
                    prev_page();
                }else{
                    load_page(res.redirect);
                }
            }else if(res.reload){
                reload_page();
            }
        }else if(res.status == 'err'){
			if(res.errors){
                var errori = 0;
                $.each(res.errors, function(k, v){
                    var errtext = '';
                    if(typeof(v) == 'object'){
                         $.each(v, function(x,y){
                            errtext += y + "\n";
                         });
                    }else if(typeof(v) == 'string'){
                        errtext = v;
                    }
                    var err_icon=$('<div class="error-icon"><i class="fa fa-exclamation-circle"></i>'+errtext+'</div>');
                    err_icon.attr('title', errtext);

                    if(res.target_modal){
                        $('#'+res.target_modal+'-'+k).parent().addClass('has-error').append(err_icon);
                    }else if(frm || res.frm){
                        if(res.frm ) frm = res.frm;
                        $(frm).find('input[name="' + k + '"]').parent().addClass('has-error').append(err_icon);
                        $(frm).find('#'+k).parent().addClass('has-error').append(err_icon);
                    }else{
                        $('input[name="' + k + '"]').parent().addClass('has-error').append(err_icon);
                        $('#'+k).parent().addClass('has-error').append(err_icon);
                    }
                });

            }
            if(res.msg) {
                msgbox_show(res.msg);
            }
            if(res.chkimg){
                $('#chkimg').attr('src', res.chkimg);
            }
            if(typeof(err_handler) === 'function') err_handler(res);

        }else{
            msgbox_show('沒有回傳值status，請聯絡程式人員處理！');
            console.log(res);
        }
    }catch(e){
        console.log('e='+e);
        msgbox_show('系統錯誤，可能是連線中斷，請稍候再試！');
        console.log(res);
    }
}
function msgbox_show( msg, title, after_callback){
    $('#msgbox_title').html(title);
    $('#msgbox_content').html(msg);
    $('#modalMsg').modal('show');
    $('#modalMsg').on('hidden.bs.modal', function () {
	    after_callback();
	});
}
function msgbox_hide(){
    $('#msgbox_title').html('');
    $('#msgbox_content').html('');
    $('#modalMsg').modal('hide');
}
function msieversion() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");
    var ievs = false;
    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {     // If Internet Explorer, return version number
        ievs = (parseInt(ua.substring(msie + 5, ua.indexOf(".", msie))));
    }
    return ievs;
}
function del_item(delobj, delsn, delid){
	var _token = $('#csrf_token').val();
    if(!confirm("確定要刪除嗎？")){
        return false;
    }else{
        get_data('/do/del_obj',{delobj:delobj, delsn:delsn, '_token':_token},function(res){
            if(delid){
                $('#' + delid).remove();
            }else if($("#item_row" + delsn)){
                $("#item_row" + delsn).remove();
            }
        });
    }
}
function preview_image(source, target, tohide) {
    var $s = $(source)[0].files;
    if ($s && $s[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(target).attr('src', e.target.result).show();
            //$(tohide).hide();
        }
        reader.readAsDataURL($s[0]);
    }
}
function sel_lang(lang){
    var _token = $('#csrf_token').val();
    if(!lang) lang = $('#sel-lang').val();
    get_data('/do/set_lang', {lang:lang, _token:_token }, function(){
        reload_page();
    });
}
function nl2br (str, is_xhtml) {   
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

function ck_null(val){
    if(val == 'null' || val == null || val.length <= 0){
        val = '-';
    }else if(val == 'undefined' || val == undefined || typeof(val) == 'undefined' ){
        val = '-';  
    }else if(val == ''){
        val = '-';          
    }
    return val;
}