<style>
    .am-radio, .am-checkbox{display:inline;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;position:relative;top:5px;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<?php echo $form->create('mailtemplates',array('action'=>'view/'.(isset($this->data['MailTemplate'])?$this->data['MailTemplate']['id']:''),'onsubmit'=>'return mail_input_checks()'));?> <input name="data[MailTemplate][id]" type="hidden" value="<?php echo isset($this->data['MailTemplate']['id'])?$this->data['MailTemplate']['id']:'';?>">
<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
    <input name="data[MailTemplateI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
<?php }}?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
        <li><a href="#plain_text"><?php echo $ld['plain_text_message_content']?></a></li>
        <li><a href="#html_email"><?php echo $ld['html_email_content']?></a></li>
    </ul>
</div>
	<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion"  >
		    <div id="basic_info" class="am-panel am-panel-default">
			        <div class="am-panel-hd">
			            <h4 class="am-panel-title">
			                <?php echo $ld['basic_information']?>
			            </h4>
			        </div>
		        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
			            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
			                <table class="am-table">
			                    <tr>
			                        <th style="padding-top:16px" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['subject']?></th>
			                    </tr>
			                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			                        <tr>
			                            <td><input style="width:200px;float:left;" id="mail_title_<?php echo $v['Language']['locale'];?>" type="text" name="data[MailTemplateI18n][<?php echo $k;?>][title]" value="<?php echo @$this->data['MailTemplateI18n'][$k]['title'];?>" /><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></td>
			                        </tr>
			                    <?php }}?>
			                    <tr>
			                        <th style="padding-top:16px" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['email_help']?></th>
			                    </tr>
			                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			                        <tr>
			                            <td><input style="width:200px;float:left;" type="text" name="data[MailTemplateI18n][<?php echo $k;?>][description]" value="<?php echo @$this->data['MailTemplateI18n'][$k]['description'];?>" /></td>
			                        </tr>
			                    <?php }}?>
			                    <tr>
			                        <th style="padding-top:16px"><?php echo $ld['email_code']?></th>
			                        <td><input style="width:200px;float:left;" type="text" name="data[MailTemplate][code]" value="<?php echo @$this->data['MailTemplate']['code'];?>" /></td>
			                    </tr>
			                    <tr>
			                        <th style="padding-top:18px"><?php echo $ld['display']?></th>
			                        <td><label class="am-radio am-success" style="padding-top:2px;"><input type="radio" name="data[MailTemplate][status]" value="1" data-am-ucheck <?php if(empty($this->data['MailTemplate']['status'])||$this->data['MailTemplate']['status']){?>checked<?php }?> > <?php echo $ld['yes'];?></label>
			                            <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input name="data[MailTemplate][status]" type="radio" value="0" data-am-ucheck <?php if(!empty($this->data['MailTemplate']['status'])&&$this->data['MailTemplate']['status']=="0"){?>checked<?php }?> > <?php echo $ld['no']?></label></td>
			                    </tr>
			                    <tr><!---纯文本--->
			                        <th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['plain_text_message_content']?></th>
			                    </tr>	
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
						<tr >
							<td > 
								<textarea cols="40" id="elm<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][text_body]" rows="10"><?php echo isset($this->data['MailTemplateI18n'][$v['Language']['locale']]['text_body'])?$this->data['MailTemplateI18n'][$v['Language']['locale']]['text_body']:"";?></textarea>
							</td>
						</tr>
						<?php }}?><!---/纯文本--->
				      <tr> <!---编辑器--->
			                 <th rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['html_email_content']?></th>
			             </tr>	
			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <?php
                        if($configs["show_edit_type"]){?>
                            <tr>
                              <td><span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span>
                                    <textarea cols="40" id="elm1<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][html_body]" rows="10"><?php echo isset($this->data['MailTemplateI18n'][$v['Language']['locale']]['html_body'])?$this->data['MailTemplateI18n'][$v['Language']['locale']]['html_body']:"";?></textarea>                             <script>
                                        var editor;
                                        KindEditor.ready(function(K) {
                                            editor = K.create('#elm1<?php echo $v['Language']['locale'];?>', {width:'93%',
                                                langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
                                        });
                                    </script>
                                </td>
                            </tr>
                        <?php }else{?>
                            <tr>
                                <td>
                                    <textarea cols="40" id="elm1<?php echo $v['Language']['locale'];?>" name="data[MailTemplateI18n][<?php echo $k;?>][html_body]" rows="10"><?php echo isset($this->data['MailTemplateI18n'][$v['Language']['locale']])?$this->data['MailTemplateI18n'][$v['Language']['locale']]['html_body']:"";?></textarea>
                                    <?php echo $ckeditor->load("elm1".$v['Language']['locale']); ?></td>
                            </tr>
                        <?php }?>
                        <?php }}?>	
			                </table>
	 
                 
                 <!---/编辑器--->
                  <div class="btnouter">
				                    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" /> <input class="am-btn am-btn-success  am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
				                </div>
			            </div>
		        </div>
	</div>
 </div>
<?php echo $form->end();?>
<script type="text/javascript">
    function mail_input_checks(){
        var mail_title_obj = document.getElementById("mail_title_"+backend_locale);
        if(mail_title_obj.value==""){
            alert("<?php echo $ld['enter_email_subject']?>");
            return false;
        }
        return true;
    }
</script>