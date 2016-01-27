<script type="text/javascript" src="/plugins/jquery-pack.js"></script>
<script type="text/javascript" src="/plugins/jquery.imgareaselect.min.js"></script>
<script type="text/javascript" src="/plugins/ajaxfileupload.js"></script>
<div class="am-cf am-user">
	<h3><?php echo $ld['editing_avatar'] ?></h3>
</div>
<div class="am-edit-user-avatar">
	<?php echo $form->create('Users',array('action'=>'edit_headimg','name'=>"HeadImgForm",'id'=>"HeadImgForm","type"=>"post",'enctype'=>'multipart/form-data','onsubmit'=>'return checkImgform();'));?>
	<input type="hidden" name="userId" value="<?php echo isset($_SESSION['User'])?$_SESSION['User']['User']['id']:''; ?>" />
	<div class="am-form-detail">
		<div class="am-g am-margin-top" id="uploadImg">
          <div class="am-u-sm-2 am-text-right"><?php echo $ld['upload_photos'] ?></div>
          <div class="am-u-sm-10">
    		<input type="file" name="userImg" id="userImg" onchange="UploadImg(this)" />
    	  </div>
        </div>
    
    	<div class="am-g am-margin-top" id="showImg" style="display:none;">
          <div class="am-u-sm-2 am-text-right">&nbsp;</div>
          <div class="am-u-sm-10">
            <img src="/theme/default/img/no_head.png" id="thumbnail" />
            <div id="thumbImg" style="width:<?php echo $thumb_width;?>px; height:<?php echo $thumb_height;?>px;">
				<img src="/theme/default/img/no_head.png" style="position: relative;" />
			</div>
			<input type='hidden' name="imgurl" id="imgurl" value="" />
			<input type="hidden" name="x1" value="0" id="x1" />
			<input type="hidden" name="y1" value="0" id="y1" />
			<input type="hidden" name="x2" value="0" id="x2" />
			<input type="hidden" name="y2" value="0" id="y2" />
			<input type="hidden" name="w" value="<?php echo $thumb_width;?>" id="w" />
			<input type="hidden" name="h" value="<?php echo $thumb_height;?>" id="h" />
    	  </div>
        </div>
    
    	<div class="am-g am-margin-top">
          <div class="am-u-sm-2 am-text-right">&nbsp;</div>
          <div class="am-u-sm-10">
    		<input class="am-btn am-btn-primary am-btn-sm am-fl" type="submit" value="<?php echo $ld['user_save'] ?>" style="margin-right:1em;" />
    		<input class="am-btn am-btn-primary am-btn-sm am-fl" type="button" value="<?php echo $ld['cancel'] ?>" onclick="clearimg()" />
    	  </div>
        </div>
	</div>
	<?php echo $form->end();?>
</div>

<script type="text/javascript">
$(function(){
	var windowHeight=$(window).height();
	$(".am-edit-user-avatar .am-form-detail").css("min-height",(windowHeight*0.7)+"px");
})
var imgwidth=0,imgheight=0;
var imgselect=$('#thumbnail').imgAreaSelect({
	instance:true,
	disable:true,
	hide:true,
	aspectRatio: '1:<?php echo $thumb_height/$thumb_width;?>',
	onSelectChange: preview,
	x1:0,
	x2:0,
	y1:0,
	y2:0
});

function UploadImg(obj){
	if($(obj).val()!=""){
		$("#showImg").css('display','none');
		$("#imgurl").val("");
		if(typeof imgselect!="undefined"){
			$('#thumbnail').imgAreaSelect({
				instance:true,
				disable:true,
				hide:true,
				aspectRatio: '1:<?php echo $thumb_height/$thumb_width;?>',
				onSelectChange: preview,
				x1:0,
				x2:0,
				y1:0,
				y2:0
			});
		}
		$("#showImg img").attr('src','/theme/default/img/no_head.png');
		var fileName_arr=$(obj).val().split('.');
		var fileType=fileName_arr[fileName_arr.length-1];
		var fileTypearray=Array('jpg','JPG','jpeg','JPEG','gif','GIF','png','PNG');
		if(in_array(fileType,fileTypearray)){
			ajaxFileUpload();
		}else{
			alert('文件类型不支持');
		}
	}
}

