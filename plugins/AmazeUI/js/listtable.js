var listTable = new Object;

listTable.url = location.href.lastIndexOf("?") == -1 ? location.href.substring((location.href.lastIndexOf("/")) + 1) : location.href.substring((location.href.lastIndexOf("/")) + 1, location.href.lastIndexOf("?"));

/*创建一个可编辑区*/
listTable.edit = function(obj,func,id){
	var tag = obj.firstChild.tagName;
	if (typeof(tag) != "undefined" && tag.toLowerCase() == "input"){
   		return;
  	}
	/* 保存原始的内容 */
	var org = obj.innerHTML;
	var val = Browser.isIE ? obj.innerText : obj.textContent;
	/* 创建一个输入框 */
	var txt = document.createElement("INPUT");
	txt.value = (val == 'N/A')|| (val == '-')? '' : val;
	txt.className = "input_text" ;
//	txt.style.width = (obj.offsetWidth + 12) + "px" ;
//	txt.style.minWidth = "120px" ;
	txt.style.width = "98%" ;

	/* 隐藏对象中的内容，并将输入框加入到对象中 */
	obj.innerHTML = "";
	obj.appendChild(txt);
	txt.focus();

	/* 编辑区输入事件处理函数 */
	txt.onkeypress = function(e){
	    var evt = Utils.fixEvent(e);
	    var obj = Utils.srcElement(e);
	    if(evt.keyCode == 13){
	    	obj.blur();
	   		return false;
	   	}
		if(evt.keyCode == 27){
	    	obj.parentNode.innerHTML = org;
	    }
	 }

	/* 编辑区失去焦点的处理函数 */
	txt.onblur = function(e){
		if(Utils.trim(txt.value)==Utils.trim(val))
		{
			obj.innerHTML = org;
			return;
		}
		$.ajax({
	        type: "POST",
	        url:admin_webroot+func,
	        data:{'id':id,'val':Utils.trim(txt.value)},
	        datatype: 'json',
	        success: function(data) {
	        	try{
					var result= JSON.parse(data);
					if(result.flag == 1){
						var result_content = (result.flag == 1) ? result.content : org;
						if(Browser.isIE){
							obj.innerText=Utils.trim(decodeURIComponent(result_content));
						}else{
							obj.innerHTML=Utils.trim(decodeURIComponent(result_content));
						}
					}
					if(result.flag == 2){
						alert(result.content);
						obj.innerHTML = org;
					}
				}catch(e){
					alert(j_object_transform_failed);
					obj.innerHTML = org;
				}
	        }
	    });
	}
}

/*切换状态*/
/*
listTable.toggle = function(obj, func, id){
	//获取图片和启用的HTML
	var img_opt = obj.parentNode.parentNode.childNodes[3].childNodes[1];
	var text_opt = obj.parentNode.parentNode.childNodes[5].childNodes[1];
	var img_str = img_opt.src;
	
	var val = (img_str.match(/yes.gif/i)) ? 0 : 1;
	YUI().use("io",function(Y) {
		//POST数据
		var postData = "val="+val+"&id="+id;
		var cfg = {
			method: "POST",
			data: postData
		};
		var sUrl = admin_webroot+func;//访问的URL地址
		var request = Y.io(sUrl, cfg);//开始请求
		var handleSuccess = function(ioId,o){
			try{
				eval('result='+o.responseText);
			}catch(e){
				alert(j_object_transform_failed);
				alert(o.responseText);
			}
			if(result.flag == 1){
				//判断是否启用修改启用和图片的html
				if(text_opt.innerHTML!="启用"){
					img_opt.src = img_str.substring(0,img_str.length-6)+"yes.gif"
					text_opt.innerHTML="启用"
				}else{
					img_opt.src = img_str.substring(0,img_str.length-7)+"no.gif"
					text_opt.innerHTML="关闭"
				}
			}
			if(result.flag == 2){
				alert(result.content);
			}
		}
		var handleFailure = function(ioId, o){
			//alert("异步请求失败!");
			obj.innerHTML = org;
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});
}
*/
listTable.toggle = function(obj, func, id){
	var val = (obj.src.match(/yes.gif/i)) ? 0 : 1;
	$.ajax({
        cache: true,
        type: "POST",
        url:admin_webroot+func,
        data:{'id':id,'val':val},
        async: false,
        success: function(data) {
        	try{
				var result= JSON.parse(data);
				if(result.flag == 1){
					var img_yes = obj.src .replace(/no.gif/g,"yes.gif");
					var img_no = obj.src .replace(/yes.gif/g,"no.gif");
					obj.src = (val > 0) ? img_yes : img_no;
				}
				if(result.flag == 2){
					alert(result.content);
				}
			}catch(e){
				alert(j_object_transform_failed);
				obj.innerHTML = org;
			}
        }
    });
}


