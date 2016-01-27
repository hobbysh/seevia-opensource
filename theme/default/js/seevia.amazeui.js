/* Write your js */
$(document).ready(function(){
	/* 头部搜索 */
	$(".am-search button").click(function(){
		var keyword=$(this).parent().parent().find("input[type=text]").val();
		keyword=$.trim(keyword);
		if(keyword.length>0){
			window.location.href="/searchs/keyword?keyword="+keyword;
		}
	});

  $(".am-search-sm button").click(function(){
    var keyword=$(this).parent().parent().find("input[type=text]").val();
    keyword=$.trim(keyword);
    if(keyword.length>0){
      window.location.href="/searchs/keyword?keyword="+keyword;
    }
  });
	
	$("body").click(function(){
		$(".search_date").hide();
		$(".search_date ul").hide();
	})
	
	$(".am-search input[type='text']").keyup(function(){
		var search_date=$(this).parent().parent().find(".search_date");
		$(search_date).html();
	    auto_search($(this).val(),search_date);
		var none = $(this).val();
		if(none==""){$(search_date).hide();$(search_date).find("ul").hide();}
	});
	
	$(".am-search-sm input[type='text']").keyup(function(){
    var search_date=$(this).parent().parent().find(".search_date");
    $(search_date).html();
      auto_search($(this).val(),search_date);
    var none = $(this).val();
    if(none==""){$(search_date).hide();$(search_date).find("ul").hide();}
  });
	/* 商品列表like */
	$(".like_icon img").bind("click",function(){
	  var type=$(this).attr("id");
	  $.ajax({ url: "/user_socials/ajax_like",
			   type:"POST",
			   dataType:"json", 
			   context: $(".likam-user-avatare_icon .like_num"), 
			   data: { 'type_id': type,'loacle':$("#local").val()},
			   success: function(data){
				 if(data.code=='1'){
				   $(".like_icon #like_num"+type).html(data.like_num);
				 }else{
				   if(data.is_login=='0'){
					 $(".am-modal-bd").html("<a href='/users/login'>"+data.msg+"</a>");
					 $('#like-icon-btn').click();
				   }else{
					 alert(data.msg);
				   }
				 }
			   }
	  });
	});
	/* 商品详情页like */
	$(".my_like_icon").bind("click",function(){
		var type=$(this).attr("id");
		$.ajax({ url: "/user_socials/ajax_like",
				type:"POST",
				dataType:"json",
				data: { 'type_id': type,'loacle':$("#local").val()},
				success: function(data){
					if(data.code=='1'){
						$(".my_like_num").html(data.like_num);
					}else{
						if(data.is_login=='0'){
							$(".am-modal-bd").html("<a href='/users/login'>"+data.msg+"</a>");
					 		$('#like-icon-btn').click();
						}else{
							alert(data.msg);
						}
					}
				}
		});	
	});
	
	if(typeof(j_config_detail_page_img_auto_scaling)!="undefined"&&j_config_detail_page_img_auto_scaling=='1'){
		$(".auto_zoom img").each(function(){
			var ImageInfo=$(this);
			var img_src=$(this).prop("src");
			var screen_width=screen.width;
			if(typeof($(this).parent().width())!="undefined"){
				screen_width=screen_width>$(this).parent().width()?$(this).parent().width():screen_width;
			}
			if(img_src.trim()!=''){
				img_src=img_src.trim();
				if(img_src.indexOf("_"+screen_width+"x"+screen_width)>0){
					return false;
				}
				var strFileName="";
				if(img_src.indexOf("/")>0){
				    strFileName=img_src.substring(img_src.lastIndexOf("/")+1,img_src.length);
				}else{
				    strFileName=img_src;
				}
				var strFileName_txt=strFileName.substring(0,strFileName.lastIndexOf("."));
				var strFileName_ext=strFileName.substring(strFileName.lastIndexOf(".")+1,strFileName.length);
				var Expression=/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
				var objExp=new RegExp(Expression);
				var New_strFileName=strFileName;
				if(objExp.test(img_src)==true){
					var web_host=window.location.host;
					if(img_src.indexOf(web_host)>=0){
						var New_strFileName=img_src.replace(strFileName,strFileName_txt+"_"+screen_width+"x"+screen_width+"."+strFileName_ext);
						$(ImageInfo).prop("src",New_strFileName);
					}
				}else{
					var New_strFileName=img_src.replace(strFileName,strFileName_txt+"_"+screen_width+"x"+screen_width+"."+strFileName_ext);
					$(ImageInfo).prop("src",New_strFileName);
				}
			}else{
				$(this).prop("src","/theme/default/images/default.png");
			}
		});
	}
	
	/*  lazyload */
	$(".auto_zoom img").each(function(){
		var src=$(this).attr("src");
		$(this).addClass('lazy');
		$(this).attr('data-original',src).removeAttr("src");
		$(this).lazyload({
			effect : 'fadeIn',
		});
	});
});

$(window).load(function(){
	$("div:not(.auto_zoom) img").each(function(){
		$(this).prop("onerror",function(e){
			if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) { 
		      	//this.src = '/theme/default/images/default.png'; 
		      } 
		});
	});
})

/*
	Pop positioning
*/
function auto_search(keyword,search_date){
	if(keyword == '' || keyword.length <2 ){return false;}
	var sUrl ="/searchs/index/";
	var postData ={keyword:keyword};
	var auto_search_Success = function(result){
		if(result.showflag == false){
			$(search_date).html("");
			$(search_date).hide();
			$(search_date).find("ul").hide();
		}else{
			$(search_date).html(result.data);
			$(search_date).show();
	    	$(search_date).find("ul").show();
		}
	}
	$.post(sUrl,postData,auto_search_Success,"json");
}
function AmazeuiPopPositioning(Id){
	var WindowWidth=$(window).width();
	var WindowHeight=$(window).height();
	var PopWidth=$("#"+Id).width();
	var PopHeight=$("#"+Id).height();
	var Popmargin_left=((WindowWidth-PopWidth)/2).toFixed(2);
	var Popmargin_top=((WindowHeight-PopHeight)/2).toFixed(2);
	$("#"+Id).css("left","0px").css("top","0px").css("margin-left",Popmargin_left+"px").css("margin-top",Popmargin_top+"px");
}

