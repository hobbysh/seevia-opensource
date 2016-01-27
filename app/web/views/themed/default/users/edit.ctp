<script src="/plugins/ajaxfileupload.js" type="text/javascript"></script>
<?php echo $htmlSeevia->js(array("region")); ?>
<div class="am-cf am-user">
	<h3><?php echo $ld['account_profile'] ?></h3>
</div>
<div class="am-u-user-edit">
	<?php echo $form->create('/users',array('action'=>'edit','id'=>'user_edit_form','class'=>' am-form am-form-horizontal','name'=>'user_edit','type'=>'POST','onsubmit'=>"return(check_form(this));"));?>
	<input type="hidden"  name="data[UserAddress][id]" value="<?php echo isset($user_list['User']['UserAddress']['UserAddress'])?$user_list['User']['UserAddress']['UserAddress']['id']:"";?>"/>
	<input type="hidden"  name="data[Users][id]"  value="<?php echo  $user_list['User']['id'];?>"/>
	<div class="am-form-detail editit">
		<div class="am-form-group" style="margin-top:1.8rem;margin-bottom:2.5rem">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label position"><?php echo $ld['user_id'] ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8"><div style="position: relative; margin-top: 10px;"><?php echo $user_list["User"]["user_sn"];?></div></div>
        </div>
        <?php if(isset($configs['enable_auditing'])&&$configs['enable_auditing']=='1'){ ?>
        <div class="am-form-group" style="margin-top:1.8rem;margin-bottom:2.5rem;">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['status'] ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8"><div style="position: relative;margin-top:10px;"><span class="verify_status<?php echo $user_list['User']['verify_status']; ?>"><?php echo isset($system_resources['verify_status'][$user_list['User']['verify_status']])?$system_resources['verify_status'][$user_list['User']['verify_status']]:$user_list['User']['verify_status'];?></span><?php echo !empty($user_list['User']['unvalidate_note'])?" - ".$user_list['User']['unvalidate_note']:''; ?></div></div>
        </div>
        <?php } ?>
    	<div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label real_name" ><?php echo $ld['real_name'] ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8"><input type="text" name="data[Users][first_name]" value="<?php echo $user_list['User']['first_name'];?>"/></div>
        </div>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" ><?php echo $ld['nickname'] ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
			<input type="text"   name="data[Users][name]" id="account"  chkRules="nnull:<?php echo $ld['nickname_empty']?>;min4:<?php echo $ld['nickname_for_at_least_four']?>;max20:<?php echo $ld['nickname_for_a_maximum_of_20']?>;ajax:check_input('account','account','<?php echo $user_list['User']['name'];?>')"  value="<?php echo $user_list['User']['name'];?>"/><em><font color="red">*</font><font></font></em>
          </div>
        </div>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" ><?php echo $ld['user_gender'] ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
    		<select name="data[Users][sex]">
    			<option value='0'><?php echo $ld['privacy'] ?></option>
    			<option value='1' <?php echo $user_list['User']['sex']==1?'selected':'';?>><?php echo $ld['user_male'] ?></option>
    			<option value='2' <?php echo $user_list['User']['sex']==2?'selected':'';?>><?php echo $ld['user_female'] ?></option>
    		</select>
          </div>
        </div>
    	<div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" >Email</label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
			<input type="text"  name="data[Users][email]" id="user_emails" chkRules="nnull:<?php echo $ld['e-mail_empty']?>;email:<?php echo $ld['e-mail_incorrectly']?>;ajax:check_input('email','user_emails','<?php echo $user_list['User']['email'];?>')" value="<?php echo $user_list['User']['email'];?>" /><em><font color="red">*</font><font></font></em>
          </div>
        </div>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['mobile'] ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
			<input type="text" name="data[Users][mobile]" id="mobile" chkRules="nnull:<?php echo $ld['phone_can_not_be_empty']?>;mobile:<?php echo $ld['phone_incorrectly_completed']?>;ajax:check_input('mobile','mobile','<?php echo $user_list['User']['mobile'];?>')" value="<?php echo $user_list['User']['mobile'];?>" /><em><font color="red">*</font><font></font></em>
          </div>
        </div>
        <div class="am-form-group" style="margin-bottom:0px;">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['region'] ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8"><input type="hidden" id="local" value="<?php echo LOCALE; ?>" /><span id="regionsupdate">
				<select gtbfieldid="1" name="region" id="region" onchange="reload_two_regions()">
					<option><?php echo $ld['state_province'] ?></option>
					<option>...</option>
				</select>
				<select gtbfieldid="2" onchange="reload_two_regions()">
					<option><?php echo $ld['city'] ?></option>
					<option>...</option>
				</select>
				<select gtbfieldid="3" onchange="reload_two_regions()">
					<option><?php echo $ld['counties'] ?></option>
					<option>...</option>
				</select>
				</span><em><font color="red"></font><font></font></em>
          </div>
        </div>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
				<label style="display:inline-block;">
				<input type="radio" style="margin-top:7px;" value="0" name="data[UserAddress][address_type]" <?php if(isset($user_list['User']['UserAddress']['UserAddress'])){ if($user_list['User']['UserAddress']['UserAddress']['address_type']==0){echo "checked='checked'";}else{echo "";}}else{echo "checked='checked'";}?> />
				<span><?php echo $ld['home_address']  ?></span>
				</label>
				<label style="display:inline-block;">
				<input type="radio" style="margin-top:7px;" value="1" name="data[UserAddress][address_type]" <?php if(isset($user_list['User']['UserAddress']['UserAddress']) && $user_list['User']['UserAddress']['UserAddress']['address_type']==1){echo "checked='checked'";}else{echo "";}?>/>
				<span><?php echo $ld['company_address'] ?></span>
				</label>
				<label style="display:inline-block;">
				<input type="radio" style="margin-top:7px;" value="2" name="data[UserAddress][address_type]" <?php if(isset($user_list['User']['UserAddress']['UserAddress']) && $user_list['User']['UserAddress']['UserAddress']['address_type']==2){echo "checked='checked'";}else{echo "";}?> />
				<span><?php echo $ld['school_address'] ?></span>
				</label>
				<label style="display:inline-block;">
				<input type="radio" style="margin-top:7px;" value="3" name="data[UserAddress][address_type]" <?php if(isset($user_list['User']['UserAddress']['UserAddress']) && $user_list['User']['UserAddress']['UserAddress']['address_type']==3){echo "checked='checked'";}else{echo "";}?>/>
				<span><?php echo $ld['other']?></span></label>
          </div>
        </div>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['address'] ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
