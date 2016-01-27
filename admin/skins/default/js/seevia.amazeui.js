/* Write your js */
$(document).ready(function(){	
	$("img").each(function(){
		$(this).prop("onerror",function(e){
			if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) { 
		      	//this.src = '/theme/AmazeUI/images/default.png'; 
		      } 
		});
	});
});



/*
	Pop positioning
*/
function AmazeuiPopPositioning(Id){
	var WindowWidth=$(window).width();
	var WindowHeight=$(window).height();
	var PopWidth=$("#"+Id).width();
	var PopHeight=$("#"+Id).height();
	var Popmargin_left=((WindowWidth-PopWidth)/2).toFixed(2);
	var Popmargin_top=((WindowHeight-PopHeight)/2).toFixed(2);
	$("#"+Id).css("left","0px").css("top","0px").css("margin-left",Popmargin_left+"px").css("margin-top",Popmargin_top+"px");
}

function checkSpecial(str){
	var reg=/[@#\$%\^&\*]+/g;
	if(reg.test(str)){
		return false;
	}
	return true;
}

/*
	分页回车
*/
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
	$.ajax({ url:admin_webroot+"pages/pagers_num/"+obj.value+"?"+new Date(),
			type:"GET",
			dataType:"html",
			data: {},
			success: function(data){
				window.location.href = window.location.href;
			}
		});
}

//ajax取区域
function show_two_regions(str,id,ii){
	if(document.getElementById('local')){
		var local=document.getElementById('local').value;
	}else{
		var local="chi";
	}
	if(id==undefined || id==0){
		var data = { str:str,local_area: local,ii:ii};
		id = '';
	}
	else 
		var data = { str:str,updateaddress_id:id,local_area: local,ii:ii}
	$.post(
		"/admin/regions/twochoice/"+str, //url
		data,//data
		function (result, textStatus){//callback
			if(result.type == "0"){
				document.getElementById('regionsupdate'+ii+id).innerHTML = result.message;
			}else{
				document.getElementById('message_content').innerHTML = result.message;
			}
			//$("#AddressRegionUpdate00").selectIt();
		},
 		"json"//type
 	);

}
//重载区域
function reload_two_regions(ii){
	var i=0;
	var str="";
	var now_id1=document.getElementById("AddressRegionUpdate"+ii+"0").value;
	var now_id2=document.getElementById("AddressRegionUpdate"+ii+"1")?document.getElementById("AddressRegionUpdate"+ii+"1").value:'';
	var now_id3=document.getElementById("AddressRegionUpdate"+ii+"2")?document.getElementById("AddressRegionUpdate"+ii+"2").value:'';
	document.getElementById("region_hidden_id"+ii).value=now_id1+" "+now_id2+" "+now_id3;
	while(true){
		if(document.getElementById('AddressRegionUpdate'+ii+i)==null){
			break;
		}
		str +=document.getElementById('AddressRegionUpdate'+ii+i).value + " ";
		i++;
	} 
    show_two_regions(str,0,ii);
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
//两边去空格
function Trim(str){ //删除左右两端的空格 
    return str.replace(/(^\s*)|(\s*$)/g,"");
}
//选择物流公司
function select_logistics_company(m){
		if(document.getElementById("logistic_save_button")){
			document.getElementById('logistic_save_button').style.display="";
			//Y.one("#logistic_save_button").setStyle('display','');
		}

	if(m!=''){
		$("#order_invoice_no_tr").removeClass('order_status');
		//Y.one("#order_invoice_no_tr").setStyle('display','');
		document.getElementById('order_invoice_no_tr').style.display="";
	}else{
		$("#order_invoice_no_tr").addClass('order_status');
	}
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

function list_delete_submit(sUrl,confirm_delete_str){
	if(typeof(confirm_delete_str)=="undefined"){
		confirm_delete_str=j_confirm_delete;
	}
	if(confirm(confirm_delete_str)){
		$.ajax({ url:sUrl,
				type:"POST",
				dataType:"json",
				data: {},
				success: function(data){
					try{  
						if(data.flag==1){
							window.location.reload();
						}else{
							alert(data.message);
						}
					}catch(e){
						alert(data);
					}
				}
			});
	}
}