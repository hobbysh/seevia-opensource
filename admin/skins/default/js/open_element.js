/*
	素材管理JS
*/
var elementArr=new Array();
var deletek=true;
var flag="";
//判断该元素是否在该数组内
Array.prototype.in_array = function(e){  
	for(i=0;i<this.length;i++){
		if(this[i] == e)
			return false;
	}
	return true;
}

function check_submit_form(){
	if(element_js_type=="1"){
		var name100=document.getElementById("title100").value;
		var pic100=document.getElementById("tip100").value;
		var text100=document.getElementById("description100").value;
		if(name100==""){
			alert(element_js_title_not_empty);
			return false;
		}else if(pic100==""){
			alert(element_js_pic_not_empty);
			return false;
		}else if(text100==""){
			alert(element_js_content_not_empty);
			return false;
		}
		document.getElementById("OpenElementForm").submit();
	}else{
		var name=document.getElementById("title100").value;
		var pic=document.getElementById("tip100").value;
		var text=document.getElementById("description100").value;
		if(name==""){
			alert("主素材"+element_js_title_not_empty);
			return false;
		}else if(pic==""){
			alert("主素材"+element_js_pic_not_empty);
			return false;
		}else if(text==""){
			alert("主素材"+element_js_content_not_empty);
			return false;
		}
		var title_count=0,pic_count=0,text_count=0;//记录未填写标题、图片、内容的素材个数
		//标记信息填写完全的素材
		var title_flag=new Array();
		var pic_flag=new Array();
		var txt_flag=new Array();
		$(".op_title").each(function(i){
			if($(this).val()==""){
				title_count++;
				title_flag[i]="no";
			}else{
				title_flag[i]="yes";
			}
		});
		$(".media_url").each(function(i){
			if($(this).val()==""){
				pic_count++;
				pic_flag[i]="no";
			}else{
				pic_flag[i]="yes";
			}
		});
		$(".op_description").each(function(i){
			if($(this).val()==""){
				text_count++;
				txt_flag[i]="no";
			}else{
				txt_flag[i]="yes";
			}
		});
		if(title_flag.length==0||pic_flag.length==0||txt_flag.length==0||(title_flag.length!=pic_flag.length&&title_flag.length!=txt_flag.length)){
			alert('Error:素材信息获取失败');
			return false;
		}
		var element_count=0;
		for(var i=0;i<title_flag.length;i++){
			if(title_flag[i]=="yes"&&pic_flag[i]=="yes"&&txt_flag[i]=="yes"){
				element_count++;
			}
		}
		if(element_count>=2){
			document.getElementById("OpenElementForm").submit();
		}else{
			alert("多图文至少需要2个信息完整的素材");
			return false;
		}
	}
}

//提交保存素材
function check_page_style(m){
	var name=document.getElementById("title"+m).value;
	var pic=document.getElementById("tip"+m).value;
	var text=document.getElementById("description"+m).value;
	
	if(element_js_type=="1"){
		if(name==""){
			alert(element_js_title_not_empty);
			return false;
		}else if(pic==""){
			alert(element_js_pic_not_empty);
			return false;
		}else if(text==""){
			alert(element_js_content_not_empty);
			return false;
		}
		document.getElementById("OpenElementForm").submit();
	}else{
		check_submit_form();
	}
}

//选择图片
function select_imge(id_str,type){
	if(typeof(type)=="undefined"){type="";}
	window.open(admin_webroot+'/image_spaces/select_image/'+id_str+"/?type="+type, 'newwindow', 'height=600, width=1024, top=0, left=0, toolbar=no, menubar=yes, scrollbars=yes,resizable=yes,location=no, status=no');
}

//来源显示
function outresource(obj){
	var x="";
	var y="";
	x=document.getElementById("ors"+obj);
	y=document.getElementById("os"+obj);
	if(x.style.display==""||x.style.display=="none"){
		x.style.display="block";
		y.style.display="none";
	}
}

//外链显示
function outaddress(obj){
	var x="";
	var y="";
	x=document.getElementById("outadd"+obj);
	y=document.getElementById("od"+obj);		
	if(x.style.display==""||x.style.display=="none"){
		x.style.display="block";
		y.style.display="none";
	}
}

