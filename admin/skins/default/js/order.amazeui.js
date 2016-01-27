/* Write your js */

//order
//搜索用户
function search_user(){
	var obj = document.getElementById('search_user_button');
	obj.className += " disablebtn";
	//document.getElementById('user_discount').innerHTML="";
	var keywords=document.getElementById("opener_select_user_name").value;
//	if(keywords==""){
//		alert("请填写内容！");
//		return;
//	}
	var postData = "keywords="+keywords;
	var sUrl = admin_webroot+"users/order_search_user_information/";//访问的URL地址
	  $.ajax({ url: sUrl,
		   type:"POST",
		   dataType:"json", 
		   data: { 'keywords': keywords },
		   success: function(data){
		   	  $("#search_user_infos").css("display","inline");
			  var sel = document.getElementById('search_user_infos');
			  sel.innerHTML = "";
			  if (data.message){
 				if(data.message.length==0){
 				 	document.getElementById("search_user_infos").className = "selecthide";
 				 	document.getElementById('user_info').innerHTML='匿名用户';
 				 	document.getElementById('opener_select_user_id').value="";
					alert("没有找到相关数据！");
	         		obj.className="am-btn am-btn-success am-btn-sm";
					edit_order_user();
	         		return;
				}
			 	if(data.message.length==1){
			 		var m=data.message[0]['User'].id+'-'+(data.message[0]['User'].user_sn==null?data.message[0]['User'].name:data.message[0]['User'].user_sn)+'-'+data.message[0]['User'].mobile;
					select_user(m);
					return;
				}
				var opt = document.createElement("OPTION");
				opt.value = "";
				opt.text = j_please_select;
				sel.options.add(opt);
	            for (i = 0; i < data.message.length; i++ ){
	            	if(data.message[i]['User'].mobile=="null"){
	             		data.message[i]['User'].mobile="";
	             	}
	                var opt = document.createElement("OPTION");
	                if(data.message[i]['User'].consignee!=""){
	           			opt.value = data.message[i]['User'].id+'-'+data.message[i]['User'].user_sn+'+'+data.message[i]['User'].consignee+'-'+data.message[i]['User'].mobile;
	                }else{
	              		opt.value = data.message[i]['User'].id+'-'+data.message[i]['User'].user_sn+'-'+data.message[i]['User'].mobile;
	                }
	                if(data.message[i]['User'].user_sn!=""&&data.message[i]['User'].user_sn!=null){
	                  	//  opt.value = result.message[i]['User'].id+'-'+result.message[i]['User'].name+'+'+result.message[i]['User'].user_sn+'-'+result.message[i]['User'].mobile;;
	                	opt.text  = data.message[i]['User'].name+'+'+data.message[i]['User'].user_sn;
	                }else{
	                	opt.text  = data.message[i]['User'].name;
	                }
	                if(data.message[i]['User'].mobile!=""){
	                	opt.text = opt.text +"-"+data.message[i]['User'].mobile;
	                }
	                if(data.message[i]['User'].email!=""){
	                	opt.text = opt.text +"-"+data.message[i]['User'].email;
	            	}
	                sel.options.add(opt);
	            }
	            document.getElementById("search_user_infos").className = "";
	         }
		     if(document.getElementById('result')){
			   document.getElementById('result').className="";
			 }
		   }
	  });
}
function select_user(value){
//	alert(value);
	if(value==""){
		document.getElementById('opener_select_user_id').value="";
	//	document.getElementById('opener_select_user_name').value="";
		document.getElementById('order_consignee_span').innerHTML="";
		document.getElementById('order_mobile').value="";
		document.getElementById('order_mobile_span').innerHTML="";
		return;
	}
	var userInfo=value.split("-");
	document.getElementById('opener_select_user_id').value=userInfo[0];
	//document.getElementById('opener_select_user_name').value=userInfo[1];
	var userNames=userInfo[1].split("+");
	document.getElementById('user_info').innerHTML=userNames[0];
	document.getElementById('order_consignee_span').innerHTML=userNames[0];
	document.getElementById('order_consignee').value= (userNames[1])?userNames[1]:userNames[0];
	document.getElementById('order_mobile').value=userInfo[2];
	document.getElementById('order_mobile_span').innerHTML=userInfo[2];
	var order_shipping_id = document.getElementById("order_shipping_id").value;//配送方式
	
	$("#search_user_infos").hide();
	order_user_data_save();
}
function sendinfo(m) {
	var tmpselect = document.getElementById("order_shipping_id").value;
	var tmptable = document.getElementById("order_address_info_table");
	if(tmpselect=="1"){
		$("#order_address_info_table tr.order_user_address_edit").hide();
	}else{
		$("#order_address_info_table tr.order_user_address_edit").show();
	}
	if(m!=0){
		edit_order_address();
	}
	get_ship_logistics_companies();
}
//编辑订单地址相关信息
function edit_order_address(){ 
  $("#order_consignee").css("display","inline");
  $("#country_select").css("display","inline");
  $("#province_select").css("display","inline");
  $("#city_select").css("display","inline");
  $("#order_best_time").css("display","inline");
  $("#select_best_time").css("display","inline");
  $("#order_how_oos").css("display","inline");
  $("#select_how_oos").css("display","inline");
  $("tr.order_user_address_edit.address_save").css("display",'table-row');
  $(".address").removeClass('address');
  $(".address_span").css("display",'none');
  //alert("show");
}
//获取配送方式下的物流公司 chenfan 2012/4/23
function get_ship_logistics_companies(){
	var id = $("#order_shipping_id").val();
	var sUrl = admin_webroot+"shippingments/get_ship_logistics_companies/";//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"json", 
		data: { 'id': id },
		success: function(data){
			if(data.flag==1){
				var sel = document.getElementById('order_logistics_company_id');
				if(document.getElementById("logistics_company_id")!=null){
					var logistics_company_id = document.getElementById('logistics_company_id').value;
				}else{
					var logistics_company_id = '';
				}
				sel.innerHTML = "";
				var opt = document.createElement("OPTION");
				opt.value = "";
				opt.text = j_order_logistics;
				sel.options.add(opt);
				if(data.lc_infos!=""){
					$.each(data.lc_infos,function(v,k){
						var opt = document.createElement("OPTION");
			        	opt.value = k['LogisticsCompany']['id'];
			        	if(logistics_company_id==opt.value){
			        		opt.selected="selected";
			        	}
			        	opt.text  = k['LogisticsCompany']['name'];
						sel.options.add(opt);
					})
					//document.getElementById('sub_pay').style.display="inline";
				}
		    }
		}
	});
}
//订单的用户相关修改 chenfan 2012/3/13---2014/12/18替换为ajax
function order_user_data_save(){
	var order_id = document.getElementById("order_id").value;
	var order_user_id = document.getElementById("opener_select_user_id").value;
	var order_shipping_id = document.getElementById("order_shipping_id").value;//配送方式
	var postData = "order_id="+order_id+"&order_user_id="+order_user_id+"&type=user"+"&order_shipping_id="+order_shipping_id;
	var sUrl = admin_webroot+"orders/order_address_data_save/?"+postData;//访问的URL地址
	//alert("a");
	$.ajax({ url: sUrl,
		dataType:"html", 
		type:"GET",
//		data: { 'order_id': order_id,'order_user_id': order_user_id ,'type': "user",'order_shipping_id': order_shipping_id},
		context: $("#order_user_info"),
		success: function(data){
			//alert("change user");
			$("#order_user_info").html(data);
			//document.getElementById("order_address_info_div").innerHTML=data;
			var html = data;
			var re = /(?:<script([^>]*)?>data)((\n|\r|.)*?)(?:<\/script>)/ig;
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
			sendinfo(0);
		}
	});
}
//订单的地址相关修改 chenfan 2012/2/10
function order_address_data_save(){
	$("#order_address_data_save").attr("disabled",true);
	var order_id = document.getElementById("order_id").value;
	if(document.getElementById("opener_select_user_id"))
	var order_user_id = document.getElementById("opener_select_user_id").value;
	//收货人信息
	var order_telephone = document.getElementById("order_telephone").value;//电话
	var order_consignee = document.getElementById("order_consignee").value;//收货人
	var order_mobile = document.getElementById("order_mobile").value;//手机
	var order_shipping_id = document.getElementById("order_shipping_id").value;//配送方式
	var sel_address = document.getElementById("sel_address").value;//是否选择地址
	var user_address_id="";//用户地址簿id
	if(sel_address!=""){
	  user_address_id=user_address_obj[sel_address].UserAddress.id;
	}
	var order_country = "";
	var order_province = "";
	var order_city = "";
	if(order_consignee==""){
		$("#order_address_data_save").attr("disabled",false);
		alert(j_consignee_not_empty);return;
	}
	var search_user_infos=document.getElementById("search_user_infos").innerHTML;
	if(search_user_infos==""){
		if(order_mobile=="" && order_telephone==""){
			$("#order_address_data_save").attr("disabled",false);
			alert(j_please_enter_phone);return;
		}
		if(order_mobile!=""){
			if(!/^1[3-9]\d{9}$/.test(order_mobile)){
				$("#order_address_data_save").attr("disabled",false);
				alert(j_phone_error);return;
			}
		}
	}
	if(order_shipping_id!=1){
		if(document.getElementById("country_select")&&document.getElementById("address_select_span").className=='order_status'){
			if(document.getElementById("country_select").value==""){
				alert(j_please_country);
				$("#order_address_data_save").attr("disabled",false);
				return;
			}
			if(document.getElementById("province_select").value==""){
				alert(j_please_province);
				$("#order_address_data_save").attr("disabled",false);
				return;
			}
			if(document.getElementById("city_select")&&document.getElementById("city_select").value==""){
				alert(j_please_city);
				$("#order_address_data_save").attr("disabled",false);
				return;
			}
			var country=document.getElementById("country_select").selectedIndex;
			var province=document.getElementById("province_select").selectedIndex;
			order_country = document.getElementById("country_select").options[country].text ;//国家
			order_province = document.getElementById("province_select").options[province].text ;//省
			if(document.getElementById("city_select")){
				var city=document.getElementById("city_select").selectedIndex;
				order_city = document.getElementById("city_select").options[city].text ;//市
			}
		}else{
			
			order_country = $("#country_select").find("option:selected").text();//国家
			order_province = $("#province_select").find("option:selected").text();//省
			order_city = $("#city_select").find("option:selected").text();//市
		}
	}
	//var order_district = document.getElementById("order_district").value;//区
	//alert(order_country+" "+order_province+" "+order_city);
	var order_district = '';//区
	var order_sign_building = document.getElementById("order_sign_building").value;//标致性建筑
	var order_address = document.getElementById("order_address").value;//地址
	var order_best_time = document.getElementById("order_best_time").value;//最佳送货时间
	var order_how_oos = document.getElementById("order_how_oos").value;//缺货处理
	var order_zipcode = document.getElementById("order_zipcode").value;//邮编
	var order_note = document.getElementById("order_note").value;//备注
	if(document.getElementById("order_postscript")==null){
		var order_postscript='';
	}else{
		var order_postscript = document.getElementById("order_postscript").value;//备注
	}
	var order_email = document.getElementById("order_email").value;//电子邮件

	var sUrl = admin_webroot+"orders/order_address_data_save/address?";//访问的URL地址

	if(document.getElementById("opener_select_user_id"))
	 sUrl += "order_id="+order_id+"&order_user_id="+order_user_id;
	else
	 sUrl += "order_id="+order_id;
	//收货人信息
	sUrl+="&order_telephone="+order_telephone+"&order_consignee="+order_consignee+"&order_mobile="+order_mobile;
	sUrl+="&order_country="+order_country+"&order_province="+order_province+"&order_city="+order_city+"&order_district="+order_district;
	sUrl+="&order_sign_building="+order_sign_building+"&order_address="+order_address+"&order_best_time="+order_best_time+"&order_how_oos="+order_how_oos;
	sUrl+="&order_zipcode="+order_zipcode+"&order_note="+order_note+"&order_postscript="+order_postscript+"&order_email="+order_email+"&order_shipping_id="+order_shipping_id+"&sel_address="+sel_address+"&user_address_id="+user_address_id;
	
	$.ajax({ url: sUrl,
		dataType:"html", 
		type:"GET",
		context: $("#order_address_info_table"),
		success: function(data){
			$("#order_address_info_table").html(data);
			//document.getElementById("order_address_info_div").innerHTML=data;
			var html = data;
			var re = /(?:<script([^>]*)?>data)((\n|\r|.)*?)(?:<\/script>)/ig;
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
			sendinfo(0);
			$("#order_address_data_save").attr("disabled",false);
		}
	});
}

