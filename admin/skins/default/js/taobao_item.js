//批量操作
function batch_operations(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var barch_opration_select = document.getElementById("barch_opration_select");
	var shop_select = document.getElementById("shop_id");
	var shop_id = shop_select.options[shop_select.selectedIndex].value;
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="checkboxes[]="+bratch_operat_check[i].value+"&";
		}
	}

	if( postData=="" ){
		alert("请选择商品或分类！");
		return;
	}
	if((barch_opration_select=="batch_top_api_update_items"||barch_opration_select=="batch_update_sellercats")&& shop_id==""){
		alert("请选择店铺！");
		return;
	}
	postData += "shop_id="+shop_id;
	if( barch_opration_select.value==0 ){
		alert("请选择操作类型！");
		return;
	}
	var strsel = barch_opration_select.options[barch_opration_select.selectedIndex].text;
	if(confirm("确定要"+strsel+"吗？")){
		YUI().use("io",function(Y) {
			var sUrl = admin_webroot+"taobao_updates/batch_operations/"+barch_opration_select.value;//访问的URL地址
	        if(barch_opration_select.value=="transferred_classification"){
				sUrl+="?category_id="+document.getElementById("transferred_classification").value;
			}
			var cfg = {
				method: "POST",
				data: postData
			};
			var request = Y.io(sUrl, cfg);//开始请求
			
			var handleSuccess = function(ioId, o){
			//	alert('操作成功');
			//	window.location.href = window.location.href;
			}

			var handleFailure = function(ioId, o){
				alert("异步请求失败!");
			}

			Y.on('io:success', handleSuccess);
			Y.on('io:failure', handleFailure);
		});
	}
}
//淘宝上架
function update_onsale(){
	var start_time=(document.getElementsByName('start_date')[0] && document.getElementsByName('start_date')[0].value) || (document.getElementsByName('start_time')[0] && document.getElementsByName('start_time')[0].value);
	var end_time=(document.getElementsByName('end_date')[0] && document.getElementsByName('end_date')[0].value) || (document.getElementsByName('end_time')[0] && document.getElementsByName('end_time')[0].value);
	var shop_id=document.getElementById('shop_id').value;
	if(start_time>end_time){
		tmp = start_time;
		start_time = end_time;
		end_time = tmp;
	}
	if(start_time==""||end_time==""){
		alert("请选择时间");
	}else if(shop_id==""){
		alert("请选择店铺");
	}else{
		window.location.href="/admin/taobao_updates/taobao_item_onsale_get/"+shop_id+"?start="+start_time+"&end="+end_time;
	}
}
//淘宝下架
function update_soldout(){
	var start_time=(document.getElementsByName('start_date')[0] && document.getElementsByName('start_date')[0].value) || (document.getElementsByName('start_time')[0] && document.getElementsByName('start_time')[0].value);
	var end_time=(document.getElementsByName('end_date')[0] && document.getElementsByName('end_date')[0].value) || (document.getElementsByName('end_time')[0] && document.getElementsByName('end_time')[0].value);
	var shop_id=document.getElementById('shop_id').value;
	if(start_time>end_time){
		tmp = start_time;
		start_time = end_time;
		end_time = tmp;
	}
	if(start_time==""||end_time==""){
		alert("请选择时间");
	}
	else if(shop_id="")
	{
		alert("请选择店铺");
	}else{
		window.location.href="/admin/taobao_updates/taobao_item_soldout/"+shop_id+"?start="+start_time+"&end="+end_time;
	}
}
//京东上架
function update_jingdong_onsale(){
	var start_time=(document.getElementsByName('start_date')[0] && document.getElementsByName('start_date')[0].value) || (document.getElementsByName('start_time')[0] && document.getElementsByName('start_time')[0].value);
	var end_time=(document.getElementsByName('end_date')[0] && document.getElementsByName('end_date')[0].value) || (document.getElementsByName('end_time')[0] && document.getElementsByName('end_time')[0].value);
	if(start_time>end_time){
		tmp = start_time;
		start_time = end_time;
		end_time = tmp;
	}
	if(start_time==""||end_time==""){
		alert("请选择时间");
	}else{
		window.location.href="/admin/jingdong_updates/jingdong_ware_listing_get/?start="+start_time+"&end="+end_time;
	}
}
//京东下架
function update_jingdong_soldout(){
	var start_time=(document.getElementsByName('start_date')[0] && document.getElementsByName('start_date')[0].value) || (document.getElementsByName('start_time')[0] && document.getElementsByName('start_time')[0].value);
	var end_time=(document.getElementsByName('end_date')[0] && document.getElementsByName('end_date')[0].value) || (document.getElementsByName('end_time')[0] && document.getElementsByName('end_time')[0].value);
	if(start_time>end_time){
		tmp = start_time;
		start_time = end_time;
		end_time = tmp;
	}
	if(start_time==""||end_time==""){
		alert("请选择时间");
	}else{
		window.location.href="/admin/jingdong_updates/jingdong_ware_delisting_get/?start="+start_time+"&end="+end_time;
	}
}

