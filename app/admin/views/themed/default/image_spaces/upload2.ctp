<?php
$timestamp = time();//时间标示
echo $javascript->link('/skins/default/js/image_space');
if((isset($configs['image-watermake-upload'])&&$configs['image-watermake-upload']==0)||(!isset($configs['image-watermake-upload']))){ ?>
<script src="/plugins/uploadify/jquery.uploadify.js" type="text/javascript"></script>
<?php }else if(isset($configs['image-watermake-upload'])&&$configs['image-watermake-upload']==1){ ?>
<script src="/plugins/ajaxfileupload.js" type="text/javascript"></script>
<?php } ?>
<style type="text/css">
/*
.c_size{width:50px;}
.uploadify-button{
	padding:5px 0;
	background:#30ad47;
	border-radius:0;
	line-height:normal;
	height:auto;
	border:none;
	word-spacing:4px;
}
.uploadify{
	padding-top:1.5em;
}
*/
    .alonetable label strong,.alonetable label span { font-weight:normal; *position:relative; *top:-5px; }
    #size .c_size{width:50px;float:left;margin:0 5px}
    #size .c_txt{float:left;margin-top:5px;}
    em{color:red;}
    .am-radio, .am-checkbox{display:inline;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}

</style>
<input type="hidden" name="type" id="type" value="<?php echo isset($type)?$type:''?>">
<input type="hidden" id="session_id" value="<?php echo $admin['id'];?>">
<input type="hidden" id="image-watermake-upload" value="<?php echo isset($configs['image-watermake-upload'])?$configs['image-watermake-upload']:0; ?>" />
<p class="am-u-md-12"><?php echo $html->link($ld['picture_list'],"/image_spaces/",array("class"=>"am-btn am-radius am-btn-sm am-fr"),false,false);?></p>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#tablemain"><?php echo $ld['upload_setting']?></a></li>
        <li><a href="#picture"><?php echo $ld['picture_preview']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="tablemain" class="tablemain am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['upload_setting']?></h4>
        </div>
        <div id="upload_setting" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table id="t1" class="am-table">
                    <tr><th><?php echo $ld['image_category']?></th><td><div id='select'><select data-am-selected id="photos_cat_id" onchange="get_size(this.value);"><option value="0"><?php echo $ld['select_a_category'];?></option><?php foreach( $photo_category_list as $k=>$v ){?><option <?php if(isset($img_cat)){ echo $img_cat==$v['PhotoCategory']['id']?'selected':'';}?> value="<?php echo $v['PhotoCategory']['id'];?>" <?php if($photo_category_id == $v['PhotoCategory']['id']){echo "selected";}?> ><?php echo $v['PhotoCategoryI18n']['name']?></option><?php }?></select><input type="button" class="am-btn am-btn-success am-radius am-btn-sm" data-am-modal="{target: '#photocat', closeViaDimmer: 0, width: 400, height: 230}" value="<?php echo $ld['quick_add_category']?>" /></div></td></tr>
                    <tr><th><?php echo $ld['thumbnail_size']?></th>
                        <td>
                            <label class="am-radio am-success"><input data-am-ucheck name='custom_size' type='radio' value='0' checked onclick='custom_size(this.value)'><?php echo $ld['system_default']?></label>
                            <label style="margin-left:10px;" class="am-radio am-success"><input data-am-ucheck name='custom_size' type='radio' value='1' onclick='custom_size(this.value)' <?php if($custom==1) echo 'checked'?>><?php echo $ld['custom']?></label>
                        </td></tr>
                    <tr id='size' name='size' style='display:none'><th><?php echo $ld['big_picture']?></th><td><p class="c_txt"><?php echo $ld['width']?></p><input class='c_size' type='text' id='big_img_width'  value='<?php echo $big_img_width;?>' onchange="swf_upload_addr()"/><p class="c_txt"> X <?php echo $ld['height']?></p><input class='c_size' type='text' id='big_img_height' value='<?php echo $big_img_height;?>' onchange="swf_upload_addr()"/><p class="c_txt"><?php echo $ld['pixel']?></p><td></tr>
                    <tr id='size' name='size' style='display:none'><th><?php echo $ld['middle_picture']?></th><td><p class="c_txt"><?php echo $ld['width']?></p><input class='c_size' type='text' id='mid_img_width'  value='<?php echo $mid_img_width;?>' onchange="swf_upload_addr()"/><p class="c_txt"> X <?php echo $ld['height']?></p><input class='c_size' type='text' id='mid_img_height' value='<?php echo $mid_img_height;?>' onchange="swf_upload_addr()"/><p class="c_txt"><?php echo $ld['pixel']?></p><td></tr>
                    <tr id='size' name='size' style='display:none'><th><?php echo $ld['small_picture']?></th><td><p class="c_txt"><?php echo $ld['width']?></p><input class='c_size' type='text' id='small_img_width'  value='<?php echo $small_img_width;?>' onchange="swf_upload_addr()"/><p class="c_txt"> X <?php echo $ld['height']?></p><input class='c_size' type='text' id='small_img_height' value='<?php echo $small_img_height;?>' onchange="swf_upload_addr()"/><p class="c_txt"><?php echo $ld['pixel']?></p><td></tr>
                    <?php if(isset($configs['enabled_watermark']) && $configs['enabled_watermark'] == 1){?>
                        <tr><th><?php echo $ld['add_watermark']?></th><td><select data-am-selected id="watermark1" onchange="swf_upload_addr()"><option value="0"><?php echo $ld['no_add']?></option><option value="1"><?php echo $ld['image_watermark']?></option><option value="2"><?php echo $ld['text_watermark']?></option></select><a target="_blank" href="/admin/configvalues?type=1"><?php echo $ld['set_up']?></a></td></tr>
                    <?php }?>
                    <tr><th><?php echo $ld['upload_picture']?></th>
                        <td>
                            <?php if((isset($configs['image-watermake-upload'])&&$configs['image-watermake-upload']==0)||(!isset($configs['image-watermake-upload']))){
                                ?>
                                <input type="file" id="file_upload" name="file_upload" />
                            <?php }else{ ?>
                                <input type="file" name="Filedata" onchange="ajaxFileUpload(this)" />
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div id="picture" class="tablemain am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title"><?php echo $ld['picture_preview']?></h4>
        </div>
        <div id="picture_preview" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr><td><div class="imagelist"><ul id="imglist"></ul></div></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="am-modal am-modal-no-btn" tabindex="-1" name="photocat" id="photocat">
	<div class="am-modal-dialog">
        <div class="am-modal-hd"><?php echo $ld['add_category']?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
    		<form id='catform1' method="POST">
            <table class="am-table" style="text-align:left;">
                <?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                <input name="data[PhotoCategoryI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
	            <?php }}?>
	            <tr><th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['picture_category_name']?></th></tr>
	            <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
	                <tr><td><input id="name_<?php echo $v['Language']['locale'];?>" name="data[PhotoCategoryI18n][<?php echo $k;?>][name]" type="text" value=""><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></td></tr>
	            <?php }}?>
	            <tr><th><?php echo $ld['sort']?></th><td><input type="text" name="data[PhotoCategory][orderby]" value="<?php echo isset($photo_categories_info['PhotoCategory'])?$photo_categories_info['PhotoCategory']['orderby']:'';?>" /></td></tr>
            </table>
            <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" name="changeDomainRankButton" value="<?php echo $ld['confirm']?>" onclick="javascript:doinsertphotocat();">
            </form>
		</div>
    </div>