function CheckKeyword(str,keyword){
	if(str.length>0){
		var KeywordArr=str.split(",");
	    $.each( KeywordArr, function(key,value){
			var patt= new RegExp(value);
			if(patt.test(keyword)){
				keyword=keyword.replace(value,"**");
			}
		});
	}
	return keyword;
}

function SetTextBoxTip_Default(name, defaultValue,ClassName) {
	if(ClassName==null){
		ClassName="SearchKeywords"
	}
    if (typeof defaultValue !== 'undefined' && typeof name !== 'undefined') {
        if ($('#' + name).attr("value") == ""||$('#' + name).attr("value").length==0) {
            $('#' + name).attr("value", defaultValue);
        }
        if ($('#' + name).attr("value") == defaultValue) {
            if (!$('#' + name).hasClass(ClassName)) {
                $('#' + name).addClass(ClassName);
            }
        } else {
            if ($('#' + name).hasClass(ClassName)) {
                $('#' + name).removeClass(ClassName);
            }
        }
    }
}

function SetTextBoxTip_focus(name, defaultValue,ClassName) {
	if(ClassName==null){
		ClassName="SearchKeywords"
	}
    if (typeof defaultValue !== 'undefined' && typeof name !== 'undefined') {
        if ($('#' + name).attr("value") == defaultValue) {
            $('#' + name).attr("value", "");
        }
        if ($('#' + name).hasClass(ClassName)) {
            $('#' + name).removeClass(ClassName);
        }
    }
}

function SetTextBoxTip_blur(name, defaultValue,ClassName) {
	if(ClassName==null){
		ClassName="SearchKeywords"
	}
    if (typeof defaultValue !== 'undefined' && typeof name !== 'undefined') {
        SetTextBoxTip_Default(name, defaultValue,ClassName)
    }
}

/* 更新验证码 */
function change_captcha(id,up_flag){
	if(up_flag!=true){
		document.getElementById(id).style.display="";
		document.getElementById(id).src = "/users/captcha/?"+Math.random();
		window.setTimeout("change_captcha_val('"+id+"')",1000);

	}else{
		change_captcha_val(id);
	}
}

function change_captcha_val(id){
	var js_obj=document.getElementById(id);
	$.ajax({ url: "/users/captcha",
			type:"POST",
			dataType:"json",
			data: {},
			success: function(data){
				$(js_obj).parent().parent().find("input[id=ck_authnum]").val(data);
			}
	});	
}

function isNone(str){return str==null||$.trim(str)==""?true:false;};//是否为空
function efocu(obj){try{$(obj).focus();}catch(e){}};//获取焦点

//验证表单
function auto_check_form(fm_id,now){
	fm = document.getElementById(fm_id);
	for(i=0;i<fm.length;i++){
		//fm[i].onblur = auto_chkInput(fm[i]);
		$(fm[i]).blur(function(){auto_chkInput(this)});
		$(fm[i]).focus(function(){auto_note(this)});
		if(now){
			auto_chkInput(fm[i]);
		}
	}
}

function auto_note(obj){
	var note , msgStr;
	note=obj.getAttribute("defaultNote");
	if(isNone(note))  return "success";

	var this_node_img="*";
	if($(obj).next().find("font").length==1){
		if($(obj).next().find("font").eq(0).css("color")=="red"){
			$(obj).next().find("font").eq(0).html(note);
			$(obj).next().find("font").eq(0).css("color","");
		}
	}else if ($(obj).next().find("font").length==2){
		if($(obj).next().find("font").eq(1).css("color")=="red"){
			$(obj).next().find("font").eq(0).html(this_node_img);
			$(obj).next().find("font").eq(0).css("color","red");
			$(obj).next().find("font").eq(1).html(note);
			$(obj).next().find("font").eq(1).css("color","");
		}
	}

}
function auto_chkInput(obj){
	var rules , msgStr;
	rules=obj.getAttribute("chkRules");
	if(isNone(rules))  return "success";
	msgStr = field_check(rules, obj);
	put_msg(obj,msgStr);
}
function put_msg(obj,msgStr){
	if(typeof(right_sign_img)=="undefined"){
		var this_right_sign_img = "<span class='am-icon-check' style='right: 16px; display: block;'></span>";
	}else{
		var this_right_sign_img = right_sign_img;
	}
	if(typeof(wrong_sign_img)=="undefined"){
		var this_wrong_sign_img = "*";
	}else{
		var this_wrong_sign_img = wrong_sign_img;
	}
	if(typeof(loading_sign_img)=="undefined"){
		var this_loading_sign_img = "...";
	}else{
		var this_loading_sign_img = loading_sign_img;
	}
	if(msgStr == "loading"){
		$(obj).next().find("font").eq(0).html(this_loading_sign_img);
		$(obj).next().find("font").eq(0).css("color","");
	}else if(msgStr != ""){
		if($(obj).next().find("font").length==1){
			$(obj).next().find("font").eq(0).html(msgStr);
			$(obj).next().find("font").eq(0).css("color","red");
		}else if ($(obj).next().find("font").length==2){
			$(obj).next().find("font").eq(0).html(this_wrong_sign_img);
			$(obj).next().find("font").eq(0).css("color","red");
			$(obj).next().find("font").eq(1).html(msgStr);
			$(obj).next().find("font").eq(1).css("color","red");
		}

	}else{
		if($(obj).val()){
			if($(obj).next().find("font").length==1){
				if(isNone(obj.getAttribute("rightNote"))){
					$(obj).next().find("font").eq(0).html(this_right_sign_img);
					$(obj).next().find("font").eq(0).css("color","green");
				}else{
					$(obj).next().find("font").eq(0).html(obj.getAttribute("rightNote"));
					$(obj).next().find("font").eq(0).css("color","");
				}
			}else if ($(obj).next().find("font").length==2){
				$(obj).next().find("font").eq(0).html(this_right_sign_img);
				$(obj).next().find("font").eq(0).css("color","green");
				$(obj).next().find("font").eq(1).html("");
				$(obj).next().find("font").eq(1).css("color","green");
			}
		}else{
			note=obj.getAttribute("defaultNote");
			if(isNone(note))  note="";
			if($(obj).next().find("font").length==1){
				$(obj).next().find("font").eq(0).html(note);
				$(obj).next().find("font").eq(0).css("color","");
			}else if ($(obj).next().find("font").length==2){
				$(obj).next().find("font").eq(0).html("");
				$(obj).next().find("font").eq(0).css("color","");
				$(obj).next().find("font").eq(1).html(note);
				$(obj).next().find("font").eq(1).css("color","");
			}
		}
	}
}

