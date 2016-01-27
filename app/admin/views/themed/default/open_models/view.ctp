<style>
    .am-radio, .am-checkbox{display:inline;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
    .info{width:300px;line-height:30px;word-wrap : break-word ;}
</style>
<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<script src="/plugins/ajaxfileupload.js" type="text/javascript"></script>
<?php echo $form->create('OpenModels',array('action'=>'view/'.(isset($this->data['OpenModel'])?$this->data['OpenModel']['id']:''),'name'=>'OpenModelForm','onsubmit'=>'return form_checks();'));?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
        <li><a href="#advanced"><?php echo $ld['advanced_config'];?></a></li>
    </ul>
</div>
	<!---基本标题---->		
<div class="am-panel-group admin-content  am-detail-view" id="accordion" >
<div id="basic_info" class="am-panel am-panel-default">
    <div class="am-panel-hd">
        <h4 class="am-panel-title">
            <?php echo $ld['basic_information']?>
        </h4>
    </div>
    <div id="basic_information" class="am-panel-collapse am-collapse am-in">
        <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
            <input id='id' name='data[OpenModel][id]' type='hidden' value='<?php echo isset($this->data['OpenModel']['id'])?$this->data['OpenModel']['id']:'';?>'>
            <?php if(isset($this->data['OpenModel']['open_type_id'])){ ?>
                <input type="hidden" value="<?php echo $this->data['OpenModel']['open_type_id']; ?>" name="data[old_open_type_id]" />
            <?php } ?>
            <table class="am-table">
                <tr>
                    <th style="width:20%;padding-top:15px;"><?php echo $ld['open_model_account']?></th>
                    <td style=" width:35%;float:left;"><input   id='OpenModelTypeId' name='data[OpenModel][open_type_id]'  type='text' value='<?php echo isset($this->data['OpenModel']['open_type_id'])?$this->data['OpenModel']['open_type_id']:'';?>'><td style="float:left;"><em>*</em></td>
                
                    </td>
                     
                </tr>
                <tr>
                    <th style="padding-top:15px;"><?php echo $ld['open_model'] ?></th>
                    <td style=" width:35%;float:left;">
                        <select  id='OpenModelType' name='data[OpenModel][open_type]' onchange="check_wechat()">
                            <option value='wechat' <?php if (isset($this->data['OpenModel']['open_type']) && $this->data['OpenModel']['open_type'] == 'wechat') echo 'selected'; ?>><?php echo $ld['wechat'] ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th style="padding-top:12px;"><?php echo $ld['avatar'] ?></th>
                    <td style=" width:35%;float:left;">
                        <?php $OpenModel_img=isset($this->data['OpenModel']['img'])&&$this->data['OpenModel']['img']!=""?$this->data['OpenModel']['img']:"";
                        ?>
                        <div><img id="OpenModelImg_show" src="<?php echo $OpenModel_img; ?>" style="width:60px;height:60px;<?php echo $OpenModel_img==""?'display:none;':'display:block;'; ?>" /></div>
                        <input type="hidden" id="data[OpenModel][img]" name="data[OpenModel][img]" value="<?php echo $OpenModel_img; ?>" />
                        <input type="file" id="OpenModelImg" onchange="ajaxFileUpload()" name="OpenModelImg" />

                    </td>
                </tr>
                <tr id="type_dy">
                    <th style="padding-top:15px;"><?php echo $ld['open_model'].$ld['type'] ?></th>
                    <td ><label class="am-radio am-success" style="padding-top:2px"><input id='OpenModeltype_dy' name='data[OpenModel][type]' type='radio' data-am-ucheck <?php echo isset($this->data['OpenModel']['type'])&&$this->data['OpenModel']['type']==0 ?'checked':'';?> value="0"><?php echo $ld['subscribe_no'] ?></label>
                        <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input id='OpenModeltype_fw' name='data[OpenModel][type]' type='radio' data-am-ucheck <?php echo isset($this->data['OpenModel']['type'])&&$this->data['OpenModel']['type']==1 ?'checked':'';?> value="1"><?php echo $ld['service_no'] ?></label>
                    </td>
                </tr>
                <tr id="verify_status">
                    <th style="padding-top:10px"><?php echo $ld['verify'].$ld['status']?></th>
                    <td><label style="margin:0;padding-top:0;" class="am-checkbox am-success"><input id='OpenModelVerifyStatus' onclick="check_status()" name='data[OpenModel][verify_status]' type='checkbox' value="1" data-am-ucheck <?php if(isset($this->data['OpenModel']['verify_status']) && $this->data['OpenModel']['verify_status']==1){echo "checked";}?> /><?php echo $ld['status_certified'] ?></label></td>
                </tr>
                <tr id="app_id">
                    <th style="padding-top:15px;"><?php echo 'AppId'?></th>
                    <td style=" width:35%;float:left;"><input   id='OpenModelAppId' name='data[OpenModel][app_id]'
                               type='text' value='<?php echo isset($this->data['OpenModel']['app_id'])?$this->data['OpenModel']['app_id']:'';?>'></td><td style="float:left;";><em>*</em></td>
                </tr>
                <tr id="app_secret">
                    <th style="padding-top:15px;"><?php echo 'AppSecret'?></th>
                    <td style=" width:35%;float:left;"><input  id='OpenModelAppSecret' name='data[OpenModel][app_secret]'
                               type='text' value='<?php echo isset($this->data['OpenModel']['app_secret'])?$this->data['OpenModel']['app_secret']:'';?>'></td><td style="float:left;";><em>*</em></td>
                </tr>
                <?php if(!empty($this->data['OpenModel'])){?>
                    <tr id='token'>
                        <th style="padding-top:15px;"><?php echo 'Signature Token'?></th>
                        <td style=" width:35%;float:left;"><input id='OpenModelSignatureToken' name='data[OpenModel][signature_token]'
                                   type='text' value='<?php echo isset($this->data['OpenModel']['signature_token'])?$this->data['OpenModel']['signature_token']:'';?>' /></td> <td style="float:left;";><em>*</em></td>
                    </tr>
                    	
                    <tr id="update_token"><th><?php echo 'Token'; ?></th>
                    	<td>
                            <p class="info"><?php echo isset($this->data['OpenModel']['token'])?$this->data['OpenModel']['token']:'';?></p>
                            <?php if(!empty($this->data['OpenModel']['id'])){ ?>
                                <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" onclick="javascript:location.href='/admin/open_models/token/<?php echo $this->data['OpenModel']['id']?>';" value="<?php echo $ld['update'] ?>Token">
                            <?php }?>
                           </td>
                     </tr>
                <?php }else{ ?>
                			
                    <tr id='token'>
                        <th><?php echo 'Signature Token'?></th>
                        <td><input  id='OpenModelSignatureToken' name='data[OpenModel][signature_token]'
                                   type='text' value='<?php echo isset($this->data['OpenModel']['signature_token'])?$this->data['OpenModel']['signature_token']:'';?>'><em>*</em></td>
                    </tr>
                    	
                <?php } ?>
                <tr>
                    <th style="padding-top:15px;"><?php echo $ld['status'] ?></th>
                    <td><label class="am-radio am-success" style="padding-top:1px;"><input type="radio" name="data[OpenModel][status]" value="1" data-am-ucheck <?php echo (isset($this->data['OpenModel']['status'])&&$this->data['OpenModel']['status']=='1')||(!isset($this->data['OpenModel']['status']))?'checked':'' ?> /><?php echo $ld['valid'];?></label>
                        <label style="margin-left:10px;padding-top:1px;" class="am-radio am-success"><input type="radio" name="data[OpenModel][status]" value="0" data-am-ucheck <?php echo isset($this->data['OpenModel']['status'])&&$this->data['OpenModel']['status']=='0'?'checked':'' ?> /><?php echo $ld['invalid'];?></label></td>
                </tr>
                <tr>
                    <th><?php echo $ld['description'] ?></th>
                    <td style=" width:75%;float:left;"><textarea name='data[OpenModel][content]'><?php echo isset($this->data['OpenModel']['content'])?$this->data['OpenModel']['content']:'';?></textarea></td>
                </tr>
                	
                	<tr>
                    <th style="padding-top:15px;" rowspan='<?php echo count($backend_locales)+1;?>'><?php echo $ld['HEADER_AREA_INFORMATION'] ?></th>
                    <td>
                        <label class="am-radio am-success" style="padding-top:1px;"><input type="radio" name="data[OpenConfig][HEADER-AREA-INFORMATION][status]" value='1' data-am-ucheck <?php echo (isset($open_config_data['HEADER-AREA-INFORMATION']['OpenConfig']['status'])&&$open_config_data['HEADER-AREA-INFORMATION']['OpenConfig']['status']=='1')||(!isset($open_config_data['HEADER-AREA-INFORMATION']))?'checked':''; ?>/><?php echo $ld['valid']; ?></label>
                        <label style="margin-left:10px;padding-top:1px;" class="am-radio am-success"><input type="radio" name="data[OpenConfig][HEADER-AREA-INFORMATION][status]" value='0' data-am-ucheck <?php echo isset($open_config_data['HEADER-AREA-INFORMATION']['OpenConfig']['status'])&&$open_config_data['HEADER-AREA-INFORMATION']['OpenConfig']['status']=='0'?'checked':''; ?> /><?php echo $ld['invalid']; ?></label>
                    </td>
                </tr>
                	     <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                        <td style=" width:75%;float:left;position:relative;"><span class="ckeditorlanguage" style="top:20px;right:-50px;" ><?php echo $ld[$v['Language']['locale']];?></span>
                            <textarea id="HEADER_AREA_INFORMATION_<?php echo $v['Language']['locale'];?>" name="data[OpenConfig][HEADER-AREA-INFORMATION][<?php echo $v['Language']['locale'];?>]"><?php echo isset($open_config_data['HEADER-AREA-INFORMATION']['OpenConfigsI18n'][$v['Language']['locale']])?$open_config_data['HEADER-AREA-INFORMATION']['OpenConfigsI18n'][$v['Language']['locale']]['value']:"" ?></textarea>
                            <?php if($configs["show_edit_type"]){?>
                                <script type="text/javascript">
                                    var editor;
                                    KindEditor.ready(function(K) {
                                        editor = K.create('#HEADER_AREA_INFORMATION_<?php echo $v['Language']['locale'];?>', {
                                        	width:'';
                                            langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false,width : '300px'});
                                    });
                                </script>
                            <?php }else{
                                echo $ckeditor->load("HEADER_AREA_INFORMATION_".$v['Language']['locale']); ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php }} ?>
                    		
                    <!---底部-->
                    <tr>
                    <th style="padding-top:15px;" rowspan='<?php echo count($backend_locales)+1;?>'><?php echo $ld['BOTTOM_AREA_INFORMATION'] ?></th>
                    <td>
                        <label class="am-radio am-success" style="padding-top:1px;"><input type="radio" name="data[OpenConfig][BOTTOM-AREA-INFORMATION][status]" value='1' data-am-ucheck <?php echo (isset($open_config_data['BOTTOM-AREA-INFORMATION']['OpenConfig']['status'])&&$open_config_data['BOTTOM-AREA-INFORMATION']['OpenConfig']['status']=='1')||(!isset($open_config_data['BOTTOM-AREA-INFORMATION']))?'checked':''; ?>/><?php echo $ld['valid']; ?></label>
                        <label style="margin-left:10px;padding-top:1px;" class="am-radio am-success"><input type="radio" name="data[OpenConfig][BOTTOM-AREA-INFORMATION][status]" value='0' data-am-ucheck <?php echo isset($open_config_data['BOTTOM-AREA-INFORMATION']['OpenConfig']['status'])&&$open_config_data['BOTTOM-AREA-INFORMATION']['OpenConfig']['status']=='0'?'checked':''; ?> /><?php echo $ld['invalid']; ?></label>
                    </td>
                </tr>	
                	<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                        <td style="position:relative;"><span class="ckeditorlanguage"  style="top:80px;right:-5px;"><?php echo $ld[$v['Language']['locale']];?></span>
                            <textarea id="BOTTOM_AREA_INFORMATION_<?php echo $v['Language']['locale'];?>"s name="data[OpenConfig][BOTTOM-AREA-INFORMATION][<?php echo $v['Language']['locale'];?>]"><?php echo isset($open_config_data['BOTTOM-AREA-INFORMATION']['OpenConfigsI18n'][$v['Language']['locale']])?$open_config_data['BOTTOM-AREA-INFORMATION']['OpenConfigsI18n'][$v['Language']['locale']]['value']:"" ?></textarea>
                            <?php if($configs["show_edit_type"]){?>
                                <script type="text/javascript">
                                    var editor;
                                    KindEditor.ready(function(K) {
                                        editor = K.create('#BOTTOM_AREA_INFORMATION_<?php echo $v['Language']['locale'];?>', { width:'93%',
                                            langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
                                    });
                                </script>
                            <?php }else{
                                echo $ckeditor->load("BOTTOM_AREA_INFORMATION_".$v['Language']['locale']); ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php }} ?>
            </table>
            <div class='btnouter'>
                <input type='submit' class="am-btn am-btn-success am-radius am-btn-sm" value='<?php echo $ld['d_submit']?>' /> 
                <input type='reset' class="am-btn am-btn-success am-radius am-btn-sm" value='<?php echo $ld['d_reset']?>' />
            </div>
        </div>
    </div>
</div>
      <!----高级设置--->					
<div id="advanced" class="am-panel am-panel-default">
    <div class="am-panel-hd">
        <h4 class="am-panel-title"><?php echo $ld['advanced_config'];?></h4>
    </div>
    <div id="advanced_config" class="am-panel-collapse am-collapse am-in">
        <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
            <table class="am-table" id='open_config'>
                 <tr>
                    <th style="padding-top:15px;" rowspan='<?php echo count($backend_locales)+1;?>'><?php echo $ld['FIRST_CONCERN'] ?></th>
                    <td>
                        <label class="am-radio am-success" style="padding-top:1px;"><input type="radio" name="data[OpenConfig][FIRST-CONCERN][status]" value='1' data-am-ucheck <?php echo (isset($open_config_data['FIRST-CONCERN']['OpenConfig']['status'])&&$open_config_data['FIRST-CONCERN']['OpenConfig']['status']=='1')||(!isset($open_config_data['FIRST-CONCERN']))?'checked':''; ?> /><?php echo $ld['valid']; ?></label>
                        <label style="margin-left:10px;padding-top:1px;" class="am-radio am-success" ><input type="radio" name="data[OpenConfig][FIRST-CONCERN][status]" value='0' data-am-ucheck <?php echo isset($open_config_data['FIRST-CONCERN']['OpenConfig']['status'])&&$open_config_data['FIRST-CONCERN']['OpenConfig']['status']=='0'?'checked':''; ?>/><?php echo $ld['invalid']; ?></label>
                    </td>
                </tr>
                <?php
                if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){
                    $open_config_data_name="text";
                    $open_config_data_val="";
                    $open_config_data_text="";
                    if(isset($open_config_data['FIRST-CONCERN']['OpenConfigsI18n'][$v['Language']['locale']])){
                        $open_config_data_name=$open_config_data['FIRST-CONCERN']['OpenConfigsI18n'][$v['Language']['locale']]['name'];
                        if($open_config_data['FIRST-CONCERN']['OpenConfigsI18n'][$v['Language']['locale']]['name']=='material'){
                            $open_config_data_text=isset($material_list[$open_config_data['FIRST-CONCERN']['OpenConfigsI18n'][$v['Language']['locale']]['value']])?$material_list[$open_config_data['FIRST-CONCERN']['OpenConfigsI18n'][$v['Language']['locale']]['value']]:'';
                        }else{
                            $open_config_data_text=$open_config_data['FIRST-CONCERN']['OpenConfigsI18n'][$v['Language']['locale']]['value'];
                        }
                        $open_config_data_val=$open_config_data['FIRST-CONCERN']['OpenConfigsI18n'][$v['Language']['locale']]['value'];
                    }
                    ?>
                    <tr>
                        <td>
                            <div style="width:50%;"><?php echo $open_config_data_name=='material'?"<a href='".$server_host."/open_elements/preview/".$open_config_data_val."' target='_blank'>".$open_config_data_text."</a>":$open_config_data_val; ?></div><input type="hidden" class="configtype" name="data[OpenConfig][FIRST-CONCERN][name][<?php echo $v['Language']['locale'];?>]" value="<?php echo $open_config_data_name; ?>" /><input type="hidden" class="configvalue" name="data[OpenConfig][FIRST-CONCERN][<?php echo $v['Language']['locale'];?>]" value="<?php echo $open_config_data_val; ?>" /><input type="button" class="answerElement am-btn am-btn-success am-radius am-btn-sm" data-am-modal="{target: '#doc-modal-1', closeViaDimmer: 0, width: 600, height: 400}" value="<?php echo $ld['set_up'] ?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?>
                        </td>
                    </tr>
                    <?php }} ?>
                <tr>
                    <th style="padding-top:15px;" rowspan='<?php echo count($backend_locales)+1;?>'><?php echo $ld['DEFAULT_ANSWER'] ?></th>
                    <td> 
                        <label class="am-radio am-success" style="padding-top:1px;"><input data-am-ucheck type="radio" name="data[OpenConfig][DEFAULT-ANSWER][status]" value='1' <?php echo (isset($open_config_data['DEFAULT-ANSWER']['OpenConfig']['status'])&&$open_config_data['DEFAULT-ANSWER']['OpenConfig']['status']=='1')||(!isset($open_config_data['DEFAULT-ANSWER']))?'checked':''; ?>/><?php echo $ld['valid']; ?></label>
                        <label style="margin-left:10px;padding-top:1px;" class="am-radio am-success"><input data-am-ucheck type="radio" name="data[OpenConfig][DEFAULT-ANSWER][status]" value='0' <?php echo isset($open_config_data['DEFAULT-ANSWER']['OpenConfig']['status'])&&$open_config_data['DEFAULT-ANSWER']['OpenConfig']['status']=='0'?'checked':''; ?>/><?php echo $ld['invalid']; ?></label>
                    </td>
                </tr>
                <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){
                    $open_config_data_name="text";
                    $open_config_data_val="";
                    $open_config_data_text="";
                    if(isset($open_config_data['DEFAULT-ANSWER']['OpenConfigsI18n'][$v['Language']['locale']])){
                        $open_config_data_name=$open_config_data['DEFAULT-ANSWER']['OpenConfigsI18n'][$v['Language']['locale']]['name'];
                        if($open_config_data['DEFAULT-ANSWER']['OpenConfigsI18n'][$v['Language']['locale']]['name']=='material'){
                            $open_config_data_text=isset($material_list[$open_config_data['DEFAULT-ANSWER']['OpenConfigsI18n'][$v['Language']['locale']]['value']])?$material_list[$open_config_data['DEFAULT-ANSWER']['OpenConfigsI18n'][$v['Language']['locale']]['value']]:'';
                        }else{
                            $open_config_data_text=$open_config_data['DEFAULT-ANSWER']['OpenConfigsI18n'][$v['Language']['locale']]['value'];
                        }
                        $open_config_data_val=$open_config_data['DEFAULT-ANSWER']['OpenConfigsI18n'][$v['Language']['locale']]['value'];
                    }
                    ?>
                    <tr>
                        <td>
                            <div style="width:50%;"><?php echo $open_config_data_name=='material'?"<a href='".$server_host."/open_elements/preview/".$open_config_data_val."' target='_blank'>".$open_config_data_text."</a>":$open_config_data_val; ?></div><input type="hidden" class="configtype" name="data[OpenConfig][DEFAULT-ANSWER][name][<?php echo $v['Language']['locale'];?>]" value="<?php echo $open_config_data_name; ?>" /><input type="hidden" class="configvalue" name="data[OpenConfig][DEFAULT-ANSWER][<?php echo $v['Language']['locale'];?>]" value="<?php echo $open_config_data_val; ?>" /><input type="button" class="answerElement am-btn am-btn-success am-radius am-btn-sm" data-am-modal="{target: '#doc-modal-1', closeViaDimmer: 0, width: 600, height: 400}" value="<?php echo $ld['set_up'] ?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?>
                        </td>
                    </tr>
                    <?php }} ?>
                <tr>
                    <th style="padding-top:15px;" rowspan='<?php echo count($backend_locales)+1;?>'><?php echo $ld['SEARCH_CONTENT'] ?></th>
                    <td>
                        <label class="am-radio am-success" style="padding-top:1px;"><input data-am-ucheck type="radio" name="data[OpenConfig][SEARCH-CONTENT][status]" value='1' <?php echo (isset($open_config_data['SEARCH-CONTENT']['OpenConfig']['status'])&&$open_config_data['SEARCH-CONTENT']['OpenConfig']['status']=='1')||(!isset($open_config_data['SEARCH-CONTENT']))?'checked':''; ?>/><?php echo $ld['valid']; ?></label>
                        <label style="margin-left:10px;padding-top:1px;" class="am-radio am-success"><input data-am-ucheck type="radio" name="data[OpenConfig][SEARCH-CONTENT][status]" value='0' <?php echo isset($open_config_data['SEARCH-CONTENT']['OpenConfig']['status'])&&$open_config_data['SEARCH-CONTENT']['OpenConfig']['status']=='0'?'checked':''; ?>/><?php echo $ld['invalid']; ?></label>
                    </td>
                </tr>
                <?php
                if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){
                    $open_config_value=isset($open_config_data['SEARCH-CONTENT']['OpenConfigsI18n'][$v['Language']['locale']]['value'])?$open_config_data['SEARCH-CONTENT']['OpenConfigsI18n'][$v['Language']['locale']]['value']:"";
                    $open_config_arr=split(";",$open_config_value);
                    ?>
                    <tr>
                        <td>
                            <label style="margin:0;padding-top:0;" class="am-checkbox am-success"><input type="checkbox" data-am-ucheck name="data[OpenConfig][SEARCH-CONTENT][<?php echo $v['Language']['locale'];?>][]" value="P" <?php echo in_array('P',$open_config_arr)?'checked':''; ?> /><?php echo $ld['product'] ?></label>
                            <label style="margin:0;padding-top:0;" class="am-checkbox am-success"><input type="checkbox" data-am-ucheck name="data[OpenConfig][SEARCH-CONTENT][<?php echo $v['Language']['locale'];?>][]" value="A" <?php echo in_array('A',$open_config_arr)?'checked':''; ?> /><?php echo $ld['article'] ?></label>
                            <label style="margin:0;padding-top:0;" class="am-checkbox am-success"><input type="checkbox" data-am-ucheck name="data[OpenConfig][SEARCH-CONTENT][<?php echo $v['Language']['locale'];?>][]" value="O" <?php echo in_array('O',$open_config_arr)?'checked':''; ?> /><?php echo $ld['order'] ?></label>
                            <label style="margin:0;padding-top:0;" class="am-checkbox am-success"><input type="checkbox" data-am-ucheck name="data[OpenConfig][SEARCH-CONTENT][<?php echo $v['Language']['locale'];?>][]" value="T" <?php echo in_array('T',$open_config_arr)?'checked':''; ?> /><?php echo $ld['topics'] ?></label>
                        	<?php if(sizeof($backend_locales)>1){?><span style="top:3px;"class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?>
                        </td>
                    </tr>
                    <?php }} ?>
            </table>
            <div class='btnouter'>
                <input type='submit' class="am-btn am-btn-success am-radius am-btn-sm" value='<?php echo $ld['d_submit']?>' /> <input class="am-btn am-btn-success  am-radius am-btn-sm" type='reset' value='<?php echo $ld['d_reset']?>' />
            </div>
        </div>
    </div>
