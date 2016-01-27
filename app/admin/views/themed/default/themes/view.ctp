<style>
    .am-radio, .am-checkbox{display:inline;}
    em{color:red;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<?php echo $form->create('Theme',array('action'=>'view/'.(isset($template_list["Template"]["id"])?$template_list["Template"]["id"]:""),'name'=>'theForm',"onsubmit"=>"return checkform();"));?>
<input type="hidden" id="theme_id" name="data[Template][id]" value="<?php echo isset($template_list['Template']['id'])?$template_list['Template']['id']:'0';?>">
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3  am-detail-menu" >
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic_info"><?php echo $ld['basic_information'];?></a></li>
        <li><a href="#computer"><?php echo $ld['computer']?>CSS</a></li>
        <?php if(!empty($template_list)){ ?>
            <li><a href="#module"><?php echo $ld['module'];?></a></li>
            <li><a href="#ad_position"><?php echo $ld['ad_position_list'];?></a></li>
        <?php }?>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9  am-detail-view" id="accordion"  >
<div id="basic_info" class="am-panel am-panel-default">
    <div class="am-panel-hd">
        <h4 class="am-panel-title">
            <?php echo $ld['basic_information']?>
        </h4>
    </div>
    <div id="basic_information" class="am-panel-collapse am-collapse am-in">
        <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
            <table class="am-table" id="hotel_img_ul">
                <!--模板名称-->
                <tr>
                    <th><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['template'].' '.$ld['name']:$ld['template'].$ld['name'];?></th>
                    <td>
                        <?php if(isset($template_list['Template']['name'])&&$template_list['Template']['name']!=''){ ?>
                            <?php echo $template_list['Template']['name']; ?><input type="hidden" name="data[Template][name]" id="template_name" value="<?php echo $template_list['Template']['name']; ?>" />
                        <?php }else{ ?>
                            <input style="width:250px;float:left;" type="text" name="data[Template][name]" id="template_name" onchange="ckecktemplate_name()" value="<?php echo isset($template_list['Template']['name'])?$template_list['Template']['name']:''; ?>" /><em>*</em>
                        <?php } ?>
                    </td>
                </tr>
                <!--模板描述-->
                <tr>
                    <th style="padding-top:15px"><?php echo $ld['description']?></th>
                    <td>
                        <input type="text" style="width:400px;" id="description"  name="data[Template][description]" value="<?php echo isset($template_list['Template']['description'])?$template_list['Template']['description']:''; ?>" />
                    </td>
                </tr>
                <!--模板颜色样式-->
                <tr>
                    <th style="padding-top:15px"><?php echo $ld['module_style'] ?></th>
                    <td><input type="text" style="width:400px;" id="style" name="data[Template][template_style]" value="<?php echo isset($template_list['Template']['template_style'])?$template_list['Template']['template_style']:''; ?>"/>
                    </td>
                </tr>
                <tr>
                    <th style="padding-top:15px"><?php echo $ld['thumbnail']?></th>
                    <td><input id="template_img" style="margin-right: 10px;width:550px;float:left;" type="text" name="data[Template][template_img]" value="<?php echo isset($template_list['Template']['template_img'])?$template_list['Template']['template_img']:'';?>" /><input class="am-btn am-btn-success am-radius am-btn-sm" type="button" onclick="select_img('template_img')" value="<?php echo $ld['choose_picture']?>" />&nbsp;
                        <div style="max-width: 200px;">
                            <?php echo $html->image((isset($template_list['Template']['template_img'])&&$template_list['Template']['template_img']!="")?$template_list['Template']['template_img']:"/media/default_no_photo.png",array('id'=>'show_template_img'))?>
                        </div>
                    </td>
                </tr>
                <!--作者地址-->
                <tr>
                    <th style="padding-top:15px"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['author'].' '.$ld['url']:$ld['author'].$ld['url'];?></th>
                    <td><input style="width:400px" type="text" name="data[Template][url]" value="<?php echo isset($template_list['Template']['url'])?$template_list['Template']['url']:'';?>"/></td>
                </tr>
                <!--状态是否有效-->
                <tr>
                    <th style="padding-top:15px"><?php echo $ld['valid']?></th>
                    <td><label class="am-radio am-success"><input type="radio" name="data[Template][status]" value="1" data-am-ucheck <?php if((isset($template_list['Template']['status'])&&$template_list['Template']['status']== 1)||!isset($template_list['Template']['status'])){echo "checked";} ?> /><?php echo $ld['yes']?></label>
                        <label style="margin-left:10px;" class="am-radio am-success"><input type="radio" name="data[Template][status]" value="0" data-am-ucheck <?php if(isset($template_list['Template']['status'])&&$template_list['Template']['status'] != 1){echo "checked";} ?> /><?php echo $ld['no']?></label>
                    </td>
                </tr>
                <!-- 手机版默认状态 -->
                <tr >
                    <th style="padding-top:16px;"><?php echo $ld['mobile'].''.$ld['valid']?></th>
                    <td><label class="am-radio am-success"><input type="radio" name="data[Template][mobile_status]" value="1" data-am-ucheck <?php if( isset($template_list['Template']['mobile_status'])&&$template_list['Template']['mobile_status'] == 1 ){ echo "checked"; } ?> /><?php echo $ld['yes']?></label>
                        <label style="margin-left:10px;" class="am-radio am-success"><input type="radio" name="data[Template][mobile_status]" value="0" data-am-ucheck <?php if(( isset($template_list['Template']['mobile_status'])&&$template_list['Template']['mobile_status'] == 0)||empty($template_list)){ echo "checked"; } ?> /><?php echo $ld['no']?></label></td>
                </tr>
               
 <!--设置默认-->
                <tr >
                    <th style="padding-top:18px;"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['set_up'].' '.$ld['default']:$ld['set_up'].$ld['default'];?></th>
                    <td><label class="am-radio am-success"><input type="radio" name="data[Template][is_default]" value="1" data-am-ucheck <?php if( isset($template_list['Template']['is_default'])&&$template_list['Template']['is_default'] == 1 ){ echo "checked"; } ?> /><?php echo $ld['yes']?></label>
                        <label style="margin-left:10px;" class="am-radio am-success"><input type="radio" name="data[Template][is_default]" value="0" data-am-ucheck <?php if(( isset($template_list['Template']['is_default'])&&$template_list['Template']['is_default'] == 0)||empty($template_list)){ echo "checked"; } ?> /><?php echo $ld['no']?></label></td>
                </tr>
                <!--作者-->
                <tr>
                    <th style="padding-top:15px"><?php echo $ld['author']?></th>
                    <td><input style="width:400px" type="text" id="author" name="data[Template][author]" value="<?php echo isset($template_list['Template']['author'])?$template_list['Template']['author']:'';?>"  /></td>
                </tr>
                <!--创建时间-->
                <tr>
                    <th style="padding-top:15px"><?php echo $ld['time']?></th>
                    <td><input style="width:200px" type="text" id="created" class="line_time" name="data[Template][created]" value="<?php echo isset($template_list['Template']['created'])?$template_list['Template']['created']:'';?>" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly/></td>
                </tr>
                	
                <!--电脑CSS-->
                <tr>
                    <th> <?php echo $ld['computer']?>CSS</th>
                    <td>  <textarea id="show_css" name="data[Template][show_css]"><?php echo isset($template_list['Template']['show_css'])?$template_list['Template']['show_css']:'' ?></textarea></td>
                </tr>
                	
                	
                	
            </table>
            <div class="btnouter">
                <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit'];?>" />  <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
            </div>
        </div>
    </div>
</div>
 <?php if(!empty($template_list)){ ?>
    <div id="module" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['module']?>
            </h4>
        </div>
        <div id="module_info" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <?php if($svshow->operator_privilege("page_types_add")){?>
                    <p class="am-u-md-12"><?php echo $html->link($ld['add_module'],"/page_types/view/0/".$template_list['Template']['name'],array("class"=>"am-btn am-btn-warning am-btn-sm am-fr","target"=>"_blank"),'',false,false);?></p>
                <?php }?>
                <table class="am-table">
                    <thead>
                    <tr>
                        <th><?php echo $ld['type'];?></th>
                        <th style="width:300px;"><?php echo $ld['code']?></th>
                        <th><?php echo $ld['module_type']?></th>
                        <th><?php echo $ld['status']?></th>
                        <th><?php echo $ld['operate']?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($page_type_list)&&sizeof($page_type_list)>0){ foreach($page_type_list as $k=>$v){ ?>
                        <tr>
                            <td><?php if($v['PageType']['page_type']==1){?>
                                    <?php echo $ld['mobilephone']?>
                                <?php }elseif($v['PageType']['page_type'] == 0){ ?>
                                    <?php echo $ld['computer']?>
                                <?php }?>
                            </td>
                            <td><?php echo $v['PageType']['code'];?></td>
                            <td><?php echo $v['PageType']['name'];?></td>
                            <td><?php if($v['PageType']['status']==1){?>
                                    <?php echo $html->image('yes.gif') ?>
                                <?php }elseif($v['PageType']['status'] == 0){?>
                                    <?php echo $html->image('no.gif')?>
                                <?php }?>
                            </td>
                            <td>
                                <a class="am-btn am-btn-default am-btn-xs  am-seevia-btn-edit" href="<?php echo $html->url("/page_types/view/{$v['PageType']['id']}/{$template_list['Template']['name']}",array("target"=>"_blank")); ?>">
                                    <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                </a>
                                <?php if($svshow->operator_privilege("page_types_reomve")){if($v['PageType']['status']==0){?>
                                <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(confirm('确认删除该模块吗？（删除模块信息，页面样式，样式模块）')){list_delete_submit(admin_webroot+'page_types/remove/<?php echo $v['PageType']['id'] ?>');}">
                                    <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                </a>
                                <?php }}?>
                            </td>
                        </tr>
                    <?php }} ?>
                    </tbody>
                </table>
                <div class="btnouter">
                    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit'];?>" />  <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
        </div>
    </div>
    <div id="ad_position" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['ad_position_list']?>
            </h4>
        </div>
        <div id="ad_position_list" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <?php if($svshow->operator_privilege("advertisement_positions_mgt")){?>
                    <p class="action-span"><?php echo $html->link($ld['layout_example'],"/advertisement_positions/position/".$template_list['Template']['name'],array("target"=>'view_adsiteall',"class"=>"addbutton"));?></p>
                <?php }?>
                <table class="am-table">
                    <thead>
                    <tr>
                        <th><?php echo $ld['number']?></th>
                        <th><?php echo $ld['ad_position_name']?></th>
                        <th><?php echo $ld['ad_position_width']?></th>
                        <th><?php echo $ld['ad_position_height']?></th>
                        <th><?php echo $ld['ad_position_description']?></th>
                        <th><?php echo $ld['sort']?></th>
                        <th><?php echo $ld['operate']?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(isset($advertisement_position_list) && sizeof($advertisement_position_list)>0){foreach($advertisement_position_list as $k=>$v){?>
                        <tr>
                            <td><?php echo $v['AdvertisementPosition']['id'];?></td>
                            <td><span onclick="javascript:listTable.edit(this, 'advertisement_positions/update_advertisement_positions_name/', <?php echo $v['AdvertisementPosition']['id']?>)"><?php echo $v['AdvertisementPosition']['name'];?></span></td>
                            <td><span onclick="javascript:listTable.edit(this, 'advertisement_positions/update_advertisement_positions_ad_width/', <?php echo $v['AdvertisementPosition']['id']?>)"><?php echo $v['AdvertisementPosition']['ad_width']?></span></td>
                            <td><span onclick="javascript:listTable.edit(this, 'advertisement_positions/update_advertisement_positions_ad_height/', <?php echo $v['AdvertisementPosition']['id']?>)"><?php echo $v['AdvertisementPosition']['ad_height']?></span></td>
                            <td><?php echo $v['AdvertisementPosition']['position_desc']?></td>
                            <td><span onclick="javascript:listTable.edit(this, 'advertisement_positions/update_advertisement_positions_orderby/', <?php echo $v['AdvertisementPosition']['id']?>)"><?php echo $v['AdvertisementPosition']['orderby']?></span></td>
                            <td><?php
                                if(!isset($v['AdvertisementPosition']['is_new'])){
                                    if($svshow->operator_privilege("advertisement_positions_mgt")){
                                        echo $html->link($ld['layout'],"/advertisement_positions/position/".$template_list['Template']['name']."#".$v['AdvertisementPosition']['code'],array("target"=>'view_adsiteall'));
                                    }
                                    if($svshow->operator_privilege("advertisement_positions_edit")){?>
                                        <a class="am-btn am-btn-default am-btn-xs  am-seevia-btn-edit" href="<?php echo $html->url("/advertisement_positions/view/{$v['AdvertisementPosition']['id']}/{$template_list['Template']['name']}",array("target"=>'balank')); ?>">
                                            <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                        </a>
                                    <?php }if($svshow->operator_privilege("advertisement_positions_remove")&&!isset($v['AdvertisementPosition']['is_new'])){?>
                                        <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(confirm('{$ld['confirm_delete_ad_position']}')){list_delete_submit(admin_webroot+'advertisement_positions/remove/<?php echo $v['AdvertisementPosition']['id'] ?>');}">
                                            <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                        </a>
                                    <?php }}else if (isset($v['AdvertisementPosition']['is_new'])&&$v['AdvertisementPosition']['is_new']=='1') {
                                    echo $html->link($ld['install'],"javascript:;",array("onclick"=>"list_delete_submit('{$admin_webroot}advertisement_positions/install/{$defaulttemplate}/{$v['AdvertisementPosition']['code']}');"));
                                }?>
                            </td>
                        </tr>
                    <?php }} ?>
                    </tbody>
                </table>
                <div class="btnouter">
                    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit'];?>" />  <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
        </div>
    </div>
<?php }?>

