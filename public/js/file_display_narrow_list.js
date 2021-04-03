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
	$(".typeNarrow .main-file-card-content-img").attr('style',"");
	if ($(".typeNarrow .main-file-card-content-img").css('position') == "relative"){
		$(".typeNarrow .main-file-card-content-img").each(function(index){			
			$(this).height((240 * $(this).width()) / 380);
		});
	}
}