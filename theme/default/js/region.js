//ajax取区域
function show_two_regions(str,id){
	var local=document.getElementById('local').value;
	if(id==undefined || id==0){
		var data = { str:str,local_area: local};
		id = '';
	}
	else 
		var data = { str:str,updateaddress_id:id,local_area: local}
 		var region_search_Success=function (result, textStatus){//callback
			if(result.type == "0"){
				document.getElementById('regionsupdate'+id).innerHTML = result.message;
			}else{
				document.getElementById('message_content').innerHTML = result.message;
			}
	};
	$.post("/regions/twochoice/"+str,data,region_search_Success,"json");

}
//ajax取无需验证区域
function show_uncheck_regions(str,id){
	var local=document.getElementById('local').value;
	if(id==undefined || id==0){
		var data = { str:str,local_area: local};
		id = '';
	}
	else 
		var data = { str:str,updateaddress_id:id,local_area: local}
 		var region_search_Success=function (result, textStatus){//callback
			if(result.type == "0"){
				document.getElementById('regionsupdate'+id).innerHTML = result.message;
			}else{
				document.getElementById('message_content').innerHTML = result.message;
			}
	};
	$.post("/regions/uncheckchoice/"+str,data,region_search_Success,"json");
}
//重载区域
function reload_two_regions(id){
	var i=0;
	var str="";
	while(true){
		if(document.getElementById('AddressRegionUpdate'+i)==null){
			break;
		}
		str +=document.getElementById('AddressRegionUpdate'+i).value + " ";
		i++;
	} 
    show_two_regions(str);
}
//重载无需验证区域
function reload_uncheck_regions(id){
	var i=0;
	var str="";
	while(true){
		if(document.getElementById('AddressRegionUpdate'+i)==null){
			break;
		}
		str +=document.getElementById('AddressRegionUpdate'+i).value + " ";
		i++;
	} 
    show_uncheck_regions(str);
}
//
function reload_edit_two_regions(addressId){
	var i=0;
	var str="";
	while(true){
		//alert('AddressRegionUpdate'+i+addressId);
		if(document.getElementById('AddressRegionUpdate'+i+addressId)==null){
			break;
		}
		str +=document.getElementById('AddressRegionUpdate'+i+addressId).value + " ";
		i++;
	}
   	show_two_regions(str,addressId);
}
function reload_edit_uncheck_regions(addressId){
	var i=0;
	var str="";
	while(true){
		//alert('AddressRegionUpdate'+i+addressId);
		if(document.getElementById('AddressRegionUpdate'+i+addressId)==null){
			break;
		}
		str +=document.getElementById('AddressRegionUpdate'+i+addressId).value + " ";
		i++;
	}
   	show_uncheck_regions(str,addressId);
}

 