//2012/2/20 银行转账，pos机：下选银行
//f1
function add_sub_pay(id){
	pv = document.getElementById(id).value;
	var sUrl = admin_webroot+"orders/get_sub_pay/";//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"json", 
		data: { 'id': pv},
		success: function(data){
			if(data.cd == 0 && data.ps.length > 0){
				var sel = document.getElementById('sub_pay');
				sel.innerHTML = "";
				var opt = document.createElement("OPTION");
				opt.value = "";
				opt.text = j_please_select;
				sel.options.add(opt);
				for (i = 0; i < data.ps.length; i++ ){
			    	var opt = document.createElement("OPTION");
			        opt.value = data.ps[i].id;
			        opt.text  = data.ps[i].value;
					sel.options.add(opt);
				}
				document.getElementById('sub_pay').style.display="inline";
			}else if(data.cd == 2 || data.ps.length <= 0){
				var sel = document.getElementById('sub_pay');
				sel.innerHTML = "";
				document.getElementById('sub_pay').style.display="none";
				//Y.one('#sub_pay').setStyle('display','none');
			}
			else{
				alert(j_failed_order_update);
			}
		}
	});
}
//地址簿的选择
function select_user_address_change(select_value){
	if(select_value==""){
		//document.getElementById("address_input_span").className='order_status';
		document.getElementById("address_select_span").className='';
		return;
	}
	document.getElementById("order_telephone").value = user_address_obj[select_value].UserAddress.telephone;
	document.getElementById("order_consignee").value = user_address_obj[select_value].UserAddress.consignee;
	document.getElementById("order_mobile").value = user_address_obj[select_value].UserAddress.mobile;
//	if(user_address_obj[select_value].UserAddress.country!=0){
//		getRegions(user_address_obj[select_value].UserAddress.country,"country");
//		getRegions(user_address_obj[select_value].UserAddress.province,"province");
//		
//
//	}

//	if(document.getElementById("address_input_span").className=='order_status'){
//		document.getElementById("address_input_span").className='';
//		document.getElementById("address_select_span").className='order_status';
//	}
	var country="";
	var country_id="";
	if(regions_info[user_address_obj[select_value].UserAddress.country]==undefined){
		country ='';
	}else{
		country = regions_info[user_address_obj[select_value].UserAddress.country];
		country_id=user_address_obj[select_value].UserAddress.country;
	}
	var province="";
	var province_id="";
	if(regions_info[user_address_obj[select_value].UserAddress.province]==undefined){
		province ='';
	}else{
		province = regions_info[user_address_obj[select_value].UserAddress.province];
		province_id=user_address_obj[select_value].UserAddress.province;
	}
	var city="";
	if(regions_info[user_address_obj[select_value].UserAddress.city]==undefined){
		city ='';
	}else{
		city = regions_info[user_address_obj[select_value].UserAddress.city];
	}
	getRegions(0,'',country);
	getRegions(country_id,'country',province);
	getRegions(province_id,'province',city);
//	if(user_address_obj[select_value].UserAddress.country==undefined){
//		document.getElementById("order_country2").value ='';
//	}else{
//		document.getElementById("order_country2").value = user_address_obj[select_value].UserAddress.country;
//	}
//	document.getElementById("order_province2").value = user_address_obj[select_value].UserAddress.province;
//	document.getElementById("order_city2").value = user_address_obj[select_value].UserAddress.city;
	//document.getElementById("order_district").value = user_address_obj[select_value].UserAddress.district;
	document.getElementById("order_sign_building").value = user_address_obj[select_value].UserAddress.sign_building;
	document.getElementById("order_address").value = user_address_obj[select_value].UserAddress.address;
	document.getElementById("order_best_time").value = user_address_obj[select_value].UserAddress.best_time;
	document.getElementById("order_zipcode").value = user_address_obj[select_value].UserAddress.zipcode;
	document.getElementById("order_email").value = user_address_obj[select_value].UserAddress.email;
	document.getElementById("order_zipcode").value = user_address_obj[select_value].UserAddress.zipcode;
		if(user_address_obj[select_value].UserAddress.province!=0){
			//alert(user_address_obj[select_value].UserAddress.province);
			//set_address(select_value);
		}
}
function set_address(select_value){
	$("#country_select").val(user_address_obj[select_value].UserAddress.country);
	$("#province_select").val(user_address_obj[select_value].UserAddress.province);
	$("#city_select").val(user_address_obj[select_value].UserAddress.city);
}
//获取地址
function getRegions(id,region,sel_value){
	if(sel_value=="undefined"){
		sel_value="";
	}
	if(region=="country"&&id==""){
		$('#province_select').addClass('order_status');
		$('#city_select').addClass('order_status');
		return;
	}
	if(region=="province"&&id==""){
		$('#city_select').addClass('order_status');
		return;
	}
	var sUrl = admin_webroot+"orders/getRegions/";//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"json", 
		data: { 'id': id},
		success: function(data){
			if(data.region.length==0){
				return;
			}
			if(region=="country"){
				$('#province_select').removeClass('order_status');
				var sel = document.getElementById('province_select');
			}else if(region=="province"){
				$('#city_select').removeClass('order_status');
				var sel = document.getElementById('city_select');
			}else if(region==""){
				 if(document.getElementById('country_select')){
				 var sel = document.getElementById('country_select');
				}else{
				 return;
				}
			}else{
				return ;
			}

			sel.options.length = 0;
			var opt = document.createElement("OPTION");
			opt.value = "";
			
			opt.text = j_please_select;
			sel.options.add(opt);
			for (i = 0; i < data.region.length; i++ ){
		    	var opt = document.createElement("OPTION");
		        opt.value = data.region[i]['Region'].id;
		        opt.text  = data.region[i]['RegionI18n'].name;
			    if(data.region[i]['RegionI18n'].name==sel_value){
		        	opt.selected=true;
		        }
				sel.options.add(opt);	
		    }
			if(typeof $("#sel_address").val()!="undefined" && $("#sel_address").val()!=""){
				set_address($("#sel_address").val());
			}
		}
	});
}
function search_order_product(){
	
	var obj = document.getElementById("add_product_button");
	var keywords=Trim(document.getElementById("order_product").value);
	if(keywords.replace(/([\u0391-\uFFE5])/ig,'11').length<3){
		$("#my-popup .am-popup-bd").html(j_keyword_three);
		return;
	}
	$("#load_div").removeClass('order_status');
	var brand="";
	var product_style="";
	var product_type="";
	if(document.getElementById("product_brand")&&document.getElementById("product_brand").value!=""){
		brand=document.getElementById("product_brand").value;
	}
	if(document.getElementById("product_type")&&document.getElementById("product_type").value!=""){
		product_type=document.getElementById("product_type").value;
	}
	var postData = {'keywords': keywords,'brand':brand,'product_style':product_style,'product_type':product_type};
	var sUrl = admin_webroot+"orders/search_order_product/";//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"json", 
		data: postData,
		success: function(data){
				$("#my-popup .am-popup-bd").html("");
			 if (data.message){
				if(data.message.length==0){
					//alert('没有搜到相关商品！');
					$("#my-popup .am-popup-bd").html(rebate_055);
					return;
				}
				var table ="<table class='am-table'>";
				table+="<tr><td>图片</td><td>名称</td><td>货号</td><td>价格</td><td>操作</td></tr>";
	            for (i = 0; i < data.message.length; i++ ){
	                table+="<tr><td><img width='40' height='40' src="+data.message[i]['Product'].img_thumb+"></td><td><div class='popup_pro_code'>"+data.message[i]['Product'].code+"</div></td>"
					table+="<td><div class='popup_pro_name'>"+data.message[i]['ProductI18n'].name+"</div></td><td>"+data.message[i]['Product'].shop_price+"</td>";
					var data_am_modal_txt="";
					if(document.getElementById("opener_select_user_id")){
						var user_id=$("#opener_select_user_id").val();
						if(user_id!=""&&data.message[i]['Product'].is_sku==1){
							data_am_modal_txt=" data-am-modal=\"{target: '#update_pro_attr'}\"";
						}
					}
					table+="<td><button "+data_am_modal_txt+" onclick='add_order_product(\""+data.message[i]['Product'].code+"\",\""+data.message[i]['Product'].is_sku+"\",\""+data.message[i]['Product'].id+"\")' class='am-btn am-btn-success am-btn-sm'>添加</button></td></tr>";
	            }
				table+="</table>";
				$("#my-popup .am-popup-bd").html(table);
	         }
			$("#load_div").addClass('order_status');
		}
	});
}
//添加订单商品
function add_order_product(order_product_code,is_sku,order_product_id){
	var order_id = document.getElementById("order_id").value;//订单ID
	//var order_product_code = document.getElementById("order_product_code").value;
	if(order_product_code==""){
		alert(j_enter_sku);
		return;
	}
	if(is_sku==1){
		$(".am-close").click();
		update_pro_attr(order_product_code,order_product_id);
		return;
	}
	var postData = {"order_id":order_id,"order_product_code":order_product_code,"order_product_id":order_product_id}
	var sUrl = admin_webroot+"orders/add_order_product/";//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"json", 
		data: postData,
		success: function(data){
			if(data.code==1){
				$('#result').addClass('order_status');
				order_reflash(data.hasproduct,data.total,data.need_pay);
				//alert(result.message);
				$(".am-close").click();
			}
			else{
				alert(j_failed_order_update);
			}
		}
	});
}
//删除订单商品
function delete_order_product(order_product_id,order_product_code){
	var order_id = document.getElementById("order_id").value;//订单ID
	if(confirm(j_delete_product_name+" "+order_product_code+" ?")){
		var sUrl = admin_webroot+"orders/delete_order_product/"+order_id+"/"+order_product_id;//访问的URL地址
		$.ajax({ url: sUrl,
			type:"POST",
			dataType:"json", 
			success: function(data){
				order_reflash(data.hasproduct,data.total,data.need_pay);
			}
		});
//		YUI().use("io",function(Y) {
//			var cfg = {
//				method: "POST",
//				data: ""
//			};
//			var sUrl = admin_webroot+"orders/delete_order_product/"+order_id+"/"+order_product_id;//访问的URL地址
//			var request = Y.io(sUrl, cfg);//开始请求
//			var handleSuccess = function(ioId, o){
//				eval('var result='+o.responseText);
//				order_reflash(result.hasproduct,result.total,result.need_pay);
//			}
//			var handleFailure = function(ioId, o){}
//			Y.on('io:success', handleSuccess);
//			Y.on('io:failure', handleFailure);
//		});
	}
}
//订单编辑页刷新
function order_reflash(hasproduct,total,need_pay){
	var order_id = document.getElementById("order_id").value;//订单ID
	var sUrl = admin_webroot+"orders/edit/"+order_id+"/1/";//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"json", 
		success: function(data){
			document.getElementById("order_product_div").innerHTML = data.result;
			$(".am-close").click();
			if(hasproduct){
				document.getElementById('OrderProductTable').style.display='table';
				document.getElementById('OrderStatusChange').style.display='table-row';
				$(".operation_notes_action").removeClass('operation_notes_action_hid');
			}else{
				document.getElementById('OrderProductTable').style.display='none';
				document.getElementById('OrderStatusChange').style.display='none';
				$(".operation_notes_action").addClass('operation_notes_action_hid');
			}
			if(total>0){
				document.getElementById('order_total').innerHTML = sprintf(js_config_price_format,total.toFixed(2));
				document.getElementById('need_pay').innerHTML = sprintf(js_config_price_format,need_pay.toFixed(2));
			}else{
				document.getElementById('order_total').innerHTML = sprintf(js_config_price_format,total.toFixed(2));
				document.getElementById('need_pay').innerHTML = sprintf(js_config_price_format,need_pay.toFixed(2));
			}
		}
	});
}
//订单商品相关修改 chenfan 2012/2/10---2014/12/19
function order_products_data_save(){
	var order_id = document.getElementById("order_id").value;
	//订单商品
	var order_product_attr = "";
	if(document.getElementsByName("order_product_attr[]")){
		var order_product_attr = document.getElementsByName("order_product_attr[]");//订单商品属性
	}
	var order_product_id = document.getElementsByName("order_product_id[]");//订单商品Id
	var order_product_code = document.getElementsByName("order_product_code[]");//订单商品货号
	var order_product_price = document.getElementsByName("order_product_price[]");//订单商品价格
	var order_product_quntity = document.getElementsByName("order_product_quntity[]");//订单商品数据
	var order_product_discount = document.getElementsByName("order_product_discount[]");//订单商品单件折扣价格
	var postData ={ "order_id":order_id};
	var order_pro_attr_arr=[];
	var order_pid_arr=[];
	var order_pro_code_arr=[];
	var order_pro_price_arr=[];
	var order_pro_qty_arr=[];
	var order_pro_discount_arr=[];
	for(var i=0;i<order_product_id.length;i++){
		if(order_product_attr[i]){
			order_pro_attr_arr[i]=order_product_attr[i].value;
		}else{
			order_pro_attr_arr[i]='';
		}
		order_pid_arr[i]=order_product_id[i].value;
		order_pro_code_arr[i]=order_product_code[i].value;
		order_pro_price_arr[i]=order_product_price[i].value;
		order_pro_qty_arr[i]=order_product_quntity[i].value;
		order_pro_discount_arr[i]=order_product_discount[i].value;
	}
	
	postData ={ "order_id":order_id,"order_product_id":order_pid_arr,"order_product_code":order_pro_code_arr,"order_product_price":order_pro_price_arr,"order_product_quntity":order_pro_qty_arr,"order_product_discount":order_pro_discount_arr,"order_product_attr":order_pro_attr_arr};
	var sUrl = admin_webroot+"orders/order_products_data_save/";//访问的URL地址
	//alert(JSON.stringify(postData));
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"json", 
		data:postData,
		success: function(data){
			if(data.code==1){
				document.getElementById("last_order_subtotal").innerHTML=data.subtotal;
				document.getElementById("order_subtotal").value=data.subtotal;
				document.getElementById("order_total").innerHTML=sprintf(js_config_price_format,data.subtotal);
				document.getElementById('need_pay').innerHTML=sprintf(js_config_price_format,data.need_pay);
				if(result.money_paid!=null){
					document.getElementById("order_money_paid_span").innerHTML=sprintf(js_config_price_format,data.need_pay);
				}
			}else{
				alert(j_failed_order_update);
			}
		}
	});
}
//改变折扣之后
function changeDiscount(m,code,total,subtotal){
	//获取数量
	if(document.getElementById("order_product_refund_"+code)!=null){
		var num = document.getElementById("order_product_quntity_"+code).value-document.getElementById("order_product_refund_"+code).value;
	}else{
		var num = document.getElementById("order_product_quntity_"+code).value;
	}

	var shop_price = document.getElementById("order_product_price_"+code).value;
    var re = /([0-9]+\.[0-9]{2})[0-9]*/;
	//折扣 单件
	var discount = parseFloat(shop_price)*(10-m)*0.1;

	document.getElementById("order_product_sumdiscount_"+code).value=-discount.toFixed(2);

	//折后单价
	var last_shop_price = shop_price-discount.toFixed(2);
	document.getElementById("order_product_shop_price_"+code).style.display="";
	document.getElementById("order_product_shop_price_"+code).innerHTML=last_shop_price.toFixed(2);


	//折后单品总价
	var last_total = last_shop_price*num;
	document.getElementById("order_product_total_"+code).innerHTML=last_total.toFixed(2);
	order_products_data_save();
}
//改变数量
function changeNum(num,code,total,subtotal){
	//alert(code);
	if(document.getElementById("order_product_have_quntity_"+code)){
		if(num>parseInt(document.getElementById("order_product_have_quntity_"+code).value)){
			document.getElementById("haveQuantity"+code).style.color='red';
		}else{
			document.getElementById("haveQuantity"+code).style.color='black';
		}
	}
	//获取单价
	var shop_price = document.getElementById("order_product_price_"+code).value;
	//alert(document.getElementById("order_product_have_quntity_"+code).value);
	//折扣 单件
	var discount=document.getElementById("order_product_sumdiscount_"+code).value;

	//折后单品总价
	var last_total = (parseFloat(shop_price)+parseFloat(discount))*parseInt(num);
	document.getElementById("order_product_total_"+code).innerHTML=last_total.toFixed(2);
	var order_product_quntity = document.getElementsByName("order_product_quntity[]");//订单商品数据
	var sum_quantity = 0;
//	alert(order_product_quntity.length);
	for(var i=0;i<order_product_quntity.length;i++){
		//alert(order_product_quntity[i].value);
		sum_quantity +=parseInt(order_product_quntity[i].value);
	}
	document.getElementById("sum_quantity").innerHTML = sum_quantity;
	order_products_data_save();
}
function order_total_check(id){
	$('#'+id).removeClass('order_total_input');
	$('#'+id).focus();
	$('#'+id+'_span').addClass('order_total_input');
}
function order_total_check_over(id){
	var value=	document.getElementById(id).value;
	$('#'+id).addClass('order_total_input');
	$('#'+id+'_span').html(sprintf(js_config_price_format,value));
	$('#'+id+'_span').removeClass('order_total_input');
}
//订单价格相关修改 chenfan 2012/2/10
function order_total_change(id){
	var this_id_val=document.getElementById(id).value;
	if(this_id_val==""){document.getElementById(id).value="0.00";}
	var order_id = document.getElementById("order_id").value;
	var order_subtotal = document.getElementById("order_subtotal").value;
	var order_shipping_fee = document.getElementById("order_shipping_fee").value;//配送费用
	var order_insure_fee = document.getElementById("order_insure_fee").value;//保价费用
	var order_payment_id = document.getElementById("order_payment_id").value;//支付方式
	var order_sub_payment ="";
	if(document.getElementById("sub_pay")&&document.getElementById("sub_pay").style.display!="none")
	order_sub_payment = document.getElementById("sub_pay").value;//支付 2方式
	var order_payment_fee = document.getElementById("order_payment_fee").value;//支付费用
	var order_shipping_id = document.getElementById("order_shipping_id").value;//配送方式
	var order_money_paid = document.getElementById("order_money_paid").value;//已付款金额
	if(document.getElementById("order_pack_fee")){
		var order_pack_fee = document.getElementById("order_pack_fee").value;//包装费用
	}else{
		var order_pack_fee ="";
	}
	if(document.getElementById("order_card_fee")){
		var order_card_fee = document.getElementById("order_card_fee").value;//贺卡费用
	}else{
		var order_card_fee ="";
	}
	var order_to_buyer = document.getElementById("order_to_buyer").value;//商家对客户的留言
	var order_tax = document.getElementById("order_tax").value;//发票税额
	var order_discount = document.getElementById("order_discount").value;//折扣
	var postData = {"order_id":order_id,"order_subtotal":order_subtotal,"order_shipping_fee":order_shipping_fee,"order_insure_fee":order_insure_fee,"order_payment_id":order_payment_id,"order_sub_payment":order_sub_payment,"order_payment_fee":order_payment_fee,"order_shipping_id":order_shipping_id,"order_pack_fee":order_pack_fee,"order_card_fee":order_card_fee,"order_tax":order_tax,"order_discount":order_discount,"order_to_buyer":order_to_buyer,"order_money_paid":order_money_paid};
	//基本信息 部份
//	postData+="&order_subtotal="+order_subtotal+"&order_shipping_fee="+order_shipping_fee+"&order_insure_fee="+order_insure_fee+"&order_payment_id="+order_payment_id+"&order_sub_payment="+order_sub_payment+"&order_payment_fee="+order_payment_fee+"&order_shipping_id="+order_shipping_id+"&order_pack_fee="+order_pack_fee+"&order_card_fee="+order_card_fee+"&order_tax="+order_tax+"&order_discount="+order_discount+"&order_to_buyer="+order_to_buyer+"&order_money_paid="+order_money_paid;
	var sUrl = admin_webroot+"orders/order_total_change/";//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"json", 
		data:postData,
		success: function(data){
			if(data.code==1){
				if(id!="order_to_buyer"&&id!="order_payment_id"&&id!="sub_pay"){
					order_total_check_over(id);
				}
				document.getElementById("order_total").innerHTML=sprintf(js_config_price_format,data.total);
				document.getElementById('need_pay').innerHTML=sprintf(js_config_price_format,data.need_pay.toFixed(2));
			}
			else{
				alert(j_failed_order_update);
			}
		}
	});
}
//选择订单状态
function order_status_select(code){
		if(code==""){
			$("#OrderProductTable tfoot").addClass('order_status');
			$(".operation_notes_action").addClass('operation_notes_action_hid');
			return;
		}else{
			document.getElementById("order_status_change").value=code;
			if($("#order_invoice_no_tr")){
				if(code=="order_has_been_receiving"){
					$("#order_invoice_no_tr").removeClass('order_status');
				}else{
					$("#order_invoice_no_tr").addClass('order_status');
				}
			}
			if($("#order_outbound")){
				if(code=="order_delivery"||code=="order_payment_delivery"){
					$("#order_outbound").removeClass('order_status');
				}else{
					$("#order_outbound").addClass('order_status');
				}
			}
			if(document.getElementById('order_logistics_company_id_tr')){
				if(code=="order_delivery"||code=="order_payment_delivery"){
					if(document.getElementById('order_logistics_company_id_tr').style.display=="none"){
						$("#order_logistics_company_id_tr").removeClass('order_status');
						document.getElementById('order_logistics_company_id_tr').style.display="";
						$(".operation_notes_action").removeClass('operation_notes_action_hid');
						return;
					}
					//$("#order_logistics_company_id_tr").setStyle('display','');
				}else{
					$("#order_logistics_company_id_tr").addClass('order_status');
					document.getElementById('order_logistics_company_id_tr').style.display="none";
					$(".operation_notes_action").addClass('operation_notes_action_hid');
					//$("#order_logistics_company_id_tr").setStyle('display','none');

				}
			}
			if(code=="order_confirm"){
				if(document.getElementById('min_num')){
					var min=parseInt(document.getElementById('min_num').innerHTML);
					var total=parseInt(document.getElementById('sum_quantity').innerHTML);
					if(min>total)
					{
						alert(j_total_amount_less);
						var status_select=document.getElementById('order_status_change');
						for(i=0;i<status_select.options.length;i++){
							if(status_select.options[i].value==''){
								status_select.options[i].selected = 'selected';
							}
						}
						$("#OrderProductTable tfoot").addClass('order_status');
						return;
					}
				}
			}
			if(code=="order_picking"){
				if(document.getElementById('order_picking_type_tr')){
					if(document.getElementById('order_picking_type_tr').style.display=="none"){
						$("#order_picking_type_tr").removeClass('order_status');
						document.getElementById('order_picking_type_tr').style.display="";
					}
				}
			}
			$("#OrderProductTable tfoot").removeClass('order_status');
			if(code!="order_confirm"&&code!="order_payment"&&code!="order_delivery"){
				$(".operation_notes_action").removeClass('operation_notes_action_hid');
			}else{
				order_status_change();
			}
		}
}
function change_order_type(obj){
	var order_id = document.getElementById("order_id").value;
	if(obj.value==''){
		document.getElementById('order_product_div').style.display="none";
		return;
	}
	var sUrl = admin_webroot+'/orders/change_order_type';//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"json", 
		data:{"type":obj.value,"oid":order_id},
		success: function(data){
			if(data.code==1){
				document.getElementById('order_product_div').style.display="";
				//Y.one("#order_product_div").setStyle('display','');
				for (var i=0; i < obj.length; i++) {
					if(obj.options[i].value==''){
						obj.remove(i);
						return;
					}
				};
			}
		}
	});
}
function order_status_change(){
	var order_user=0;
	if(document.getElementById("opener_select_user_id")){
		order_user=document.getElementById("opener_select_user_id").value;
	}
	var order_status_message_code = document.getElementById("order_status_change").value;
	if(order_status_message_code==""){
		return;
	}
	if((order_user==0||order_user=='')&&order_status_message_code!="order_cancel"&&order_status_message_code!="order_invalid"){
		alert(j_consignee_not_empty);
		return;
	}
	var order_id = document.getElementById("order_id").value;//订单ID
	if(document.getElementById("picking_type")){//配货方式 
		var picking_type=document.getElementById("picking_type").value;
	}else{
		var picking_type="0";
	}
	if(document.getElementById("order_invoice_no")){
		var order_invoice_no = document.getElementById("order_invoice_no").value;//发货单号
	}else{
		var order_invoice_no ="";
	}
	if(document.getElementById("order_logistics_company_id")&&document.getElementById("order_logistics_company_id").value!=""){
		var order_logistics_company_id = document.getElementById("order_logistics_company_id").value;//物流公司
		var obj = document.getElementById("order_logistics_company_id");
		if(obj.options[obj.selectedIndex].value!='0'&&order_invoice_no=="" && order_status_message_code=="order_delivery"){
				alert(j_empty_invoice_number);return;
		}
//		if(order_logistics_company_id!='0' && order_status_message_code=="order_delivery"){
//			alert(j_please_select+j_order_logistics_company);return;
//		}
	}else{
		var order_logistics_company_id =0;
	}

	var operation_notes = document.getElementById("operation_notes").value;//操作备注
	var postData = "order_id="+order_id;
	postData+="&order_status_message_code="+order_status_message_code;//操作代码标志
	postData+="&operation_notes="+operation_notes;//操作备注
	postData+="&order_invoice_no="+order_invoice_no;//发货单号
	postData+="&order_logistics_company_id="+order_logistics_company_id;//物流公司
	postData+="&picking_type="+picking_type;//配货方式 
//	var sUrl = admin_webroot+"orders/form_data_save/?"+postData;//访问的URL地址
//	$.ajax({ url: sUrl,
//		type:"POST",
//		dataType:"json", 
//		success: function(data){
//			if(data.code==1){
//    		  	window.location.reload();
//				//order_reflash();
//				alert(data.message);
//			}
//			else{
//				alert(j_failed_order_update);
//			}
//		}
//	});
	if(document.getElementById("warehouse")!=null && (order_status_message_code=='order_payment_delivery' || order_status_message_code=='order_delivery')){
		var w=Y.one('#warehouse').get('value');
		if(w==''){
			if(!confirm('订单发货，商品不出库，确定吗？')){
				return;
			}
		}
	}else{
		var w='';
	}
	//按钮变灰
//	disablebtn(document.getElementById('order_status_change_btn'));
	$("#order_status_change_btn").attr("disabled",true)
	if(w!=''){
		var sUrl = admin_webroot+"outbounds/can_ship/?"+"order_id="+order_id+"&w="+w;//访问的URL地址 order_shippings/can_ship
		$.ajax({ url: sUrl,
			type:"POST",
			dataType:"json", 
			success: function(data){
				if(data.can_ship==3){
					if(!confirm(data.message+'马上填写调仓申请？')){
						return false;
					}else{
						alert('其他仓库没有申请调仓的商品，申请失败');
						return false;
					}
				}
				if(data.can_ship==2){
					if(!confirm(data.message+'马上填写调仓申请？')){
						return false;
					}else{
						document.getElementById('transfer').innerHTML=data.transfer;
						popOpen('transfer');
						return false;
					}
				}else if(data.can_ship==1&&data.code==null){
					var sUrl = admin_webroot+"orders/order_status_change/?"+ postData+"&w="+w;//访问的URL地址
					$.ajax({ url: sUrl,
						type:"POST",
						dataType:"json", 
						success: function(data){
							if(data.code==1){
								//order_reflash();
								alert(data.message)
								window.location.reload();
								//history.back();
							}
						}
					});
				}
			}
		});
	}else{
		var sUrl = admin_webroot+"orders/order_status_change/?"+ postData;//访问的URL地址
		$.ajax({ url: sUrl,
			type:"POST",
			dataType:"json", 
			success: function(data){
				if(data.code==1){
					//order_reflash();
					alert(data.message)
					window.location.reload();
					//history.back();
				}
			}
		});
	}
}
function order_logistics_data_save(){
	var logistic_id = $('#order_logistics_company_id').val();
	var	invoice_no	= $('#order_invoice_no').val();
	var	order_id = $('#order_id').val();
	if(logistic_id==''){
		logistic_id=0;
		invoice_no='';
	}
	var postData = "logistic_id="+logistic_id+"&invoice_no="+invoice_no+'&order_id='+order_id;
	var sUrl = admin_webroot+"orders/change_order_logistic/?"+ postData;//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"json", 
		success: function(data){
			alert(j_modified_successfully);
			window.location=window.location;
		}
	});
}
//订单
function order_data_save(){
	if(document.getElementById('min_num')){
		var min=parseInt(document.getElementById('min_num').innerHTML);
		var total=parseInt(document.getElementById('sum_quantity').innerHTML);
		if(min>total)
		{
			alert(j_total_amount_less);
			return;
		}
	}
	var order_id = document.getElementById("order_id").value;
	var postData = "order_id="+order_id;
	//基本信息 部份
	if(document.getElementById("opener_select_user_id"))
	var order_user_id = document.getElementById("opener_select_user_id").value;
	var order_shipping_fee = document.getElementById("order_shipping_fee").value;//配送费用
	var order_insure_fee = document.getElementById("order_insure_fee").value;//保价费用
	var order_payment_id = document.getElementById("order_payment_id").value;//支付方式
	var order_payment_fee = document.getElementById("order_payment_fee").value;//支付费用
	var order_shipping_id = document.getElementById("order_shipping_id").value;//配送方式
	if(document.getElementById("order_pack_fee")){
		var order_pack_fee = document.getElementById("order_pack_fee").value;//包装费用
	}else{
		var order_pack_fee ="";
	}
	if(document.getElementById("order_card_fee")){
		var order_card_fee = document.getElementById("order_card_fee").value;//贺卡费用
	}else{
		var order_card_fee ="";
	}
	//var order_postscript = document.getElementById("order_postscript").value;//客户给商家留言
	var order_tax = document.getElementById("order_tax").value;//发票税额
	var order_discount = document.getElementById("order_discount").value;//折扣

	//收货人信息
	var order_telephone = document.getElementById("order_telephone").value;//电话
	var order_consignee = document.getElementById("order_consignee").value;//收货人
	var order_mobile = document.getElementById("order_mobile").value;//手机
	var order_country = document.getElementById("order_country2").value;//国家
	var order_province = document.getElementById("order_province2").value;//省
	var order_city = document.getElementById("order_city2").value;//市
	//var order_district = document.getElementById("order_district").value;//区
	var order_district = '';//区
	var order_sign_building = document.getElementById("order_sign_building").value;//标致性建筑
	var order_address = document.getElementById("order_address").value;//地址
	var order_best_time = document.getElementById("order_best_time").value;//最佳送货时间
	var order_zipcode = document.getElementById("order_zipcode").value;//邮编
	var order_note = document.getElementById("order_note").value;//备注
	var order_email = document.getElementById("order_email").value;//电子邮件

	//其它信息
	var order_to_buyer = document.getElementById("order_to_buyer").value;//商家对客户的留言
	var order_invoice_type = document.getElementById("order_invoice_type").value;//发票类型
	var order_invoice_payee = document.getElementById("order_invoice_payee").value;//发票抬头
	var order_invoice_content = document.getElementById("order_invoice_content").value;//发票内容
	var order_how_oos = document.getElementById("order_how_oos").value;//缺货处理

	//POST数据组合
	if(document.getElementById("opener_select_user_id"))
	var postData = "order_id="+order_id+"&order_user_id="+order_user_id;
	else
	var postData = "order_id="+order_id;
	//基本信息 部份
	postData+="&order_shipping_fee="+order_shipping_fee+"&order_insure_fee="+order_insure_fee+"&order_payment_id="+order_payment_id+"&order_payment_fee="+order_payment_fee+"&order_shipping_id="+order_shipping_id+"&order_pack_fee="+order_pack_fee+"&order_card_fee="+order_card_fee+"&order_tax="+order_tax+"&order_discount="+order_discount;
	//收货人信息
	postData+="&order_telephone="+order_telephone+"&order_consignee="+order_consignee+"&order_mobile="+order_mobile;
	postData+="&order_country="+order_country+"&order_province="+order_province+"&order_city="+order_city+"&order_district="+order_district;
	postData+="&order_sign_building="+order_sign_building+"&order_address="+order_address+"&order_best_time="+order_best_time;
	postData+="&order_zipcode="+order_zipcode+"&order_note="+order_note+"&order_email="+order_email;
	//其它信息
	postData+="&order_to_buyer="+order_to_buyer+"&order_invoice_type="+order_invoice_type+"&order_invoice_payee="+order_invoice_payee;
	postData+="&order_invoice_content="+order_invoice_content+"&order_how_oos="+order_how_oos;
	//订单商品
	var order_product_attr = document.getElementsByName("order_product_attr[]");//订单商品价格
	var order_product_code = document.getElementsByName("order_product_code[]");//订单商品货号
	var order_product_price = document.getElementsByName("order_product_price[]");//订单商品价格
	var order_product_quntity = document.getElementsByName("order_product_quntity[]");//订单商品数据
	var order_product_discount = document.getElementsByName("order_product_discount[]");//订单商品折扣
	for(var i=0;i<order_product_code.length;i++){
		postData+="&order_product_attr[]="+order_product_attr[i].value;
		postData+="&order_product_code[]="+order_product_code[i].value;
		postData+="&order_product_price[]="+order_product_price[i].value;
		postData+="&order_product_quntity[]="+order_product_quntity[i].value;
		postData+="&order_product_discount[]="+order_product_discount[i].value;
	}
	var sUrl = admin_webroot+"orders/form_data_save/?"+postData;//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"json", 
		success: function(data){
			if(data.code==1){
    		  	window.location.reload();
				//order_reflash();
				alert(data.message);
			}
			else{
				alert(j_failed_order_update);
			}
		}
	});
}
function ajaxFileUpload(Id,inputName){
	 if(Id==0){alert(j_please_select_order_user);return false;}
	 $.ajaxFileUpload({
		  url:'/admin/users/ajaxuploadavatar/'+Id+'/'+inputName,
		  secureuri:false,
		  fileElementId:inputName,
		  dataType: 'json',
		  success: function (result){
		  	  if(result.code==1){
		  	  	var avatar_url=result.img_url;
		  	  	$("#"+inputName+"_priview").attr("src",avatar_url);
		  	  	$("#"+inputName+"_hid").val(avatar_url);
		  	  }else{
		  	  	alert(result.msg);
		  	  }
		  },
		  error: function (data, status, e)//服务器响应失败处理函数
		  {
		  	  alert('上传失败');
          }
	 });
	return false;
}
function clearNoNum(event,obj){ 
    //响应鼠标事件，允许左右方向键移动 
    event = window.event||event; 
    if(event.keyCode == 8||event.keyCode == 37 | event.keyCode == 39){ 
        return; 
    }
    //先把非数字的都替换掉，除了数字和. 
    obj.value = obj.value.replace(/[^\d.]/g,""); 
    //必须保证第一个为数字而不是. 
    obj.value = obj.value.replace(/^\./g,""); 
    //保证只有出现一个.而没有多个. 
    obj.value = obj.value.replace(/\.{2,}/g,"."); 
    //保证.只出现一次，而不能出现两次以上 
    obj.value = obj.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
}
function checkNum(obj){ 
    //为了去除最后一个. 
    obj.value = obj.value.replace(/\.$/g,"");
    var valuestr=Number(obj.value);
    obj.value = valuestr.toFixed(2);
}
function edit_order_user(){
	var order_user_id = document.getElementById("opener_select_user_id").value;
	if(order_user_id==""){order_user_id=0;}
	if(order_user_id==0){
		document.getElementById("create_user_info").style.display="table-row";
	}
	$(".order_user").css("display","inline");
	$("tr.order_user").css("display","table-row");
	$(".order_user").removeClass('order_user');
	$(".order_user_span").css("display","none");
//	YUI().use('*', function(Y) {
//	 	Y.all(".order_user").setStyles({display:'inline'});
//	 	Y.all("tr.order_user").setStyles({display:'table-row'});
//		Y.all(".order_user").removeClass('order_user');
//		Y.all(".order_user_span").setStyles({
//			display : 'none'
//		});
// 	});
}
function order_user_save(){
	var order_user_id=0;
	var order_id = document.getElementById("order_id").value;
	var order_shipping_id = document.getElementById("order_shipping_id").value;//配送方式
	var postdata="order_id="+order_id+"&order_shipping_id="+order_shipping_id;
	var create_user_name=document.getElementById("create_user_name").value;//新增用户信息
	var create_user_mobile=document.getElementById("create_user_mobile").value;//新增用户手机
	
	if(document.getElementById("opener_select_user_id")){
		order_user_id = document.getElementById("opener_select_user_id").value;
		if(order_user_id==""){order_user_id=0;}
	}
	if(order_user_id==0){
		if(create_user_name==""){
			alert("请输入购货人姓名");return false;
		}
		if(create_user_mobile==""){
			alert("请输入购货人手机");return false;
		}
		if(!/^1[3-9]\d{9}$/.test(create_user_mobile)){
			alert("购货人手机格式不正确");return false;
		}
		if(typeof isset_mobile!='undefined' && isset_mobile){
			alert("手机号已存在");return false;
		}
	}
	postdata+="&create_user_name="+create_user_name;
	postdata+="&create_user_mobile="+create_user_mobile;
	postdata+="&order_user_id="+order_user_id;
	postdata+="&"+$("#order_user_avatar_from").serialize();
	postdata+="&"+$("#order_user_config_from").serialize();
	var sUrl = admin_webroot+"orders/order_address_data_save/user?"+postdata;//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"html", 
		success: function(data){
			document.getElementById('order_user_info').innerHTML = data;
		}
	});
