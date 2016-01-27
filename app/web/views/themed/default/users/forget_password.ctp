<div class="am-g am-container">
<div class="am-cf am-u-ser-forget-password">
	<h3 style="color:#0e90d2;"><?php echo $ld['forget_password'] ?></h3>
	<hr />
</div>
<div class="am-u-ser-forget-password">
<?php echo $form->create('/users',array('action'=>'forget_password','class'=>'am-form am-form-horizontal','id'=>'forget_form','name'=>'forget','type'=>'POST','onsubmit'=>'return(check_form(this));'));?>
	<div class="am-form-detail">
		<div class="am-form-group">
          <label class="am-u-lg-4 am-u-md-3 am-u-sm-3 am-form-label">Email</label>
          <div class="am-u-lg-6 am-u-md-6 am-u-sm-9"><input type="text" name="email" chkRules="nnull:<?php echo $ld['e-mail_empty']?>;email:<?php echo $ld['e-mail_incorrectly']?>" /><em><font color="red">*</font><font></font>&nbsp;</em></div>
        </div>
    	<div class="am-form-group">
          <label class="am-u-lg-4 am-u-md-2 am-u-sm-2 am-form-label">&nbsp;</label>
          <div class="am-u-lg-6 am-u-md-6 am-u-sm-10">
    		<input class="am-btn am-btn-primary am-btn-sm am-fl" type="submit" value="<?php echo $ld['submit'] ?>" style="margin-right:10px;" />
    		<input class="am-btn am-btn-primary am-btn-sm am-fl" type="button" value="<?php echo $ld['back'] ?>" onclick="to_back()" />
    	  </div>
        </div>
	</div>
<?php echo $form->end();?>
</div>
</div>
<script type="text/javascript">
auto_check_form("forget_form",false);
</script>