</div>
</div>
<?php echo $form->end();?>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
    <div class="am-modal-dialog">
        <div class="am-modal-hd"><?php echo $ld['reply'].$ld['set_up'];?>
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <form>
                <table class="am-table" style="text-align:left;">
                    <tr>
                        <th><?php echo $ld['reply'].$ld['type'];?></th>
                        <td>
                            <select id="msgtype" onchange="changemsgtype(this)">
                                <option value="text"><?php echo $ld['word']; ?></option>
                                <option value="material"><?php echo $ld['graphics']; ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr id="writtenwords">
                        <th><?php echo $ld['reply'];?></th>
                        <td>
                            <div>
                                <div style="display:none;">
                                    <span id="biaoqingnew" onclick="biaoqingclick()"><img src="/admin/skins/default/img/haha.png"/><?php echo $ld['expression'] ?></span>
                                    <div class="expression">
                                        <?php
                                        foreach($Expression as $k3=>$v3){
                                            echo "<div class='picks' onclick=\"pclick(this)\" id='[@F_".($k3+1)."@]' ><img style='margin-left:0' src='/admin/skins/default/img/gif/F_".($k3+1).".gif' title='".$v3."' /></div>";
                                        }
                                        ?>
                                        <div style="clear:both"></div>
                                    </div>
                                </div>
                                <div>
                                    <textarea class="writtenwords_text" id="writtenwords_text" ></textarea>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr id="imagetext" style="display:none;">
                        <th><?php echo $ld['source_material'] ?></th>
                        <td>
                            <select id="material">
                                <option value=""><?php echo $ld['please_select']?></option>
                                <?php if(isset($material_list)&&!empty($material_list)){ ?>
                                    <?php foreach($material_list as $k2=>$v2){?>
                                        <option value="<?php echo $k2; ?>"><?php echo $v2;?></option>
                                    <?php }}?>
                            </select>
                        </td>
                    </tr>
                </table>
                <div class="btnouter">
                    <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" id="setanswer"/> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </form>
        </div>
    </div>
