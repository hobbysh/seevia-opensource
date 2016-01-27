<?php
echo $html->css('/skins/default/css/open_elements');
//设置添加时的默认空白图片
$element_default_img="/admin/skins/default/img/element_add_default.png";
if(isset($this->data['OpenElement'])){
    $element_default_img="/admin/skins/default/img/element_default.jpg";
}
?>
<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<script type="text/javascript">
    var element_js_type="<?php echo $type; ?>";
    var element_js_locale="<?php echo isset($backend_locale)?$backend_locale:'chi' ?>";
    //预先定义JS语言资源
    var element_js_title_not_empty="<?php printf($ld['name_not_be_empty'],$ld['title']); ?>";
    var element_js_pic_not_empty="<?php printf($ld['name_not_be_empty'],$ld['picture']); ?>";
    var element_js_content_not_empty="<?php printf($ld['name_not_be_empty'],$ld['body']); ?>";
    var please_submit_the_first_vice_material="<?php echo $ld['please_submit_the_first_vice_material']; ?>";//主素材还未提交
    var multiple_graphic_not_delete="<?php echo $ld['multiple_graphic_not_delete'] ?>";//多图文至少2条
    var element_js_confirm_delete="<?php echo $ld['confirm_delete'] ?>";
    var element_js_title="<?php echo $ld['title'] ?>";
    var element_js_pic="<?php echo $ld['title'] ?>";
    var element_js_body="<?php echo $ld['body'] ?>";
    var element_js_cove="<?php echo $ld['cover']; ?>";
    var element_js_cover_desc="<?php echo $ld['cover_desc'] ?>";
    var element_js_required="<?php echo $ld['required']; ?>";
    var article_select_file="<?php echo $ld['article_select_file']; ?>";
    var open_element_bulk_desc="<?php echo $ld['open_element_bulk_desc'] ?>";
    var element_js_add="<?php echo $ld['add']; ?>";
    var element_js_delete="<?php echo $ld['delete']; ?>";
    var element_js_address="<?php echo $ld['address']; ?>";
    var external_chain="<?php echo $ld['external_chain']; ?>";
    var external_chain_desc="<?php echo $ld['external_chain_desc']; ?>";
    var element_js_reffer="<?php echo $ld['reffer']; ?>";
    var element_js_reffer_url_desc="<?php echo $ld['reffer_url_desc']; ?>";
    var element_js_d_sumbit="<?php echo $ld['d_submit']; ?>";
    var element_js_d_reset="<?php echo $ld['d_reset']; ?>";
    var element_Id="<?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['id'];} ?>";
