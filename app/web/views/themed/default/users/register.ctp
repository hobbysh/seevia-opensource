<div class="am-container am-register">
	<div class="am-form-group am-register-title">
      <label class="am-u-sm-3 am-form-label am-text-primary"><?php  echo $ld['new_user_registration'] ?></label>
      <div class="am-u-sm-12"><?php echo $ld['mark_must']?></div>
      <div style="clear:both;"></div>
    </div>
<?php if(isset($configs['enable_registration_closed']) && $configs['enable_registration_closed']==1){echo "<div class='box register_pause'>$ld[register_pause]</div>";}else{?>
<?php echo $form->create('/users',array('action'=>'register','id'=>'register_form','class'=>'am-form am-form-horizontal','name'=>'user_register','type'=>'POST','onsubmit'=>'return(check_form(this));'));?>
	<div class="am-form-detail">
		<div class="am-form-group">
          <label class="am-u-sm-3 am-form-label"><?php echo $ld['email'] ?></label>
          <div class="am-u-sm-9"><input type="text"  name="data[Users][email]" id="user_emails" chkRules="nnull:<?php echo $ld['e-mail_empty']?>;email:<?php $ld['e-mail_incorrectly']?>;ajax:check_input('sn_email','user_emails')" value="<?php echo isset($this->data['Users'])?$this->data['Users']['email']:'';?>" /><em><font color="red">*</font><font></font></em></div>
        </div>
		<div class="am-form-group">
          <label class="am-u-sm-3 am-form-label"><?php echo $ld['please_set_your_password'] ?></label>
          <div class="am-u-sm-9"><input type="password" name="data[Users][password]" id="user_password" chkRules="nnull:<?php echo $ld['login_password_empty']?>;min6:<?php echo $ld['confirm_password_can_not_be_less_than_6_digits']?>;max16:<?php echo $ld['password_up_to_16']?>" /><em><font color="red">*</font><font></font></em></div>
        </div>
		<div class="am-form-group">
          <label class="am-u-sm-3 am-form-label"><?php echo $ld['confirm_password'] ?></label>
          <div class="am-u-sm-9"><input type="password"  name="data[Users][confirm_password]" id="confirm_password" chkRules="nnull:<?php echo $ld['confirm_password_can_not_be_empty']?>;min4:<?php echo $ld['confirm_password_can_not_be_less_than_6_digits']?>;cpwd:<?php echo $ld['the_two_passwords_do_not_match']?>:user_password" /><em><font color="red">*</font><font></font></em></div>
        </div>
	<?php if(isset($configs['register_captcha'])&&$configs['register_captcha']=='1'){ ?>
		<div class="am-form-group" style="margin-bottom:0px;">
          <label class="am-u-sm-3 am-form-label"><?php echo $ld['please_enter_the_code'] ?></label>
	      <div class="am-u-sm-2 keywordauthen am-form-icon am-form-feedback">
	        <input type="hidden" id="ck_authnum" value="" />
	        <input type="text" class="am-form-field" name="data[Users][authnum]" id="authnums" chkRules="authnum:验证码错误" /><span style="right:16px;"></span>
		  </div>
		  <div class="am-u-sm-5 authentication">
			<img id='authnum_img' align='absmiddle' src="/securimages/index/?1234" alt="<?php echo $ld['not_clear']?>" onclick="change_captcha('authnum_img');" /><a href="javascript:change_captcha('authnum_img');"><?php echo $ld['not_clear']?></a><em><font color="red">*</font></em>
		  </div>
	    </div>
        <div class="am-form-group am-margin-top02" style="display:none;">
        	<label class="am-u-sm-3 am-form-label" style="padding-top:0px;">&nbsp;</label>
        	<div class="am-u-sm-9 authnum_msg">&nbsp;</div>
        </div>
	<?php } ?>
		<div class="am-form-group am-margin-top3">
          <label class="am-u-sm-3 am-form-label">&nbsp;</label>
          <div class="am-u-sm-9"><input class="am-btn am-btn-primary am-btn-sm am-fl" name="login" type="submit" value="<?php echo $ld['submit_register'] ?>" /></div>
        </div>
		<div class="am-form-group am-margin-top3">
          <div class="am-u-sm-9"><?php echo $ld['already_a_member']?> <a id="log" onclick="ajax_login_show()"  href="javascript:void(0);<?php //echo $html->url('/users/login');?>"><?php echo $ld['login']?></a></div>
        </div>
	</div>
<?php echo $form->end();}?>
	<hr />
</div>
<script type="text/javascript">
 $(document).ready(function(){
  change_captcha('authnum_img',true);
  auto_check_form("register_form",false);

});
</script>