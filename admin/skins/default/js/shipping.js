function region_country(mo){
		mod = mo;
		var country_id = document.getElementById('country_id').value;
		$.ajax({
				url:admin_webroot+"shippingments/province/"+country_id,
				type:"POST",
				data:{},
				postData:"json",
				success:function(data){
					eval('result='+data)
					if(mod=="country"){
						var sel = document.getElementById('province_id');
						document.getElementById('citys').innerHTML = "";
						 opt = document.createElement("OPTION");  
						 opt.selected = "false";
			             opt.value = " ";;
			             opt.text  = j_please_select+"...";
			             document.getElementById('citys').options.add(opt);
			             document.getElementById('area_id').innerHTML = "";
						 opt = document.createElement("OPTION");  
						 opt.selected = "false";
			             opt.value = " ";;
			             opt.text  = j_please_select+"...";
			             document.getElementById('area_id').options.add(opt);
					}
					
			        //alert(result['number']);
					if(mod=="province"){
						var sel = document.getElementById('citys');
						document.getElementById('area_id').innerHTML = "";
						 opt = document.createElement("OPTION");  
						 opt.selected = "false";
			             opt.value = " ";;
			             opt.text  = j_please_select+"...";
			             document.getElementById('area_id').options.add(opt);
						
					}
					if(mod=="city"){
						var sel = document.getElementById('area_id');
					}
					if (result['message']){
					 //	 alert("aa");
						 sel.innerHTML = "";
						 opt = document.createElement("OPTION");  
						 opt.selected = "false";
			             opt.value = " ";
			             opt.text  = j_please_select+"...";
			             sel.options.add(opt);
			             for (i = result.first_key; i < result['number']; i++ ){
			              	 	var opt = document.createElement("OPTION");
			                 	opt.value = result.message[i]['Region'].id;
			                 	opt.text  = result.message[i]['RegionI18n'].name;
			                 	sel.options.add(opt);
			              	
			              }
			         
			         }
						}
		});
}

//配送方式  所辖地区
var mod;
function regions(mo){
	mod = mo;
	var province_id=document.getElementById('province_id').value;
	$.ajax({
		url:admin_webroot+"shippingments/province/"+province_id,
		type:"POST",
		data:{},
		dataType:"json",
		success:function(data){
			//alert(data.number);
			//eval('result='+data)
			if(mod=="country"){
				alert("bb");
				var sel = document.getElementById('province_id');
				document.getElementById('citys').innerHTML = "";
				 opt = document.createElement("OPTION");  
				 opt.selected = "false";
	             opt.value = " ";;
	             opt.text  = j_please_select+"...";
	             document.getElementById('citys').options.add(opt);
	             document.getElementById('area_id').innerHTML = "";
				 opt = document.createElement("OPTION");  
				 opt.selected = "false";
	             opt.value = " ";;
	             opt.text  = j_please_select+"...";
	             document.getElementById('area_id').options.add(opt);
			}
			if(mod=="province"){
				//alert("ff");
				var sel = document.getElementById('citys');
				document.getElementById('area_id').innerHTML = "";
				 opt = document.createElement("OPTION");  
				 opt.selected = "false";
	             opt.value = " ";;
	             opt.text  = j_please_select+"...";
	             document.getElementById('area_id').options.add(opt);
				
			}
			if(mod=="city"){
				var sel = document.getElementById('area_id');
			}
			if (data.message){
				 sel.innerHTML = "";
				 opt = document.createElement("OPTION");  
				 opt.selected = "false";
	             opt.value = " ";
	             opt.text  = j_please_select+"...";
	             sel.options.add(opt);
	             //alert("aa");
	             for (i = data.first_key; i < data.number; i++ ){
	              	 	var opt = document.createElement("OPTION");
	                 	opt.value = data.message[i]['Region'].id;
	                 	opt.text  = data.message[i]['RegionI18n'].name;
	                 	sel.options.add(opt);
	                 //	alert(data.message[i]['Region'].id);
	                 //	alert(data.message[i]['RegionI18n'].name);
	             	//("cc");
	              	
	              }
	         
	         }

		}
		});
}




function region_city(mo){
	mod = mo;
	var citys=document.getElementById('citys').value;
	$.ajax({
		url:admin_webroot+"shippingments/province/"+citys,
		type:"POST",
		data:{},
		dataType:"json",
		success:function(data){
			if(mod=="country"){
				var sel = document.getElementById('province_id');
				document.getElementById('citys').innerHTML = "";
				 opt = document.createElement("OPTION");  
				 opt.selected = "false";
	             opt.value = " ";;
	             opt.text  = j_please_select+"...";
	             document.getElementById('citys').options.add(opt);
	             document.getElementById('area_id').innerHTML = "";
				 opt = document.createElement("OPTION");  
				 opt.selected = "false";
	             opt.value = " ";;
	             opt.text  = j_please_select+"...";
	             document.getElementById('area_id').options.add(opt);
			}
			if(mod=="province"){
				var sel = document.getElementById('citys');
				document.getElementById('area_id').innerHTML = "";
				 opt = document.createElement("OPTION");  
				 opt.selected = "false";
	             opt.value = " ";;
	             opt.text  = j_please_select+"...";
	             document.getElementById('area_id').options.add(opt);
				
			}
			if(mod=="city"){
				var sel = document.getElementById('area_id');
			}
			if (data.message){
				 sel.innerHTML = "";
				 opt = document.createElement("OPTION");  
				 opt.selected = "false";
	             opt.value = " ";;
	             opt.text  = j_please_select+"...";
	             sel.options.add(opt);
	             //alert(result.number);
	             for (i = data.first_key; i < data.number; i++ ){
	              	 	var opt = document.createElement("OPTION");
	                 	opt.value = data.message[i]['Region'].id;
	                 	opt.text  = data.message[i]['RegionI18n'].name;
	                 	sel.options.add(opt);
	              	
	              }
	         
	         }

		}
		});
}