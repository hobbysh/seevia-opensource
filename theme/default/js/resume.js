$(".cont_r>div h1 span").live("click",function(){
	imgShow($(this));
});
var imgShow = function(obj){
	$(obj).parent("h1").parent().toggleClass("cont_r_hidden");
	obj[0].innerHTML = (obj[0].innerHTML == j_evgreen_148)?j_evgreen_149:j_evgreen_148;
}

$(".cont_r td textarea").live("focus keyup input paste textInput",function(){
	var tmp    = $(this);
	var tmpmax = tmp.attr("maxlength");
	if(tmpmax>0){
		var tmpNum  = tmp.attr("value").length;
		var tmpTips = tmp.parent().children(":last-child").children(":last-child");
		var tmpText = j_evgreen_150.split("0");
		tmpTips.text(tmpText[0] + tmpNum + tmpText[1]);
		if(tmpNum>=tmpmax){
			tmpTips.addClass("tips_alert");
		}
		else {
			tmpTips.removeClass("tips_alert");
		}
	}
});