</div>

<script type="text/javascript">
var img_addr = "/"+document.getElementById("photos_cat_id").value+"/"+document.getElementById("session_id").value;//图片保存路径
var image_watermake_upload=document.getElementById("image-watermake-upload").value;//图片上传方式
var MaxFileSize=2;//图片大小限制（M）
var filetype='*.gif; *.jpg; *.png; *.jpeg; *.GIF; *.JPG; *.PNG; *.JPEG';//限制允许上传的图片后缀

var img_id_upload=new Array();//初始化数组，存储已经上传的图片名
var img_is_upload=0;//初始化数组下标
var formData={
	'<?php echo session_name(); ?>':'<?php echo session_id(); ?>',
	'timestamp':'<?php echo $timestamp;?>',
	'token':'<?php echo md5('unique_salt' . $timestamp);?>',
	'img_addr':img_addr,
	"watermark":0,
	"watermark_file":"<?php echo !isset($configs['image-watermake-image'])?0:$configs['image-watermake-image']; ?>",
	"watermark_location":"<?php echo !isset($configs['image-watermake-location'])?0:$configs['image-watermake-location']; ?>",
	"watermark_transparency":"<?php echo !isset($configs['image-watermake-transparency'])?'0':$configs['image-watermake-transparency']; ?>",
	"doc_root":"<?php echo $doc_root;?>",
	"photo_category_id":document.getElementById("photos_cat_id").value,
	".what" : "OKAY",
	"la":"<?php echo isset($la['Language']['locale'])?isset($la['Language']['locale']):'chi' ?>",
	"small_img_height":"<?php echo $small_img_height;?>",
	"small_img_width":"<?php echo $small_img_width;?>",
	"mid_img_height":"<?php echo $mid_img_height;?>",
	"mid_img_width":"<?php echo $mid_img_width;?>",
	"big_img_height":"<?php echo $big_img_height;?>",
	"big_img_width":"<?php echo $big_img_width;?>",
	"water_text":"<?php echo !isset($configs['image-watermake-word'])?'':$configs['image-watermake-word'];?>",
	"water_text_font":"<?php echo !isset($configs['image-watermake-typeface'])?'':$configs['image-watermake-typeface']; ?>",
	"water_text_size":"<?php echo !isset($configs['image-watermake-wordsize'])?'':$configs['image-watermake-wordsize']; ?>",
	"water_text_color":"<?php echo !isset($configs['image-watermake-wordcolor'])?'':$configs['image-watermake-wordcolor']; ?>"
};

