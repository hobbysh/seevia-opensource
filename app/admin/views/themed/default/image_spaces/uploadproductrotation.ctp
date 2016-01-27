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
<p class="am-u-md-12"><?php echo $html->link($ld['picture_list'],"/image_spaces/",array("class"=>"am-btn am-btn-sm am-fr"),false,false);?></p>
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
                    <tr><th style="padding-top:12px;"><?php echo $ld['add_watermark']?></th><td><select id="watermark1" data-am-selected onchange="swf_upload_addr()"><option value="0"><?php echo $ld['no_add']?></option><option value="1"><?php echo $ld['image_watermark']?></option><option value="2"><?php echo $ld['text_watermark']?></option></select><a href="/admin/configvalues?type=1"><?php echo $ld['set_up']?></a></td></tr>
                    <tr><th style="padding-top:20px;"><?php echo $ld['upload_picture']?></th>
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
var	img_addr = "/"+document.getElementById("session_id").value;
var image_watermake_upload=document.getElementById("image-watermake-upload").value;//图片上传方式
var MaxFileSize=2;//图片大小限制（M）
var filetype='*.jpg;*.jpeg;*.JPG;*.JPEG';//限制允许上传的图片后缀
var img_id_upload=new Array();//初始化数组，存储已经上传的图片名
var img_is_upload=0;//初始化数组下标
var formData={
    '<?php echo session_name(); ?>':'<?php echo session_id(); ?>',
    'timestamp':'<?php echo $timestamp;?>',
    'token':'<?php echo md5('unique_salt' . $timestamp);?>',
    'product_code':'<?php echo isset($product_code)?$product_code:sv0000; ?>',
    'img_addr':img_addr,
    "watermark":0,
    "watermark_file":"<?php echo !isset($configs['image-watermake-image'])?0:$configs['image-watermake-image']; ?>",
    "watermark_location":"<?php echo !isset($configs['image-watermake-location'])?0:$configs['image-watermake-location']; ?>",
    "watermark_transparency":"<?php echo !isset($configs['image-watermake-transparency'])?'0':$configs['image-watermake-transparency']; ?>",
    "doc_root":"<?php echo $doc_root;?>",
    ".what" : "OKAY",
    "la":"<?php echo isset($la['Language']['locale'])?isset($la['Language']['locale']):'chi' ?>",
    "water_text":"<?php echo !isset($configs['image-watermake-word'])?'':$configs['image-watermake-word'];?>",
    "water_text_font":"<?php echo !isset($configs['image-watermake-typeface'])?'':$configs['image-watermake-typeface']; ?>",
    "water_text_size":"<?php echo !isset($configs['image-watermake-wordsize'])?'':$configs['image-watermake-wordsize']; ?>",
    "water_text_color":"<?php echo !isset($configs['image-watermake-wordcolor'])?'':$configs['image-watermake-wordcolor']; ?>"
};

$(function(){
    $(document).ready(function(){
        if(image_watermake_upload==0){//Flash上传
            $(function(){
                $('#file_upload').uploadify({
                    'formData'     : formData,
                    'auto'     : true,//自动上传
                    'removeTimeout' : 1,//文件队列上传完成1秒后删除
                    'swf'      : '/plugins/uploadify/uploadify.swf',
                    'uploader' : admin_webroot+"photo_category_gallery/product_photo/",
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
                            var img_url=result.img;
                            if(img_url!=""){
                                img_url="/"+img_url;
                                var img_url_arr=img_url.split("/");
                                var img_name=img_url_arr[img_url_arr.length-1];
                                add_rotation_img(img_url,img_name);
                            }
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
                    url:admin_webroot+"photo_category_gallery/product_photo/?"+paramstxt,
                    secureuri:false,
                    fileElementId:'Filedata',
                    dataType: 'text',
                    success: function (result){
                        var data=eval("(" + result + ")");
                        if(data.error==false){
                            var img_url=data.img;
                            if(img_url!=""){
                                img_url="/"+img_url;
                                var img_url_arr=img_url.split("/");
                                var img_name=img_url_arr[img_url_arr.length-1];
                                add_rotation_img(img_url,img_name);
                            }
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
    formData={
        '<?php echo session_name(); ?>':'<?php echo session_id(); ?>',
        'timestamp':'<?php echo $timestamp;?>',
        'token':'<?php echo md5('unique_salt' . $timestamp);?>',
        'product_code':'<?php echo isset($product_code)?$product_code:sv0000; ?>',
        'img_addr':img_addr,
        "watermark":watermark_value,
        "watermark_file":"<?php echo !isset($configs['image-watermake-image'])?0:$configs['image-watermake-image']; ?>",
        "watermark_location":"<?php echo !isset($configs['image-watermake-location'])?0:$configs['image-watermake-location']; ?>",
        "watermark_transparency":"<?php echo !isset($configs['image-watermake-transparency'])?'0':$configs['image-watermake-transparency']; ?>",
        "doc_root":"<?php echo $doc_root;?>",
        ".what" : "OKAY",
        "la":"<?php echo $la['Language']['locale']?>",
        "water_text":"<?php echo !isset($configs['image-watermake-word'])?'':$configs['image-watermake-word'];?>",
        "water_text_font":"<?php echo !isset($configs['image-watermake-typeface'])?'':$configs['image-watermake-typeface']; ?>",
        "water_text_size":"<?php echo !isset($configs['image-watermake-wordsize'])?'':$configs['image-watermake-wordsize']; ?>",
        "water_text_color":"<?php echo !isset($configs['image-watermake-wordcolor'])?'':$configs['image-watermake-wordcolor']; ?>"
    };
}
function add_rotation_img(img_url,img_name){
    var html_str="<li><blockquote><a class='div_img' href='javascript:void(0);'><img src='"+img_url+"'></a><p class='div_img_name'>"+img_name+"</p></blockquote></li>";
    $("#imglist").append(html_str);
}

/*
 var publicobj = "";
 function remove_img(obj,thisid){
 publicobj = obj;
 if(confirm("<?php echo $ld['confirm_delete']?>")){
 YUI().use("io",function(Y) {
 var PhotoCategoryGallery_id = "PhotoCategoryGallery_id="+thisid;
 var sUrl = admin_webroot+"photo_categories/photo_del/?status=1";//访问的URL地址
 var cfg = {
 method: "POST",
 data:PhotoCategoryGallery_id
 };
 var request = Y.io(sUrl, cfg);//开始请求
 var newhtml = "";
 var handleSuccess = function(ioId, o){
 var rep_value = publicobj.parentNode.parentNode.innerHTML;
 if(isFirefox=navigator.userAgent.indexOf("Firefox")>0){
 rep_value = "<li>"+rep_value+"</li>";
 }
 else{
 rep_value = "<LI>"+rep_value+"</LI>";
 }
 var thispreServerData = document.getElementById("preServerData");
 thispreServerDatavalue = thispreServerData.innerHTML;
 thispreServerData.innerHTML = thispreServerDatavalue.replace(rep_value," ");

 }
 var handleFailure = function(ioId, o){
 alert("<?php echo $ld['asynchronous_request_failed']?>");
 }
 Y.on('io:success', handleSuccess);
 Y.on('io:failure', handleFailure);
 });
 }
 }
 */
</script>