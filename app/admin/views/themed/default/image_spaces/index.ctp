<?php echo $javascript->link('/skins/default/js/image_space');?>
<style>
    .admin-content{overflow: inherit;}
    .imagelistnew ul, .imagelistnew li {
        display:block;
    }

    .imagelistnew li {
        display:inline-block;
        vertical-align:top;
       
    }

    .imagelistnew .div_img {
        display:block;
    }

    .imagelistnew .div_img_name {
        display:block;
        overflow:hidden;
        padding-top:5px;
    }

    .imagelistnew .div_img_name a {
        float:none;
       text-align:center;
    }

    .div_img{
      text-align:center;
      margin-top:5px;
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
        color:#444;
    }

    .imagelistnew a:hover {
        color:#4444;
        text-decoration:none;
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
        width:140px;
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
    
    .imagelistnew li div.am-g{position: relative;}
    .imagelistnew li label.am-checkbox{margin-top:0px;display:inline-block;}
    
    .sx{margin-top:5px;}
    
    .am-img-responsive {
    display: inline-block;
    max-height: 150px;
    max-width: 100%;
}
</style>
 
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><h2><?php echo $ld['default_category']?></h2></li>
        <li><?php echo $html->link($ld['select_pictures'],"/image_spaces/index/".$id_str."/".$orderby."/0/".$photo_category_id."/".$search_key_word."/",$cat==0?array("class"=>"am-active"):"",false,false);?></li>
        <li><?php echo $html->link($ld['today_upload'],"/image_spaces/index/".$id_str."/".$orderby."/1/".$photo_category_id."/".$search_key_word."/",$cat==1?array("class"=>"am-active"):"",false,false);?></li>
        <li><?php echo $html->link($ld['upload_in_three_days'],"/image_spaces/index/".$id_str."/".$orderby."/2/".$photo_category_id."/".$search_key_word."/",$cat==2?array("class"=>"am-active"):"",false,false);?></li>
        <li><?php echo $html->link($ld['upload_in_seven_days'],"/image_spaces/index/".$id_str."/".$orderby."/3/".$photo_category_id."/".$search_key_word."/",$cat==3?array("class"=>"am-active"):"",false,false);?></li>
        <li><?php echo $html->link($ld['upload_in_thirty_days'],"/image_spaces/index/".$id_str."/".$orderby."/4/".$photo_category_id."/".$search_key_word."/",$cat==4?array("class"=>"am-active"):"",false,false);?></li>
        <li><?php echo $html->link($ld['upload_before_january'],"/image_spaces/index/".$id_str."/".$orderby."/5/".$photo_category_id."/".$search_key_word."/",$cat==5?array("class"=>"am-active"):"",false,false);?></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion"  >
    <div class="am-panel am-panel-default">
        <div class="am-panel-collapse am-collapse am-in ">
        	
            <div class="am-u-md-12 am-btn-group-xs am-text-right ">
             <?php if($svshow->operator_privilege("image_spaces_upload")){echo $html->link($ld['upload_picture'],"/image_spaces/upload/",array("class"=>"am-btn am-btn-warning am-btn-sm "),'',false,false);}?>&nbsp;&nbsp;
                   <?php if($svshow->operator_privilege("image_spaces_category")){echo $html->link($ld['pictures_category'],"/image_spaces/category_list",array("class"=>"am-btn am-btn-warning am-btn-sm ","style"=>"margin-left:5px;"),'',false,false);}?>
           </div>
           	   
            <form class='am-form am-form-inline am-form-horizontal'>
                <div class="listsearch">
                    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0px">
                        <li style="margin:0 0 10px 0">
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
                    </ul>
                    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 7px">
                        <li style="margin:0 0 10px 0">
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
                    <div class="infotips"><?php echo $html->link($ld['no_picture']." ".$ld['click_upload_now'],"/image_spaces/upload/".$photo_category_id,false,false);?></div></div>
                <?php }else{?>
                 
                      <ul class="am-avg-lg-4"><?php foreach($photo_category_gallery_list as $k=>$v){      ?>
                      
                        <li style="margin-top:20px;"> 
                          <?php if(isset($v['PhotoCategoryGallery']['img_small'])) $a= explode("http://",$v['PhotoCategoryGallery']['img_small']); ?>
                            
                                <a class="div_img" href="/admin/image_spaces/view/<?php echo $v['PhotoCategoryGallery']['id'];?>" target="_blank">	<div style="max-height:200px;"><img  class="am-img-responsive" src="<?php if(isset($a)&&count($a)==1){ echo $v['PhotoCategoryGallery']['img_small'];}else{echo $v['PhotoCategoryGallery']['img_small'];}?>" id="img<?php echo $v['PhotoCategoryGallery']['id'];?>" /></div>
                              </a>
                              
                            <div class="am-g"><label class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['PhotoCategoryGallery']['id'];?>" class="input_checkbox" /></label><div style="width:88%;text-overflow:ellipsis; white-space:nowrap; overflow:hidden;float: right;"  onclick="javascript:listTable.edit(this, 'image_spaces/update_photo_name/', <?php echo $v['PhotoCategoryGallery']['id']?>)"><?php echo $v['PhotoCategoryGallery']['name'];?></div></div>
						<a class="mt  am-btn am-btn-default am-btn-xs am-seevia-btn" href="javascript:;" data-am-modal="{target: '#tip-copy1', closeViaDimmer: 0, width: 400, height: 225}" onclick="photo_copy(event,'<?php echo $server_host.$v['PhotoCategoryGallery']['img_original'];?>')"><?php echo $ld['copy']?>
						</a>
                                 <?php
						echo   $html->link($ld['replace'],"/image_spaces/replace_img/".$v['PhotoCategoryGallery']['id'],array("class"=>"mt am-seevia-btn am-btn am-btn-default am-btn-xs"),false,false).'&nbsp;';
						echo   $html->link($ld['transfer_category'],"/image_spaces/category_change/".$v['PhotoCategoryGallery']['id'],array("class"=>"mt am-seevia-btn am-btn am-btn-default am-btn-xs"),false,false).'&nbsp;';
						if($svshow->operator_privilege("image_spaces_remove")){
						echo $html->link($ld['delete'],"javascript:;",array("class"=>"mt am-seevia-btn am-btn am-btn-default am-btn-xs  ","onclick"=>"if(confirm('{$ld['confirm_delete']}')){remove_shop_image('{$admin_webroot}image_spaces/remove/{$v['PhotoCategoryGallery']['id']}');}")).'&nbsp;'; };?>
                   
                            </li>
                           	   <?php } ?>
                           	    </ul>
                     
            </div>
            <?php if($svshow->operator_privilege("image_spaces_remove")){?>
                    <div id="btnouterlist" class="btnouterlist">
                        <div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
                            <div id="edt_act_batch"><a href="javascript:void(0);" class="am-btn am-btn-danger am-btn-sm" onclick="act_batch();"><?php echo $ld['batch_operate']?></a></div>
                            <div id="batch_value" style="display:none;">
                                <a href="javascript:void(0);" onclick="cancel_act_batch();" class="am-btn am-btn-default am-btn-sm"><?php echo $ld['cancel_batch_operate']?></a>
                                <label style="margin-right:5px;float:left;" class="am-checkbox am-default"><input type="checkbox" name="checkbox" data-am-ucheck value="checkbox" onclick='listTable.selectAll(this,"checkboxes[]")'/><?php echo $ld['select_all']?></label>
                                <select id="batch_opration_select" onchange="show_water_type(this)" data-am-selected>
                                    <option value="0" selected><?php echo $ld['please_select']?></option>
                                    <option value="remove"><?php echo $ld['batch_delete']?></option>
                                    <option value="batch_water"><?php echo $ld['batch_add_watermark'] ?></option>
                        		 <option value="rebuild_pictures"><?php echo $ld['rebuild_pictures']; ?></option>
                                </select>
            					<span id='water_type_span' style='display:none'>
                					<select id="water_type" data-am-selected>
                                        		<option value="1"><?php echo $ld['image_watermark']; ?></option>
                                        		<option value="2"><?php echo $ld['text_watermark']; ?></option>
                                    </select>
            					</span>
                                <input type="button" class="am-btn am-btn-danger am-btn-sm" value="<?php echo $ld['submit']?>" onclick="batch_operations()" /></div>
                        </div>
                        <div class="am-u-lg-12 am-u-md-12 am-u-sm-12"><?php echo $this->element('pagers'); ?></div>
                        <div class="am-cf"></div>
                    </div>
                <?php }?>
            <?php }?>
            <div class="am-modal am-modal-no-btn" tabindex="-1" id="tip-copy1">
                <div class="am-modal-dialog">
                    <div class="am-modal-hd">
                        <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
                    </div>
                    <div class="am-modal-bd">
                        <input type="text" id="tip-copy1-text">
                        <p><?php echo $ld['do_not_copy']?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var id_str = "<?php echo $id_str;?>";
    var cat = "<?php echo $cat;?>";

    function select_image_search(){
        var search_key_word = document.getElementById("search_key_word");
        var orderby_num = document.getElementById("orderby_num");
        var photo_category_id = document.getElementById("photo_category_id");
        window.location.href = admin_webroot+"image_spaces/index/"+id_str+"/"+orderby_num.value+"/"+cat+"/"+photo_category_id.value+"/"+search_key_word.value+"/";
    }

    var tmpdiv = $("#applist");
    function act_batch(){
        tmpdiv.addClass("checkbox_div");
        document.getElementById("edt_act_batch").style.display = "none";
        document.getElementById("batch_value").style.display = "block";
    }

    function cancel_act_batch(){
        tmpdiv.removeClass("checkbox_div");
        document.getElementById("edt_act_batch").style.display = "";
        document.getElementById("batch_value").style.display = "none";
    }

    function batch_operations(){
        var sl_obj = document.getElementById('batch_opration_select');
        if(sl_obj.value==0 ){
            alert(j_select_operation_type);
            return;
        }
        var act = sl_obj.options[sl_obj.selectedIndex].value;
        var ch_image_obj = document.getElementsByName("checkboxes[]");
        var str = "";
        for(var i=0;i<ch_image_obj.length;i++){
            if(ch_image_obj[i].checked){
                str+=ch_image_obj[i].value+"-";
            }
        }
        str=str.replace(/(^\s*)|(\s*$)/g, "");//去除两边空格
        if(str==""||str.length==0){
            alert(j_please_select+j_image);
            return;
        }else{
            str=str.substr(0,str.length-1)
        }
        if(act=='remove'){
            var confirm_delete_the_selected_image="<?php echo $ld['confirm_delete_the_selected_image']; ?>";
            if(confirm(confirm_delete_the_selected_image)){
                sUrl=admin_webroot+'image_spaces/batch_remove/'+str;
            }else{
                return;
            }
        }
        if(act == 'batch_water'){
            var obj1=document.getElementById('water_type');
            var water_type = obj1.options[obj1.selectedIndex].value;
            var confirm_add_watermark_to_selected_image="<?php echo $ld['confirm_add_watermark_to_selected_image']; ?>";
            if(confirm(confirm_add_watermark_to_selected_image)){
                sUrl = admin_webroot+'image_spaces/batch_water/'+str+"?type="+water_type;
            }else{
                return;
            }
        }
        if(act=="rebuild_pictures"){
        	sUrl=admin_webroot+'photo_category_gallery/rebuild_pictures/'+str;
        }
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            success: function (result) {
                if(result.flag==1){
                    alert(result.message);
                    window.location.reload();
                }
                if(result.flag==2){
                    alert(result.message);
                }
            }
        });
    }

    function show_water_type(obj){
        if(obj.value=='batch_water'){
            document.getElementById('water_type_span').style.display='';
        }else{
            document.getElementById('water_type_span').style.display='none';
        }
    }
</script>