$(function(){
	$(document).ready(function(){
		custom_size(<?php echo @$custom?>);
		
		if(image_watermake_upload==0){//Flash上传
			$(function(){
				$('#file_upload').uploadify({
					'formData'     : formData,
					'auto'     : true,//自动上传
	    			'removeTimeout' : 1,//文件队列上传完成1秒后删除
					'swf'      : '/plugins/uploadify/uploadify.swf',
					'uploader' : admin_webroot+"photo_category_gallery/photo/",
					'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
					'buttonText' : '选择图片',//设置按钮文本
			        'multi'    : true,//允许同时上传多张图片
			        'fileTypeDesc' : 'Is Not Image Files',//只允许上传图像
			        'fileTypeExts' : filetype,//限制允许上传的图片后缀
			        'fileSizeLimit' : MaxFileSize*1024,//限制上传的图片,默认单位KB
			        'onUploadSuccess' : function(file, data, response) {//每次成功上传后执行的回调函数，从服务端返回数据到前端
						eval("result="+data);
						if(result.error==false){
							img_id_upload[img_is_upload]=data;
							img_is_upload++;
							image_data_save(data);//保存图片数据，加载预览显示
						}else{
							alert(result.error_img);
						}
			        },
			         'onQueueComplete' : function(queueData) {//上传队列全部完成后执行的回调函数
			         		img_is_upload=0;
			         		img_id_upload=new Array();
			        },
			        'onSelect':function (event, queueID, fileObj){
			        	$("#file_upload").uploadify('settings','formData',formData);//动态设置参数
			        }
				});
				$(".uploadify-button").addClass("am-btn am-btn-success am-radius am-btn-sm").css("height",'auto').css("width",'auto');
			});
		}
	});
});

