<div class="am-cf am-user">
	<h3><?php echo $ld['privacy_settings'] ?></h3>
</div>
<div class="am-user-privacy-settings">
<?php echo $form->create('/user_socials',array('action'=>'privacy_settings','class'=>'am-form am-form-horizontal','id'=>'privacy_settings_form','name'=>'privacy_settings','type'=>'POST'));?>
	<input type="hidden"  name="data[Users][id]"  value="<?php echo $user_list['User']['id'];?>"/>
	<div class="am-form-detail">
		<?php if(!empty($default_user_config_list)){ foreach($default_user_config_list as $ck=>$cv){ ?>
			
			<div class="am-form-group" >
	          <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $cv['name']; ?></label>
	          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-btn-group radio-btn"  data-am-button style="min-width:200px;">
	            <?php
						$user_config_values_arr=split("\r\n",$cv['user_config_values']);
						$user_config_values=array();
						$user_config_value=isset($user_config_list[$ck])?$user_config_list[$ck]:$cv['value'];
						if(!empty($user_config_values_arr[0])){
							foreach($user_config_values_arr as $selk=>$selv){
								if(empty($selv)){continue;}
								$selv_txt_arr=split(':',$selv);
								if(empty($selv_txt_arr[1])){continue;}
								$user_config_values[$selv_txt_arr[0]]=$selv_txt_arr[1];
							}
						}
						foreach($user_config_values as $kk=>$vv){
				?>
	    		<label class="am-btn am-btn-primary <?php echo $kk==$user_config_value?'am-active':'' ?> am-btn-xs"><input type="radio" name="data[UserConfig][<?php echo $ck; ?>]" value="<?php echo $kk; ?>" <?php echo $kk==$user_config_value?'checked':'' ?> /><?php echo $vv; ?></label>
	    		<?php } ?>
	    	  </div>
	        </div>
			
		<?php }} ?>
        <div class="am-form-group">
          <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"  >
            	<input class="am-btn am-btn-primary am-btn-sm am-fl" type="submit" value="<?php echo $ld['user_save'] ?>" />
          </div>
        </div>
	</div>
<?php echo $form->end();?>
</div>