//图文主素材编辑
function disright(){
	//获取总共的子项素材
	var t=document.getElementById("element_count").value;
	//隐藏所有的div
	for(var i=0;i<=t;i++){
		if(elementArr.in_array(i)){
			if(document.getElementById("right"+i)){
				document.getElementById("right"+i).style.display="none";
			}
		}
	}
	if(deletek){
		document.getElementById("rightk")!=null?document.getElementById("rightk").style.display="none":'';
	}
	var a=document.getElementById("right");
	$("#rightmenu #OpenElementForm .btnouter").css("top","770px");
	$("#smallmenu").css("min-height","890px");
	$("#right").css("top","12px");
	a.style.display="block";
}

//调整素材图片
function set_flash_img(obj,wid,hei){
	var img_src=obj.src;
	var img=new Image();
	img.src=img_src;
	if(img.width>0){
		var img_w,img_h;
		if(typeof(wid)!="undefined"&&img.width>wid){
			img_w=wid;
		}else{
			img_w=img.width;
		}
		if(typeof(hei)!="undefined"&&img.height>hei){
			img_h=(img.height*img_w) / img.width;
		}else{
			img_h=img.height;
		}
		if(typeof(hei)!="undefined"&&img_h>hei){
			img_h=hei;
			img_w=(img.width*img_h) / img.height;
		}
		obj.style.width=img_w+"px";
		obj.style.height=img_h+"px";
	}
}

//子素材编辑、删除按钮显示特效
function back1(obj){
	var x="";
	if(obj==100){
		$("#titleback").css("display","block");
	}else{
		$("#titleback"+obj).css("display","block");
	}
}

//子素材编辑、删除按钮隐藏特效
function back2(obj){
	var x="";
	if(obj==100){
		$("#titleback").css("display","none");
	}else{
		$("#titleback"+obj).css("display","none");
	}
}

//图文子素材的选择
function appear(k){
	//获取总共的子项素材
	var t=document.getElementById("element_count").value;
	var oidd=document.getElementById("oid100").value;
	document.getElementById("right").style.display="none";	
	//隐藏所有的div
	if(t==0){
		document.getElementById("right0").style.display="none";
	}
//	for(var i=0;i<=t;i++){
//		if(elementArr.in_array(i)){
//			if(document.getElementById("right"+i)){
//				document.getElementById("right"+i).style.display="none";
//			}
//		}
//	}
	$(".msg-editer-wrapper").each(function(){
		if($(this).css("display")=="block"){
			$(this).css("display","none");
		}
	});
	//判断元素是否存在
	if(deletek&&document.getElementById("right0")!=null){
		document.getElementById("right0").style.display="none";
	}
	//显示对应的div
	//获取现在父级div的id作为自己的parentid
	var par=document.getElementById("oid100").value;
	//alert($("#right"+k).index(".msg-editer-wrapper"));
	var index_value=$("#right"+k).index(".msg-editer-wrapper")-1;
	var num=index_value*96+245;
	var top=800;
	if(k==0){
		top=235+830;
	}else{
		top=index_value*95+830+235;
	}
	$("#smallmenu").css("min-height",top+120+"px");
	$("#rightmenu #OpenElementForm .btnouter").css("top",top+"px");
	document.getElementById("right"+k).style.top=num+"px";
	document.getElementById("right"+k).style.display="block";
	//填充par
	document.getElementById("opid"+k).value=par;
}

//标题编辑同步显示
function strLenCalc(obj){
	var word=document.getElementById("title"+obj).value;
	document.getElementById("biaoti"+obj).innerHTML=word;
}