<style id="phonestyle">
</style>
<style id="phonestylecustom">
</style>

</div>
<?php echo $form->end();?>
<style type="text/css">
    .tablemain .lefttable th,.tablemain .righttable th, .tablemain .alonetable th{width:30%;}
    .tablemain .lefttable input[type="text"]{width:70%;}
    .tablemenu{padding:0;}
    .action-span{margin-top:8px;}
    .tablelist td{text-align:center;}
    .tablelist td:last-child a{color:#000;margin:0;}
    #mobile_css_tab th{width:47%;}
    #mobile_css,#show_css{width:99%;min-height:300px;resize:none;}

    .ui-header-fixed, .ui-footer-fixed { position:static; }
    .ui-mobile [data-role="page"], .ui-mobile [data-role="dialog"], .ui-page { display:block; position:static; }
    .ui-icon-searchfield::after { background:none; }
    .homeprod .ui-collapsible-content .ui-listview .ui-icon-arrow-r{}
    .ui-icon, .ui-icon-searchfield::after{}
    .ui-header .ui-btn-left, .ui-footer .ui-btn-left { left: auto; top: auto; }
    .ui-header .ui-btn {margin-top: 5px;margin-left: 5px;}

    .ui-header-fixed, .ui-footer-fixed { position:static; }
    .ui-mobile [data-role="page"], .ui-mobile [data-role="dialog"], .ui-page { display:block; position:static; }
    .ui-icon-searchfield::after { background:none; }
    .homeprod .ui-collapsible-content .ui-listview .ui-icon-arrow-r{}
    .ui-icon, .ui-icon-searchfield::after{}
    .ui-header .ui-btn-left, .ui-footer .ui-btn-left { left: auto; top: auto; }
    .ui-header .ui-btn {margin-top: 5px;margin-left: 5px;}

    /*.per_date .ui-btn-inner, .next_date .ui-btn-inner { background:#F1F1F1; }*/
    .per_date .ui-btn, .next_date .ui-btn { /**background:#F1F1F1;*/ filter:alpha(opacity=0); }

    .homeproducttopic .ui-collapsible-content .z {background: url(../images/arrowb-left.png) no-repeat 0 50%,url(../images/arrowb-right.png) no-repeat 100% 50%;}

    #phonereview{width: 330px;margin:5px auto;}
    .phonereview { display:none; *visibility:hidden; }
    .phonereviewshow { display:block; *visibility:visible; }

    .ui-listview .ui-btn { /*background:none; *background:url(http://thm.ioco.cn/themed/admin/img/blank.gif); */ *zoom:1; }
    .pro_img,.picdate,.pro_date,.ui-collapsible-heading .ui-btn,.homeproducttopic .ui-collapsible-content{ *zoom:1; }
    #mobile_info .tablemain .lefttable th,#mobile_info .tablemain .righttable th,#mobile_info .tablemain .alonetable th{text-align: right;width: 55%;}
    #mobile_info .tablemain .lefttable td,#mobile_info .tablemain .righttable td,#mobile_info .tablemain .alonetable td .color{margin-bottom:5px;}

    #tablemain h3{margin:0;padding-left:0;height:auto;}
    #tablemain h3 *{margin-bottom:0;margin-top:0;}
    #tablemain h3 a{float:none;padding-right:0;}
    #tablemain h3 span{float:none;padding-right:0;}

    #page .ui-content{padding:2px 8px 0px 8px;}
    #page .ui-header .ui-title{margin:0 auto;}
    .ui-field-contain{margin:0px;}
    .ui-collapsible-heading .ui-btn-inner, .ui-collapsible-heading .ui-btn-icon-left .ui-btn-inner{display:inline;}
    .ui-footer > div > div{padding:0;}
    .ui-collapsible{margin:0.4em 0;}
    .homeproducttopic .ui-collapsible-content .zaa{padding:0;}

    .CodeMirror{width:99%;margin:0px;}
</style>
<script type="text/javascript">
<?php if(isset($template_list['Template']['name'])&&$template_list['Template']['name']!=''){ ?>
var returnflag=true;
<?php }else{ ?>
var returnflag;
<?php } ?>
var editor1,editor2;
function setCodeMirror(){
    if($("#show_css").parent().find(".CodeMirror").length>0){
        var _value1=editor1.getValue();
        document.getElementById("show_css").value=_value1;
        $("#show_css").parent().find(".CodeMirror").remove();
    }
    editor1 = CodeMirror.fromTextArea(document.getElementById("show_css"), {
        lineNumbers: true
    });

    if($("#mobile_css").parent().find(".CodeMirror").length>0){
        var _value2=editor2.getValue();
        document.getElementById("mobile_css").value=_value2;
        $("#mobile_css").parent().find(".CodeMirror").remove();
    }
    editor2 = CodeMirror.fromTextArea(document.getElementById("mobile_css"), {
        lineNumbers: true
    });
}
setCodeMirror();

$("#tablemenu li").live("click",function(){
    setCodeMirror();
});
$("#tablemain h2").live("click",function(){
    setCodeMirror();
});
function ckecktemplate_name(){
    returnflag=false;
    var template_name=document.getElementById("template_name").value;
    var sUrl = admin_webroot+"themes/check_themes_name/";
    if(template_name!=""){
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {template_name: template_name},
            success: function (result) {
                if(result.code==1){
                    returnflag=true;
                }else{
                    alert(result.msg);
                }
            }
        });
    }else{
        alert("<?php printf($ld['name_not_be_empty'],$ld['template'].$ld['name']); ?>");
    }
}
function checkform(){
    if(typeof(returnflag)=='undefined'){
        alert("<?php printf($ld['name_not_be_empty'],$ld['template'].$ld['name']); ?>");
    }else{
        return returnflag;
    }
    return false;
}
var phonecolor;
var phonecolorstyle="";
var phonebgimage="";
var phonecolorFn = function(){
    phonecolor = {
        header_font_color:[".ui-title","color",$("#header_font_color").val()],
        header_font_shadow_color:[".ui-title","text-shadow",$("#header_font_shadow_color").val()],
        header_background_color1:[".ui-header","background",$("#header_background_color1").val(),$("#header_background_color2").val()],
        header_frame_color:[".ui-header","border-color",$("#header_frame_color").val()],
        title_font_color:[".ui-collapsible-heading .ui-btn","color",$("#title_font_color").val()],
        title_font_shadow_color:[".ui-collapsible-heading .ui-btn","text-shadow",$("#title_font_shadow_color").val()],
        title_background_color1:[".ui-collapsible-heading .ui-btn","background",$("#title_background_color1").val(),$("#title_background_color2").val()],
        title_frame_color:[".ui-collapsible-heading .ui-btn,.ui-collapsible-content","border-color",$("#title_frame_color").val()],
        home_list_font_color:[".ui-collapsible-content .ui-listview .ui-btn .ui-link-inherit","color",$("#home_list_font_color").val()],
        home_list_font_shadow_color:[".ui-collapsible-content .ui-listview .ui-btn .ui-link-inherit","text-shadow",$("#home_list_font_shadow_color").val()],
        home_list_background_color1:[".ui-collapsible-content .ui-listview .ui-btn","background",$("#home_list_background_color1").val(),$("#home_list_background_color2").val()],
        home_list_frame_color:[".ui-collapsible-content .ui-listview .ui-btn","border-color",$("#home_list_frame_color").val()],
        foot_font_color:[".ui-footer .ui-link","color",$("#foot_font_color").val()],
        foot_font_shadow_color:[".ui-footer .ui-link","text-shadow",$("#foot_font_shadow_color").val()],
        foot_background_color1:[".ui-footer","background",$("#foot_background_color1").val(),$("#foot_background_color2").val()],
        foot_frame_color:[".ui-footer","border-color",$("#foot_frame_color").val()],
        foot_hightlight_background_color1:[".ui-footer .workselected","background",$("#foot_hightlight_background_color1").val(),$("#foot_hightlight_background_color2").val()],
        foot_hightlight_font_color:[".ui-footer .workselected .ui-link","color",$("#foot_hightlight_font_color").val()],
        home_product_background_color1:[".homeproducttopic .ui-collapsible-content","background",$("#home_product_background_color1").val(),$("#home_product_background_color2").val()],
        home_product_frame_color:[".homeproducttopic .ui-collapsible-content a","border-color",$("#home_product_frame_color").val()],
        home_product_price_background_color1:[".homeproducttopic .ui-collapsible-content a span","background",$("#home_product_price_background_color1").val(),$("#home_product_price_background_color2").val()],
        home_product_price_font_color:[".homeproducttopic .ui-collapsible-content a span","color",$("#home_product_price_font_color").val()],
        home_product_price_font_shadow_color:[".homeproducttopic .ui-collapsible-content a span","text-shadow",$("#home_product_price_font_shadow_color").val()],
        head_button_background_color1:[".ui-header .ui-btn, .ui-footer .ui-btn","background",$("#head_button_background_color1").val(),$("#head_button_background_color2").val()],
        head_button_font_color:[".ui-header .ui-btn, .ui-footer .ui-btn","color",$("#head_button_font_color").val()],
        head_button_font_shadow_color:[".ui-header .ui-btn, .ui-footer .ui-btn","text-shadow",$("#head_button_font_shadow_color").val()],
        head_button_frame_color:[".ui-header .ui-btn, .ui-footer .ui-btn","border-color",$("#head_button_frame_color").val()],
        list_background_color1:[".ui-listview .ui-btn","background",$("#list_background_color1").val(),$("#list_background_color2").val()],
        list_font_color:[".ui-listview .ui-btn .ui-link-inherit","color",$("#list_font_color").val()],
        list_font_shadow_color:[".ui-listview .ui-btn .ui-link-inherit","text-shadow",$("#list_font_shadow_color").val()],
        list_frame_color:[".ui-listview .ui-btn","border-color",$("#list_frame_color").val()],
        product_img_background_color1:[".pro_img","background",$("#product_img_background_color1").val(),$("#product_img_background_color2").val()],
        product_img_background_frame_color:[".pro_img","border-color",$("#product_img_background_frame_color").val()],
        product_img_frame_color:[".pro_img span","border-color",$("#product_img_frame_color").val()],
        product_attr_background_color1:[".picdate","background",$("#product_attr_background_color1").val(),$("#product_attr_background_color2").val()],
        product_attr_font_color:[".picdate","color",$("#product_attr_font_color").val()],
        product_attr_font_shadow_color:[".picdate","text-shadow",$("#product_attr_font_shadow_color").val()],
        product_attr_price_color:[".picdate .newpic i","color",$("#product_attr_price_color").val()],
        product_attr_price_shadow_color:[".picdate .newpic i","text-shadow",$("#product_attr_price_shadow_color").val()],
        product_attr_frame_color:[".picdate","border-color",$("#product_attr_frame_color").val()],
        product_desc_background_color1:[".pro_date","background",$("#product_desc_background_color1").val(),$("#product_desc_background_color2").val()],
        product_desc_font_color:[".pro_date","color",$("#product_desc_font_color").val()],
        product_desc_font_shadow_color:[".pro_date","text-shadow",$("#product_desc_font_shadow_color").val()],
        product_desc_frame_color:[".pro_date","border-color",$("#product_desc_frame_color").val()],
        next_product_name_color:[".per_date span, .next_date span","color",$("#next_product_name_color").val()],
        next_product_name_shadow_color:[".per_date span, .next_date span","text-shadow",$("#next_product_name_shadow_color").val()],
        next_product_price_color:["#last_pro_price, #next_pro_price","color",$("#next_product_price_color").val()],
        next_product_price_shadow_color:["#last_pro_price, #next_pro_price","text-shadow",$("#next_product_price_shadow_color").val()],
        next_color:[".per_date .ui-btn .ui-btn-text, .next_date .ui-btn .ui-btn-text","color",$("#next_color").val()],
        next_shadow_color:[".per_date .ui-btn .ui-btn-text, .next_date .ui-btn .ui-btn-text","text-shadow",$("#next_shadow_color").val()],
        product_button_background_color1:[".per_date .ui-btn, .next_date .ui-btn","background",$("#product_button_background_color1").val(),$("#product_button_background_color2").val()],
        product_button_frame_color:[".per_date .ui-btn, .next_date .ui-btn","border-color",$("#product_button_frame_color").val()]
    };
    phonebgimage = [
        ".ui-collapsible-heading .ui-icon-minus",".ui-collapsible-heading .ui-icon-plus",".homeproducttopic .ui-collapsible-content .z",".homeproducttopic .ui-collapsible-content .z",".homearticle .ui-collapsible-content .ui-listview .ui-icon-arrow-r,.ui-btn-icon-left .ui-btn-inner .ui-icon-arrow-r, .ui-btn-icon-right .ui-btn-inner .ui-icon-arrow-r"
    ];
    phonecolorstyle="";
    for(var item in phonecolor){
        if(phonecolor[item][2]){
            if(phonecolor[item][1]=="text-shadow"){
                phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + phonecolor[item][2] + "!important}";
            }else if(phonecolor[item][3]==''){
                phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + phonecolor[item][2] + "!important}";
            }else{
                if(phonecolor[item][1]=="color"){
                    phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + phonecolor[item][2] + "!important}";
                }else if(phonecolor[item][1]=="background"){
                    phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + "-webkit-linear-gradient(top," + phonecolor[item][2] + ',' + phonecolor[item][3] + ")" + "!important}";
                    phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + "-moz-linear-gradient(top," + phonecolor[item][2] + ',' + phonecolor[item][3] + ")" + "!important}";
                    phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + "-ms-linear-gradient(top," + phonecolor[item][2] + ',' + phonecolor[item][3] + ")" + "!important}";
                    phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + "-o-linear-gradient(top," + phonecolor[item][2] + ',' + phonecolor[item][3] + ")" + "!important}";
                    phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + "linear-gradient(top," + phonecolor[item][2] + ',' + phonecolor[item][3] + ")" + "!important}";
                    phonecolorstyle += phonecolor[item][0] + "{" + "filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='" + phonecolor[item][2] + "', endColorstr='" + phonecolor[item][3] + "')" + "!important;*background:none;}";
                }else if(phonecolor[item][1]=="border-color"){
                    phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + phonecolor[item][2] + "!important}";
                }
            }
        }
    }

    $("#tablemain .alonetable input.background").each(function(index, element) {
        if (index==2){
            phonecolorstyle += phonebgimage[index] + "{ background-image:url(" + $(this).val() + ")";
        }else if (index==3 && $(this).val()){
            phonecolorstyle += ",url(" + $(this).val() + ");}";
        }else if (index==3) {
            phonecolorstyle += ";}";
        }else if (index==4) {
            phonecolorstyle += phonebgimage[index] + "{ background:url(" + $(this).val() + ") no-repeat 50%;}";
        }else {
            phonecolorstyle += phonebgimage[index] + "{ background-image:url(" + $(this).val() + ")}";
        }
        //$("#next_product_name_color").val()
    });
    if($.browser.msie){
        $("#phonestyle").remove();
        $('<style type="text/css" id="phonestyle">' + phonecolorstyle + '</style>').appendTo("head");
    } else {
        $("#phonestyle").html(phonecolorstyle);
    }
}

$(document).ready(function(){
    $(".mobile_css").css("min-height",$("#phonereview").height())
    $("#phonereview").appendTo("mobile_info");
    phonecolorFn();
});

$("input.color").on("blur",phonecolorFn);
$("input.background").on("blur",phonecolorFn);

$(document).on("click","#tablemenu li",function(){
    $("#tablemenu li").each(function(index, element) {
        if($(this)[0].className.match("show")){
            var tmp = index;
            if(tmp){
                tmp--;
            }
            $("#phonereview .phonereviewshow").removeClass("phonereviewshow");
            $("#phonereview .phonereview").eq(tmp).addClass("phonereviewshow");
        };
    });
});

$(document).on("click","#tablemain h2",function(){
    $("#tablemenu li").each(function(index, element) {
        if($(this)[0].className.match("show")){
            var tmp = index;
            if(tmp){
                tmp--;
            }
            $("#phonereview .phonereviewshow").removeClass("phonereviewshow");
            $("#phonereview .phonereview").eq(tmp).addClass("phonereviewshow");
        };
    });
});
</script>