//淘宝搜索商品批量导入
function batch_taobao_search_import(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var barch_opration_select = document.getElementById("barch_opration_select");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="checkboxes[]="+bratch_operat_check[i].value+"&";
		}
	}

	if( postData=="" ){
		alert("请选择商品或分类！");
		return;
	}
	if( barch_opration_select.value==0 ){
		alert("请选择操作类型！");
		return;
	}
	var strsel = barch_opration_select.options[barch_opration_select.selectedIndex].text;
	if(confirm("确定要"+strsel+"吗？")){
//		YUI().use("io",function(Y) {
//			var sUrl = admin_webroot+"taobao_item_searches/batch_import/";//访问的URL地址
//			var cfg = {
//				method: "POST",
//				data: postData
//			};
//			var request = Y.io(sUrl, cfg);//开始请求
//			var handleSuccess = function(ioId, o){
//				alert('操作成功');
////				window.location.href = window.location.href;
//			}
//
//			var handleFailure = function(ioId, o){
//				alert("异步请求失败!");
//			}
//
//			Y.on('io:success', handleSuccess);
//			Y.on('io:failure', handleFailure);
//		});
	}
}
//淘宝搜索商品导入
function taobao_search_import(num_iid){
	var postData = "checkboxes="+num_iid;
//		YUI().use("io",function(Y) {
//			var sUrl = admin_webroot+"taobao_item_searches/batch_import/";//访问的URL地址
//			var cfg = {
//				method: "POST",
//				data: postData
//			};
//			var request = Y.io(sUrl, cfg);//开始请求
//			var handleSuccess = function(ioId, o){
//				alert('操作成功');
////				window.location.href = window.location.href;
//			}
//
//			var handleFailure = function(ioId, o){
//				alert("异步请求失败!");
//			}
//
//			Y.on('io:success', handleSuccess);
//			Y.on('io:failure', handleFailure);
//		});
}
//所在地
	function get_citys(id){
		if(id == 0){
			var location_city = document.getElementById('location_city');
			var city_opt = new Option('--请选择--', 0);
			location_city.innerHTML = '';
			location_city.appendChild(city_opt);
			return;
		}
		$.ajax({
			url:admin_webroot+"/taobao_item_types/get_citys/",
			type:"POST",
			data:{id:id},
			dataType:"json",
			success:function(data){
				var location_city = document.getElementById('location_city');
					location_city.innerHTML = "";
					var opt = document.createElement("OPTION");
					opt.value = "";
			        opt.text  = "请选择...";
			        location_city.options.add(opt);
					if(data.taobao_citys){
						for(var i = 0; i < data.taobao_citys.length; i++){
							var opt = document.createElement("OPTION");
							opt.value = data.taobao_citys[i].TaobaoArea.name;
			                opt.text  = data.taobao_citys[i].TaobaoArea.name;
			                location_city.options.add(opt);

						}
					}
		
		}
		});
		
/*		YUI().use("io",function(Y) {
		var sUrl = webroot_dir+"/taobao_item_types/get_citys/";
           var cfg = {
           method: "POST",
           data: 'id='+id
           };
           var request = Y.io(sUrl, cfg);
           var handleSuccess = function(ioId, o){
                try{
                     eval('result='+o.responseText);
                     var location_city = document.getElementById('location_city');
						location_city.innerHTML = "";
						var opt = document.createElement("OPTION");
						opt.value = "";
				        opt.text  = "请选择...";
				        location_city.options.add(opt);
						if(result.taobao_citys){
							for(var i = 0; i < result.taobao_citys.length; i++){
								var opt = document.createElement("OPTION");
								opt.value = result.taobao_citys[i].TaobaoArea.name;
				                opt.text  = result.taobao_citys[i].TaobaoArea.name;
				                location_city.options.add(opt);

							}
						}

                }catch(e){
                     alert("对象转换失败");
                }
           }
           var handleFailure = function(ioId, o){
                alert("异步请求失败");
           }
           Y.on('io:success', handleSuccess);
           Y.on('io:failure', handleFailure);
      });*/
	}

	//分类选择
	function select_cat_tree_click(obj){ //alert(11);
    	document.getElementById("select_cat_tree_inner").className = " loading";
//		YUI().use("io",function(Y) {
//		var sUrl = webroot_dir+"/taobao_item_types/select_cat_get/"+obj.value;
//           var cfg = {
//           method: "POST",
//           data: "id="+obj.value
//           };
//           var request = Y.io(sUrl, cfg);
//           var handleSuccess = function(ioId, o){
//                try{
//                    var node = Y.one('#select_cat_tree_inner');
//					node.set('innerHTML', o.responseText);
//					var html = o.responseText;
//					var re = /(?:<script([^>]*)?>)((\n|\r|.)*?)(?:<\/script>)/ig;
//					var srcRe = /\ssrc=([\'\"])(.*?)\1/i;
//					var typeRe = /\stype=([\'\"])(.*?)\1/i;
//					var match;
//					while(match = re.exec(html)){
//						if(match[2] && match[2].length > 0){
//					          if(window.execScript) {
//					                  window.execScript(match[2]);
//					          } else {
//					                  window.eval(match[2]);
//					          }
//					    }
//
//					 }
//					 document.getElementById("select_cat_tree_inner").className = " ";
//                }catch(e){
//                     alert("对象转换失败");
//                }
//           }
//           var handleFailure = function(ioId, o){
//                alert("异步请求失败");
//           }
//           Y.on('io:success', handleSuccess);
//           Y.on('io:failure', handleFailure);
//      });
	}

		//属性模块
	function itemprops_change(pid,obj){
	//	alert(obj.value);
		if(obj.value=="-1"){
			document.getElementById("itemprops_input["+pid+"]").style.display = "block";
			document.getElementById("itemprops_pid_vid"+pid).innerHTML = "";
		}else{
			document.getElementById("itemprops_input["+pid+"]").style.display = "none";
			if(obj.value!=""&& pid!=""){
				var pid_vid=document.getElementById("pid-vid2").value;
				if(pid_vid==""){
					document.getElementById("pid-vid2").value=pid+":"+obj.value;
				}else{
					var rs=pid_vid.indexOf(pid);
					if(rs>=0){ 
						document.getElementById("pid-vid2").value=pid+":"+obj.value;
					}
				}
				var pid_vid=document.getElementById("pid-vid2").value;
				var sel=document.getElementById('cid1').parentNode.children.length;
				var cid=document.getElementById('cid'+sel).value;
				document.getElementById("itemprops_pid_vid"+pid).className = " loading";
//				YUI().use("io",function(Y) {
//				var sUrl = webroot_dir+"/taobao_item_types/prop_vid_get/"+pid+"/"+obj.value+"/"+cid;
//		           var cfg = {
//		           method: "POST",
//		           data: "pid_vid="+pid_vid
//		           };
//		           var request = Y.io(sUrl, cfg);
//		           var handleSuccess = function(ioId, o){
//		                try{ 
//		                    var node = Y.one('#itemprops_pid_vid'+pid);
//							node.set('innerHTML', o.responseText);
//							var html = o.responseText;
//							var re = /(?:<script([^>]*)?>)((\n|\r|.)*?)(?:<\/script>)/ig;
//							var srcRe = /\ssrc=([\'\"])(.*?)\1/i;
//							var typeRe = /\stype=([\'\"])(.*?)\1/i;
//							var match;
//							while(match = re.exec(html)){
//								if(match[2] && match[2].length > 0){
//							          if(window.execScript) {
//							                  window.execScript(match[2]);
//							          } else {
//							                  window.eval(match[2]);
//							          }
//							    }
//
//							 }
//							document.getElementById("itemprops_pid_vid"+pid).className = " ";
//		                }catch(e){
//		                     alert("对象转换失败");
//		                }
//		           }
//		           var handleFailure = function(ioId, o){
//		                alert("异步请求失败");
//		           }
//		           Y.on('io:success', handleSuccess);
//		           Y.on('io:failure', handleFailure);
//		      });
		   }
		}
	}

	//显示属性的子属性
	function show_son_props(pid,obj){
      //alert(pid+"-"+obj.value);      
       var test=new Array();
       var t=document.getElementsByTagName("tr");
       	for(i=0;i<t.length;i++){
  			if(t[i].id!=""){
  				var s = t[i].id;
  				var n = s.indexOf("-");
  				var s1=s.substring(0,n);
 			 	if(s1==pid){
 			 		if(s==pid+"-"+obj.value){
 			 			t[i].style.display="";  //alert(s);
 			 		}
 			 		else{
 			 			t[i].style.display="none";
 			 			t[i].cells[1].childNodes[0].value="";
 			 		}
 			 	}
		  	}
		}
		
	}
	//淘宝商品类目检测
	function taobao_itemtype_check(){
		if(document.getElementById("itemtype_name").value==""){
			alert("商品类型名称不能为空！！");
			return false;
		}
		if(document.getElementById("location_state").value==0){
			alert("请选择省份！！");
			return false;
		}
		if(document.getElementById("location_city").value==0){
			alert("请选择城市！！");
			return false;
		}
		if(document.getElementById("cid").value ==0){
			alert("请选择宝贝类目！！");
			return false;
		}
		if(isNaN(document.getElementById("auction_point").value)||document.getElementById("auction_point").value==0){
			alert("请输入正确的返点比例！！");
			return false;
		}
		for (i=0; i<test.length; i++)
	    {
//	    	var pid = document.getElementById(test[i]).value;
//	    	if (pid==""){
//	        	var item_pid=1;
//	        	alert("信息填写不完整！！");
//	        	return false;
//	            break;
//        	}
	    	var pid_check= document.getElementsByName(test[i]);
	    	//alert(test[i]);
	    	if(pid_check.length==1){
	    		var pid = document.getElementById(test[i]).value;
		    	if (pid==""){
		        //	var item_pid=0;
		        	alert("信息填写不完整！！");
		        	return false;
		            break;
	        	}
	    	}else{
	    		var item_pid=0;
		    	for(j=0; j<pid_check.length; j++){
		    		var type_pid=pid_check[j].checked;
		    		//alert(type_pid);
		    		if (type_pid==true){
			        	var item_pid=1;
			        	break;
	        		}
		    	}
		    	//alert(item_pid);
		    	if(item_pid!=1){
		    		alert("信息填写不完整！！");
			        return false;
			        break;
		    	}
	    	}
	    }
		var type = document.getElementsByName("data[TaobaoItem][freight_payer]");
	    var item_type = 0;
	    for (i=0; i<type.length; i++)
	    {
	        if (type[i].checked)
	        {
	        	if(type[i].value=='buyer')
	        	var item_type=1;
	            break;
	        }
	    }
		if(item_type==1){
			if(document.getElementById("postage_id").value==0){
				if(isNaN(document.getElementById("taobao_item_post_fee").value)||document.getElementById("taobao_item_post_fee").value==0){
					alert("请输入正确的平邮费用");
					return false;
				}
				if(isNaN(document.getElementById("taobao_item_express_fee").value)||document.getElementById("taobao_item_express_fee").value==0){
					alert("请输入正确的快递费用");
					return false;
				}
				if(isNaN(document.getElementById("taobao_item_ems_fee").value)||document.getElementById("taobao_item_ems_fee").value==0){
					alert("请输入正确的EMS费用");
					return false;
				}
			}
		}
	}