</div>
<style type="text/css">
    #biaoqingnew img{position:relative;top:5px;margin-right:5px;cursor:pointer;}
    .expression {
        display: none;
        position: absolute;
        width: 250px;
        padding:10px;
        background:#fff;
        border:1px solid #cecece;
    }
    .expression .picks{float:left;border:1px solid #cecece;background:#fff;cursor:pointer;}
    #writtenwords_text{width:350px;height:150px;resize:none;}
</style>
<script type='text/javascript'>
    var answerElement=null;
    $(".answerElement").on('click',function(){
        answerElement=$(this).parent();
        var configtype=answerElement.find('.configtype').val();
        var configvalue=answerElement.find('.configvalue').val();
        $("#doc-modal-1 #msgtype").val(configtype);
        if(configtype=="text"){
            $("#doc-modal-1 #writtenwords_text").val(configvalue);
            $("#doc-modal-1 #writtenwords").css("display","table-row");
            $("#doc-modal-1 #imagetext").css("display","none");
        }else{
        	$("#doc-modal-1 #material").val(configvalue);
            $("#doc-modal-1 #imagetext").css("display","table-row");
            $("#doc-modal-1 #writtenwords").css("display","none");
        }
    });

    function changemsgtype(obj){
        var type=$(obj).val();
        if(type=="text"){
            $("#imagetext").css('display','none');
            $("#writtenwords").css('display','table-row');
        }else if(type=="material"){
            $("#writtenwords").css('display','none');
            $("#imagetext").css('display','table-row');
        }else{
            $("#imagetext").css('display','none');
            $("#writtenwords").css('display','table-row');
        }
    }
    var biaoqingclicks = true;
    var Url="<?php echo $server_host; ?>/admin/img/gif/";//表情图片路径
    //表情数组
    var Expression=new Array("/微笑","/撇嘴","/好色","/发呆","/得意","/流泪","/害羞","/睡觉","/尴尬","/呲牙","/惊讶","/冷汗","/抓狂","/偷笑","/可爱","/傲慢","/犯困","/流汗","/大兵","/咒骂","/折磨/","/衰","/擦汗","/抠鼻","/鼓掌","/坏笑","/左哼哼","/右哼哼","/鄙视","/委屈","/阴险","/亲亲","/可怜","/爱情","/飞吻","/怄火","/回头","/献吻","/左太极");
    //多次替换
    String.prototype.replaceAll = function (findText, repText){
        var newRegExp = new RegExp(findText, 'gm');
        return this.replace(newRegExp, repText);
    }

    //表情文字替换
    function replace_content(con){
        for(var i=0;i<Expression.length;i++){
            con = con.replaceAll(Expression[i],"<img src='" + Url + "F_"+(i+1)+".gif' />");
        }
        return con;
    }

    function biaoqingclick(){
        if($(".expression").css("display")=="block"){
            $(".expression").css("display","none");
        }else{
            $(".expression").css("display","block");
            biaoqingclicks=false;
        }
    }

    function pclick(obj){
        var titles=$(obj).children().attr("title");
        var ids=$(obj).attr("id");
        if($("#writtenwords_text").val()==""){
            $("#writtenwords_text").val(titles);
        }else{
            $("#writtenwords_text").val($("#writtenwords_text").val()+titles);
        }
        $(".expression").css("display","none");
    }

    $("#setanswer").on("click",function(){
        if(answerElement!=null){
            var msgtype=$("#msgtype").val();
            var value="",_value="";
            if(msgtype=="material"){
                if($("#material").val()!=""){
                    _value=$("#material").val();
                    value="<a href='<?php echo $server_host; ?>/open_elements/preview/"+_value+"' target='_blank'>"+$("#material").find("option:selected").text()+"</a>";
                }else{
                    alert('请选择素材');
                    return false;
                }
            }else{
                value=$("#writtenwords_text").val();
                value=replace_content(value);
                _value=value;
            }
            var hiddenType=answerElement.find(".configtype");
            var hiddenVal=answerElement.find(".configvalue");
            var textDiv=answerElement.find("div");
            hiddenType.val(msgtype);
            textDiv.html(value);
            hiddenVal.val(_value);
            $("#doc-modal-1 .am-close").click();
        }
        answerElement=null;
    });
</script>
<!-- 多图文回复设置end -->
<style>
    .ellipsis {
        overflow: hidden;
        text-overflow: ellipsis;
        text-transform: capitalize;
        white-space: nowrap;
        width: 300px;
    }
    #type_dy,#verify_status{display:none;}
    #app_id,#app_secret,#token,#update_token{display:none;}
    #open_config textarea{width:400px;height:80px;resize:none;}
</style>
<script type='text/javascript'>
    function form_checks(){
        var OpenModelTypeId = document.getElementById('OpenModelTypeId');
        if(OpenModelTypeId.value==''){
            alert('<?php echo '请输入公众平台帐号！'?>');
            return false;
        }
        if(document.getElementById("OpenModelVerifyStatus").checked){
            var AppId = document.getElementById('OpenModelAppId');
            var AppSecret = document.getElementById('OpenModelAppSecret');
            var SignatureToken = document.getElementById('OpenModelSignatureToken');
            if(AppId.value==''){
                alert('<?php echo '请输入公众平台接入AppId！'?>');
                return false;
            }
            if(AppSecret.value==''){
                alert('<?php echo '请输入公众平台接入AppSecret！'?>');
                return false;
            }
            if(SignatureToken.value==''){
                alert('<?php echo '请输入公众平台Signature token！'?>');
                return false;
            }
        }
        return true;
    }

    function check_wechat(){
        var OpenModelType = document.getElementById('OpenModelType');
        if(OpenModelType.value=='wechat'){
            document.getElementById("verify_status").style.display="table-row";
            document.getElementById("type_dy").style.display="table-row";
            if(navigator.userAgent.indexOf("MSIE") >0){
                document.getElementById("verify_status").style.display="block";
                document.getElementById("type_dy").style.display="block";
            }
        }else{
            document.getElementById("verify_status").style.display="none";
            document.getElementById("type_dy").style.display="none";
        }
        //判断认证
        if(document.getElementById("OpenModelVerifyStatus").checked){
            document.getElementById("app_id").style.display="table-row";
            document.getElementById("app_secret").style.display="table-row";
            document.getElementById("token").style.display="table-row";
            if(document.getElementById("update_token")!=null){
                document.getElementById("update_token").style.display="table-row";
            }
            if(navigator.userAgent.indexOf("MSIE") >0){
                document.getElementById("app_id").style.display="block";
                document.getElementById("app_secret").style.display="block";
                document.getElementById("token").style.display="block";
                if(document.getElementById("update_token")!=null){
                    document.getElementById("update_token").style.display="block";
                }
            }
        }else{
            document.getElementById("app_id").style.display="none";
            document.getElementById("app_secret").style.display="none";
            document.getElementById("token").style.display="none";
            if(document.getElementById("update_token")!=null){
                document.getElementById("update_token").style.display="none";
            }
        }
    }

    function check_status(){
        //判断认证
        if(document.getElementById("OpenModelVerifyStatus").checked){
            document.getElementById("app_id").style.display="table-row";
            document.getElementById("app_secret").style.display="table-row";
            document.getElementById("token").style.display="table-row";
            if(document.getElementById("update_token")!=null){
                document.getElementById("update_token").style.display="table-row";
            }
            if(navigator.userAgent.indexOf("MSIE") >0){
                document.getElementById("app_id").style.display="block";
                document.getElementById("app_secret").style.display="block";
                document.getElementById("token").style.display="block";
                if(document.getElementById("update_token")!=null){
                    document.getElementById("update_token").style.display="block";
                }
            }
        }else{
            document.getElementById("app_id").style.display="none";
            document.getElementById("app_secret").style.display="none";
            document.getElementById("token").style.display="none";
            if(document.getElementById("update_token")!=null){
                document.getElementById("update_token").style.display="none";
            }
        }
    }

    window.onload=check_wechat;
    function ajaxFileUpload(){
        $.ajaxFileUpload({
            url:'/admin/open_models/uploadimg/', //你处理上传文件的服务端
            secureuri:false,
            fileElementId:'OpenModelImg',
            dataType: 'json',
            success: function (result){
                if(result.code=='1'){
                    $("#OpenModelImg_show").attr("src",result.upload_img_url).css('display','block');
                    $("#OpenModelImg").parent().find("input[type=hidden]").val(result.upload_img_url);
                }else{
                    alert(result.msg);
                }
            },
            error: function (data, status, e)//服务器响应失败处理函数
            {
                alert('上传失败');
            }
        });
        return false;
    }
</script>