<script src="/plugins/AmazeUI/js/md5.js" type="text/javascript"></script>
<div class="am-cf am-user">
	<h3><?php echo $ld['change_password'] ?></h3>
</div>
<div class="am-u-ser-edit-pwd">
	<?php echo $form->create('Users',array('action'=>'edit_pwd','name'=>"user_edit_pwd","id"=>"edit_pwd_form",'class'=>"am-form am-form-horizontal","type"=>"post",'enctype'=>'multipart/form-data','onsubmit'=>'return(check_form(this));'));?>
	<input type="hidden"  name="data[User][id]"  value="<?php echo  $user_list['User']['id'];?>"/>
	<input type="hidden" id="old_pwd" name="data[User][password2]"  value="<?php echo $user_list['User']['password'];?>"/>
	<?php if(trim($user_list['User']['password'])!=""){ ?>
	  <div class="am-form-group am-g">
	    <label  class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><font color="red">*</font><?php echo $ld['current_password']?></label>
	    <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
	      <input type="password"  name="data[User][password1]" id="password1"  chkRules="nnull:<?php echo $ld['original_password_not_empty']?>;min6:<?php echo $ld['confirm_password_can_not_be_less_than_6_digits']?>;max16:<?php echo $ld['password_up_to_16']?>;edit_pwd:<?php echo $ld['password_error'] ?>" />
			<em><font color="red"></font><font></font></em>
	    </div>
	  </div>
	<?php } ?>
	  <div class="am-form-group am-g">
	    <label  class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><font color="red">*</font><?php echo $ld['new_password']?></label>
	    <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
	      <input type="password"  name="data[User][password]" id="password"  chkRules="nnull:<?php echo $ld['new_password_not_empty']?>;min6:<?php echo $ld['confirm_password_can_not_be_less_than_6_digits']?>;max16:<?php echo $ld['password_up_to_16']?>" />
			<em><font color="red"></font><font></font></em>
	    </div>
	  </div>
	  <div class="am-form-group am-g">
	    <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><font color="red">*</font><?php echo $ld['confirm_password']?></label>
	    <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
	      <input type="password"  name="data[User][confirm_password]" id="confirm_password"  chkRules="nnull:<?php echo $ld['confirm_password_can_not_be_empty']?>;min6:<?php echo $ld['confirm_password_can_not_be_less_than_6_digits']?>;cpwd:<?php echo $ld['the_two_passwords_do_not_match']?>:password" />
			<em><font color="red"></font><font></font></em>
	    </div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"></label>
	    <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
	      <button type="submit" class="am-btn am-btn-primary"><?php echo $ld['user_save'] ?></button>
	    </div>
	  </div>
	<?php echo $form->end();?>
</div>

<script type="text/javascript">
  $(document).ready(function(){
	auto_check_form("edit_pwd_form",false);
	var windowHeight = $(window).height();
	$("#edit_pwd_form").parent().css("min-height",(windowHeight*0.7)+"px");
});
</script>
