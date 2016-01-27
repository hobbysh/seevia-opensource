<div class="am-g am-container">
<div class="am-cf am-u-ser-reset-password">
	<h3 style="color:#0e90d2;"><?php echo $ld['reset_pwd'] ?></h3>
	<hr />
</div>
<div class="am-u-ser-reset-password">
	<?php echo $form->create('/users',array('action'=>'reset_password','class'=>'am-form am-form-horizontal','id'=>'forget_form','name'=>'forget','type'=>'POST'));?>
		<div class="am-form-detail">
			<div class="am-form-group">
	          <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['password']; ?></label>
	          <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
					<input type="password" name="ps" id='1_pass' /><em>&nbsp;<font color="red">*</font><font></font>&nbsp;</em>
			  </div>
	        </div>
	
			<div class="am-form-group">
	          <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['confirm_password']; ?></label>
	          <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
					<input type="password" name="ps2" id='2_pass' /><em>&nbsp;<font color="red">*</font><font></font>&nbsp;</em>
			  </div>
	        </div>
	
	    	<div class="am-form-group">
	          <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">&nbsp;</label>
	          <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
	    		<input class="am-btn am-btn-primary am-btn-sm am-fl" type="button" value="<?php echo $ld['submit'] ?>" onclick="check_user()" style="margin-right:10px;" />
	    		<input class="am-btn am-btn-primary am-btn-sm am-fl" type="button" value="<?php echo $ld['back'] ?>" onclick="to_back()" />
	    	  </div>
	        </div>
		</div>
	<?php echo $form->end();?>
</div>
</div>

<script type="text/javascript">
function check_user(){
	var id = document.getElementById("1_pass").value;
	var ps = document.getElementById("2_pass").value;
	if(id.replace(/(^\s*)|(\s*$)/g,"")==""){alert('<?php echo $ld['fill_pwd']; ?>');return;}
	if(ps.replace(/(^\s*)|(\s*$)/g,"")==""){alert('<?php echo $ld['fill_pwd']; ?>');return;}
	if(id != ps){alert("<?php echo $ld['the_two_passwords_do_not_match'] ?>");return;}
	$("#forget_form").submit();
}

function to_back(){
	window.location.href="<?php echo $html->url('/users/login'); ?>";
}
</script>