<style type="text/css">
.am-form-label{font-weight:bold;}
.btnouter{margin:50px;}
.am-form-horizontal .am-radio {
    display: inline;
    margin-top: 0.5rem;
    padding-top: 0;
    position: relative;
    top: 5px;
}
</style>
<div>
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
		   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
		<?php echo   $form->create('UserConfigs',array('action'=>'view/'.(isset($this->data['UserConfig'])?$this->data['UserConfig']['id']:''),'name'=>'UserConfigForm','onsubmit'=>'return user_config_checks();'));?>
			<div id="basic_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<input id="id" name="data[UserConfig][id]" type="hidden" value="<?php echo isset($this->data['UserConfig']['id'])?$this->data['UserConfig']['id']:'';?>">
						<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
							<input name="data[UserConfigI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
						<?php }}?>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:18px;"><?php echo $ld['type'];?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<select name="data[UserConfig][type]" onchange="user_config_type_change(this.value)" data-am-selected>
										<option value="0"><?php echo $ld['please_select'];?></option>
										<?php if(isset($Resource_info['user_config_type'])&&!empty($Resource_info['user_config_type'])>0){foreach($Resource_info['user_config_type'] as $k=>$v){ ?>
										<option value="<?php echo $k; ?>" <?php if(isset($this->data['UserConfig']['type']) && $this->data['UserConfig']['type'] == $k) echo 'selected'?>><?php echo $v; ?></option>
										<?php }} ?>
									</select>
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="padding-top:21px;"><em style="color:red;">*</em></label>
			    			</div>
			    		</div>
                        <div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:18px;"><?php echo $ld['group'];?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<select name="data[UserConfig][group_code]" id='user_config_group_code'>
										<option value=""><?php echo $ld['please_select'];?></option>
									</select>
			    				</div>
			    			</div>
			    		</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:22px;"><?php echo $ld["userconfig_code"]?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="code" onblur="operator_change()" name="data[UserConfig][code]" value="<?php echo isset($this->data['UserConfig']['code'])?$this->data['UserConfig']['code']:'';?>" />
			    				</div>
			    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="padding-top:20px;"><em style="color:red;">*</em></label>
			    			</div>
			    		</div>
			    					
						<div class="am-form-group" >
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:22px;"><?php echo $ld['userconfig_name']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
			    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
			    					<input id="userconfig_name_<?php echo $v['Language']['locale'];?>" name="data[UserConfigI18n][<?php echo $k;?>][name]" type="text" value="<?php echo isset($this->data['UserConfigI18n'][$v['Language']['locale']])?$this->data['UserConfigI18n'][$v['Language']['locale']]['name']:'';?>">
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-form-label am-text-left" style="font-weight:normal;padding-top:22px;">
			    						<?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em>
			    					</label>
				    			<?php }?>
				    		<?php }}?>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:18px;"><?php echo $ld['value_type'];?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<select  name="data[UserConfig][value_type]" data-am-selected>
										<option value="pleaseselect"><?php echo $ld['please_select']?></option>
										<option value="text" <?php if(isset($this->data['UserConfig']['value_type']) && $this->data['UserConfig']['value_type'] =="text"){echo "selected";}?>>text</option>
                                        <option value="numbertext" <?php if(isset($this->data['UserConfig']['value_type']) && $this->data['UserConfig']['value_type'] =="numbertext"){echo "selected";}?>>number text</option>
										<option value="radio"<?php if(isset($this->data['UserConfig']['value_type']) && $this->data['UserConfig']['value_type'] 
										== "radio"){echo "selected";}?>>radio</option>
										<option value="select"<?php if(isset($this->data['UserConfig']['value_type']) && $this->data['UserConfig']['value_type']  == "select"){echo "selected";}?>>select</option>
										<option value="checkbox"<?php if(isset($this->data['UserConfig']['value_type']) &&  $this->data['UserConfig']['value_type']  == "checkbox"){echo "selected";}?>>checkbox</option>
										<option value="textarea"<?php if(isset($this->data['UserConfig']['value_type']) && $this->data['UserConfig']['value_type']  == "textarea"){echo "selected";}?>>textarea</option>
                                        <option value="file"<?php if(isset($this->data['UserConfig']['value_type']) && $this->data['UserConfig']['value_type']  == "file"){echo "selected";}?>>file</option>
									</select>&nbsp;&nbsp;
			    				</div>
				    			<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left">
			    					</label>
				    			<?php }?>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['description'];?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
			    				<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    					<textarea name="data[UserConfigI18n][<?php echo $k;?>][description]"><?php echo isset($this->data['UserConfigI18n'][$v['Language']['locale']])?$this->data['UserConfigI18n'][$v['Language']['locale']]['description']:"";?></textarea>&nbsp;
				    				</div>
					    			<?php if(sizeof($backend_locales)>1){?>
				    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:30px;">
				    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;
				    					</label>
					    			<?php }?>
				    			<?php }}?>
			    			</div>
			    		</div>
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['user_config_value'];?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
			    				<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
				    					<textarea name="data[UserConfigI18n][<?php echo $k;?>][user_config_values]"><?php echo isset($this->data['UserConfigI18n'][$v['Language']['locale']])?$this->data['UserConfigI18n'][$v['Language']['locale']]['user_config_values']:"";?></textarea>
				    				</div>
					    			<?php if(sizeof($backend_locales)>1){?>
				    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:30px;">
				    						<?php echo $ld[$v['Language']['locale']]?>&nbsp;
				    					</label>
					    			<?php }?>
				    			<?php }}?>
			    			</div>
			    		</div>
                        <div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:22px;"><?php echo $ld['required']?></label>
    		    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    			    				<label class="am-radio am-success" style="padding-top:2px;">
    									<input type="radio" name="data[UserConfig][is_required]" data-am-ucheck  value="1" <?php echo (isset($this->data['UserConfig']['is_required'])&&$this->data['UserConfig']['is_required']=='1')?'checked':''; ?>/><?php echo $ld['yes']?>
    								</label>&nbsp;&nbsp;
    								<label class="am-radio am-success" style="padding-top:2px;">
    									<input type="radio" name="data[UserConfig][is_required]" data-am-ucheck  value="0" <?php echo (isset($this->data['UserConfig']['is_required'])&&$this->data['UserConfig']['is_required']=='0')||(!isset($this->data['UserConfig']['is_required']))?'checked':''; ?>/><?php echo $ld['no']?>
    								</label>
    		    				</div>
    		    			</div>
    		    		</div>
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:20px;"><?php echo $ld['sort']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-7">
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    					<input type="text" name="data[UserConfig][orderby]" value="<?php echo isset($this->data['UserConfig']['orderby'])?$this->data['UserConfig']['orderby']:50 ?>" onkeyup="check_input_num(this)"/>
				    					<?php echo $ld['sort_info']?>
				    				</div>
			    			</div>
			    		</div>
                    </div>
					<div class="btnouter">
						<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
					</div>
				</div>
			</div>
		<?php echo $form->end();?>
	</div>