var is_checkform=true;
function check_form(fm){
	is_checkform=false;
	if(fm.elements['submit']&&fm.elements['submit_ing']){
		fm.elements['submit'].style.display="none";
		fm.elements['submit_ing'].style.display="";
	}
	for(i=0;i<fm.length;i++){
		var msgStr = chkInput(fm[i]);
		if(msgStr != "success" && msgStr != "loading"){
			if(fm.elements['submit']&&fm.elements['submit_ing']){
				fm.elements['submit'].style.display="";
				fm.elements['submit_ing'].style.display="none";
			}
			efocu(fm[i]);
			return false;
		}
	}
	if(emailstatus==false||accountstatus==false||mobilestatus==false){
		is_checkform=true;
		return false;
	}else{
		return true;
	}
	return false;
}
function reload_check_form(fm){
	for(i=0;i<fm.length;i++){
		auto_chkInput(fm[i]);
	}
	return true;
}
function chkInput(obj){
	var rules , msgStr;
	rules=obj.getAttribute("chkRules");
	if(isNone(rules))  return "success";
	msgStr = field_check(rules, obj);
	if(msgStr != ""){
		return msgStr;
	}
	else{
		return "success";
	}
}

function field_check(rules, field){
	var split_1,split_2;
	split_1 = isNone(field.getAttribute("split_1"))?";":field.getAttribute("split_1");
	split_2 = isNone(field.getAttribute("split_2"))?":":field.getAttribute("split_2");
	var rules_arr = rules.split(split_1)
    for(var i in rules_arr){
    	var rule_arr = rules_arr[i].split(split_2);
    	if(rule_arr[0]=='nnull'){//不能为空
    		if(isNone(field.value)){
    			if(rule_arr.length>1)
    				return rule_arr[1];
    			else
    				return rule_arr[0];
    		}
    	}else if(rule_arr[0]=='email') {
    		//邮箱&& !/(\,|^)([\w+._]+@\w+\.(\w+\.){0,3}\w{2,4})/.test(field.value.replace(/-|\//g,""))
    		 var reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w{2,4}$/;
    		if(!isNone(field.value) ){
    			if(rule_arr.length>1){
    				if(!reg.test(field.value)){
    					return rule_arr[0]+ j_format_is_incorrect ;
       				}else{
    					//return rule_arr[1];
    				}
    			}else{
    				return rule_arr[0];
    			}
    		}
    	}else if(rule_arr[0]=='tel'){//电话号码
			if(!isNone(field.value) && !/^((0\d{2,4})-)(\d{7,8})/.test(field.value)){
    			if(rule_arr.length>1)
    				return rule_arr[1];
    			else
    				return rule_arr[0];
			}

    	}else if(rule_arr[0]=='mobile'){//手机号码
			if(!isNone(field.value) && !/^1[3-9]\d{9}$/.test(field.value)){
    			if(rule_arr.length>1)
    				return rule_arr[1];
    			else
    				return rule_arr[0];
			}

    	}else if(rule_arr[0]=='zip_code'){//邮政编码
			if(!isNone(field.value) && !/(^[0-9]{4,10}$)/.test(field.value)){
    			if(rule_arr.length>1)
    				return rule_arr[1];
    			else
    				return rule_arr[0];
			}

    	}else if(rule_arr[0]=='cpwd'){//确认密码
			if( field.value != $("#"+rule_arr[2])[0].value){
				return rule_arr[1];
			}

    	}else if(rule_arr[0]=='must_one'){//二选一，必填一项
			if( field.value =="" && $("#"+rule_arr[2])[0].value==""){
				return rule_arr[1];
			}

    	}else if(rule_arr[0]=='check'){	//复选框
			var flag=0;
			var c=document.getElementsByName(field.getAttribute("name"));
			for(var i = 0,len = c.length; i<len; i++)
			{
				if(c[i].checked == true)
				{
					flag=1;
				}
			}
			if(flag!=1)
			{
				return rule_arr[1];
			}

    	}else if(rule_arr[0].indexOf('min')>-1){//最短
    		if(field.value.replace(/[^\x00-\xff]/g,"**").length<rule_arr[0].match(/\d+/ig)){
    			if(rule_arr.length>1)
    				return rule_arr[1];
    			else
    				return rule_arr[0];
			}

    	}else if(rule_arr[0].indexOf('max')>-1){//最长
    		if(field.value.replace(/[^\x00-\xff]/g,"**").length>rule_arr[0].match(/\d+/ig)){
    			if(rule_arr.length>1)
    				return rule_arr[1];
    			else
    				return rule_arr[0];
			}

    	}else if(rule_arr[0]=='ischeck'){//勾选协议
    		if(!field.checked){
    			if(rule_arr.length>1)
    				return rule_arr[1];
    			else
    				return rule_arr[0];
			}
    	}else if(rule_arr[0]=='region'){//区域
    		if(field.value==j_please_select){

    			if(rule_arr.length>1)
    		{$(".region_msg").html(rule_arr[1]);return rule_arr[1];}
    			else
    				return rule_arr[0];
			}
    	}else if(rule_arr[0]=='domain'){//域名
    		if(field.value.trim().length>0 && !checkDomain(field.value)){
    			if(rule_arr.length>1)
    				return rule_arr[1];
    			else
    				return rule_arr[0];
			}
    	}else if(rule_arr[0]=='special'){//特殊字符
    		if(field.value.trim().length>0 && !checkSpecial(field.value)){
    			if(rule_arr.length>1)
    				return rule_arr[1];
    			else
    				return rule_arr[0];
			}
    	}else if(rule_arr[0]=='not_default'){//特殊字符
    		if(field.value==rule_arr[2]){
    			if(rule_arr.length>1)
    				return rule_arr[1];
    			else
    				return rule_arr[0];
			}
    	}else if(rule_arr[0]=='ajax'){//ajax
    		if(is_checkform){
	    		if(field.value.trim().length>0){
	    			if(rule_arr.length>1){
	    				eval(rule_arr[1]);
	    			}
	    			return "loading";
				}
			}
    	}else if(rule_arr[0]=='edit_pwd'){
    		var pwdflag=hex_md5(field.value);
    		var old_pwd=$("#old_pwd").val();
    		if(pwdflag!=old_pwd){
    			return rule_arr[1];
    		}
    	}else if(rule_arr[0]=='authnum'){
    		var authnum_msg="Error";
    		var authnum_msg_div=$(field).parent().parent().parent().find(".authnum_msg");
    		var authnum_val=field.value.trim();
    		var ck_auth_num=$(field).parent().parent().find("input[id=ck_authnum]").length;
    		if(authnum_val.length==0){
    			authnum_msg_div.parent().css("display","block");
    			authnum_msg_div.css("color","red").html("验证码必填");
    			$(field).parent().removeClass("am-form-success");
    			$(field).parent().removeClass("am-form-error");
    			$(field).parent().addClass("am-form-warning");
    			$(field).parent().find("span").removeClass("am-icon-times").removeClass("am-icon-check");
    			$(field).parent().find("span").addClass("am-icon-warning").css("display","block");
    		}else if(ck_auth_num>0){
    			var ck_auth=$(field).parent().parent().find("input[id=ck_authnum]").val();
    			if(ck_auth.trim().length>0){
    				if(authnum_val.toLowerCase()!=ck_auth){
    					authnum_msg_div.parent().css("display","block");
    					authnum_msg_div.css("color","red").html("验证码错误");
		    			$(field).parent().removeClass("am-form-success");
		    			$(field).parent().removeClass("am-form-warning");
		    			$(field).parent().addClass("am-form-error");
		    			$(field).parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-check");
		    			$(field).parent().find("span").addClass("am-icon-times").css("display","block");
    				}else{
    					authnum_msg_div.parent().css("display","none");
    					authnum_msg_div.css("color","green").html("");
		    			$(field).parent().removeClass("am-form-error");
		    			$(field).parent().removeClass("am-form-warning");
		    			$(field).parent().addClass("am-form-success");
		    			$(field).parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-times");
		    			$(field).parent().find("span").addClass("am-icon-check").css("display","block");
    					authnum_msg="";
    				}
    			}
    		}
    		return authnum_msg;
    	}
    }
	return "";
}

