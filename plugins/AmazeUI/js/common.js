//后台提交按钮
function sv_search_action(){
	document.SeearchForm.onsubmit= "";
	document.SeearchForm.submit();
}

//标签切换
function tab(){
alert("xx");
	if (document.getElementById("tabbar-div")){
		/**
		 * 处理点击标签的事件的函数
		 * @param : e  FireFox 事件句柄
		 *
		 * @return
		 */
		document.getElementById("tabbar-div").onclick = function(e){
			var obj = Utils.srcElement(e).parentNode;
		  	if (obj.className == "hover" || obj.className == '' || obj.tagName.toLowerCase() != 'li'){
				return;
		  	}
		  	else{
		    	objTable = obj.id.substring(0, obj.id.lastIndexOf("-")) + "-table";
		    	var divs = document.getElementsByTagName("div");
		    	var lis  = document.getElementsByTagName("li");

		    	for (i = 0; i < divs.length; i ++ ){
			      	if (divs[i].id == objTable){
			        	divs[i].className = "display";
			      	}
			      	else{
				        var divId = divs[i].id.match(/-table$/);
				        if (divId == "-table"){
				        	divs[i].className = "none";
				        }
			      	}
		    	}
				for (i = 0; lis.length; i ++ ){
		      		if (lis[i].className == "hover"){
		        		lis[i].className = "normal";
		        		obj.className = "hover";
		        		break;
		      		}
		    	}
		  	}
		}
	}
}
//后台提交按钮
function sv_search_action(){
	document.SeearchForm.onsubmit= "";
	document.SeearchForm.submit();
}
//回车提交
function sv_search_action_onkeypress(obj,e){
	if(window.event){
		keynum = event.keyCode
	}else if(e.which){
		keynum = e.which
	}
	if(keynum==13){
		sv_search_action();
	}
}

/**
  * 新增一个图片
  */