//JS上传
function ajaxFileUpload(target){
	//组合参数为字符串
	var paramstxt="";
	for(var item in formData){
		paramstxt=paramstxt+item+"="+formData[item]+"&";
	}
	if(paramstxt!=""){
		paramstxt=paramstxt.substring(0,paramstxt.length-1);
	}
	var isIE = /msie/i.test(navigator.userAgent) && !window.opera;
	var max_file_size=MaxFileSize*1024*1024;//图片大小限制
	if(target.id==""){target.id="Filedata";}
	if(checkfile(target.id)){
		try{
			var fileSize = 0; 
			if (isIE && !target.files) { 
				var filePath = target.value; 
				var fileSystem = new ActiveXObject("Scripting.FileSystemObject"); 
				var file = fileSystem.GetFile (filePath); 
				fileSize = file.Size; 
			}else{ 
				fileSize = target.files[0].size; 
			}
			if(fileSize==0||fileSize>max_file_size){
				alert("<?php echo $ld['file_attachment_size_exceeds']?>");
			}else{
				$.ajaxFileUpload({
				  url:admin_webroot+"photo_category_gallery/photo/?"+paramstxt,
				  secureuri:false,
				  fileElementId:'Filedata',
				  dataType: 'text',
				  success: function (result){
				  	var data=eval("(" + result + ")");
					if(data.error==false){
						image_data_save(result);//保存图片数据，加载预览显示
						$("#Filedata").parent().html("<input type='file' name='Filedata' id='Filedata' onchange='ajaxFileUpload(this)' />");
					}else{
						alert(data.error_img);
					}
				  },
				  error: function (data, status, e){//服务器响应失败处理函数{
				  	  alert('上传失败');
		          }
			 	});
			}
		}catch(e){
			alert(ie_getfilesize_error);
		}
	}
}

//JS检验文件合法性
function checkfile(Id){
	var filepath = document.getElementById(Id).value;  
	var re = /(\\+)/g; 
	var filename=filepath.replace(re,"#");
	//对路径字符串进行剪切截取
	var one=filename.split("#");
	//获取数组中最后一个，即文件名
	var two=one[one.length-1];
	//再对文件名进行截取，以取得后缀名
	var three=two.split(".");
	 //获取截取的最后一个字符串，即为后缀名
	var last=three[three.length-1];
	//返回符合条件的后缀名在字符串中的位置
	var rs=filetype.indexOf(last);
	//如果返回的结果大于或等于0，说明包含允许上传的文件类型
	if(rs>=0){
		return true;
	}else{
		alert("<?php echo $ld['file_format_error']?>");
		document.getElementById(Id).value = "";
		return false;
	}
}

//组合参数
function swf_upload_addr(){
	var watermark1 = document.getElementById("watermark1");
	var watermark_value = watermark1==null?0:watermark1.options[watermark1.selectedIndex].value;
	var size_radio = document.getElementsByName('custom_size');
	var custom=0;
	for (var i=0; i < size_radio.length; i++) {
		if(size_radio[i].checked){
			custom = size_radio[i].value;
		}
	};
	var small_img_height = custom==1?document.getElementById('small_img_height').value:<?php echo $small_img_height_sys;?>,
		small_img_width = custom==1?document.getElementById('small_img_width').value:<?php echo $small_img_width_sys;?>,
		mid_img_height = custom==1?document.getElementById('mid_img_height').value:<?php echo $mid_img_height_sys?>,
		mid_img_width = custom==1?document.getElementById('mid_img_width').value:<?php echo $mid_img_width_sys;?>,
		big_img_height = custom==1?document.getElementById('big_img_height').value:<?php echo $big_img_height_sys;?>,
		big_img_width = custom==1?document.getElementById('big_img_width').value:<?php echo $big_img_width_sys;?>;
	
	formData={
		'<?php echo session_name(); ?>':'<?php echo session_id(); ?>',
		'timestamp':'<?php echo $timestamp;?>',
		'token':'<?php echo md5('unique_salt' . $timestamp);?>',
		'img_addr':img_addr,
		"watermark":watermark_value,
		"watermark_file":"<?php echo !isset($configs['image-watermake-image'])?0:$configs['image-watermake-image']; ?>",
		"watermark_location":"<?php echo !isset($configs['image-watermake-location'])?0:$configs['image-watermake-location']; ?>",
		"watermark_transparency":"<?php echo !isset($configs['image-watermake-transparency'])?'0':$configs['image-watermake-transparency']; ?>",
		"doc_root":"<?php echo $doc_root;?>",
		"photo_category_id":document.getElementById("photos_cat_id").value,
		".what" : "OKAY",
		"la":"<?php echo $la['Language']['locale']?>",
		"small_img_height":small_img_height,
		"small_img_width":small_img_width,
		"mid_img_height":mid_img_height,
		"mid_img_width":mid_img_width,
		"big_img_height":big_img_height,
		"big_img_width":big_img_width,
		"water_text":"<?php echo !isset($configs['image-watermake-word'])?'':$configs['image-watermake-word'];?>",
		"water_text_font":"<?php echo !isset($configs['image-watermake-typeface'])?'':$configs['image-watermake-typeface']; ?>",
		"water_text_size":"<?php echo !isset($configs['image-watermake-wordsize'])?'':$configs['image-watermake-wordsize']; ?>",
		"water_text_color":"<?php echo !isset($configs['image-watermake-wordcolor'])?'':$configs['image-watermake-wordcolor']; ?>"
	};
}