function preview(img, selection) { 
	var scaleX = <?php echo $thumb_width;?> / selection.width; 
	var scaleY = <?php echo $thumb_height;?> / selection.height;
	
	$('#thumbImg img').css({ 
		width: Math.round(scaleX * imgwidth) + 'px', 
		height: Math.round(scaleY * imgheight) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
	});
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);
}

function in_array(search,array){
    for(var i in array){
        if(array[i]==search){
            return true;
        }
    }
    return false;
}
function ajaxFileUpload(){
	 $.ajaxFileUpload({
		  url:'/users/uploadheadimg/',
		  secureuri:false,
		  fileElementId:'userImg',
		  dataType: 'json',
		  success: function (result){
		  	  if(result.code=='0'){
		  	  		alert(result.error);
		  	  }else{
		  	  		var imgurl=result.img_url;
		  	  		imgwidth=result.width;
		  	  		imgheight=result.height;
		  	  		$("#uploadImg").css('display','none');
		  	  		$("#showImg img").attr('src',imgurl);
		  	  		$("#imgurl").val(imgurl);
					$("#showImg").css('display','block');
					imgselect=$('#thumbnail').imgAreaSelect({
						instance:true,
						enable:true,
						show:true,
						disable:false,
						hide:false,
						aspectRatio: '1:<?php echo $thumb_height/$thumb_width;?>',
						onSelectChange: preview,
						x1:0,
						x2:<?php echo $thumb_width;?>,
						y1:0,
						y2:<?php echo $thumb_width;?>
					});
		  	  }
		  }
	 });
	return false;
}

var clearImg=true;
function clearimg(){
	if($("#imgurl").val()!=""){
		if(clearImg){
			clearImg=false;
		}else{
			return false;
		}
		$.ajax({ url: "/users/clearimg/",
		    		dataType:"json",
		    		type:"POST",
		    		data: { 'img_url': $("#imgurl").val() },
		    		context: $("#imgurl"),
		    		success: function(data){
		    			if(data.code=='1'){
		    				$("#userImg").remove();
		    				$("#uploadImg .am-u-sm-10").append('<input type="file" name="userImg" id="userImg" onchange="UploadImg(this)" />');
		    				$("#uploadImg").css('display','block');
				    		$("#showImg img").attr('src',"/theme/default/img/no_head.png");
							$("#showImg").css('display','none');
							$("#imgurl").val("");
							if(typeof imgselect!="undefined"){
									$('#thumbnail').imgAreaSelect({
										instance:true,
										disable:true,
										hide:true,
										aspectRatio: '1:<?php echo $thumb_height/$thumb_width;?>',
										onSelectChange: preview,
										x1:0,
										x2:0,
										y1:0,
										y2:0
									});
							}
						}else{
							alert(data.error);
						}
						clearImg=true;
		  			},
			        error: function (xhr, ajaxOptions, thrownError) {
			        	clearImg=true;
			            alert("Operation failure! Status=" + xhr.status + " Message=" + thrownError);
			        },
			        traditional: true
		});
	}else{
		$("#userImg").remove();
		$("#uploadImg .am-u-sm-10").append('<input type="file" name="userImg" id="userImg" onchange="UploadImg(this)" />');
		$("#uploadImg").css('display','block');
		$("#showImg img").attr('src',"/theme/default/img/no_head.png");
		$("#showImg").css('display','none');
	}
}

function checkImgform(){
	var imgflag=true;
	$("#showImg input[type=hidden]").each(function(index,val){
		if(val.value==""){
			imgflag=false;
		}
	});
	return imgflag;
}
</script>