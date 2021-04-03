function page_init() {
    console.log("page_init");
    changeImgSize();
}

function page_resize() {
    console.log("page_resize");
    changeImgSize();
}
//---------------------------------
function changeImgSize(){
	$(".main-file-picture-img").attr('style',"");
	if ($(".main-file-picture-img").css('position') == "relative"){
		$(".main-file-picture-img").each(function(index){			
			$(this).height((240 * $(this).width()) / 380);
		});
	}
}