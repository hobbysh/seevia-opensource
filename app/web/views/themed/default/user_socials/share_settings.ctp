<div class="am-cf am-user">
	<h3><?php echo $ld['share_settings'] ?></h3>
</div>
<div class="am-cf am-user-share-settings">
<?php
	$sina_status=0;$qq_status=0; 
	if(isset($share_list) && !empty($share_list)){
		foreach($share_list as $k=>$v){
			if($v['SynchroUser']['type']=="QQWeibo"&&$v['SynchroUser']['status']==1){
				$qq_status=1;
			}else if($v['SynchroUser']['type']=="SinaWeibo"&&$v['SynchroUser']['status']==1){
				$sina_status=1;
			}
		}
	}
	
	echo $form->create('/user_socials',array('action'=>'share_settings','class'=>'am-form am-form-horizontal','id'=>'share_settings_form','name'=>'share_settings','type'=>'POST'));?>
<input type="hidden"  name="data[Users][id]"  value="<?php echo  $user_list['User']['id'];?>"/>
	<div class="am-form-detail">
		
		<div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['authorization'] ?></label>
          <div class="am-u-lg-10 am-u-md-6 am-u-sm-6 am-btn-group">
            
    		<?php if(in_array("SinaWeibo",$user_app_array)){?>
			<span><?php if($sina_status==0){?><a href="javascript:void(0)" onclick="checktoken('sinaweibo')"><img id="sina_icon" src="/theme/default/img/sina.png" width="40" height="40" style="<?php if($sina_status==0){echo 'filter: Alpha(opacity=10);-moz-opacity:.1;opacity:0.3;';} ?>"/></a>
				<?php }else{?><img id="sina_icon" src="/theme/default/img/sina.png" width="40" height="40" /><?php }?>
			</span>
			<?php }?>
				
			<?php if(in_array("QQWeibo",$user_app_array)){?>
			<span><?php if($qq_status==0){?><a href="javascript:void(0)" onclick="checktoken('qqweibo')"><img id="qq_icon" src="/theme/default/img/qie.png" width="40" height="40" style="<?php if($qq_status==0){echo 'filter: Alpha(opacity=10);-moz-opacity:.1;opacity:0.3;';}?>"/></a>
				<?php }else{?><img id="qq_icon" src="/theme/default/img/qie.png" width="40" height="40" /><?php }?>
			</span>
			<?php }?>
    	  </div>
        </div>
        	  
        <?php
        	if(isset($share_list) && !empty($share_list)){
        		foreach($share_list as $k=>$v){
        ?>
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo isset($ld[$v['SynchroUser']['type']])?$ld[$v['SynchroUser']['type']]:$v['SynchroUser']['type']; ?></label>
          <div class="am-u-lg-10 am-u-md-6 am-u-sm-6 am-btn-group radio-btn" data-am-button>
    		<label class="am-btn am-btn-primary am-btn-xs <?php if(!empty($v['SynchroUser']) && $v['SynchroUser']['status']=='1')echo 'am-active';?>"><input type="radio" name="data[SynchroUser][<?php echo $v['SynchroUser']['type']?>]" value="1" <?php if(!empty($v['SynchroUser']) && $v['SynchroUser']['status']=='1')echo "checked";?>/><?php echo $ld['share']; ?></label>
    		<label class="am-btn am-btn-primary am-btn-xs <?php if(!empty($v['SynchroUser']) && $v['SynchroUser']['status']=='0')echo 'am-active';?>"><input type="radio" name="data[SynchroUser][<?php echo $v['SynchroUser']['type']?>]" value="0" <?php if(!empty($v['SynchroUser']) && $v['SynchroUser']['status']=='0')echo "checked";?>/><?php echo $ld['no_share']; ?></label>
    	  </div>
        </div>
        	<?php } ?>
        	
        
        <div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
          <div class="am-u-lg-10 am-u-md-6 am-u-sm-6 am-btn-group">
    		<input class="am-btn am-btn-primary am-btn-sm am-fl" type="submit" value="<?php echo $ld['user_save'] ?>" />
    	  </div>
        </div>
        <?php } ?>
	</div>
<?php echo $form->end();?>
</div>