var input;
var emailstatus=true;
var accountstatus=true;
var mobilestatus=true;
function check_input(type,id,email,local){
	if(type=="sn_email"||type=="email"){emailstatus=false;}
	if(type=="account"){accountstatus=false;}
	if(type=="mobile"){mobilestatus=false;}
	input = document.getElementById(id);
	if(typeof(arguments[2])!='undefined'&&!arguments[2])
	{
		email="";
		if(local!=null)
			var sUrl = local+'/users/check_input';//webroot + 'user/check';
		else
			var sUrl = '/users/check_input';//webroot + 'user/check';
		eval("var postData = {"+type+":input.value,type_id:id}");
		$.post(sUrl,postData,check_input_Success,"json");
	}
	if(email!="" && input.value!=email)
	{
		var sUrl = '/users/check_input';//webroot + 'user/check';
		eval("var postData = {"+type+":input.value,type_id:id}");
		$.post(sUrl,postData,check_input_Success,"json");
	}else{
		if(type=="sn_email"||type=="email"){emailstatus=true;}
		if(type=="account"){accountstatus=true;}
		if(type=="mobile"){mobilestatus=true;}
		setTimeout('put_msg(input,"")',10);
	}
}

var check_input_Success = function(result){
	if(result==null){
		return;
	}
	var inputtype=result.type;
	var input_id=result.type_id;
	var inputfile;
	if(input_id!=''){
		inputfile=document.getElementById(input_id);
	}else{
		inputfile=input;
	}
	if(result.error){
		put_msg(inputfile,result.msg);
		if(document.getElementById("isNo")!=null){
			document.getElementById("isNo").value  = '1';
		}
	}else{
		if(inputtype=="sn_email"||inputtype=="email"){emailstatus=true;}
		if(inputtype=="account"){accountstatus=true;}
		if(inputtype=="mobile"){mobilestatus=true;}
		if(document.getElementById("isNo")!=null){
			document.getElementById("isNo").value  = '0';
		}
		put_msg(inputfile,"");
	}
}
function checkDomain(nname){
	var arr = new Array(
	'.com','.net','.org','.biz','.coop','.info','.museum','.name',
	'.pro','.edu','.gov','.int','.mil','.ac','.ad','.ae','.af','.ag',
	'.ai','.al','.am','.an','.ao','.aq','.ar','.as','.at','.au','.aw',
	'.az','.ba','.bb','.bd','.be','.bf','.bg','.bh','.bi','.bj','.bm',
	'.bn','.bo','.br','.bs','.bt','.bv','.bw','.by','.bz','.ca','.cc',
	'.cd','.cf','.cg','.ch','.ci','.ck','.cl','.cm','.cn','.co','.cr',
	'.cu','.cv','.cx','.cy','.cz','.de','.dj','.dk','.dm','.do','.dz',
	'.ec','.ee','.eg','.eh','.er','.es','.et','.fi','.fj','.fk','.fm',
	'.fo','.fr','.ga','.gd','.ge','.gf','.gg','.gh','.gi','.gl','.gm',
	'.gn','.gp','.gq','.gr','.gs','.gt','.gu','.gv','.gy','.hk','.hm',
	'.hn','.hr','.ht','.hu','.id','.ie','.il','.im','.in','.io','.iq',
	'.ir','.is','.it','.je','.jm','.jo','.jp','.ke','.kg','.kh','.ki',
	'.km','.kn','.kp','.kr','.kw','.ky','.kz','.la','.lb','.lc','.li',
	'.lk','.lr','.ls','.lt','.lu','.lv','.ly','.ma','.mc','.md','.mg',
	'.mh','.mk','.ml','.mm','.mn','.mo','.mp','.mq','.mr','.ms','.mt',
	'.mu','.mv','.mw','.mx','.my','.mz','.na','.nc','.ne','.nf','.ng',
	'.ni','.nl','.no','.np','.nr','.nu','.nz','.om','.pa','.pe','.pf',
	'.pg','.ph','.pk','.pl','.pm','.pn','.pr','.ps','.pt','.pw','.py',
	'.qa','.re','.ro','.rw','.ru','.sa','.sb','.sc','.sd','.se','.sg',
	'.sh','.si','.sj','.sk','.sl','.sm','.sn','.so','.sr','.st','.sv',
	'.sy','.sz','.tc','.td','.tf','.tg','.th','.tj','.tk','.tm','.tn',
	'.to','.tp','.tr','.tt','.tv','.tw','.tz','.ua','.ug','.uk','.um',
	'.us','.uy','.uz','.va','.vc','.ve','.vg','.vi','.vn','.vu','.ws',
	'.wf','.ye','.yt','.yu','.za','.zm','.zw');

	var mai = nname;
	var val = true;

	var dot = mai.lastIndexOf(".");
	var dname = mai.substring(0,dot);
	var ext = mai.substring(dot,mai.length);
	//alert(ext);

	if(dot>2 && dot<57){
	 for(var i=0; i<arr.length; i++){
	   if(ext == arr[i]){
	   	val = true;
	  	break;
	   }
	   else{
	   	val = false;
	   }
	 }
	 if(val == false){
	    //alert("Your domain extension "+ext+" is not correct");
	   	return false;
	 }
	 else{
	  for(var j=0; j<dname.length; j++){
	    var dh = dname.charAt(j);
	    var hh = dh.charCodeAt(0);
	    if((hh > 47 && hh<59) || (hh > 64 && hh<91) || (hh > 96 && hh<123) || hh==45 || hh==46){
		    if((j==0 || j==dname.length-1) && hh == 45){
		        //alert("Domain name should not begin are end with '-'");
		         return false;
		     }
	    }else {
	      //alert("Your domain name should not have special characters");
	   	  return false;
	    }
	  }

	  //add www check
	  if(dname.indexOf(".")>0){
	  	  dname_ext = dname.substring(0,dname.indexOf("."));
	  	  if(dname_ext!="www")
	  	  	  return false;
	  }else{
	  	  return false;
	  }

	 }
	}else{
	 //alert("Your Domain name is too short/long");
	 return false;
	}
	return true;
}
function checkSpecial(str){
	var reg=/[@#\$%\^&\*]+/g;
	if(reg.test(str)){
		return false;
	}
	return true;
}

//购物车
function checkChanges(o,arg,i){
		var form_name=$(o).attr("id");
		var sUrl = "/carts/update_num/";
		var postData ={
			is_ajax:1,
			data:$("form[name="+form_name+"]").serialize()
		};
		$.ajax({ url: "/carts/update_num/",
				type:"POST",
				dataType:"json",
				data:$("form[name="+form_name+"]").serialize(), 
				success: function(data){
					if(data.type==0){
//						$("#sum_discount").html(data.discount_price);
//						$("#cart_price").html(data.sum_subtotal);
//						$("#total_price").html(data.sum_market_subtotal);
						var inputPTR=$(o).parent().parent().parent();
						var pro_totalTR=inputPTR.next();
						pro_totalTR.find(".total").html(data.pro_total);
						
						$("input[name='subBox']").each(function (index){
							if ($(this).prop("checked")) {
								var checked = jQuery.inArray($(this).val(), selectedIds);
				                if (checked == -1) {
				                    selectedIds.push($(this).val());
				                }
			                }
						});
						var postData = {
							selectedIds: selectedIds.join(",")
						};
						changeCart(postData);
					}else{
						alert(data.msg);
						$(o).val(data.return_qty);
					}
				}
		});
	//window.open图片管理
	function img_sel(number,assign_dir){

		var win = window.open (webroot_dir+"images/?status=1", 'newwindow', 'height=600, width=800, top=0, left=0, toolbar=no, menubar=yes, scrollbars=yes,resizable=yes,location=no, status=no');
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
}
//购买商品
function buy_now_no_ajax(id,q,type){
	document.forms['buy_now'+type+id].submit();
}
//删除收藏
function del_fav_products(type_id,user_id,type,local){
	if(local != '')
    	window.location.href=local+"favorites/del_products_t/"+type_id+"/"+user_id+"/"+type;
    else
    	window.location.href="/favorites/del_products_t/"+type_id+"/"+user_id+"/"+type;

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






//carts check_address
function   is_int(txt){
	txt.value=txt.value.replace(/\D/g,"");
}

/**
 * 判断输入框字符长度
 * @input o dom
 * @len limited char size
 */
function limitTxtByte(o, len)
{
var str = o.value;
var rscn = /^[\u4E00-\u9FA5]$/i;
var ach;
var rsstr = '';
var count = 0;
for (var i=0; i<str.length; i++)
{
ach = str.substr(i, 1);
if (rscn.test(ach))
{
if (count+2 <= len)
{
count += 2;
}else
{
break;
}
}else
{
if (count+1 <= len)
{
count += 1;
}else
{
break;
}
}
rsstr += ach;
}

if (rsstr != str)
{
o.value = rsstr;
}
}


            
// carts index
function cart_product_num(obj,type){
    var cart_quantity_input=$(obj).parent().find("input[type='text']");
    var cart_quantity=$(cart_quantity_input).val();
    if(cart_quantity==""){
        cart_quantity=1;
    }else{
        cart_quantity=parseInt(cart_quantity);
    }
    var update_num=cart_quantity;
    if(type=="minus"){
        update_num=cart_quantity-1>=1?cart_quantity-1:1;
    }else if(type=="plus"){
        update_num=update_num+1;
    }
    $(cart_quantity_input).val(update_num);
    checkChanges($(cart_quantity_input),'1');
}

var promotion_product_change_Success=function(result){
	if(result.total){
		document.getElementById('cart_price').innerHTML=result.total;
		document.getElementById('sum_discount').innerHTML=result.sum_discount;
	}
}


var documentHeight = 0;
var topPadding =115;

function ajax_cart(){
	$.ajax({ url: "/carts/index/?ajax=1",dataType:"json", context: $("#shoppingcart a"), success: function(data){
			//alert(data.sum_quantity);
			$("#shoppingcart a").html("购物车("+data.sum_quantity+")");
	}});
}


$(".item_box").mouseover(function(){
  		var id=$(this).attr("id");
  		sid=id.replace("product_","suspension");
  		$("#"+sid).css("display","block");
  		$(this).css("cursor","pointer");
  	});
    $(".item_box").mouseout(function(){
 		var id=$(this).attr("id");
  		sid=id.replace("product_","suspension");
  		$("#"+sid).css("display","none");
    });



    var selectedIds = [];
    $(document).ready(function() {
    	$('input[name="subBox"]').prop("checked","checked");
    	updateMasterCheckbox();
    	$("input[name='subBox']").each(function (index) {
			var checked = jQuery.inArray($(this).val(), selectedIds);
		    if (checked == -1) {
				selectedIds.push($(this).val());
			}
		});
    	
    	//购物车全选，反选
    	$("#checkAll").click(function() {
    		selectedIds = [];
			$("input[name='subBox']").prop('checked', $(this).prop("checked"));
			$("input[name='subBox']").each(function (index){
				if ($(this).prop("checked")) {
					var checked = jQuery.inArray($(this).val(), selectedIds);
	                if (checked == -1) {
	                    selectedIds.push($(this).val());
	                }
                }
			});
			var postData = {
				selectedIds: selectedIds.join(",")
			};
	        changeCart(postData);
		});
		var $subBox = $("input[name='subBox']");
		$subBox.on('change', function(){
			var $check = $(this);
            if ($check.is(":checked") == true) {
                var checked = jQuery.inArray($check.val(), selectedIds);
                if (checked == -1) {
                    selectedIds.push($check.val());
                }
            }
            else {
                var checked = jQuery.inArray($check.val(), selectedIds);
                if (checked > -1) {
                    selectedIds = $.grep(selectedIds, function (item, index) {
                        return item != $check.val();
                    });
                }
            }
	        var postData = {
				selectedIds: selectedIds.join(",")
			};
			$(this).prop('checked', $(this).is(':checked'))
	        changeCart(postData);
	   		updateMasterCheckbox();
		});
		var width1=document.documentElement.clientWidth;
		//alert(screen.availWidth);
		if(screen.availWidth<997){
		//	$("#your_order").css("right",""+((width1-980)/2+2)+"px");
		}
    	if(screen.availWidth==1280){
    		//$("#your_order").css("left","875px");
    		//alert(screen.availWidth);
		   // $("#your_order").css("right",""+((width1-980)/2+2)+"px");
    	}
    	if(screen.availWidth==1440){
    		//alert(screen.availWidth+1);
    		//$("#your_order").css("left","955px");
    	//	$("#your_order").css("right",""+((width1-980)/2+2)+"px");
    	}
    	if(screen.availWidth==1366){
    		//$("#your_order").css("left","915px");
    		//$("#your_order").css("right",""+((width1-980)/2+2)+"px");
    	}
    	if(screen.availWidth==1920){
    		//$("#your_order").css("left","1315px");
    		//$("#your_order").css("right",""+((width1-980)/2+2)+"px");
    	}
    	$(window).resize(function() {
    		//alert(document.documentElement.clientWidth);
    		var width=document.documentElement.clientWidth;
    		if(width<997){
    			$("#your_order").css("left","777px").css("right","0px");
    		}
    		else{
    			$("#your_order").css("right",""+((width-980)/2+2)+"px").css("left","");
    		}
		});
	//绿色透明悬浮层相关js	
      $(".picture").mouseover(function(){
      		var id=$(this).attr("id");
      		id=id.replace("picture","suspension");
      		$("#"+id).css("display","block");
      });
      $(".picture").mouseout(function(){
      		var id=$(this).attr("id");
      		id=id.replace("picture","suspension");
      		$("#"+id).css("display","none");   
      });
      $(".suspension").mouseover(function(){
      	  	var id=$(this).attr("id");
      	  	id=id.replace("suspension","name");
      	$(this).css("display","block");
      	$(this).css("cursor","pointer");
      
      });
      $(".suspension").mouseout(function(){
      	  	var id=$(this).attr("id");
      	  	id=id.replace("suspension","name");
      	$(this).css("display","none");
      	$(this).css("cursor","pointer");
      
      });
      
    });

 function checkbox(){
		var str=document.getElementsByName("subBox");
		var leng=str.length;
		var chestr="";
		for(i=0;i<leng;i++){
			if(str[i].checked == true)
		  {
		   chestr+=str[i].value+",";
		  };
		};
		return chestr;
	};

	function changeCart(postData) {
	    $.ajax({
	        type: "POST",
	        url: "/carts/changeCart",
	        data: postData,
	       	dataType:"json",
	        success: function (data) {
				$("#sum_discount").html("￥"+data.sum_discount_ajax+"元");
				$("#cart_price").html("￥"+data.sum_subtotal_ajax+"元");
				$("#total_price").html("￥"+data.sum_market_subtotal+"元");
	        },
	        error: function (xhr, ajaxOptions, thrownError) {
	            alert("Operation failure! Status=" + xhr.status + " Message=" + thrownError);
	        },
	        traditional: true
	    });
	}

		function updateMasterCheckbox() {
		var numChkBoxes = $("input[name='subBox']").length;
		var numChkBoxesChecked=0;
		$("input[name='subBox']").each(function (index){
			if ($(this).prop("checked")) {
				numChkBoxesChecked++;
            }
		});
		$('#checkAll').prop('checked', numChkBoxes == numChkBoxesChecked && numChkBoxes > 0);
	}


	(function ($) {
            $.fn.extend({
                Scroll: function (opt, callback) {
                    if (!opt) var opt = {};
                    var _btnUp = $("#" + opt.up); //Shawphy:向上按钮
                    var _btnDown = $("#" + opt.down); //Shawphy:向下按钮
                    var _this = this.eq(0).find("ul:first");
                    var lineH = _this.find("li:first").height(); //获取行高    
                    var line = opt.line ? parseInt(opt.line, 10) : parseInt(this.height() / lineH, 10); //每次滚动的行数，默认为一屏，即父容器高度
                    var speed = opt.speed ? parseInt(opt.speed, 10) : 600; //卷动速度，数值越大，速度越慢（毫秒） 
                    var m = line;  //用于计算的变量
                    var count = _this.find("li").length; //总共的<li>元素的个数
                    var upHeight = line * lineH;
                    function scrollDown() {
                    	count = _this.find("li").length; 
                        if (!_this.is(":animated")) {  //判断元素是否正处于动画，如果不处于动画状态，则追加动画。
                            if (m < count) {  //判断 m 是否小于总的个数
                                m += line;
                                _this.animate({ marginTop: "-=" + upHeight + "px" }, speed);
                            }
                        }
                    }
                    function scrollUp() {
                    	count = _this.find("li").length; 
                    	//alert(m+"||"+count);
                        if (!_this.is(":animated")) {
                            if (m > line) { //判断m 是否大于一屏个数
                                m -= line;
                                _this.animate({ marginTop: "+=" + upHeight + "px" }, speed);
                            }
                        }
                    }
                    _btnUp.live("click", scrollUp);
                    _btnDown.live("click", scrollDown);
                }
            });
        })(jQuery);





// open_elements preview
function setcontentImg(div){
	$(div+" img").each(function(){
		var content_img=$(this);
		var img_src=content_img.attr("src");
		var img=new Image();
		img.onload=function(){
			img_w=$(div).width();
			img_h=img.height;
			if(img.width/img.height >= img_w/img_h){
				if(img.width > img_w)
				{
					content_img.css("width",img_w+"px");
					content_img.css("height",((img.height*img_w) / img.width)+"px");
					W=img_w;
					H=(img.height*img_w)/img.width;
				}
				else
				{
					content_img.css("width",img.width+"px");
					content_img.css("height",img.height+"px");
					
					W=img.width;
					H=img.height;
				}
			}else{
				if(img.width > img_w)
				{
					W=img_w;
					H=(img.height*img_w)/img.width;
					
					content_img.css("width",img.width+"px");
					content_img.css("height",H+"px");
				}
			}
			content_img.css("display","block");
		}
		img.src=img_src;
	});
}
$(function(){
//	setcontentImg(".cover");
//	setcontentImg(".cont");
	$(".cont span").css("white-space","inherit");
	var windowH=parseInt($(window).height());
	var pageH=parseInt($("#wrapper").height());
	if(windowH>(pageH+10)){
		$("#wrapper").css("min-height",(windowH-10)+"px");
	}
});


// open_elements view
function setcontentImg(div){
	$(div+" img").each(function(){
		var content_img=$(this);
		var img_src=content_img.attr("src");
		var img=new Image();
		img.onload=function(){
			img_w=$(div).width();
			img_h=img.height;
			if(img.width/img.height >= img_w/img_h){
				if(img.width > img_w)
				{
					content_img.css("width",img_w+"px");
					content_img.css("height",((img.height*img_w) / img.width)+"px");
					W=img_w;
					H=(img.height*img_w)/img.width;
				}
				else
				{
					content_img.css("width",img.width+"px");
					content_img.css("height",img.height+"px");
					
					W=img.width;
					H=img.height;
				}
			}else{
				if(img.width > img_w)
				{
					W=img_w;
					H=(img.height*img_w)/img.width;
					
					content_img.css("width",img.width+"px");
					content_img.css("height",H+"px");
				}
			}
			content_img.css("display","block");
		}
		img.src=img_src;
	});
}

// orders orderpay
function wechat_ajax_payaction(){
	if(typeof(wechat_pay_time)!="undefined"){
		window.clearInterval(wechat_pay_time);
	}
	var post_data=$("#payform").serialize();
	$.ajax({
	    	url: "/balances/balance_deposit2",
	    	type: 'POST',
	    	data: post_data,
	    	dataType: 'html',
	    	success: function (result) {
	    		$('#order_pay').modal('close');
	    		
	        	$("#wechat_ajax_payaction").modal({width:350,height:350});
	        	$("#wechat_ajax_payaction .am-modal-bd").html(result);
	        	
	        	$('#wechat_ajax_payaction').on('closed.modal.amui', function(){
			  	if(typeof(wechat_pay_time)!="undefined"){
					window.clearInterval(wechat_pay_time);
				}
			});
	        }
	});
}

// orders view 
$(document).ready(function() {
	if(document.getElementById('order_invoice_no') && document.getElementById('logistics_company_id')){
		var invoice_no=$("#order_invoice_no").val();
		var logistics_company_id=$("#logistics_company_id").val();
		if(invoice_no!=""&&logistics_company_id!=""){
			var express_code = $("#Company_express_code").val();
			var sel = $("#express_info");
			var sell = $("#ex_info");
			var githubAPI="http://www.kuaidi100.com/query?type="+express_code+"&postid="+invoice_no+"";
			//此处url需要改为不在本域的地址,该页需要返回json数据
			//var githubAPI ="http://www.kuaidi100.com/query?type=huitongkuaidi&postid=210252031223";
			$.ajax({
			    type : "get",
			    async:false,
				url : githubAPI,
				dataType : "jsonp",
				success : function(response){
					var message="<br />";
			    	if(response.message=='ok'){
			    		var data=response.data;
						for(var v in data){
							message	+="<p>"+data[v].time+" =>"+data[v].context+"</p>";
						}
						sell.html(message);
					}else{
						alert(response.message)
					}
				},
				error:function(){
					//alert('fail');
				}
			});
		}
	}
});

// resumes index
//继续添加
	function addinfo(info_type,form_id){
   		var sUrl = "/resumes/addinfo";
   		var count = 0;
		if(info_type == 'education'){
   			count = $('.cont_r_2 form').length;
		}else if(info_type == 'experience'){
   			count = $('.cont_r_3 form').length;
		}else if(info_type == 'language'){
   			count = $('.cont_r_4 form').length;
		}
		var lan = $('#lan').val();
		var postData = {info_type:info_type,is_ajax:1,count:count,lan:lan};
		var addinfo_Success = function(result){
			if(form_id != ""){
				if(info_type == 'education'){
					$('#education_form_'+form_id).after(result.data);
				}else if(info_type == 'experience'){
					$('#experience_form_'+form_id).after(result.data);
				}else if(info_type == 'language'){
					$('#language_form_'+form_id).after(result.data);
				}

			}else{
				if(info_type == 'education'){
					$('#r_education').after(result.data);
				}else if(info_type == 'experience'){
					$('#r_experience').after(result.data);
				}else if(info_type == 'language'){
					$('#r_language').after(result.data);
				}
			}
		}
		$.post(sUrl,postData,addinfo_Success,"json");
	}
function ajaxFileUpload(){
		 $.ajaxFileUpload({
			  url:'/resumes/uploadPic/', //你处理上传文件的服务端
			  secureuri:false,
			  fileElementId:'avatar_file',
			  dataType: 'json',
			  success: function (result){
				if(result.code==1){
					$('#avatar_path').val(result.upload_img_url);
					$('#r_photo').attr('src',result.upload_img_url);
					$('#avatar_file').val('');
					$('#pop_bg').removeClass('open');
					$(".am-close").click();
				}else{
					alert(result.msg);
				}
			  }
		 })
		return false;
	}

	function input_box_set(obj,input_type){
		var checkbox=$(obj);
		var input_box=$(obj).parent().parent();
		if(checkbox.prop('checked')){
			$(input_box).find(input_type).removeAttr('disabled');
			$('#abroad').val("1");
		}else{
			$(input_box).find(input_type).prop('disabled',true);
			$(input_box).find(input_type).val('');
			$('#abroad').val("0");
		}
	}

// searchs both_search
function search_price(link_url){
	var search_str="";
	var search_price_start=$("#search_price_start").val();
	var search_price_end=$("#search_price_end").val();
	search_str+="&search_price_start="+search_price_start;
	search_str+="&search_price_end="+search_price_end;
	window.location.href=link_url+search_str;
}

// user_socials
//日志分享
	function checktoken(type){
		$.ajax({ url: "/synchros/checktoken/"+type,
			dataType:"json",
			type:"POST",
			success: function(data){
				if(data.flag==0){
					window.location.href='/synchros/opauth/'+type.toLowerCase();
				}else if(data.status=='1'){
					$(".tongbu #"+type+"_icon").attr("style","width:16px;height:16px;");
				}else if(data.status=='0'){
					$(".tongbu #"+type+"_icon").attr("style","width:16px;height:16px;filter: Alpha(opacity=10);-moz-opacity:.1;opacity:0.1;");
				}
		    }
		});
	}


	var img_size=true;
	function filesize(ele) {
    	try{
	    	img_size=(ele.files[0].size / 1024).toFixed(2);
	    	if(img_size<1024){
	    		img_size=true;
	    		$("#res_btn").bind("click",foo);
	    		return true;
	    	}else{
	    		img_size=false;
	    		$("#res_btn").unbind("click",foo);
	    		alert(js_picture_is_too_large);
	    		return false;
	    	}
    	}catch(e){
    		img_size=true;
    		$("#res_btn").bind("click",foo);
    		return true;
    	}
	}


// user_socials privacy_settings
$(function(){
	var windowHeight=$(window).height();	
	$(".am-user-privacy-settings .am-form-detail").css("min-height",(windowHeight*0.7)+"px");
	                                              
})

// user_socials share_setting
$(function(){
	var windowHeight=$(window).height();
	$(".am-user-share-settings .am-form-detail").css("min-height",(windowHeight*0.8)+"px");
})

// user_styles index
function update_remark(obj,Id){
    var remark=$(obj).parent();am-login-btn.parent().find(".remark_data").html();
    $("#user_style_remark input[name='data[UserStyle][id]']").val(Id);
    $("#user_style_remark input[name='data[UserStyle][remark]']").val(remark);
    $('#user_style_remark').modal('open');
}


//users forget_password
  function to_back(){
    window.location.href="/";
  } 
// websites custom_domain
function al(){
  alert('保存成功！');
}			



// elements users_menu 
$(function(){
   $(".admin-user-img img").hover(
   		function(){
   			$("#am-user-avatar.am-popover").show();
   		},
   		function(){
   			$("#am-user-avatar.am-popover").hide();
   		}
	);
   $("#am-user-avatar").hover(
   	function(){
   		$(this).show();
   	},
   	function(){
   		$(this).hide();
   	}
   	);
	});


// elements users_offcanvas
$(function(){
	$('.admin-user-img').click(function(){
		if($("#am-user-avatar-offcanvas.am-popover").css("display")=="none"){
			$("#am-user-avatar-offcanvas.am-popover").css("display","block");
		}else{
			$("#am-user-avatar-offcanvas.am-popover").css("display","none");
		}
	});
})



// module_pro_info
function buy_at_once(){
	if(js_login_user_data==null){
		//未登录
    	if($(".am-ajax-login").css("display") =="none"){
    		$("#popup_login").click();
    		change_captcha('authnum_popup_login',true);
    		return false;
    	}
	}
	if($(".rule .sale_attr_ul li").length>0){
		if($(".rule .sale_attr_ul div.sv_selected").length==$(".rule .sale_attr_ul li").length){
			$('#CartsBuyNowForm').submit(); return false;
		}else{
			alert("请先选择销售属性！");
		}
	}else{
		$('#CartsBuyNowForm').submit(); return false;
	}
}


// user_socials share_settings

function checktoken(type){
	$.ajax({ url: "/products/checktoken/"+type,
		dataType:"json",
		type:"POST",
		success: function(data){
			if(type=='sinaweibo'){
				if(data.flag==0){
					window.location.href='/synchros/opauth/'+type;
				}else if(data.status=='1'){
					$("#sina_icon").attr("style","");
				}else if(data.status=='0'){
					$("#sina_icon").attr("style","filter: Alpha(opacity=10);-moz-opacity:.1;opacity:0.1;");
				}
			}
			if(type=='qqweibo'){
				if(data.flag==0){
					window.location.href='/synchros/opauth/'+type;
				}else if(data.status=='1'){
					$("#qq_icon").attr("style","");
				}else if(data.status=='0'){
					$("#qq_icon").attr("style","filter: Alpha(opacity=10);-moz-opacity:.1;opacity:0.1;");
				}
			}
	    }
	});
}





// websites open_service

function bxo(){
    sub=document.getElementById('sub');
    if(sub.disabled==false){
        sub.disabled=true;
    }else{
        sub.disabled=false;
    }
}



$(function() {
  $('#open_service').validator({
    onValid: function(validity) {
      $(validity.field).closest('.am-form-group').find('.am-alert').hide();
    },
    onInValid: function(validity) {
      var $field = $(validity.field);
      var $group = $field.closest('.am-form-group');
      var $alert = $group.find('.am-alert');
      // 使用自定义的提示信息 或 插件内置的提示信息
      var msg = $field.data('validationMessage') || this.getValidationMessage(validity);
      if(typeof(validity.message)!="undefined"&&validity.message!=null){
            msg=validity.message;
      }
      if (!$alert.length) {
            $alert = $('<div class="am-alert am-alert-danger"></div>').hide().appendTo($group);
      }
      $alert.html(msg).show();
    },
    validate: function(validity) {
      var v = $(validity.field).val();
      if ($(validity.field).is('.js-ajax-validate')) {
        if(v.trim()!=""){
            return $.ajax({  
                url: "/websites/AjaxPost",
                type:"post",
                data:{subdomain:v},
                dataType: 'text'
            }).then(function(data){
                if(data==1){
                    validity.message="域名重复";
                    validity.valid = false;
                    return validity; 
                }else{
                    validity.message=null;
                    $(validity.field).closest('.am-form-group').find('.am-alert').hide();
                    validity.valid =true;
                    return validity; 
                }
            });
        }
     }
    }
  });
});




// module_pro_category
$(".am-resp img").addClass("am-img-responsive");