//子素材新增
function addson(){
	//获取总共的子项素材
	var t=document.getElementById("element_count").value;
	if(t<7){
		var element_default_img="/admin/skins/default/img/element_add_default.png";
		if(flag==""){flag=t;}
		var count=$(".titlediv").length-2;
		var num=count*96+245;
		//增加左边的div
		var pin="<div class='titlediv' id='tit"+flag+"' onmousemove='back1("+flag+")' onmouseout='back2("+flag+")' onmouseleave='back2("+flag+")'><div class='title1' id='biaoti"+flag+"' ><h4><?php echo $ld['title']?></h4></div><img class='smallback' id='show_tip"+flag+"' onclick=\"select_imge('tip"+flag+"')\" src='"+element_default_img+"'/><ul class='sub-msg-opr' id='titleback"+flag+"' ><a class='opr-icon edit-icon' href='javascript:;' onclick='appear("+flag+")'></a><a class='opr-icon del-icon' href='javascript:;' onclick='del(0,"+flag+")'></a></ul></div>";	
		//增加右边的div
		var pinr="<div id='right"+flag+"' class='msg-editer-wrapper' style='display:none;top:"+num+"px;'><div><div class='msg-editer'><input type='hidden'  id='oid"+flag+"'name='data["+flag+"][OpenElement][id]' value='' /><input type='hidden' id='opid"+flag+"' name='data["+flag+"][OpenElement][parent_id]' value=''/><input type='hidden' id='oeid"+flag+"' name='data["+flag+"][OpenElement][element_type]' value='"+element_js_type+"'/><div class='control-group group_title' ><label class='control-label'>"+element_js_title+"</label><span class='maroon'>*</span><span class='help-inline'>("+element_js_required+")</span><div class='group_title'><input class='op_title span5' id='title"+flag+"' onkeyup='strLenCalc("+flag+")' type='text' name='data["+flag+"][OpenElement][title]' style='width: 682px;' value=''></div></div><div class='control-group group_img'><label class='control-label'>"+element_js_cove+"</label><span class='maroon'>*</span><span class='help-inline'>("+element_js_required+")</span><div class='choosebutton'><div class='ch' onclick=\"select_imge('tip"+flag+"')\">"+article_select_file+"</div><span class='sp1'>"+element_js_cover_desc+"<br />"+open_element_bulk_desc+"</span></div><input class='op_tip media_url' type='hidden' name='data["+flag+"][OpenElement][media_url]' id='tip"+flag+"' value=''/></div><div class='uplod' id='uplod"+flag+"' style='display:none;'><div style='float:left;'><img id='show1_tip"+flag+"' src='' class='show_media_url' /></div><div style='float:left;'><input type='button' value='"+element_js_delete+"' onclick='dele("+flag+")' class='del_btn' /></div><div style='clear:both;'></div></div><div class='control-group group_content'><label class='control-label'>"+element_js_body+"</label><span class='maroon'>*</span><span class='help-inline'>("+element_js_required+")</span><div class='choosebutton' style='border:none;'><textarea style='resize:none;height:275px; width:682px;top:-4px;position:relative;left:-2px;overflow:hidden;' class='op_description' id='description"+flag+"' name='data["+flag+"][OpenElement][description]'></textarea></div></div><div class='control-group group_link' id='od"+flag+"' ><a class='am-btn am-btn-warning am-radius am-btn-sm' href='javascript:void(0);' onclick='outaddress("+flag+")'>"+(element_js_locale=='eng'?(element_js_add+' '+external_chain):(element_js_add+external_chain))+"</a></div><div style='display:none;' class='control-group out_address' id='outadd"+flag+"'><label class='control-label'>"+external_chain+element_js_address+"</label><span class='help-inline'>("+external_chain_desc+")</span><div><input id='url"+flag+"' class='span5' type='text' name='data["+flag+"][OpenElement][url]' style='width: 682px;' value=''></div></div><div class='control-group out_link' id='os"+flag+"' ><a class='am-btn am-btn-warning am-radius am-btn-sm' href='javascript:void(0);' onclick='outresource("+flag+")'>"+(element_js_locale=='eng'?(element_js_add+' '+element_js_reffer):(element_js_add+element_js_reffer))+"</a></div><div style='display:none;' class='control-group out_source' id='ors"+flag+"'><label class='control-label' >"+element_js_reffer+"</label><span class='help-inline'>("+element_js_reffer_url_desc+")</span><div><input id='link"+flag+"' class='span5' type='text' name='data["+flag+"][OpenElement][link]' style='width: 682px;' value=''></div></div><span class='a-out' style='margin-top: 0px;'></span>	<span class='a-in' style='margin-top: 0px;'></span></div></div></div>";
		var d=document.getElementById("m_item");
		var content=document.getElementById("add_one_row");
		var contentml=document.getElementById("add_one_row").innerHTML;
		d.removeChild(content);
		var iner=document.getElementById("m_item").innerHTML;//获取当前的innerHTML
		var aa="<div id='add_one_row' class='titlediv'>"+contentml+"</div>";
		iner=iner+pin;
		iner=iner+aa;
		document.getElementById("m_item").innerHTML=iner;
		$("#OpenElementForm .btnouter").before(pinr);
		KindEditor.create('#description'+t, {
								cssPath : '/css/index.css',filterMode : false,
								resizeType : 1,
								allowPreviewEmoticons : false,
								allowImageUpload : false,
								items : [
									'source','fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
									'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
									'insertunorderedlist', '|', 'emoticons', 'image', 'link'],
								afterBlur: function(){this.sync();}
							});
		t++;
		document.getElementById("element_count").value=t;
	}else{
		alert("最多添加8个素材！");
	}
	flag++;
}