//列表批量选中
listTable.selectAll = function(obj,chk){
	if(chk == null){
    	chk = 'checkboxes';
  	}
  	var elems = document.getElementsByName(chk);
  	for (var i=0; i < elems.length; i++){
      	elems[i].checked = obj.checked;
  	}
}
/*创建一个可编辑区*/
listTable.editattr = function(obj,func,id){
	var tag = obj.firstChild.tagName;
	if (typeof(tag) != "undefined" && tag.toLowerCase() == "input"){
   		return;
  	}

	/* 保存原始的内容 */
	var org = obj.innerHTML;
	var val = Browser.isIE ? obj.innerText : obj.textContent;
	/* 创建一个输入框 */
	var txt = document.createElement("INPUT");
	txt.value = (val == 'N/A')|| (val == '-')? '' : val;
	txt.className = "input_text" ;
	//txt.style.width = (obj.offsetWidth + 24) + "px" ;
	txt.style.width = "98%" ;

	/* 隐藏对象中的内容，并将输入框加入到对象中 */
	obj.innerHTML = "";
	obj.appendChild(txt);
	txt.focus();

	/* 编辑区输入事件处理函数 */
	txt.onkeypress = function(e){
	    var evt = Utils.fixEvent(e);
	    var obj = Utils.srcElement(e);
	    if(evt.keyCode == 13){
	    	obj.blur();
	   		return false;
	   	}
		if(evt.keyCode == 27){
	    	obj.parentNode.innerHTML = org;
	    }
	 }

	/* 编辑区失去焦点的处理函数 */
	txt.onblur = function(e){
		if(Utils.trim(txt.value).length > 0 || true){
			$.ajax({
		        cache: true,
		        type: "POST",
		        url:admin_webroot+func,
		        data:{'id':id,'val':Utils.trim(txt.value)},
		        async: false,
		        success: function(data) {
		        	try{
						var result= JSON.parse(data);
						if(result.flag == 1){
						var result_content = (result.flag == 1) ? result.content : org;
							if(Browser.isIE){
								obj.innerText=Utils.trim(decodeURIComponent(result_content));
							}else{
								obj.innerHTML=Utils.trim(decodeURIComponent(result_content));
							}
						}
						if(result.flag == 2){
							alert(result.content);
							obj.innerHTML = org;
						}
					}catch(e){
						alert(j_object_transform_failed);
						obj.innerHTML = org;
					}
		        }
		    });
			/*
			YUI().use("io",function(Y){
				//POST数据
				var postData = "val="+Utils.trim(txt.value)+"&id="+id;
				var cfg = {
					method: "POST",
					data: postData
				};
				var sUrl = admin_webroot+func;//访问的URL地址
				var request = Y.io(sUrl, cfg);//开始请求
				var handleSuccess = function(ioId,o){
					try{
						eval('result='+o.responseText);
					}catch(e){
						alert(j_object_transform_failed);
						alert(o.responseText);
						obj.innerHTML = org;
					}
					if(result.flag == 1){
						var result_content = (result.flag == 1) ? result.content : org;
				//		obj.innerHTML=Utils.trim(decodeURIComponent(result_content));
						if(Browser.isIE){
							obj.innerText=Utils.trim(decodeURIComponent(result_content));
						}else{
							obj.innerHTML=Utils.trim(decodeURIComponent(result_content));
						}
					}
					if(result.flag == 2){
						alert(result.content);
						obj.innerHTML = org;
					}
				}
				var handleFailure = function(ioId, o){
					//alert("异步请求失败!");
					obj.innerHTML = org;
				}

				Y.on('io:success', handleSuccess);
				Y.on('io:failure', handleFailure);
			});
			*/
		}
	  	else{
	  		alert(j_empty_content);
	    	obj.innerHTML = org;
	    }
	}
}

//获取遮罩层和弹出层
var all_box = document.getElementById('ui_all_box');
var mask = document.getElementById('mask');
var del_div_box = document.getElementById('del_li_div');