//	YUI().use("io",function(Y) {
//		var sUrl = admin_webroot+'orders/order_address_data_save/user';//访问的URL地址
//		var cfg = {
//			method: "POST",
//			data: postdata
//		};
//		var request = Y.io(sUrl,cfg);//开始请求
//		var handleSuccess = function(ioId, o){
//			try{
//				document.getElementById('order_user_info').innerHTML = o.responseText;
//			}catch (e){
//				alert(o.responseText);
//			}
//		}
//		var handleFailure = function(ioId, o){
//			alert(j_object_transform_failed);
//		}
//		Y.on('io:success', handleSuccess);
//		Y.on('io:failure', handleFailure);
//    });
}
//单件折扣  单独修改
function changeSumDiscount(discount_price,code,shop_price,total,subtotal){
	//修改折扣后单件
	var last_shop_price=parseFloat(shop_price)+parseFloat(discount_price);
	document.getElementById("order_product_shop_price_"+code).innerHTML=last_shop_price.toFixed(2);
	if(shop_price!=0){
		var discount=(last_shop_price/shop_price)*10;
		document.getElementById("order_product_discount_"+code).value=discount.toFixed(2);
	}

	if(document.getElementById("order_product_refund_"+code)!=null){
		var num = document.getElementById("order_product_quntity_"+code).value-document.getElementById("order_product_refund_"+code).value;
	}else{
		var num = document.getElementById("order_product_quntity_"+code).value;
	}
	var last_total = last_shop_price*num;
	document.getElementById("order_product_total_"+code).innerHTML=last_total.toFixed(2);
	order_products_data_save();
}
function order_status_select_reload(){
	var order_id = document.getElementById("order_id").value;
	var postData ="order_id="+order_id;
	var sUrl = admin_webroot+"orders/order_status_select_reload/";//访问的URL地址
	$.ajax({ url: sUrl,
		type:"POST",
		dataType:"html",
		data:{"order_id":order_id},
		success: function(data){
			eval('var result='+data);
			var order_status_change=document.getElementById("order_status_change");
			order_status_change.value="";
			var	status_change_td=document.getElementById("status_change_td");
			status_change_td.innerHTML="";
			$.each(result,function(v,k){
				if(v!=""){
					var btn = "<input id='"+v+"' type='button' name='order_status_change' class='am-btn am-btn-success am-btn-sm' onclick='order_status_select(this.id)' value='"+k+"' />"
					status_change_td.innerHTML+=btn;
				}
			})
		}
	});
//	YUI().use("io",function(Y) {
//		var cfg = {
//			method: "POST",
//			data: postData
//		};
//		var sUrl = admin_webroot+"orders/order_status_select_reload/";//访问的URL地址
//		var request = Y.io(sUrl, cfg);//开始请求
//		var handleSuccess = function(ioId, o){
//			try{
//				eval('var result='+o.responseText);
//				var order_status_change=document.getElementById("order_status_change");
//				order_status_change.innerHTML="";
//				Y.each(result,function(v,k){
//					var opt = document.createElement("OPTION");
//		        	opt.value = k;
//		        	opt.text  = v;
//					order_status_change.options.add(opt);
//				})
//				
//			}catch(e){
//				alert(j_object_transform_failed);
//				alert(o.responseText);
//			}
//		}
//		var handleFailure = function(ioId, o){}
//		Y.on('io:success', handleSuccess);
//		Y.on('io:failure', handleFailure);
//	});
}
$(function(){
	get_ship_logistics_companies();
	sendinfo('0');
	if(document.getElementById("sub_pay")){
		if($('#order_payment_id') && document.getElementById("sub_pay").style.display=="none"){
		  	add_sub_pay('order_payment_id');
		}
	}
	//getRegions(0,'');
});
