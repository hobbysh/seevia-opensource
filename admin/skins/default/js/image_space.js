//快速添加图片分类
function doinsertphotocat(){
	if(document.getElementById("name_chi")&&document.getElementById("name_chi").value==""){
		alert("分类名称不能为空");
		return false;
	}
	if(document.getElementById("name_eng")&&document.getElementById("name_eng").value==""){
		alert("分类名称不能为空");
		return false;
	}
	$.ajax({
	  type: 'POST',
	  url: "/admin/image_spaces/doinsertphotocat/",
	  data: $('#catform1').serialize(),
	  dataType:"json",
  	  success: function(data){
	      if(data.flag==1){
	          $('#select').html(data.cat);
		      $(".am-close").click();
	      }
	      if(data.flag==2){
		     alert(data.message);
		  }
  	  }
    });
}

//保存图片数据
function image_data_save(o){
	var imgdata=eval("(" + o + ")");
	var act='add';
	if(imgdata['img']=="product"){
		return;
	}
	if(imgdata['img']['id']>0){
		act='replace';
	}
	var sUrl = admin_webroot+"image_spaces/image_data_save/";
	$.ajax({
        url: sUrl,
        type: 'POST',
        data: {'image_data':o},
        dataType: 'json',
        success: function (serverData) {
            if(serverData.error){
				alert(serverData.error_img);
			}else{
				if(act=='replace'){
					window.location=window.location;
				}else{
					var imglist = document.getElementById("imglist");
					imglist.innerHTML = serverData.img + "\n" + imglist.innerHTML;
				}
			}
        }
    });
}

function confirm_remove_img(obj,thisid){
	var sUrl = admin_webroot+"image_spaces/remove/"+thisid;
	$.ajax({
        url: sUrl,
        type: 'GET',
        dataType: 'json',
        success: function (serverData) {
            if(serverData.flag==1){
				alert(serverData.message);
				var rep_value = obj.parentNode.parentNode;
				rep_value.innerHTML = "";
				rep_value.parentNode.removeChild(rep_value);
			}
        }
    });
}

function photo_copy(ev,src){
	if(navigator.userAgent.search("MSIE") != -1){
		window.clipboardData.setData("Text",src);
		alert(j_replicate_successfully);
	}else{
		var event= ev || window.event;
		var div = document.getElementById('tip-copy1');
		div.className=div.className.replace("hidden"," ");
		document.getElementById('tip-copy1-text').value = src;
	}
}
/*
function photo_copy1(ev,src){
	if(isFirefox=navigator.userAgent.indexOf("Firefox")>0){
	   	var event= ev || window.event;
	   	var div = document.getElementById('tip-copy1');
		div.className=div.className.replace("hidden"," ");
		div.style.left = event.clientX + 'px';
		div.style.top = (document.documentElement.scrollTop + event.clientY) + 'px';
		document.getElementById('tip-copy1-text').value = src;
	}
	else{
	    window.clipboardData.setData("Text",src);
	    alert(j_replicate_successfully);
	}
}*/

function remove_shop_image(sUrl){
	$.ajax({
        url: sUrl,
        type: 'POST',
        dataType: 'json',
        success: function (result) {
            if(result.flag==1){
				window.location.reload();
			}
			if(result.flag==2){
				alert(result.message);
			}
        }
    });
}