<input class="detail" type="text" name="data[UserAddress][address]" value="<?php echo isset($user_list['User']['UserAddress']['UserAddress'])?$user_list['User']['UserAddress']['UserAddress']['address']:"";?>"/>
				<em><font color="red"></font><font></font></em>
          </div>
        </div>
    	<div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['address_to'] ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
			<input class="detail" type="text" name="data[UserAddress][sign_building]" 
					value="<?php echo isset($user_list['User']['UserAddress']['UserAddress'])?$user_list['User']['UserAddress']['UserAddress']['sign_building']:"";?>"/><em><font color="red"></font><font></font></em>
          </div>
        </div>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['zip'] ?></label>
          <div class="am-u-lg-4 am-u-md-9 am-u-sm-8">
			<input type="text"  name="data[UserAddress][zipcode]" maxlength="6" chkRules="zip_code:<?php echo $ld['zipcode_incorrectly']?>"  value="<?php echo isset($user_list['User']['UserAddress']['UserAddress'])?$user_list['User']['UserAddress']['UserAddress']['zipcode']:"";?>"/>
				<em><font color="red"></font><font></font></em>
          </div>
        </div>
        
        <?php if(isset($review_configs)&&!empty($review_configs)){ ?>
        <div class="am-panel-collapse am-collapse am-in user_config_data">
            <div class="am-panel-bd">
                <div class="am-panel am-panel-default">
                    <div class="am-panel-hd">
				      <h4 class="am-panel-title">用户认证</h4>
				    </div>
                </div>
                <div class="am-panel-collapse">
			    	<div><!---class="am-panel-bd" ---->
                        <?php foreach($review_configs as $gk=>$gv){ if(empty($gv)){continue;} ?>
                            
                            <div class="am-panel am-panel-default">
            	    			<div class="am-panel-hd">
            				      <h4 class="am-panel-title"><?php echo isset($user_config_group_list[$gk])?$user_config_group_list[$gk]:'未分组'; ?></h4>
            				    </div>
				    			<div class="am-panel-collapse">
                                        <div class="am-panel-bd">
                                           <?php foreach($gv as $v){ ?>
                                           
        <div class="am-form-group">
          <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-text-left am-form-label" ><?php echo $v['UserConfigI18n']['name']; ?></label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8"><?php
            $user_config_id=isset($user_review_data[$v['UserConfig']['type']][$v['UserConfig']['code']])?$user_review_data[$v['UserConfig']['type']][$v['UserConfig']['code']]['id']:0;
            $user_config_value=isset($user_review_data[$v['UserConfig']['type']][$v['UserConfig']['code']])?$user_review_data[$v['UserConfig']['type']][$v['UserConfig']['code']]['value']:$v['UserConfig']['value'];
            $user_config_values_arr=array();
            if(!empty($v['UserConfigI18n']['user_config_values'])){
                $user_config_values_arr=split("\r\n",$v['UserConfigI18n']['user_config_values']);
            }
            if(!empty($user_config_values_arr[0])){
        		foreach($user_config_values_arr as $selk=>$selv){
        			if(empty($selv)){continue;}
        			$selv_txt_arr=split(':',$selv);
        			if(empty($selv_txt_arr[1])){continue;}
        			$user_config_values[$selv_txt_arr[0]]=$selv_txt_arr[1];
        		}
        	}?>
            <input type="hidden" name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>][id]" value="<?php echo $user_config_id; ?>" />
            <?php 
                if($v['UserConfig']['value_type']=='textarea'){ ?>
					<textarea name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>]" <?php echo $v['UserConfig']['is_required']=='1'?'required':''; ?>><?php echo $user_config_value; ?></textarea>
			<?php }else if($v['UserConfig']['value_type']=='radio'){ foreach($user_config_values as $k=>$v){ ?>
					<label class="am-radio-inline"><input type="radio" name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>][value]" value="<?php echo $kk; ?>" <?php echo $user_config_value==$kk?" checked='checked'":""; ?> <?php echo $v['UserConfig']['is_required']=='1'?'required':''; ?>><?php echo $vv; ?></label>
					<?php } ?>
			<?php }else if($v['UserConfig']['value_type']=='select'){ ?>
					<select name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>][value]" <?php echo $v['UserConfig']['is_required']=='1'?'required':''; ?>>
						<?php foreach($user_config_values as $kk=>$vv){ ?>
						<option value="<?php echo $k; ?>" <?php echo $user_config_value==$kk?" selected='selected'":""; ?>><?php echo $vv; ?></option>
						<?php } ?>
					</select>
			<?php }else if($v['UserConfig']['value_type']=='file'){ ?>
                    <span><input type="hidden" name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>][value]" value="<?php echo $user_config_value; ?>" <?php echo $v['UserConfig']['is_required']=='1'?'required':''; ?>><input type="file" class="am-fl" style="margin-top:5px;width:90%" id="<?php echo $v['UserConfig']['type'].'_'.$v['UserConfig']['code']; ?>" name="<?php echo $v['UserConfig']['type'].'_'.$v['UserConfig']['code']; ?>" onchange="ajax_upload_files(this,'<?php echo $v['UserConfig']['type'].'_'.$v['UserConfig']['code']; ?>')"><?php if($v['UserConfig']['is_required']=='1'){ ?><em class="am-fl" style="top:5px;"><font color="red">*</font><font></font></em><?php } ?>
                        <?php if(!empty($user_config_value)&&file_exists(WWW_ROOT.$user_config_value)){
                                $user_file_type=mime_content_type(WWW_ROOT.$user_config_value);
                                if(strpos($user_file_type,'image')!==false){ ?>
                                <p class='user_config_file'><img src="<?php echo $user_config_value; ?>"><a href='javascript:void(0);' onclick="clean_user_file(this,'<?php echo $user_config_value; ?>')"><?php echo $ld['delete'] ?></a></p>
                        <?php   }else{ ?>
                            <p class='user_config_file'><a target='_blank' href="<?php echo $user_config_value; ?>">下载</a><a href="javascript:void(0);" onclick="clean_user_file(this,'<?php echo $user_config_value; ?>')"><?php echo $ld['delete']; ?></a></p>
                        <?php }} ?>
                        </span>
            <?php }else if($v['UserConfig']['value_type']=='numbertext'){ ?>
                    <input type="text" class="js-pattern-number" name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>][value]" value="<?php echo $user_config_value; ?>" <?php echo $v['UserConfig']['is_required']=='1'?'required':''; ?> />
            <?php }else{ ?>
					<input type="text"   name="data[UserConfig][<?php echo $v['UserConfig']['type']; ?>][<?php echo $v['UserConfig']['code']; ?>][value]" value="<?php echo $user_config_value; ?>" <?php echo $v['UserConfig']['is_required']=='1'?'required':''; ?> />
			<?php } if($v['UserConfig']['is_required']=='1'&&$v['UserConfig']['value_type']!='file'){ ?><em><font color="red">*</font><font></font></em><?php } ?>
            </div>
        </div>
                                           <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
          <div class="am-u-lg-8 am-u-md-9 am-u-sm-8 am-text-left">
                <?php if(isset($configs['enable_auditing'])&&$configs['enable_auditing']=='1'&&$user_list['User']['verify_status']!='1'&&$user_list['User']['verify_status']!='3'){ ?><input class="am-btn am-btn-primary am-btn-sm" type="submit" name="submit_review" value="<?php echo $ld['submit_review'] ?>" /><?php } ?>
            	<input class="am-btn am-btn-primary am-btn-sm" name="user_save" onclick="user_edit_save()" type="submit" value="<?php echo $ld['user_save'] ?>" />
          </div>
        </div>
	</div>
	<?php echo $form->end();?>