</script>
<?php echo $javascript->link('/skins/default/js/open_element'); ?>
<input type="hidden" value="<?php echo isset($manypic)?sizeof($manypic):1 ?>" id="element_count" />
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0;">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#tablemain"><?php echo $ld['basic_information']?></a></li>
        <?php if(isset($open_model_list)&&sizeof($open_model_list)>0){ ?>
            <li><a href="#open_type_action">预览/群发</a></li>
        <?php }?>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion" >
    <div id="tablemain" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['basic_information']?>
            </h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <div id="smallmenu">
                    <div class="msg-item" id="m_item">
                        <div onclick="disright()" style="cursor:pointer;">
                            <p class="msg-meta">
                                <span class="msg-date"><?php echo date("Y-m-d");?></span>
                            </p>
                            <div class="cover">
                                <img onload="set_flash_img(this,352,156)" class="default-tip" id="show_tip100" onclick="select_imge('tip100')" src="<?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['media_url'].'?time='.time();}else{ echo  $element_default_img.'?time='.time();}?>" />
                            </div>
                            <div class="biaoti" id="biaoti100"><h3><?php if(isset($this->data['OpenElement'])){ echo $this->data['OpenElement']['title'];}else{echo $ld['title'];}?></h3></div>
                        </div>
                        <!--循环parent子项的div-->
                        <?php
                        if($type==2){//多图文
                            if(!isset($manypic)||empty($manypic)){
                                ?>
                                <div class="titlediv" id="tit0" onmousemove="back1(0)" onmouseleave="back2(0)" onmouseout="back2(0)">
                                    <div class="title1" id="biaoti0"><h4><?php echo $ld['title']?></h4></div>
                                    <img class="smallback" id="show_tip0" onclick="select_imge('tip0')" src="<?php echo $element_default_img; ?>" />
                                    <ul class="sub-msg-opr" id="titleback0" >
                                        <li><a class="opr-icon edit-icon" href="javascript:;" onclick="appear(0)"></a></li>
                                        <li><a class="opr-icon del-icon" href="javascript:;" onclick="del(0,'0')"></a></li>
                                    </ul>
                                </div>
                            <?php
                            }else{
                                foreach($manypic as $k=>$v){
                                    ?>
                                    <div class="titlediv" id="tit<?php echo $k;?>" onmousemove="back1(<?php echo $k;?>)" onmouseleave="back2(<?php echo $k;?>)" onmouseout="back2(<?php echo $k;?>)">
                                        <div class="title1" id="biaoti<?php echo $k;?>"><h4><?php echo $v['OpenElement']['title']?></h4></div>
                                        <img class="smallback" id="show_tip<?php echo $k;?>" onclick="select_imge('tip<?php echo $k;?>')" src="<?php if(!empty($v['OpenElement']['media_url'])){echo $v['OpenElement']['media_url'];}else{echo $element_default_img;}?>" />
                                        <ul class="sub-msg-opr" id="titleback<?php echo $k;?>" >
                                            <li><a class="opr-icon edit-icon" href="javascript:;" onclick="appear(<?php echo $k;?>)"></a></li>
                                            <li><a class="opr-icon del-icon" href="javascript:;" onclick="del(<?php echo $v['OpenElement']['id']?>,<?php echo $k?>)"></a></li>
                                        </ul>
                                    </div>
                                <?php  }}?>
                            <div class="titlediv" id="add_one_row">
                                <div class="add_row">
                                    <label><span class="addpic" onclick="addson()"></span><span class="addletter" onclick="addson()"><?php echo $ld['add'] ?></span></label>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <!--右边开始的编辑div-->
                    <div id="rightmenu">
                        
                        <?php echo $form->create('OpenElement',array('action'=>'/view/'.$type,'name'=>"OpenElementForm",'id'=>"OpenElementForm"));?>
                        <div id="right" class="msg-editer-wrapper" >
                            <div class="left_arrows"><span class="a-out" style="margin-top: 0px;"></span><span class="a-in" style="margin-top: 0px;"></span></div>
                            <div class="msg-editer-wrapper1">
                                <div class="msg-editer">
                                    <input type="hidden" id="oid100" name="data[100][OpenElement][id]" value="<?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['id'];} ?>" />
                                    <input type="hidden" id="opid100" name="data[100][OpenElement][parent_id]" value="<?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['parent_id'];} ?>"/>
                                    <input type="hidden" id="oeid100" name="data[100][OpenElement][element_type]" value="<?php echo $type;?>"/>
                                    <div class="control-group group_title" >
                                        <label class="control-label"><?php echo $ld['title']?></label>
                                        <span class="maroon">*</span>
                                        <span class="help-inline">(<?php echo $ld['required'] ?>)</span>
                                        <div class="group_title">
                                            <input class="op_title span5" id="title100" onkeyup="strLenCalc(100)" type="text" name="data[100][OpenElement][title]" style="width: 682px;" value="<?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['title'];}?>">
                                        </div>
                                    </div>
                                    <div class="control-group group_img">
                                        <label class="control-label"><?php echo $ld['cover'] ?></label>
                                        <span class="maroon">*</span>
                                        <span class="help-inline">(<?php echo $ld['required'] ?>)</span>
                                        <div class="choosebutton">
                                            <div class="ch" onclick="select_imge('tip100',100)"><?php echo $ld['article_select_file'] ?></div>
                                            <span class="sp1"><?php echo trim($ld['cover_desc']); ?><br /><?php echo $ld['open_element_bulk_desc'] ?></span>
                                        </div>
                                        <input type="hidden" class="op_tip media_url" name="data[100][OpenElement][media_url]" id="tip100" value="<?php echo (isset($this->data['OpenElement'])&&$this->data['OpenElement']['media_url']!="")?$this->data['OpenElement']['media_url']:'';?>"/>
                                    </div>
                                    <div class="uplod" id="uplod100" style="<?php echo (!empty($this->data['OpenElement']['media_url']))?'':'display:none;';?>" >
                                        <div style="float:left;">
                                            <img id="show1_tip100" src="<?php echo (isset($this->data['OpenElement'])&&$this->data['OpenElement']['media_url']!="")?$this->data['OpenElement']['media_url']:'';?>"  class="show_media_url" />
                                        </div>
                                        <div style="float:left;"><input type="button" value="<?php echo $ld['delete'] ?>" onclick="dele(100)" class="del_btn am-btn am-btn-danger am-btn-xs am-radius" /></div>
                                        <div style="clear:both;"></div>
                                    </div>
                                    <div class="control-group group_content">
                                        <label class="control-label"><?php echo $ld['body'] ?></label>
                                        <span class="maroon">*</span>
                                        <span class="help-inline">(<?php echo $ld['required'] ?>)</span>
                                        <div class="choosebutton" style="border:none">
                                            <textarea  style="resize:none;height:300px; width:682px;top:-4px;position:relative;left:-2px;overflow:hidden;" class="op_description" id="description100" name="data[100][OpenElement][description]"><?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['description'];}?></textarea>
                                            <script type="text/javascript">
                                                var editor;
                                                KindEditor.ready(function(K) {
                                                    editor = K.create('#description100', {
                                                        cssPath : '/css/index.css',filterMode : false,
                                                        resizeType : 1,
                                                        allowPreviewEmoticons : false,
                                                        allowImageUpload : false,
                                                        items : [
                                                            'source','fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                                                            'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                                                            'insertunorderedlist', '|', 'emoticons', 'image', 'link'],
                                                        afterBlur: function(){this.sync();}});
                                                });
                                            </script>
                                        </div>
                                    </div>
                                    <?php if(empty($this->data['OpenElement']['url'])){?>
                                        <div class="control-group group_link" id="od100" >
                                            <a class="am-btn am-btn-warning am-radius am-btn-sm" href="javascript:void(0);" onclick="outaddress(100)"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['add'].' '.$ld['external_chain']:$ld['add'].$ld['external_chain'];?></a>
                                        </div>
                                        <div class="control-group out_address dis_none" id="outadd100">
                                            <label class="control-label" ><?php echo $ld['external_chain'].$ld['address']; ?></label>
                                            <span class="help-inline">(<?php echo $ld['external_chain_desc'] ?>)</span>
                                            <div><input id="url100" class="span5" type="text" name="data[100][OpenElement][url]" style="width: 682px;" value="<?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['url'];}?>">
                                            </div>
                                        </div>
                                    <?php }else{?>
                                        <div class="control-group out_address" id="outadd100">
                                            <label class="control-label" ><?php echo $ld['external_chain'].$ld['address']; ?></label>
                                            <span class="help-inline">(<?php echo $ld['external_chain_desc'] ?>)</span>
                                            <div><input id="url100" class="span5" type="text" name="data[100][OpenElement][url]" style="width: 682px;" value="<?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['url'];}?>"></div>
                                        </div>
                                    <?php }?>
                                    <?php if(empty($this->data['OpenElement']['link'])){?>
                                        <div class="control-group out_link" id="os100">
                                            <a class="am-btn am-btn-warning am-radius am-btn-sm" href="javascript:void(0);" onclick="outresource(100)"><?php echo (isset($backend_locale)&&$backend_locale=='eng')?$ld['add'].' '.$ld['reffer']:$ld['add'].$ld['reffer'];?></a>
                                        </div>
                                        <div class="control-group out_source dis_none" id="ors100">
                                            <label class="control-label" ><?php echo $ld['reffer']?></label>
                                            <span class="help-inline">(<?php echo $ld['reffer_url_desc'] ?>)</span>
                                            <div><input id="url100" class="span5" type="text" name="data[100][OpenElement][link]" style="width: 682px;" value="<?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['link'];}?>"></div>
                                        </div>
                                    <?php }else{?>
                                        <div class="control-group out_source" id="ors100">
                                            <label class="control-label" ><?php echo $ld['reffer']?></label>
                                            <span class="help-inline">(<?php echo $ld['reffer_url_desc'] ?>)</span>
                                            <div><input id="url100" class="span5" type="text" name="data[100][OpenElement][link]" style="width: 682px;" value="<?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['link'];}?>"></div>
                                        </div>
                                    <?php }?>
                                </div>
                            </div>
                        </div>

                        <?php if($type==2&&empty($manypic)){ //多图文 ?>
                        <div id="right0" class="msg-editer-wrapper" style="display:none;top:245px;">
                            <div>
                                <div class="msg-editer">
                                    <input type="hidden"  id="oid0" name="data[0][OpenElement][id]" value="" />
                                    <input type="hidden" id="opid0" name="data[0][OpenElement][parent_id]" value=""/>
                                    <input type="hidden" id="oeid0" name="data[0][OpenElement][element_type]" value="<?php echo $type;?>"/>
                                    <div class="control-group group_title" >
                                        <label class="control-label"><?php echo $ld['title']?></label>
                                        <span class="maroon">*</span>
                                        <span class="help-inline">(<?php echo $ld['required'] ?>)</span>
                                        <div class="group_title">
                                            <input class="op_title span5" id="title0" onkeyup="strLenCalc('0')" type="text" name="data[0][OpenElement][title]" style="width: 682px;" value="">
                                        </div>
                                    </div>
                                    <div class="control-group group_img">
                                        <label class="control-label"><?php echo $ld['cover'] ?></label>
                                        <span class="maroon">*</span>
                                        <span class="help-inline">(<?php echo $ld['required'] ?>)</span>
                                        <div class="choosebutton"><div class="ch" onclick="select_imge('tip0')"><?php echo $ld['article_select_file'] ?></div><span class="sp1"><?php echo trim($ld['cover_desc']) ?><br /><?php echo $ld['open_element_bulk_desc'] ?></span></div>
                                        <input type="hidden" class="media_url" name="data[0][OpenElement][media_url]" id="tip0" value=""/>
                                    </div>
                                    <div class="uplod" id="uplod0" style="display:none;">
                                        <div style="float:left;"><img id="show1_tip0" src="" class="show_media_url" /></div>
                                        <div style="float:left;"><input type="button" value="<?php echo $ld['delete'] ?>" onclick="dele('0')" class="del_btn am-btn am-btn-danger am-btn-xs am-radius"  /></div>
                                        <div style="clear:both;"></div>
                                    </div>
                                    <div class="control-group group_content">
                                        <label class="control-label"><?php echo $ld['body'] ?></label>
                                        <span class="maroon">*</span>
                                        <span class="help-inline">(<?php echo $ld['required'] ?>)</span>
                                        <div class="choosebutton" style="border:none">
                                            <textarea style="resize:none;height:275px; width:682px;top:-4px;position:relative;left:-2px;overflow:hidden;" class="op_description" id="description0" name="data[0][OpenElement][description]"></textarea>
                                            <script type="text/javascript">
                                                var editor;
                                                KindEditor.ready(function(K) {
                                                    editor = K.create('#description0', {
                                                        cssPath : '/css/index.css',filterMode : false,
                                                        resizeType : 1,
                                                        allowPreviewEmoticons : false,
                                                        allowImageUpload : false,
                                                        items : [
                                                            'source','fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                                                            'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                                                            'insertunorderedlist', '|', 'emoticons', 'image', 'link'],
                                                        afterBlur: function(){this.sync();}});
                                                });
                                            </script>
                                        </div>
                                    </div>
                                    <?php if(empty($this->data['OpenElement']['url'])){?>
                                        <div class="control-group group_link" id="od0">
                                            <a class="am-btn am-btn-warning am-radius am-btn-sm" href="javascript:void(0);" onclick="outaddress('0')"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['add'].' '.$ld['external_chain']:$ld['add'].$ld['external_chain'];?></a>
                                        </div>
                                        <div class="control-group out_address dis_none"  id="outadd0">
                                            <label class="control-label" ><?php echo $ld['external_chain'].$ld['address']; ?></label>
                                            <span class="help-inline">(<?php echo $ld['external_chain_desc'] ?>)</span>
                                            <div>
                                                <input id="url0" class="span5" type="text" name="data[0][OpenElement][url]" style="width: 682px;" value="<?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['url'];}?>">
                                            </div>
                                        </div>
                                    <?php }else{?>
                                    <div class="control-group out_address"  id="outadd0">
                    						<label class="control-label" ><?php echo $ld['external_chain'].$ld['address']; ?></label>
                                    <span class="help-inline">(<?php echo $ld['external_chain_desc'] ?>)</span>
                                    <div>
                                        <input id="url0" class="span5" type="text" name="data[0][OpenElement][url]" style="width: 682px;" value="<?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['url'];}?>">
                                    </div>
                                </div>
                                <?php }?>
                                <?php if(empty($this->data['OpenElement']['link'])){?>
                                    <div class="control-group out_link" id="os0">
                                        <a class="am-btn am-btn-warning am-radius am-btn-sm" href="javascript:void(0);" onclick="outresource('0')"><?php echo (isset($backend_locale)&&$backend_locale=='eng')?$ld['add'].' '.$ld['reffer']:$ld['add'].$ld['reffer'];?></a>
                                    </div>
                                    <div class="control-group out_source dis_none" id="ors0">
                                        <label class="control-label" ><?php echo $ld['reffer']?></label>
                                        <span class="help-inline">(<?php echo $ld['reffer_url_desc'] ?>)</span>
                                        <div>
                                            <input id="link0" class="span5" type="text" name="data[0][OpenElement][link]" style="width: 682px;" value="<?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['link'];}?>">
                                        </div>
                                    </div>
                                <?php }else{?>
                                    <div class="control-group out_source" id="ors0">
                                        <label class="control-label" ><?php echo $ld['reffer']?></label>
                                        <span class="help-inline">(<?php echo $ld['reffer_url_desc'] ?>)</span>
                                        <div>
                                            <input id="link0" class="span5" type="text" name="data[0][OpenElement][link]" style="width: 682px;" value="<?php if(isset($this->data['OpenElement'])){echo $this->data['OpenElement']['link'];}?>">
                                        </div>
                                    </div>
                                <?php }?>
                                <span class="a-out" style="margin-top: 0px;"></span>
                                <span class="a-in" style="margin-top: 0px;"></span>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <?php
                    if(isset($manypic)&&!empty($manypic)){
                        foreach($manypic as $k=>$v){
                            ?>
                            <div id="right<?php echo $k;?>" class="msg-editer-wrapper" style="display:none;">
                                <div>
                                    <div class="msg-editer">
                                        <input type="hidden"  id="oid<?php echo $k;?>" name="data[<?php echo $k;?>][OpenElement][id]" value="<?php if(isset($v['OpenElement']['id'])){echo $v['OpenElement']['id'];} ?>" />
                                        <input type="hidden" id="opid<?php echo $k;?>" name="data[<?php echo $k;?>][OpenElement][parent_id]" value="<?php if(isset($v['OpenElement']['parent_id'])){echo $v['OpenElement']['parent_id'];} ?>"/>
                                        <input type="hidden" id="oeid<?php echo $k;?>" name="data[<?php echo $k;?>][OpenElement][element_type]" value="<?php echo $type;?>"/>
                                        <div class="control-group group_title">
                                            <label class="control-label"><?php echo $ld['title']?></label>
                                            <span class="maroon">*</span>
                                            <span class="help-inline">(<?php echo $ld['required'] ?>)</span>
                                            <div class="group_title">
                                                <input class="op_title span5" id="title<?php echo $k;?>" onkeyup="strLenCalc(<?php echo $k;?>)" type="text" name="data[<?php echo $k;?>][OpenElement][title]" style="width: 682px;" value="<?php if(isset($v['OpenElement']['title'])){echo $v['OpenElement']['title'];}?>">
                                            </div>
                                        </div>
                                        <div class="control-group group_img">
                                            <label class="control-label"><?php echo $ld['cover'] ?></label>
                                            <span class="maroon">*</span>
                                            <span class="help-inline">(<?php echo $ld['required'] ?>)</span>
                                            <div class="choosebutton"><div class="ch" onclick="select_imge('tip<?php echo $k;?>')"><?php echo $ld['article_select_file'] ?></div><span class="sp1"><?php echo trim($ld['cover_desc']) ?><br /><?php echo $ld['open_element_bulk_desc'] ?></span></div>
                                            <input class="op_tip media_url" type="hidden" name="data[<?php echo $k;?>][OpenElement][media_url]" id="tip<?php echo $k;?>" value="<?php echo (isset($v['OpenElement']['media_url'])&&$v['OpenElement']['media_url']!="")?$v['OpenElement']['media_url']:'';?>"/>
                                        </div>
                                        <div class="uplod" id="uplod<?php echo $k;?>" style="<?php echo (!empty($v['OpenElement']['media_url']))?'':'display:none;';?>">
                                            <div style="float:left;"><img id="show1_tip<?php echo $k;?>" src="<?php echo $v['OpenElement']['media_url'];?>" class="show_media_url" /></div>
                                            <div style="float:left;"><input type="button" value="<?php echo $ld['delete'] ?>" onclick="dele(<?php echo $k;?>)" class="del_btn am-btn am-btn-danger am-btn-xs am-radius" /></div>
                                            <div style="clear:both;"></div>
                                        </div>
                                        <div class="control-group group_content">
                                            <label class="control-label"><?php echo $ld['body'] ?></label>
                                            <span class="maroon">*</span>
                                            <span class="help-inline">(<?php echo $ld['required'] ?>)</span>
                                            <div class="choosebutton" style="border:none">
                                                <textarea style="resize:none;height:275px; width:682px;top:-4px;position:relative;left:-2px;overflow:hidden;" class="op_description" id="description<?php echo $k;?>" name="data[<?php echo $k;?>][OpenElement][description]"><?php if(!empty($v['OpenElement']['description'])){echo $v['OpenElement']['description'];}?></textarea>
                                                <script type="text/javascript">
                                                    KindEditor.create('#description<?php echo $k;?>', {
                                                        cssPath : '/css/index.css',filterMode : false,
                                                        resizeType : 1,
                                                        allowPreviewEmoticons : false,
                                                        allowImageUpload : false,
                                                        items : [
                                                            'source','fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                                                            'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                                                            'insertunorderedlist', '|', 'emoticons', 'image', 'link'],
                                                        afterBlur: function(){this.sync();}
                                                    });

                                                </script>
                                            </div>
                                        </div>
                                        <?php if(empty($v['OpenElement']['url'])){?>
                                            <div class="control-group group_link" id="od<?php echo $k;?>" >
                                                <a class="am-btn am-btn-warning am-radius am-btn-sm" href="javascript:void(0);" onclick="outaddress('<?php echo $k;?>')"><?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['add'].' '.$ld['external_chain']:$ld['add'].$ld['external_chain'];?></a>
                                            </div>
                                            <div class="control-group out_address dis_none"  id="outadd<?php echo $k;?>">
                                                <label class="control-label" ><?php echo $ld['external_chain'].$ld['address']; ?></label>
                                                <span class="help-inline">(<?php echo $ld['external_chain_desc'] ?>)</span>
                                                <div><input id="url<?php echo $k;?>" class="span5" type="text" name="data[<?php echo $k;?>][OpenElement][url]" style="width: 682px;" value="<?php if(!empty($v['OpenElement']['url'])){echo $v['OpenElement']['url'];}?>"></div>
                                            </div>
                                        <?php }else{?>
                                            <div class="control-group out_address"  id="outadd<?php echo $k;?>">
                                                <label class="control-label" ><?php echo $ld['external_chain'].$ld['address']; ?></label>
                                                <span class="help-inline">(<?php echo $ld['external_chain_desc'] ?>)</span>
                                                <div><input id="url<?php echo $k;?>" class="span5" type="text" name="data[<?php echo $k;?>][OpenElement][url]" style="width: 682px;" value="<?php if(!empty($v['OpenElement']['url'])){echo $v['OpenElement']['url'];}?>"></div>
                                            </div>
                                        <?php }?>
                                        <?php if(isset($v['OpenElement']['link']) && $v['OpenElement']['link']==""){?>
                                            <div class="control-group out_link" id="os<?php echo $k;?>">
                                                <a class="am-btn am-btn-warning am-radius am-btn-sm" href="javascript:void(0);" onclick="outresource('<?php echo $k;?>')"><?php echo (isset($backend_locale)&&$backend_locale=='eng')?$ld['add'].' '.$ld['reffer']:$ld['add'].$ld['reffer'];?></a>
                                            </div>
                                            <div class="control-group out_source dis_none"  id="ors<?php echo $k;?>">
                                                <label class="control-label" ><?php echo $ld['reffer']?></label>
                                                <span class="help-inline">(<?php echo $ld['reffer_url_desc'] ?>)</span>
                                                <div>
                                                    <input id="link<?php echo $k;?>" class="span5" type="text" name="data[<?php echo $k;?>][OpenElement][link]" style="width: 682px;" value="<?php if(!empty($v['OpenElement']['link'])){echo $v['OpenElement']['link'];}?>">
                                                </div>
                                            </div>
                                        <?php }else{?>
                                            <div class="control-group out_source"  id="ors<?php echo $k;?>">
                                                <label class="control-label" ><?php echo $ld['reffer']?></label>
                                                <span class="help-inline">(<?php echo $ld['reffer_url_desc'] ?>)</span>
                                                <div>
                                                    <input id="link<?php echo $k;?>" class="span5" type="text" name="data[<?php echo $k;?>][OpenElement][link]" style="width: 682px;" value="<?php if(!empty($v['OpenElement']['link'])){echo $v['OpenElement']['link'];}?>">
                                                </div>
                                            </div>
                                        <?php }?>
                                        <span class="a-out" style="margin-top: 0px;"></span>
                                        <span class="a-in" style="margin-top: 0px;"></span>
                                    </div>
                                </div>
                            </div>
                        <?php }} ?>
                    <div class="btnouter" style="margin-top:18px;">
                        <input type='button' class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" onclick='check_submit_form()'/>  <input class="am-btn am-btn-success am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                    </div>
                    <?php echo $form->end();?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
                        
    
    <?php if(isset($open_model_list)&&sizeof($open_model_list)>0){?>
    <div id="open_type_action" class="am-panel-group">
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#qunfa', target: '#qunfa_info'}">预览/群发</h4>
            </div>
            <div id="qunfa_info" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form-horizontal" style="padding-bottom:0;">
                    <div id="user_list">
                        <div>
                            <div class="listsearch">
                                <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3">
                                    <li style="margin-bottom:10px;">
                                        <label class="am-u-sm-4 am-form-label" style="margin-top:4px;"><?php echo $ld['open_model_account'];?></label>
                                        <div class="am-u-sm-6">
                                            <input type="hidden" id="media_id" value="" />
                                            <input type="hidden" id="element_type" value="<?php echo $type ?>" />
                                            <input type="hidden" id="element_id" value="<?php echo isset($this->data['OpenElement']['id'])?$this->data['OpenElement']['id']:0 ?>" />
                                            <select id="open_type_id" style="margin-top:10px;">
                                                <option value=""><?php echo $ld['open_model_account'] ?></option>
                                                <?php foreach($open_model_list as $k=>$v){ ?>
                                                    <option value="<?php echo $v ?>"><?php echo $v ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- 关注用户列表 start -->
                            <div id="open_user_list"></div>
                            <!-- 关注用户列表 end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }?>
    
</div>
<style type="text/css">
    #user_list #t1{margin:5px 0;}
    <?php if(isset($open_model_list)&&sizeof($open_model_list)>0){?>
    #smallmenu{min-height:0;}
    <?php } ?>
    #OpenElementForm .btnouter{border-bottom:0px;}
</style>
<script>
    $(function(){
        //设置添加时的默认空白图片
        if(typeof(element_Id)!="undefined"&&element_Id==""){
            $(".default-tip").attr("src","/admin/skins/default/img/element_add_default.png");
            $(".smallback").attr("src","/admin/skins/default/img/element_add_default.png");
        }

        $("#open_type_id").change(function(){
            var open_type_id=$(this).val();
            $.ajax({ url: "/admin/open_elements/open_user_list/"+encodeURI(open_type_id),
                dataType:"html",
                data: {},
                success: function(data){
                    $("#open_user_list").html(data);
                }
            });
        });
    })
    /* ------------------------- 微信群发 --------------------------- */
    function send(openid){
        if(confirm("是否发送微信")){
            ajax_send(openid);
        }
    }
    
    function batch_send(){
	        var postData =[];
	        var bratch_operat_check = document.getElementsByName("checkboxes[]");
	        var ck_count=0;
	        for(var i=0;i<bratch_operat_check.length;i++){
	            if(bratch_operat_check[i].checked){
	                postData[ck_count]=bratch_operat_check[i].value;
	                ck_count++;
	            }
	        }
	        if(postData.length==0){
	            alert("<?php echo $ld['please_select'] ?>");
	            return;
	        }
	        if(postData.length==1){
	            alert("必须选择2个以上的关注用户");
	            return;
	        }
	        if(confirm("是否群发微信")){
	            ajax_send(postData,'send');
	        }
    }

    function ajax_send(touser,send_type){
        $("#open_user_list input[type=checkbox]").attr("checked",false);
        var open_type_id=$("#open_type_id").val();
        var element_type=$("#element_type").val();
        var element_id=$("#element_id").val();

        if(open_type_id!=""){
            $.ajax({ url: "/admin/open_elements/send",
                dataType:"json",
                type:"POST",
                data:{'element_id':element_id,'open_type_id': open_type_id,'element_type':element_type,'touser': touser,'send_type':send_type},
                success: function(data){
                    alert(data.msg);
                }
            });
        }else{
            alert("<?php echo $ld['please_select'].$ld['open_model_account']; ?>");
        }
    }
    
    function batch_preview(){
		var postData =[];
		var bratch_operat_check = document.getElementsByName("checkboxes[]");
		var ck_count=0;
		for(var i=0;i<bratch_operat_check.length;i++){
			if(bratch_operat_check[i].checked){
				postData[ck_count]=bratch_operat_check[i].value;
				ck_count++;
			}
		}
		if(postData.length==0){
			alert("<?php echo $ld['please_select'] ?>");
			return;
		}
		if(postData.length>1){
	            	alert("只能发送给1个关注用户");
	            	return;
	        }
	        ajax_send(postData,'preview');
    }
</script>