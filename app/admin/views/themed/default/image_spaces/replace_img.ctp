<?php
$timestamp = time();//时间标示
echo $javascript->link('/skins/default/js/image_space');
if((isset($configs['image-watermake-upload'])&&$configs['image-watermake-upload']==0)||(!isset($configs['image-watermake-upload']))){ ?>
<script src="/plugins/uploadify/jquery.uploadify.js" type="text/javascript"></script>
<?php }else if(isset($configs['image-watermake-upload'])&&$configs['image-watermake-upload']==1){ ?>
<script src="/plugins/ajaxfileupload.js" type="text/javascript"></script>
<?php }?>
<style type="text/css">
.c_size{width:50px;}
.alonetable label strong,.alonetable label span { font-weight:normal; *position:relative; *top:-5px; }
.uploadify-button{
 
    background:#30ad47;
    border-radius:0;
    line-height:normal;
    height:auto;
    border:none;
    word-spacing:4px;
}
.am-radio, .am-checkbox{display:inline;}
.am-checkbox {margin-top:0px; margin-bottom:0px;}
label{font-weight:normal;}
.am-form-horizontal .am-radio{padding-top:0;position:relative;}
.am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<?php if((isset($configs['image-watermake-upload'])&&$configs['image-watermake-upload']==0)||(!isset($configs['image-watermake-upload']))){ ?>
<script src="/plugins/uploadify/jquery.uploadify.js" type="text/javascript"></script>
<script src="/plugins/uploadify/jquery.uploadify.js" type="text/javascript"></script>
<?php }else if(isset($configs['image-watermake-upload'])&&$configs['image-watermake-upload']==1){ ?>
<script src="/plugins/ajaxfileupload.js" type="text/javascript"></script>
<?php }?>
<style type="text/css">
.c_size{width:50px;}
.alonetable label strong,.alonetable label span { font-weight:normal; *position:relative; *top:-5px; }
em{color:red;}
.am-radio, .am-checkbox{display:inline;}
.am-checkbox {margin-top:0px; margin-bottom:0px;}
label{font-weight:normal;}
.am-form-horizontal .am-radio{padding-top:0;position:relative;}
.am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<input type="hidden" id="session_id" value="<?php echo $admin['id'];?>">
<input type="hidden" id="image-watermake-upload" value="<?php echo isset($configs['image-watermake-upload'])?$configs['image-watermake-upload']:0; ?>" />
    <div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0;">
        <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
            <li><a href="#tablemain"><?php echo $ld['replace_original']?></a></li>
        </ul>
    </div>
    <div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
        <div id="tablemain" class="tablemain am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title"><?php echo $ld['replace_original']?></h4>
            </div>
            <div id="upload_setting" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                    <table class="am-table">
                        <tr><th><?php echo $ld['image_category']?></th><td><select style="width:200px" id="photos_cat_id" onchange="swf_upload_addr()" disabled="true">
                                    <option value="0"><?php echo $ld['select_a_category']?></option>
                                    <?php foreach( $photo_category_list as $k=>$v ){?>
                                        <option value="<?php echo $v['PhotoCategory']['id'];?>" <?php if($image_info["PhotoCategoryGallery"]["photo_category_id"] == $v['PhotoCategory']['id']){echo "selected";}?> ><?php echo $v['PhotoCategoryI18n']['name'];?></option>
                                    <?php }?>
                                </select>
                                <input type="hidden" id="img_id" value='<?php echo $image_info["PhotoCategoryGallery"]["id"]?>' name="data[PhotoCategoryGallery][id]"/>
                            </td></tr>
                        <tr><th><?php echo $ld['whether_add_watermark']?></th><td><label class="am-radio am-success"><input data-am-ucheck type="radio" name="watermark1" value="0" onclick="swf_upload_addr()" checked /><?php echo $ld['no']?></label><label style="margin-left:10px;" class="am-radio am-success"><input data-am-ucheck type="radio" name="watermark1" value="1" onclick="swf_upload_addr()"/><?php echo $ld['yes']?></label></td></tr>
                        <tr>
                            <th><?php echo $ld['original_image']?></th><td valign="top" ><span class="gallery" id="preServerData"></span><?php echo $html->image($image_info["PhotoCategoryGallery"]["img_small"]); ?></td>
                        </tr>
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
    "la":"<?php echo $la['Language']['locale']?>",
    "img_id":document.getElementById("img_id").value,//有此项为替换 没有则为新上传
    "img_small_addr":'<?php echo $image_info["PhotoCategoryGallery"]["img_small"] ?>',
    "img_detail_addr":'<?php echo $image_info["PhotoCategoryGallery"]["img_detail"] ?>',
    "img_big_addr":'<?php echo $image_info["PhotoCategoryGallery"]["img_big"] ?>',
    "img_original_addr":'<?php echo $image_info["PhotoCategoryGallery"]["img_original"] ?>',
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
                    'uploader' : admin_webroot+"photo_category_gallery/photo_replace",
                    'method'   : 'post',//方法，服务端可以用$_POST数组获取数据
                    'buttonText' : '选择图片',//设置按钮文本
                    'multi'    : false,//允许同时上传多张图片
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
        'img_addr':img_addr,
        "watermark":watermark_value,
        "watermark_file":"<?php echo !isset($configs['image-watermake-image'])?0:$configs['image-watermake-image']; ?>",
        "watermark_location":"<?php echo !isset($configs['image-watermake-location'])?0:$configs['image-watermake-location']; ?>",
        "watermark_transparency":"<?php echo !isset($configs['image-watermake-transparency'])?'0':$configs['image-watermake-transparency']; ?>",
        "doc_root":"<?php echo $doc_root;?>",
        "photo_category_id":document.getElementById("photos_cat_id").value,
        ".what" : "OKAY",
        "la":"<?php echo $la['Language']['locale']?>",
        "img_id":document.getElementById("img_id").value,//有此项为替换 没有则为新上传
        "img_small_addr":'<?php echo $image_info["PhotoCategoryGallery"]["img_small"] ?>',
        "img_detail_addr":'<?php echo $image_info["PhotoCategoryGallery"]["img_detail"] ?>',
        "img_big_addr":'<?php echo $image_info["PhotoCategoryGallery"]["img_big"] ?>',
        "img_original_addr":'<?php echo $image_info["PhotoCategoryGallery"]["img_original"] ?>',
        "water_text":"<?php echo !isset($configs['image-watermake-word'])?'':$configs['image-watermake-word'];?>",
        "water_text_font":"<?php echo !isset($configs['image-watermake-typeface'])?'':$configs['image-watermake-typeface']; ?>",
        "water_text_size":"<?php echo !isset($configs['image-watermake-wordsize'])?'':$configs['image-watermake-wordsize']; ?>",
        "water_text_color":"<?php echo !isset($configs['image-watermake-wordcolor'])?'':$configs['image-watermake-wordcolor']; ?>"
    };
}
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
                    url:admin_webroot+"photo_category_gallery/photo_replace/?"+paramstxt,
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
</script>