<?php
    echo $javascript->link('/skins/default/js/image_space');
?>
<style type="text/css" media="screen">
.imagelistnew ul, .imagelistnew li {
    display:block;
}

.imagelistnew li {
    display:inline-block;
    vertical-align:top;
    margin: 10px 0px 0px;
}

.imagelistnew .div_img {
    display:block;
    text-align: center;
}

.imagelistnew .div_img_name {
    display:block;
    overflow:hidden;
    padding-top:5px;
}

.imagelistnew .div_img_name a {
    float:none;
    padding:0;
}

.imagelistnew .div_img img {
	max-height: 100px;
	max-width: 100%;
	height:auto;
}

.imagelistnew .div_img_add {
    color:#333;
    display:block;
    text-align:center;
    height:138px;
    width:138px;
    line-height:138px;
    cursor:pointer;
    border:1px solid #ccc;
    background:#f2f2f2;
}

.imagelistnew .div_img_add:hover {
    color:#FF7E00;
    text-decoration:none;
}

.imagelistnew a {
    color:#21964D;
}

.imagelistnew a:hover {
    color:#FF7E00;
    text-decoration:underline;
}

.imagelistnew .div_img_name {
    display:block;
    overflow:hidden;
    padding-top:5px;
}

.imagelistnew .div_img_name a {
    float:none;
    padding:0;
}

.imagelistnew .div_img_detail {
    padding-top:5px;
}

.imagelistnew p {
    margin-top:0px;
    margin-bottom:2px;
}

.imagelistnew p input[type="text"] {
    width:100px;
}

.imagelistnew .div_img_detail input[type="text"] {
    width:134px;
}

.imagelistnew p a {
    float:right;
    padding:6px 0;
}

.imagelistnew .div_img_btn {
    padding:7px 0;
}

.imagelistnew .div_img_btn a {
    display:inline-block;
    padding:3px;
    padding-left:0;
    margin:0;
    float:none;
}

.imagelistnew .div_img_btn p a {
    float:right;
    padding:6px 0;
}

.imagelistnew textarea {
    width:130px;
}

.imagelistnew li .div_img_name {
    width:95%;
    margin:0 auto;
    text-overflow:ellipsis;
    white-space:nowrap;
    overflow:hidden;
}

.imagelistnew .div_img_name .btn_to_uninstall {
    color:gray;
    cursor:pointer;
}

.imagelistnew .div_img_name .btn_to_uninstall:hover {
    color:#FF7E00;
}

.imagelistnew .div_img_name .btn_to_set {
    float:right;
    color:#FF7E00;
    cursor:pointer;
}

.imagelistnew .div_img_name .btn_to_set:hover {
    color:#FF7E00;
}