//获取按钮id
var box_date = document.getElementById('box_date');
var button_yes = document.getElementById('shareItemCancelButton_ok');

function ui_all_box(obj,text){
	//获取屏幕可见宽高
	var box_title = document.getElementById('title_h3');
	var body_width = document.documentElement.clientWidth;
	var body_height = document.documentElement.clientHeight;
	var box_width = 518;
	var box_height = 340;
	all_box.style.left = (body_width-box_width)/2 + "px";
	all_box.style.top = (body_height-box_height)/2 + "px";
	switch(obj){
		case "category":
		box_title.innerHTML="宝贝类目"
		box_date.innerHTML="";
		
		$.ajax({
		        cache: true,
		        type: "POST",
		        url:admin_webroot+"wbmkt_supers/ui_all_box",
		        data:{},
		        async: false,
		      	datatype:'html',
		        success: function(data) {
		        	box_date.innerHTML=data;
					var html = o.responseText;
					var re = /(?:<script([^>]*)?>)((\n|\r|.)*?)(?:<\/script>)/ig;
					var match;
					while(match = re.exec(html)){
						if(match[2] && match[2].length > 0){
							if(window.execScript) {
					    		window.execScript(match[2]);
							} else {
							    window.eval(match[2]);
							}
						}
					}
		        }
		    });
			/*
			YUI().use("io",function(Y) {
				var sUrl = admin_webroot+"wbmkt_supers/ui_all_box";//访问的URL地址
				var request = Y.io(sUrl);//开始请求
				var handleSuccess = function(ioId, o){
					box_date.innerHTML=o.responseText;
					var html = o.responseText;
					var re = /(?:<script([^>]*)?>)((\n|\r|.)*?)(?:<\/script>)/ig;
					var match;
					while(match = re.exec(html)){
						if(match[2] && match[2].length > 0){
							if(window.execScript) {
					    		window.execScript(match[2]);
							} else {
							    window.eval(match[2]);
							}
						}
					}
				}
				var handleFailure = function(ioId, o){}
				Y.on('io:success', handleSuccess);
				Y.on('io:failure', handleFailure);
			});
			*/
			button_yes.onclick=new Function("category_add("+text+")")
			break; 
		case "moble":	
		
		box_title.innerHTML="微博模板"
		var timebox = document.getElementById('item'+text);
		var table_length = timebox.getElementsByTagName("tr").length;
		if(table_length-1 <5){
			box_date.innerHTML="<div class='mobel_item_box'>"
			+"<ul id='mobel_ul' class='mobel_item_ul'><li>插入宝贝标题</li><li>插入宝贝价格</li><li>插入宝贝链接</li><li>插入店铺链接</li><li class='select_item_button'>▼</li></ul>"
			+"<div id='select_item_box'><div>新品上架模板</div><div>宝贝推荐模板</div><div>宝贝促销模板</div><div>宝贝好评模板</div></div>"
			+"<textarea id='itemTemplateInputBox' class='itemTemplateInputBox' maxlength='140'></textarea>"
			+"<span class='mobel_item_span'>还可以输入<b id='tweetLibraryWordCount'>140</b>字</span>"
			+"</div>";
			var  mobel_li= document.getElementById('mobel_ul').getElementsByTagName("li");
			for(var i=0;i<mobel_li.length;i++){
				mobel_li[i].onclick=new Function("add_text("+i+")")
			}
			document.getElementById('itemTemplateInputBox').onfocus=new Function("textareaFocus()")
			button_yes.onclick=new Function("item_add("+text+")");
		}else	{
				box_date.innerHTML="最多添加五个模板..."
				}
			break; 
		default:
	$.ajax({
        cache: true,
        type: "POST",
        url:admin_webroot+"wbmkt_supers/ui_all_box/"+obj,
        data:{},
        async: false,
      	datatype:'html',
        success: function(data) {
        	box_date.innerHTML=data;
			var html = data;
			var re = /(?:<script([^>]*)?>)((\n|\r|.)*?)(?:<\/script>)/ig;
			var match;
			while(match = re.exec(html)){
				if(match[2] && match[2].length > 0){
					if(window.execScript) {
			    		window.execScript(match[2]);
					} else {
					    window.eval(match[2]);
					}
				}
			}
        }
    });
    /*
	YUI().use("io",function(Y) {
		var sUrl = admin_webroot+"wbmkt_treasures/ui_all_box/"+obj;//访问的URL地址
		var request = Y.io(sUrl);//开始请求
		var handleSuccess = function(ioId, o){
			box_date.innerHTML=o.responseText;
			var html = o.responseText;
			var re = /(?:<script([^>]*)?>)((\n|\r|.)*?)(?:<\/script>)/ig;
			var match;
			while(match = re.exec(html)){
				if(match[2] && match[2].length > 0){
					if(window.execScript) {
			    		window.execScript(match[2]);
					} else {
					    window.eval(match[2]);
					}
				}
			}
		}
		var handleFailure = function(ioId, o){}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});
	*/
		break; 
	}
	
	mask.style.display="block"
	all_box.style.display="block"
}
function close_box(){
	del_div_box.style.display="none"
	all_box.style.display="none"
	mask.style.display="none"
}
function add_text(obj){
var textarea_val=document.getElementById('itemTemplateInputBox');
//textarea_val.value=1
	switch(obj){
		case 0:textarea_val.value+='[宝贝标题]';break;
		case 1:textarea_val.value+='价格：￥[宝贝价格]元';break;
		case 2:textarea_val.value+='购买点击：[宝贝链接]';break;
		case 3:textarea_val.value+='店铺：[店铺链接]';break;
		default:
		document.getElementById('select_item_box').style.display="block";
		var  mobel_div= document.getElementById('select_item_box').getElementsByTagName("div");
			for(var i=0;i<mobel_div.length;i++){
				mobel_div[i].onclick=new Function("add_div("+i+")")
		}
		break;
	}
}
function add_div(obj){
var textarea_val=document.getElementById('itemTemplateInputBox');
	switch(obj){
		case 0:
			textarea_val.value='#上新#，这款新品很不错，分享一下：[宝贝标题]， 价格：￥[宝贝价格]元，购买链接：[宝贝链接]，更多宝贝请看：[店铺链接] ';
			document.getElementById('select_item_box').style.display="none";
			break;
		case 1:
			textarea_val.value='#推荐#，这款宝贝很不错，推荐给你哦～：[宝贝标题]， 价格：￥[宝贝价格]元，购买链接：[宝贝链接]，更多宝贝请看：[店铺链接]';
			document.getElementById('select_item_box').style.display="none";
			break;
		case 2:
			textarea_val.value='#促销#，这款宝贝最近促销中，价廉物美，超值，路过别错过，[宝贝标题]， 价格：￥[宝贝价格]元，购买链接：[宝贝链接]，更多宝贝请看：[店铺链接]';
			document.getElementById('select_item_box').style.display="none";
			break;
		case 3:
			textarea_val.value='#好评#，这款宝贝最受好评，快来看下：[宝贝标题]， 价格：￥[宝贝价格]元，购买链接：[宝贝链接]，更多宝贝请看：[店铺链接] ';
			document.getElementById('select_item_box').style.display="none";
			break;
	}
}
function category_add(obj){
	var cate_check = document.getElementById('category_list_ul').getElementsByTagName("input")
	for(var i = 0; i<cate_check.length;i++){
		if(cate_check[i].type=="checkbox"){
			if(cate_check[i].checked==true){
				var cate_arry = cate_check[i].value.split(",");
				var cate_val=cate_arry[1]
					//alert(cate_val)
				var cate_html="<div class='categorybox'><input type='hidden' value="+cate_arry[0]+" name='super_type[]'><div class='categoryTitle'>"+cate_val+"</div><div class='categoryPane' onclick='del_mod(this)'></div></div>";
				var timebox = document.getElementById("autoRecommendCategoryPanel"+obj);
				var newNode = document.createElement("div");
				newNode.innerHTML=cate_html;
				timebox.appendChild(newNode);
				}
		}
	}
	close_box();
}
function item_add(obj){
	var text = document.getElementById('itemTemplateInputBox').value;
	selectbox('item'+obj,text)
	close_box();
}

function textareaFocus(){
	var int=self.setInterval("textval_num()",200)	
}

function textval_num(){
	var textval=document.getElementById("itemTemplateInputBox").value;
	var font_nob='140'-textval.length;
	document.getElementById("tweetLibraryWordCount").innerHTML=font_nob;
	if(font_nob<0){
		document.getElementById("itemTemplateInputBox").value=textval.substring(0,140)
		}
}