//设置缩略图大小
function custom_size(check){
	var trs = document.getElementsByName('size');
	if(check==1){
		for (var i=0; i < trs.length; i++) {
			trs[i].style.display='';
		};
	}else{
		for (var i=0; i < trs.length; i++) {
			trs[i].style.display='none';
		};
	}
	swf_upload_addr();
}

//获取缩略图大小参数
function get_size(cat_id){
	$.ajax({
	  type: 'POST',
	  url: "/admin/image_spaces/get_cat_size/",
	  data: {"cat_id":cat_id},
	  dataType:"json",
  	  success: function(data){
  	  	try{
            $('#small_img_height').val(data.content.small_img_height);
            $('#small_img_width').val(data.content.small_img_width);
            $('#mid_img_height').val(data.content.mid_img_height);
            $('#small_img_width').val(data.content.small_img_width);
            $('#big_img_height').val(data.content.big_img_height);
            $('#big_img_width').val(data.content.big_img_width);
        }catch (e){
            alert("<?php echo $ld['object_transform_failed']?>");
        }
		swf_upload_addr();
  	  }
	});
}
</script>
<script>
//图片选取
var id_str = "<?php echo $id_str;?>";
function selected_image(obj,img_detail,img_original,img_name){
	var img_small=obj.src;
	var server_host="<?php echo isset($server_host)?$server_host:''; ?>";
	if(server_host==""){
		server_host="http://"+window.location.host;
	}
	img_small=img_small.replace("http://"+window.location.host,'');
	if(document.getElementById("type").value=="OpenElementDescription"){//素材描述信息自动拼接上域名
        window.opener.document.getElementById(id_str).value = server_host+img_small;
    	}else if(document.getElementById("type").value=="travel"){
		if(window.opener.document.getElementById("upload_now_div")){
			window.opener.document.getElementById("upload_now_div").style.display="none";
		}
		var pp = window.opener.document.getElementById(id_str);
		var li = window.opener.document.createElement("li");
		var k = pp.getElementsByTagName('li').length;
		li.innerHTML="<blockquote><span class='closebtn'>×</span><input type='hidden' id='data[TravelGallary][small_img][]' name='data[TravelGallary][small_img][]' value='"+img_originalz+"'><input type='hidden' id='data[TravelGallary][big_img][]' name='data[TravelGallary][big_img][]' value='"+img_detail+"'><input type='hidden' id='data[TravelGallary][orignal_img][]' name='data[TravelGallary][orignal_img][]' value='"+img_original+"'><a class='div_img'><img src='"+img_small+"'></a><p class='div_img_detail'><input type='text' id = 'data[TravelGallary][description][]' name = 'data[TravelGallary][description][]' value='"+img_name+"'/></p>"
		+ "<p><?php echo $ld['sort']?><input style='width:50px' type='text' id = 'data[TravelGallary][orderby][]' name = 'data[TravelGallary][orderby][]' value='50'; /></p><p class='div_img_label_tag'><input type='button' name='data[TravelGallary][tags]["+(k-1)+"][]' class='dishow' value='+添加' /><input type='text' /></p></blockquote>";
		var p =  window.opener.document.getElementById(id_str).getElementsByTagName('li')[k-1];
		var pclone = p.cloneNode(true);
		pp.removeChild(p);
	    pp.appendChild(li);
		pp.appendChild(pclone);
	}else if(document.getElementById("type").value == "article"){
		if(window.opener.document.getElementById("upload_now_div")){
			window.opener.document.getElementById("upload_now_div").style.display="none";
		}
		var pp = window.opener.document.getElementById(id_str);
		var li = window.opener.document.createElement("li");
		var k = pp.getElementsByTagName('li').length;
		var input ="";
		<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			input += "<p class='div_img_detail'><input type='text' name = 'data[ArticleGalleryI18n]["+k+"][<?php echo $k;?>][description]' value='"+img_name+"'/><?php if(sizeof($backend_locales)>1){?><span class='lang'><?php echo $ld[$v['Language']['locale']]?></span><?php }?></p>";
		<?php }}?>
		//
		li.innerHTML="<blockquote><span class='closebtn'>×</span><input type='hidden'  name='data[ArticleGallery]["+k+"][img_original]' value='"+img_original+"'><a class='div_img'><img src='"+img_original+"'></a>"+input
		+ "<p><?php echo $ld['sort']?><input style='width:50px' type='text'  name = 'data[ArticleGallery]["+k+"][orderby]' value='50'; /></p></blockquote>";
		var p =  window.opener.document.getElementById(id_str).getElementsByTagName('li')[k-1];
		var pclone = p.cloneNode(true);
		pp.removeChild(p);
	    pp.appendChild(li);
		pp.appendChild(pclone);
	}else{
		var i=0;
		window.opener.document.getElementById(id_str).value = img_original;
		if(window.opener.document.getElementById("show_"+id_str)){
			window.opener.document.getElementById("show_"+id_str).src= img_original;
			window.opener.document.getElementById("show_"+id_str).parentNode.className += " img_exist";
			//window.opener.document.getElementById("show_"+id_str).parentNode.style.width = window.opener.document.getElementById(id_str).width;
		}
		if(window.opener.document.getElementById("show1_"+id_str)){
			window.opener.document.getElementById("show1_"+id_str).src= img_original;
			window.opener.document.getElementById("show1_"+id_str).parentNode.className += " img_exist";
			window.opener.document.getElementById("show1_"+id_str).parentNode.parentNode.style.display="block";
			//window.opener.document.getElementById("show_"+id_str).parentNode.style.width = window.opener.document.getElementById(id_str).width;
		}
		if(window.opener.document.getElementById("show_img_detail_"+id_str)){
			window.opener.document.getElementById("show_img_detail_"+id_str).src = img_detail;
			window.opener.document.getElementById("show_img_detail_"+id_str).parentNode.className += " img_exist";
			i=1;
		}
		if(window.opener.document.getElementById("related_detail_"+id_str)){
			window.opener.document.getElementById("related_detail_"+id_str).value = img_detail;
			window.opener.document.getElementById("related_detail_"+id_str).parentNode.className += " img_exist";
			i=1;
		}
		if(window.opener.document.getElementById("img_detail_"+id_str)){
			if(window.opener.document.getElementById(id_str+"_td") && window.opener.document.getElementById("img_detail_"+id_str).value == ""){
				var tmp=window.opener.document.getElementById(id_str+"_td").value;
				window.opener.document.getElementById(id_str+"_dl").style.display = "block";
				window.opener.document.getElementById(id_str+"_des").style.display = "block";
				window.opener.document.getElementById(id_str+"_tag").style.display = "block";
				window.opener.document.getElementById(id_str+"_oy").style.display = "block";
				window.opener.document.getElementById(id_str+"_tag").outerHTML = window.opener.document.getElementById(id_str+"_tag").outerHTML.replace( "name=\"\"",   "name=\"on\" ");
				window.opener.document.getElementById('is_'+tmp).click();
			}
			window.opener.document.getElementById("img_detail_"+id_str).value = img_detail;
			window.opener.document.getElementById("img_detail_"+id_str).parentNode.className += " img_exist";
			i=1;
		}
		if(window.opener.document.getElementById("show_img_big_"+id_str)){
			window.opener.document.getElementById("show_img_big_"+id_str).src = img_original;
			window.opener.document.getElementById("show_img_big_"+id_str).parentNode.className += " img_exist";
			i=1;
		}
		if(window.opener.document.getElementById("img_big_"+id_str)){
			window.opener.document.getElementById("img_big_"+id_str).value = img_original;
			window.opener.document.getElementById("img_big_"+id_str).parentNode.className += " img_exist";
			i=1;
		}
		if(i==0){
			window.opener.document.getElementById(id_str).value = img_original;
			//window.opener.document.getElementById(id_str).parentNode.style.display = "none";
		}
		//window.opener.document.getElementById(id_str).parentNode.getElementsByTagName("div")[0].className += " img_exist";
	}
	window.close();
}
</script>