//删除子项的div,前面的值是对应的id，没有的话为0，后面一个是位置
function del(id,pois){
	var smallmenu_flag=document.getElementById("right").style.display=="block"?false:true;
	if(confirm(element_js_confirm_delete)){
		//多图文素材判断现在有多少子素材，大于2个才能删
		var t=document.getElementById("element_count").value;
		var func="open_elements/dell";
		if(t>1){
			//先删除数据库里的子素材
			if(id!=0&&id!=""){
				YUI().use("io",function(Y){
					//POST数据
					var postData = "id="+id;
					var cfg = {
						method: "POST",
						data: postData
					};
					var sUrl = admin_webroot+func;//访问的URL地址
					var request = Y.io(sUrl, cfg);//开始请求
					var handleSuccess = function(ioId,o){
					}
					var handleFailure = function(ioId, o){
						//obj.innerHTML = org;
					}
					Y.on('io:success', handleSuccess);
					Y.on('io:failure', handleFailure);
				});
			}
			//在目录中删除
			var d=document.getElementById("m_item");
			var content=document.getElementById("tit"+pois);
			d.removeChild(content);
			//在右边目录中删除
			var index_value=$("#right"+pois).index(".msg-editer-wrapper");
			var e=document.getElementById("OpenElementForm");
			var contente=document.getElementById("right"+pois);
			e.removeChild(contente);
			t--;
			document.getElementById("element_count").value=t;
			//把删除的id传到数组
			elementArr.push(pois);
			//右边显示top样式减小
			if(t>=1){
				if(smallmenu_flag){
					$('.msg-editer-wrapper').eq(index_value-1).show();
					$('.msg-editer-wrapper').eq(index_value-1).css("top",(index_value-2)*96+245+"px");
					$(".msg-editer-wrapper").each(function(){
						if($(this).css("display")=="block"){
							var btn_top=$("#rightmenu #OpenElementForm .btnouter").css("top").replace("px","")-96;
							var min_h=$("#smallmenu").css("min-height").replace("px","")-96;
							$("#rightmenu #OpenElementForm .btnouter").css("top",btn_top+"px");
							$("#smallmenu").css("min-height",min_h+"px");
						}
					});
				}
			}else{
				disright();	
			}
		}else{
			alert("多图文至少需要2个素材")
		}
	}

}

//删除图片
function dele(obj){
	//设置添加时的默认空白图片
	var element_default_img="/admin/skins/default/img/element_default.jpg";
	if(typeof(element_Id)!="undefined"&&element_Id==""){
		element_default_img="/admin/skins/default/img/element_add_default.png";
	}
	if(obj==100){
		$("#show_tip"+obj).attr("src",element_default_img);
	}else{
		$("#show_tip"+obj).attr("src",element_default_img);	
	}
	$("#tip"+obj).val("");
	//最后把自己的div清空并隐藏
	$("#uplod"+obj).hide();
}