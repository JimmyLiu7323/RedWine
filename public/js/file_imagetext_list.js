//�膄鈭�蝚砌�甈�嚗諹��枂�𣶹撟暹�
var _tableShowNum = 2;
//蝘餃�閙活�彍
var _tableMove = 0;
//�虾蝘餃�閙活�彍
var _tableCanMove = 0;
//蝮賢�勗嗾甈�嚗���鉄蝚砌�甈�
var _tableThNum = 0;
function page_init() {
    //�鱓���崕蝡贝身摰𡄯�𨧣oading蝯鞉�笔��嘑銵�
    //�嘑銵屸�摨�3
    console.log("page_init");
    //銵冽聢
    _obj = $(".main-file-tableList");
    _tableThNum = $("th",_obj).length;
    _tableCanMove = Math.ceil((_tableThNum - 1)/_tableShowNum) - 1;
    $("tr",_obj).each(function(index){
    	_str = "td";
    	if(index == 0){
    		_str = "th";
    	}
    	$(_str,this).each(function(index){
    		_targetBox = $(this).parent().parent();
    		$(this).addClass("col"+index);
    		if(index > _tableShowNum){
				_targetBox.find(".col"+index).addClass('hide');
			}
    	});
	});
	$(".main-file-tablecontrolBox .left").click(function(){
		_tableMove--;
		checkTable(_tableMove);
		if(_tableMove <= 0){
    		$(this).hide();
    	}
    	$(".main-file-tablecontrolBox .right").show();
    });
    $(".main-file-tablecontrolBox .right").click(function(){
    	_tableMove++;
    	checkTable(_tableMove);
    	if(_tableMove >= _tableCanMove){
    		$(this).hide();
    	}
    	$(".main-file-tablecontrolBox .left").show();
    });
}

function page_resize() {
    //�鱓���崕蝡贝身摰𡄯�丯esize���嘑銵�
    console.log("page_resize");
}
//---------------------------------
function checkTable(_num){
	_targetBox = $(".main-file-tableList");
	_min = _num*_tableShowNum;
	_max = _min+_tableShowNum;
	for (var i = 1; i < _tableThNum; i++) {
		if(i <= _max && i > _min){
			$(".col"+i,_targetBox).removeClass("hide");
		}else{
			$(".col"+i,_targetBox).addClass("hide");
		}
	}
}