function addImg(obj){
	var src  = obj.parentNode.parentNode;
	var idx  = rowindex(src);
	var tbl  = document.getElementById('gallery-tables');
	var row  = tbl.insertRow(idx + 1);
	var cell = row.insertCell(-1);
	var img_str = src.cells[0].innerHTML.replace(/(.*)(addImg)(.*)(\[)(\+)/i, "$1removeImg$3$4-");
	img_str = img_str.replace(/\'9999\'/g,obj.name)
	img_str = img_str.replace(/9999/g,obj.name)
	cell.innerHTML = img_str;
	obj.name = obj.name-0+1;
}
/**
  * 删除图片上传
  */
function removeImg(obj){
	var row = rowindex(obj.parentNode.parentNode);
	var tbl = document.getElementById('gallery-tables');
	tbl.deleteRow(row);
}

function list_delete_submit(sUrl){
	$.ajax({
		type: "POST",
		url: sUrl,
        dataType: 'json',
        success: function (result) {
            if(result.flag==1){
			//	alert(result.message);
				window.location.reload();
			}
			if(result.flag==2){
				alert(result.message);
			}
        }
    });
}

function list_huanyuan_submit(sUrl){
	YUI().use("io",function(Y) {
		var request = Y.io(sUrl, {method: "POST"});//开始请求
		var handleSuccess = function(ioId, o){
			try{
				eval('result='+o.responseText);
			}catch (e){
				alert(j_object_transform_failed);
				alert(o.responseText);
			}
			if(result.flag==1){
				window.location.reload();
			}
			if(result.flag==2){
				alert(result.message);
			}
		}
		var handleFailure = function(ioId, o){
			//alert("异步请求失败!");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});
}
//分页回车
function pagers_onkeypress(obj,e){
	if(window.event){
		keynum = event.keyCode
	}else if(e.which){
		keynum = e.which
	}
	if(keynum==13){
		pagers_onblur(obj,e);
	}
}
function pagers_onblur(obj,e){
	YUI().use("io",function(Y) {
		var sUrl = admin_webroot+"pages/pagers_num/"+obj.value+"/"+new Date();
		var request = Y.io(sUrl, {method: "Get"});//开始请求
		var handleSuccess = function(ioId, o){
			window.location.href = window.location.href;
		}
		var handleFailure = function(ioId, o){
			alert("异步请求失败!");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});
}

function BrowserisIE(){
	if(navigator.userAgent.search("Opera")>-1){
		return false;
	}
	if(navigator.userAgent.indexOf("Mozilla/5.")>-1){
        return false;
    }
    if(navigator.userAgent.search("MSIE")>0){
        return true;
    }
}

/* 设置页问号说明 */
function config_help(id){
	var config_help = document.getElementById("config_help_"+id);
	if(config_help.style.display == "none"){
		config_help.style.display = "inline";
	}else{
	config_help.style.display = "none";

	}
}

/**
  * 商品关联页鼠标滑动删除图片变色
  */
function onMouseout_deleteimg(obj){
	obj.src="/admin/skins/default/img/delete1.gif"
}
function onmouseover_deleteimg(obj){
	obj.src="/admin/skins/default/img/delete2.gif"
}

//高级搜索
function sv_advanced_search(obj,advanced_id){
	document.getElementById(advanced_id).style.display = "block";
//	obj.style.display = "none";
}

//后台选择图片用
function select_img(id_str){
	window.open(admin_webroot+'/image_spaces/select_image/'+id_str+"/", 'newwindow', 'height=600, width=1024, top=0, left=0, toolbar=no, menubar=yes, scrollbars=yes,resizable=yes,location=no, status=no');
}

//首页折叠
function details_change2(obj,id){
	var table_obj = obj.parentNode;
	var str = obj.childNodes[0].innerHTML;
	var table_status = "";
	if(str.indexOf("-")>=0){
		table_status = "-";
		outstr=str.replace(/-/g,"+");
	}
	if(str.indexOf("+")>=0){
		table_status = "+";
		outstr=str.replace(/\+/g,"-");
	}
	if(table_status=="+"){
		document.getElementById(id).style.display="";
	}
	if(table_status=="-"){
		document.getElementById(id).style.display="none";
	}

	obj.childNodes[0].innerHTML = outstr;
}

	//window.open图片管理
	function img_sel(number,assign_dir){

		var win = window.open (webroot_dir+"image_spaces/?status=1", 'newwindow', 'height=600, width=800, top=0, left=0, toolbar=no, menubar=yes, scrollbars=yes,resizable=yes,location=no, status=no');
		GetId('img_src_text_number').value = number;
		GetId("assign_dir").value = assign_dir;
	}
	function img_src_return(img_obj){
		if( window_option_status == 1 ){
			var img_src_text_number = window.opener.GetId('img_src_text_number').value;
			var src_arr = img_obj.src.split("/");
			var j=0;
			var src_str = "";
			for(var i=3;i<=src_arr.length-1;i++){
				src_str+="/"+src_arr[i];
				j++;
			}
			window.opener.GetId('upload_img_text_'+img_src_text_number).value = img_obj.name;
			window.opener.GetId('img_src_text_number').value = "";
			window.opener.GetId('logo_thumb_img_'+img_src_text_number).src = src_str;
			window.opener.GetId('logo_thumb_img_'+img_src_text_number).style.display="block";
			window.close();
		}
	}

	//遮罩层JS

	/*function layer_dialog(){
		tabView = new YAHOO.widget.TabView('contextPane');
        layer_dialog_obj = new YAHOO.widget.Overlay("layer_dialog",
							{
								width:"422px",
								visible:false,
								draggable:false,
								modal:true,close: true,
								fixedcenter: true,zindex:"40"
							}
						); alert("asdf");
		layer_dialog_obj.render();
	}
	function message_content(){
		tabView = new YAHOO.widget.TabView('contextPane');
        message_content_obj = new YAHOO.widget.Overlay("message_content",
							{
								width:"422px",
								visible:false,
								draggable:false,
								modal:true,close: true,
								fixedcenter: true,zindex:"40"
							}
						);
		message_content_obj.render();
		message_content_obj.show();
	}
	function message_content_hide(){
		YAHOO.example.container.message.hide();
		message_content_obj.hide();
	}*/
	//后台对话框
	/***************************提示信息********连接地址********3*******类拟alert					*/
	/***************************提示信息********连接地址********4*******类拟alert   加刷新			*/
	/***************************提示信息********函数************5*******类拟confirm但用法不一样		*/
	function layer_dialog_show(admin_dialog_content,url_or_function,button_num){
		if(url_or_function!=''){
			GetId('confirm').value = url_or_function;//删除层传URL
		}

		//alert(document.getElementById("dialog_content").innerHTML);
		GetId('admin_dialog_content').innerHTML = admin_dialog_content;//对话框中的中文
		var button_replace = GetId('admin_button_replace');
		if(button_num==3){
			button_replace.innerHTML = "<a href='javascript:layer_dialog_obj.hide();' style='padding-right:50px;'>"+j_submit+"</a>";
		}
		if(button_num==4){
			button_replace.innerHTML = "<a href='javascript:window.location.reload();' style='padding-right:50px;'>"+j_submit+"</a>";
		}
		if(button_num==5){
			button_replace.innerHTML = "<a href='javascript:layer_dialog_obj.hide();' >"+j_cancel+"</a><a href='javascript:layer_dialog_obj.hide();"+url_or_function+";' >"+j_submit+"</a>";

		}
		if(button_num==6){
			button_replace.innerHTML = "<a href='javascript:layer_dialog_obj.hide();YAHOO.example.container.wait.hide();' >"+j_cancel+"</a><a href="+url_or_function+" >"+j_submit+"</a>";
		}
		layer_dialog_obj.show();
	}
	function layer_dialog_hide(){
		GetId('layer_dialog').style.display = "none";
	}
	//确认后操作
	/*function confirm_record(){
		layer_dialog_obj.hide()
		container_message_show();
		var sUrl = GetId('confirm').value;
		var request = YAHOO.util.Connect.asyncRequest('POST', sUrl, remove_record6_callback);
	}*/
	var remove_record6_Success = function(o){
		layer_dialog();
		layer_dialog_show(j_deleted_success,"",4);
	}

	var remove_record6_Failure = function(o){
		alert("error");
	}

	var remove_record6_callback ={
		success:remove_record6_Success,
		failure:remove_record6_Failure,
		timeout : 30000,
		argument: {}
	};
	//确认后操作
	var thistext_out = "";
	/*function confirm_record6(thistext,url){
		layer_dialog_obj.hide()
		thistext_out = thistext;
		container_message_show();
		var sUrl = url;
		var request = YAHOO.util.Connect.asyncRequest('POST', sUrl, remove_record_callback);
	}*/
	var remove_record_Success = function(o){
		layer_dialog();
		layer_dialog_show(thistext_out,"",4);
	}

	var remove_record_Failure = function(o){
		alert("error");
	}

	var remove_record_callback ={
		success:remove_record_Success,
		failure:remove_record_Failure,
		timeout : 30000,
		argument: {}
	};
//后台对话框end
//通过ID获取对象
function GetId(id){
	return document.getElementById(id)
}

//后台问号帮助信息
function help_show_or_hide(text_id){
	var text_help = GetId(text_id);
	if(text_help.style.display  == "none"){
		text_help.style.display  = "block";
	}else{
		text_help.style.display  = "none";

	}
}
//后台关闭新功能弹框
function close_hide(){
	var ai = document.getElementById("newsdiv");
	ai.style.display  = "none";
	//alert(document.cookie);
	var nowday = new Date();
	var datetime = nowday.format('yyyy-MM-dd');
	//var timesecond=nowday.getTime()*3600*24*30*365;
	YUI().use('cookie', function(Y){
    	Y.Cookie.set("iocolasttime", datetime, {path: "/",expires: new Date("January 12, 2025")});
	});
}
//后台关闭新功能弹框 并修改商店设置
function close_hide_change(){
	var ai = document.getElementById("newsdiv");
	ai.style.display  = "none";
	//alert(document.cookie);
	var nowday=new Date();
	var datetime=nowday.format('yyyy-MM-dd');
	//var timesecond=nowday.getTime()*3600*24*30*365;
	YUI().use('io', function(Y){
		var sUrl = admin_webroot+"configvalues/change_shop_prompt/";//访问的URL地址    	
		var request = Y.io(sUrl);//开始请求
		var handleSuccess = function(ioId, o){
		}
		var handleFailure = function(ioId, o){
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);    	
	});
}
function AddCookie(name,value,days)//两个参数，
{

    var exdate=new Date();
    exdate.setDate(exdate.getDate()+days);
    document.cookie=name+ "=" +escape(value)+
    ((days==null) ? "" : ";expires="+exdate.toGMTString())+";path=/";

}
Date.prototype.format = function(format){
var o =
{
"M+" : this.getMonth()+1, //month
"d+" : this.getDate(), //day
"h+" : this.getHours(), //hour
"m+" : this.getMinutes(), //minute
"s+" : this.getSeconds(), //second
"q+" : Math.floor((this.getMonth()+3)/3), //quarter
"S" : this.getMilliseconds() //millisecond
}
if(/(y+)/.test(format))
format=format.replace(RegExp.$1,(this.getFullYear()+"").substr(4 - RegExp.$1.length));
for(var k in o)
if(new RegExp("("+ k +")").test(format))
format = format.replace(RegExp.$1,RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
return format;
}



//菜单滚动-begin
var my_loop;
function scroll_stop()
{
	clearInterval(my_loop);
}
function scroll_go_right()
{
	my_loop = setInterval(go_right,1);
}

function scroll_go_left()
{
	my_loop = setInterval(go_left,1);
}
function scroll_go_left_fast()
{
	clearInterval(my_loop);
	my_loop = setInterval(go_left_fast,1);
}
function scroll_go_right_fast()
{
	clearInterval(my_loop);
	my_loop = setInterval(go_right_fast,1);
}

function scroll_go_menu_right()
{
	my_loop = setInterval(go_menu_right,1);
}

function scroll_go_menu_left()
{
	my_loop = setInterval(go_menu_left,1);
}
function scroll_go_menu_left_fast()
{
	clearInterval(my_loop);
	my_loop = setInterval(go_menu_left_fast,1);
}
function scroll_go_menu_right_fast()
{
	clearInterval(my_loop);
	my_loop = setInterval(go_menu_right_fast,1);
}


function go_left()
{
	document.getElementById('subMenu').scrollLeft -=1;
}
function go_right()
{
	document.getElementById('subMenu').scrollLeft +=1;
}
function go_left_fast()
{
	document.getElementById('subMenu').scrollLeft -=5;
}
function go_right_fast()
{
	document.getElementById('subMenu').scrollLeft +=5;
}

function go_menu_left()
{
	document.getElementById('main_nav').scrollLeft -=1;
}
function go_menu_right()
{
	document.getElementById('main_nav').scrollLeft +=1;
}
function go_menu_left_fast()
{
	document.getElementById('main_nav').scrollLeft -=5;
}
function go_menu_right_fast()
{
	document.getElementById('main_nav').scrollLeft +=5;
}

//菜单滚动-end

//清除缓存

function clear_cache_bt(){
	if(!confirm(j_empty_cache)){
		return ;
	}
	YUI().use("io",function(Y) {
		var cfg = {
			method: "POST"
		};
		var sUrl = admin_webroot+"pages/clear_cache/";//访问的URL地址
		var request = Y.io(sUrl, cfg);//开始请求
		var handleSuccess = function(ioId, o){
			try{
				eval('result='+o.responseText);
			}catch (e){
				alert('ERROR！');
			}
			alert(result.msg);
		}
		var handleFailure = function(ioId, o){}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});
}

//两边去空格
function Trim(str){ //删除左右两端的空格 
    return str.replace(/(^\s*)|(\s*$)/g,"");
}
//提交服务单
function show_message_dialog(){
	popOpen('message_dialog');
}
function check_message_form(){
	
	YUI().use("io",function(Y) {
		var title = Y.one('#mess_title').get('value');
		var content = Y.one('#mess_content').get('value');
		if(title==""||content==""){
			alert(j_feedback_not_empty);
			return;
		}
		var sUrl = "/admin/applications/send_meesage/";//访问的URL地址
		var cfg = {
				method: 'POST',
				form: {
					id: 'mgt_msg_form',
					useDisabled: true
				}
		};
		var request = Y.io(sUrl,cfg);
		var handleSuccess = function(ioId, o){
			try{
				eval('result='+o.responseText);
				if(result.flag==1){
					 alert(j_feedback_success);
					 btnClose();
				}
			}catch (e){
				alert("<?php echo $ld['object_transform_failed']?>");
				alert(o.responseText);
			}
		}
		var handleFailure = function(ioId, o){
			alert("<?php echo $ld['asynchronous_request_failed']?>");
		}
		Y.on('io:success',handleSuccess);
		Y.on('io:failure', handleFailure);
		})
}

function sprintf(){
    var arg = arguments,
        str = arg[0] || '',
        i, n;
    for (i = 1, n = arg.length; i < n; i++) {
        str = str.replace(/%s/, arg[i]);
    }
    return str;
}