function page_init() {
    console.log("page_init");
}

function page_resize() {
    console.log("page_resize");
}

function reloadPage(elem){
	window.location=$(elem).val()
}

function changeMinorCatg(elem){
	let currentURL=location.protocol+'//'+location.host+location.pathname;
	if($(elem).val()!=='all')
		currentURL+="?minor="+$(elem).val();
	window.location=currentURL;
}
//---------------------------------