.imagelistnew .div_img_name .btn_status {
    padding-right:10px;
    background:no-repeat right center;
    float:right;
    margin-right:5px;
}
.set_img_size{
    background: #f37b1d none repeat scroll 0 0;
    margin: 0 auto;
    position: relative;
    margin-top: -100px;
    z-index: 1000;
}
.set_img_size a{
    display: inline-block;
    margin: 0;
    padding: 3px 3px 3px 0;
    color:#fff;
}
.set_img_size a:hover{color:#fff;text-decoration: underline;}
</style>
<input type="hidden" name="type" id="type" value="<?php echo isset($type)?$type:''?>">
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><?php echo $html->link($ld['select_pictures'],"/image_spaces/select_image/".$id_str."/".$orderby."/0/".$photo_category_id."/".$search_key_word."/",array('class'=>($cat==0?'am-active':'')),false,false);?></li>
        <li><?php echo $html->link($ld['today_upload'],"/image_spaces/select_image/".$id_str."/".$orderby."/1/".$photo_category_id."/".$search_key_word."/",array('class'=>($cat==1?'am-active':'')),false,false);?></li>
        <li><?php echo $html->link($ld['upload_in_three_days'],"/image_spaces/select_image/".$id_str."/".$orderby."/2/".$photo_category_id."/".$search_key_word."/",array('class'=>($cat==2?'am-active':'')),false,false);?></li>
        <li><?php echo $html->link($ld['upload_in_seven_days'],"/image_spaces/select_image/".$id_str."/".$orderby."/3/".$photo_category_id."/".$search_key_word."/",array('class'=>($cat==3?'am-active':'')),false,false);?></li>
        <li><?php echo $html->link($ld['upload_in_thirty_days'],"/image_spaces/select_image/".$id_str."/".$orderby."/4/".$photo_category_id."/".$search_key_word."/",array('class'=>($cat==4?'am-active':'')),false,false);?></li>
        <li><?php echo $html->link($ld['upload_before_january'],"/image_spaces/select_image/".$id_str."/".$orderby."/5/".$photo_category_id."/".$search_key_word."/",array('class'=>($cat==5?'am-active':'')),false,false);?></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div class="am-panel am-panel-default">
        <div class="am-panel-collapse am-collapse am-in">
            <p class="am-u-md-12"><?php if($svshow->operator_privilege("image_spaces_upload")){
                if(isset($type)){ $path = '/image_spaces/upload2/'.$id_str.'/?type='.$type;}else{ 	$path ="/image_spaces/upload2/".$id_str;  }
            echo $html->link($ld['upload_picture'],$path,array("class"=>"am-btn am-btn-warning am-btn-sm am-fl"),'',false,false);}?></p>
            <form class='am-form am-form-inline am-form-horizontal'>
                <div class="listsearch">
                    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0">
                        <li>
                            <div class="am-u-sm-12">
                                <select id="photo_category_id" style="width:100px;float:left;margin-right:5px;">
                                    <option value="0"><?php echo $ld['select_pictures']?></option>
                                    <?php foreach($photo_category_data as $k=>$v){?>
                                        <option value='<?php echo $v["PhotoCategory"]["id"];?>' <?php if($photo_category_id==$v["PhotoCategory"]["id"]){echo "selected";}?>><?php echo $v["PhotoCategoryI18n"]["name"];?></option>
                                    <?php }?>
                                </select>
                                <input type="text" style="width:150px;float:left;margin-right:5px;" id="search_key_word" value="<?php echo $search_key_word;?>" />
                                <input type="button" style="float:left;" class="am-btn am-btn-success am-btn-sm" value="<?php echo $ld['search']?>" onclick="select_image_search()" />
                            </div>
                        </li>
                        <li>
                            <label class="am-u-sm-3 am-form-label"><?php echo $ld['sort_by']?></label>
                            <div class="am-u-sm-9">
                                <select id="orderby_num" onchange="select_image_search();" style="width:230px;">
                                    <option value="0" <?php if($orderby==0){echo "selected";}?>><?php echo $ld['orderby_time_from_late_morning']?></option>
                                    <option value="1" <?php if($orderby==1){echo "selected";}?>><?php echo $ld['orderby_time_from_morning_to_night']?></option>
                                    <option value="2" <?php if($orderby==2){echo "selected";}?>><?php echo $ld['orderby_picture_size_desc']?></option>
                                    <option value="3" <?php if($orderby==3){echo "selected";}?>><?php echo $ld['orderby_picture_size_asc']?></option>
                                    <option value="4" <?php if($orderby==4){echo "selected";}?>><?php echo $ld['orderby_modify_time_desc']?></option>
                                    <option value="5" <?php if($orderby==5){echo "selected";}?>><?php echo $ld['orderby_modify_time_asc']?></option>
                                    <option value="6" <?php if($orderby==6){echo "selected";}?>><?php echo $ld['orderby_picture_name_desc']?></option>
                                    <option value="7" <?php if($orderby==7){echo "selected";}?>><?php echo $ld['orderby_picture_name_asc']?></option>
                                </select>
                            </div>
                        </li>
                    </ul>
                </div>
            </form>
            <div id="applist" class="imagelistnew am-panel-bd" style="padding-bottom:0;">
                <?php if(empty($photo_category_gallery_list)){ ?>
                    <div class="infotips"><?php echo $html->link($ld['no_picture']." ".$ld['click_upload_now'],"/image_spaces/upload/".$photo_category_id,false,false);?></div>
                <?php }else{?>
                    <ul class="am-avg-lg-4 am-avg-md-4 am-avg-sm-3"><?php
                        foreach($photo_category_gallery_list as $k=>$v){ ?>
                        <li>
                            <a class="div_img" href="javascript:void(0)">
                                <img src="<?php echo $v['PhotoCategoryGallery']['img_small'];?>" id="img<?php echo $v['PhotoCategoryGallery']['id'];?>" onclick="img_small=null;selected_image(this,'<?php echo $v['PhotoCategoryGallery']['img_detail'];?>','<?php echo $v['PhotoCategoryGallery']['img_big'];?>','<?php echo $v['PhotoCategoryGallery']['img_original'];?>','<?php echo $v['PhotoCategoryGallery']['name'];?>');">
                            </a>
                            <p class="div_img_name"><?php echo $v['PhotoCategoryGallery']['name'];?></p>
                            <p class="div_img_btn">
                                <?php
                                if($svshow->operator_privilege("image_spaces_remove")){
                                    echo $html->link($ld['delete'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete']}')){remove_shop_image('{$admin_webroot}image_spaces/remove/{$v['PhotoCategoryGallery']['id']}');}"));
                                }
                                ?>
                            </p>
                             <input type="hidden" class="img_size_small_url" value="<?php echo $v['PhotoCategoryGallery']['img_small'] ?>" />
                            <input type="hidden" class="img_size_middle_url" value="<?php echo $v['PhotoCategoryGallery']['img_detail'] ?>" />
                            <input type="hidden" class="img_size_big_url" value="<?php echo $v['PhotoCategoryGallery']['img_big'] ?>" />
                            <input type="hidden" class="img_size_original_url" value="<?php echo $v['PhotoCategoryGallery']['img_original'] ?>" />
                            <input type="hidden" class="img_name" value="<?php echo $v['PhotoCategoryGallery']['name'] ?>" />
                            </li><?php
                        }
                        ?></ul>
                    <?php if($svshow->operator_privilege("image_spaces_remove")){?>
                        <div id="btnouterlist" class="btnouterlist">
                            <div id="edt_act_batch"><a href="javascript:void(0)" onclick="act_batch();"><?php echo $ld['batch_operate']?></a></div>
                            <div id="batch_value" style="display:none;"><a href="javascript:void(0)" onclick="cancel_act_batch();"><?php echo $ld['cancel_batch_operate']?></a>
                                <label style="margin-right:5px;float:left;" class="am-checkbox am-success"><input type="checkbox" name="checkbox" data-am-ucheck value="checkbox" onclick='listTable.selectAll(this,"checkboxes[]")'/><?php echo $ld['select_all']?></label>
                                <select id="batch_opration_select" onchange="show_water_type(this)">
                                    <option value=""><?php echo $ld['please_select']?></option>
                                    <option value="remove"><?php echo $ld['delete']?></option>
                                    <option value="batch_water"><?php echo '添加水印'?></option>
                                </select>
					<span id='water_type_span' style='display:none'>
					<select id="water_type">
                        <option value="1">图片水印</option>
                        <option value="2">文字水印</option>
                    </select>
					</span>
                                <input type="button" class="am-btn am-btn-success am-btn-sm" value="<?php echo $ld['submit']?>" onclick="batch_operations()" /></div>
                            <?php echo $this->element('pagers')?>
                        </div>
                    <?php }?>
                <?php }?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var id_str = "<?php echo $id_str;?>";
var cat = "<?php echo $cat;?>";
var img_small=null;
if(backend_locale=="eng"){
    var set_img_html="<div class='set_img_size' id='set_img_size'><a href='javascript:void(0);' title='<?php echo $ld['label_initials_img_small'] ?>' onclick='set_img_size_small(this)'>S</a><a href='javascript:void(0);'  title='<?php echo $ld['label_initials_img_middle'] ?>' onclick='set_img_size_middle(this)'>M</a><a href='javascript:void(0);' title='<?php echo $ld['label_initials_img_big'] ?>' onclick='set_img_size_big(this)'>B</a><a href='javascript:void(0);'  title='<?php echo $ld['label_initials_img_original'] ?>'  onclick='set_img_size_original(this)'>O</a></div>";
}else{
    var set_img_html="<div class='set_img_size' id='set_img_size'><a href='javascript:void(0);' onclick='set_img_size_small(this)'><?php echo $ld['label_initials_img_small'] ?></a><a href='javascript:void(0);' onclick='set_img_size_middle(this)'><?php echo $ld['label_initials_img_middle'] ?></a><a href='javascript:void(0);' onclick='set_img_size_big(this)'><?php echo $ld['label_initials_img_big'] ?></a><a href='javascript:void(0);' onclick='set_img_size_original(this)'><?php echo $ld['label_initials_img_original'] ?></a></div>";
}
$(function(){
    if(!(id_str.indexOf("product_add_img")>=0)){
        $(".imagelistnew ul li a.div_img").mouseover(function(){
            img_small=null;
            var li_obj=$(this).parent();
            if($("#set_img_size").length>0&&li_obj.find("#set_img_size").length==0){
                $("#set_img_size").remove();
            }
            if(li_obj.find("#set_img_size").length==0){
                li_obj.append(set_img_html);
            }else{
                if(Number(parseFloat(li_obj.css("padding-left")))==20){
                    $("#set_img_size").css("left","13.5%").css("top","60.5%");
                }
            }
        });

        $(".imagelistnew ul li a.div_img").mouseout(function(){
            var li_obj=$(this).parent();
            if(li_obj.find("#set_img_size").length>0){
                if(Number(parseFloat(li_obj.css("padding-left")))==20){
                    $("#set_img_size").css("left","5%").css("top","64%");
                }
            }
        });
    }
});

function set_img_size_small(obj){
    var label=$(obj).parent().parent();
    img_small=label.find(".img_size_small_url").val();
    var img_detail=label.find(".img_size_middle_url").val();
    var img_big=label.find(".img_size_big_url").val();
    var img_original=label.find(".img_size_original_url").val();
    var img_name=label.find(".img_name").val();

    var img_obj_id=label.find(".div_img img").attr("id");
    img_obj=document.getElementById(img_obj_id);
    selected_image(img_obj,img_detail,img_big,img_original,img_name);
}

function set_img_size_middle(obj){
    var label=$(obj).parent().parent();
    img_small=label.find(".img_size_middle_url").val();
    var img_detail=label.find(".img_size_middle_url").val();
    var img_big=label.find(".img_size_big_url").val();
    var img_original=label.find(".img_size_original_url").val();
    var img_name=label.find(".img_name").val();

    var img_obj_id=label.find(".div_img img").attr("id");
    img_obj=document.getElementById(img_obj_id);
    selected_image(img_obj,img_detail,img_big,img_original,img_name);
}

function set_img_size_big(obj){
    var label=$(obj).parent().parent();
    img_small=label.find(".img_size_big_url").val();
    var img_detail=label.find(".img_size_middle_url").val();
    var img_big=label.find(".img_size_big_url").val();
    var img_original=label.find(".img_size_original_url").val();
    var img_name=label.find(".img_name").val();

    var img_obj_id=label.find(".div_img img").attr("id");
    img_obj=document.getElementById(img_obj_id);
    selected_image(img_obj,img_detail,img_big,img_original,img_name);
}

function set_img_size_original(obj){
    var label=$(obj).parent().parent();
    img_small=label.find(".img_size_original_url").val();
    var img_detail=label.find(".img_size_middle_url").val();
    var img_big=label.find(".img_size_big_url").val();
    var img_original=label.find(".img_size_original_url").val();
    var img_name=label.find(".img_name").val();

    var img_obj_id=label.find(".div_img img").attr("id");
    img_obj=document.getElementById(img_obj_id);
    selected_image(img_obj,img_detail,img_big,img_original,img_name);
}

function selected_image(obj,img_detail,img_big,img_original,img_name){
    if(window.opener.document.getElementById(id_str+"_pic")){
        window.opener.document.getElementById(id_str+"_pic").innerHTML = "";
    }
    var server_host="<?php echo isset($server_host)?$server_host:''; ?>";
    if(server_host==""){
        server_host="http://"+window.location.host;
    }
    if(id_str.indexOf("product_add_img")>=0){
        img_small=obj.src;
    }
    if(img_small==null){
        img_small=img_detail;//小图
    }
    img_small=img_small.replace(server_host,'');
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
        //+ "<p class='div_img_btn'><a href='javascript:;' onclick='addtags(this,"+k+")'>添加标签</a></p></blockquote>";
        /*
         p=document.getElementById("mynode")
         pclone = p.cloneNode(true);
         p.parentNode.appendChild(pclone);
         */
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
//		if(id_str.indexOf("product_add_img")>=0){//商品相册
//			window.opener.document.getElementById(id_str).value = img_small;
//		}else{
//			window.opener.document.getElementById(id_str).value = img_original;
//		}
        window.opener.document.getElementById(id_str).value = img_small;
        if(window.opener.document.getElementById("show_"+id_str)){
            //window.opener.document.getElementById("show_"+id_str).src= img_original;
//			if(id_str.indexOf("product_add_img")>=0){//商品相册
//				window.opener.document.getElementById("show_"+id_str).src = img_small;
//			}else{
//				window.opener.document.getElementById("show_"+id_str).src = img_original;
//			}
            window.opener.document.getElementById("show_"+id_str).src = img_small;
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
            window.opener.document.getElementById("show_img_big_"+id_str).src = img_big;
            window.opener.document.getElementById("show_img_big_"+id_str).parentNode.className += " img_exist";
            i=1;
        }
        if(window.opener.document.getElementById("img_big_"+id_str)){
            window.opener.document.getElementById("img_big_"+id_str).value = img_big;
            window.opener.document.getElementById("img_big_"+id_str).parentNode.className += " img_exist";
            i=1;
        }
        if(window.opener.document.getElementById("show_img_original_"+id_str)){
            window.opener.document.getElementById("show_img_original_"+id_str).src = img_original;
            window.opener.document.getElementById("show_img_original_"+id_str).parentNode.className += " img_exist";
            i=1;
        }
        if(window.opener.document.getElementById("img_original_"+id_str)){
            window.opener.document.getElementById("img_original_"+id_str).value = img_original;
            window.opener.document.getElementById("img_original_"+id_str).parentNode.className += " img_exist";
            i=1;
        }
//		if(i==0){
//			window.opener.document.getElementById(id_str).value = img_small;
//			window.opener.document.getElementById(id_str).parentNode.style.display = "none";
//		}
        //window.opener.document.getElementById(id_str).parentNode.getElementsByTagName("div")[0].className += " img_exist";
    }
    window.close();
}

function select_image_search(){
    var search_key_word = document.getElementById("search_key_word");
    var orderby_num = document.getElementById("orderby_num");
    var photo_category_id = document.getElementById("photo_category_id");
    var type = document.getElementById("type");
    var path =admin_webroot+"image_spaces/select_image/"+id_str+"/"+orderby_num.value+"/"+cat+"/"+photo_category_id.value+"/"+search_key_word.value+"/";
    if(type!=""){
        path = admin_webroot+"image_spaces/select_image/"+id_str+"/"+orderby_num.value+"/"+cat+"/"+photo_category_id.value+"/"+search_key_word.value+"/?type="+type.value;
    }
    window.location.href = path;
}
</script>