</div>
<script type="text/javascript">
var UserConfig_group_code="<?php echo isset($this->data['UserConfig']['group_code'])?$this->data['UserConfig']['group_code']:''; ?>";
function user_config_checks(){
	var userconfig_name_obj = document.getElementById("userconfig_name_"+backend_locale);
	var code = document.getElementById("code").value;
	if(userconfig_name_obj.value==""){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['userconfig_name']); ?>");
		return false;
	}
	if(code==""){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['userconfig_code']); ?>");
		return false;
	}
	return submit_flag;
}

function user_config_type_change(config_type){
    $("#user_config_group_code option").remove();
    $("<option></option>").val('').text(j_please_select).appendTo("#user_config_group_code");
    if(config_type!='0'){
        $.ajax({
            url:admin_webroot+"user_configs/user_configs_group",
            type:"POST",
            data: {'user_config_type':config_type},
            dataType:"json",
            success:function(data){
                if(data.flag == 1){
                    $.each(data.group_data,function(value,text){
                        if(UserConfig_group_code==value){
                            $("<option selected='selected'></option>").val(value).text(text).selected('selected').appendTo("#user_config_group_code");
                        }else{
                            $("<option></option>").val(value).text(text).appendTo("#user_config_group_code");
                        }
                    })
                    
                }
                $("#user_config_group_code").selected();
            }
        });
    }else{
        $("#user_config_group_code").selected();
    }
}
</script>