</div>
<script type="text/javascript">
var submit_review_flag="<?php echo isset($configs['enable_auditing'])?$configs['enable_auditing']:'0'; ?>";
$(document).ready(function(){
    auto_check_form("user_edit_form",false);
});

$('#user_edit_form').validator({
  validate: function(validity) {
      if($(validity.field).is("input[type='file']")){
        var required_attr=$(validity.field).prop("required");
        if(typeof(required_attr)!='undefined'){
            var filelink=$(validity.field).parent().find("input[type='hidden']").val();
            if(filelink.trim()==""){
                validity.valid = false;
            }
        }
      }
  },
  submit: function(){
    var formValidity = this.isFormValid();
    console.log("formValidity:"+formValidity);
    if(!formValidity&&submit_review_flag=='1'){
        return false;
    }
  }
});

function user_edit_save(){
    var check_form_flag=check_form(document.getElementById("user_edit_form"));
    if(check_form_flag==true){
        document.user_edit.submit();
    }
}

<?php $regions_add=isset($user_list['User']['UserAddress']['UserAddress'])?$user_list['User']['UserAddress']['UserAddress']['regions']:"";?>
var regions_add = "<?php echo $regions_add;?>";
show_uncheck_regions(regions_add);

/*
    上传配置数据文件
*/
function ajax_upload_files(inputFile,fileCode){
    var file_link_html="<p class='user_config_file'><a target='_blank' href='FILELINK'>下载</a><a href='javascript:void(0);' onclick=\"clean_user_file(this,'DELFILELINK')\"><?php echo $ld['delete'] ?></a></p>";
    var image_link_html="<p class='user_config_file'><img src='FILELINK'><a href='javascript:void(0);' onclick=\"clean_user_file(this,'DELFILELINK')\"><?php echo $ld['delete'] ?></a></p>";
    var Filehidden=$(inputFile).parent().find("input[type='hidden']");
    $.ajaxFileUpload({
		  url:"<?php echo $html->url('/users/ajax_upload_files/'); ?>",
		  secureuri:false,
		  fileElementId:fileCode,
		  dataType: 'json',
          data:{'fileCode':fileCode},
		  success: function (result){
              if(result.code=='1'){
                 $(Filehidden).val(result.file_name);
                 $(Filehidden).parent().find("p.user_config_file").remove();
                 var FileType=result.file_type;
                 if(FileType.indexOf("image")>=0){
                    image_link_html=image_link_html.replace("FILELINK", result.file_name);
                    image_link_html=image_link_html.replace("DELFILELINK", result.file_name);
                    $(Filehidden).parent().append(image_link_html);
                 }else{
                    file_link_html=file_link_html.replace("FILELINK", result.file_name);
                    file_link_html=file_link_html.replace("DELFILELINK", result.file_name);
                    $(Filehidden).parent().append(file_link_html);
                 }
              }else{
                alert(result.msg);
              }
		  },
		  error: function (data, status, e)//服务器响应失败处理函数
		  {
		  	  alert('上传失败');
          }
	 });
}

function clean_user_file(FileLink,FileUrl){
    if(confirm(confirm_delete)){
        $.ajax({url: "/users/ajax_remove_files/",
			type:"POST",
			data:{'FileUrl':FileUrl},
			dataType:"json",
			success: function(data){
				if(data.code=='1'){
                    $(FileLink).parent().parent().find("input[type='hidden']").val("");
                    $(FileLink).parent().remove();
                }else{
                    alert(data.msg);
                }
	  		}
	  	});
    }
}
</script>