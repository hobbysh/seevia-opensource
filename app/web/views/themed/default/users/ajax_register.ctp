<?php echo $form->create('/users',array('action'=>'register','class'=>'am-form','id'=>'ajax_registerform','name'=>'user_register','type'=>'POST'));?>
	<div class="errors"></div>
	<div class="am-form-group">
		<label><?php echo $ld['e-mail'] ?></label>
		<input class="register_user_names" type="text" style="width:100%" name="data[Users][email]" id="user_names"  chkRules="nnull:请填写第一次注册时填写的email;email:<?php echo $ld['accounts_incorrectly_completed']?>" />
	</div>
	<div class="am-form-group">
		<label><?php echo $ld['password'] ?></label>
		<input style="display:none" type="password"  style="width:100%" name="md5password"  id="md5pwd" value=""/>
		<input class="input1"  type="password" style="width:100%" name="data[Users][password]"  id="register_pwd" />
	</div>
	<div class="am-form-group">
		<label><?php echo $ld['confirm_password'] ?></label>
		<input class="input1"  type="password" style="width:100%" name="data[Users][confirm_password]"  id="confirm_pwd" />
	</div>
	<?php if(isset($configs['register_captcha'])&&$configs['register_captcha']=='1'){ ?>
	<div class="am-form-group">
		<label><?php echo $ld['verify_code'] ?></label>
		<div style="height: 45px;">
			<div class='am-fl am-form-icon am-form-feedback'>
				<input type="hidden" id="ck_authnum" value="" />
				<input type="text" style="width:85px;" class="keywordauthen am-form-field" name="data[Users][authnum]" chkRules="authnum:验证码错误" id="ajax_register_authnums" />
				<span></span>
			</div>
			<div class="authentication" style="float:left;width:90px;">
				<img id='authnum_ajax_register' align='absmiddle' src="/securimages/index/?1234" alt="<?php echo $ld['not_clear']?>" onclick="change_captcha('authnum_ajax_register');" />
				<a style="margin:0 0 0 5px;color:#65c5b3;" href="javascript:change_captcha('authnum_ajax_register');"><span class="am-icon-refresh"></span></a>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="am-form-group" style="clear:both;margin-top:10px;">
		<a class="am-btn am-btn-primary am-btn-block reg_email" href="javascript:void(0);" onclick="ajax_register()"><span><?php echo $ld['register']?></span></a><input type="reset" style="display:none;" />
	</div>
	<div class="am-form-group">
		<div class="am-fr">
			<a href="javascript:void(0);" onclick="window.location.href='<?php echo $html->url('/users/forget_password'); ?>';"><?php echo $ld['forget_password'] ?></a>
		</div>
	</div>
	<div class="mashangzhuce" style="margin-top:10px;">
		<?php echo $ld['already_a_member']?> <a onclick="ajax_login_show()" href="javascript:void(0);"><?php echo $ld['login']?></a>
	</div>
<?php echo $form->end();?>
<script type="text/javascript">
$(function(){
	//更新验证码
	change_captcha('authnum_ajax_register');
})

var ajax_register_authnum_status=false;
<?php if(isset($configs['register_captcha'])&&$configs['register_captcha']=='0'){ ?>
ajax_register_authnum_status=true;
<?php }?>

$("#ajax_registerform .register_user_names").blur(function(){
	var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.\w{2,4}$/; //验证邮箱的正则表达式
	if(!reg.test($(this).val())){
		$("#ajax_registerform .errors").css("height","23px");
		$("#ajax_registerform .errors").html(j_aler_erro_mail);
		$("#ajax_registerform .reg_email").attr("onclick","return false");
		return false;
	}else{
		$("#ajax_registerform .errors").html("&nbsp;");
		$("#ajax_registerform .errors").css("height","0px");
		$("#ajax_registerform .reg_email").attr("onclick","ajax_register()");
	}
});

$("#ajax_registerform #register_pwd").blur(function(){
	if($(this).val()!="" && $("#ajax_registerform #confirm_pwd").val()!=""){
		if($(this).val()==$("#confirm_pwd").val()){
			$("#ajax_registerform .errors").html("&nbsp;");
			$("#ajax_registerform .errors").css("height","0px");
		}else{
			$("#ajax_registerform .errors").html(js_password_different);
			$("#ajax_registerform .errors").css("height","23px");
		}
	}
});
$("#ajax_registerform #confirm_pwd").blur(function(){
	if($(this).val()!="" && $("#ajax_registerform #register_pwd").val()!=""){
		if($(this).val()==$("#ajax_registerform #register_pwd").val()){
			$("#ajax_registerform .errors").html("&nbsp;");
			$("#ajax_registerform .errors").css("height","0px");
		}else{
			$("#ajax_registerform .errors").html(js_password_different);
			$("#ajax_registerform .errors").css("height","23px");
		}
	}
});

$("#ajax_registerform #ajax_register_authnums").blur(function(){
   	var authnum_msg="Error";
	var authnum_val=$(this).val();
	var ck_auth_num=$(this).parent().parent().find("input[id=ck_authnum]").length;
	if(authnum_val.length==0){
		$(this).parent().removeClass("am-form-success");
		$(this).parent().removeClass("am-form-error");
		$(this).parent().addClass("am-form-warning");
		$(this).parent().find("span").removeClass("am-icon-times").removeClass("am-icon-check");
		$(this).parent().find("span").addClass("am-icon-warning").css("display","block");
		ajax_register_authnum_status=false;
	}else if(ck_auth_num>0){
		var ck_auth=$(this).parent().parent().find("input[id=ck_authnum]").val();
		if(ck_auth.trim().length>0){
			if(authnum_val.toLowerCase()!=ck_auth){
	    			$(this).parent().removeClass("am-form-success");
	    			$(this).parent().removeClass("am-form-warning");
	    			$(this).parent().addClass("am-form-error");
	    			$(this).parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-check");
	    			$(this).parent().find("span").addClass("am-icon-times").css("display","block");
	    			ajax_register_authnum_status=false;
			}else{
	    			$(this).parent().removeClass("am-form-error");
	    			$(this).parent().removeClass("am-form-warning");
	    			$(this).parent().addClass("am-form-success");
	    			$(this).parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-times");
	    			$(this).parent().find("span").addClass("am-icon-check").css("display","block");
				authnum_msg="";
				ajax_register_authnum_status=true;
			}
		}
	}
});

var ajax_register_lock=false;
function ajax_register(){
	if(ajax_register_lock){return false;}
	if($("#ajax_registerform #register_pwd").val()!="" && $("#ajax_registerform .register_user_names").val()!=""){
		if($("#ajax_registerform #register_pwd").val()==$("#ajax_registerform #confirm_pwd").val()){
			if(!ajax_register_authnum_status){
				$("#ajax_registerform .errors").html("验证码错误").css("height","23px");
				return false;
			}
			$(".errors").html("&nbsp;");
			$(".errors").css("height","0px");
			ajax_register_lock=true;
			$.ajax({ 
					url:"<?php echo $html->url('/users/register'); ?>",
					type:"POST",
					data:$("#ajax_registerform").serialize()+"&is_ajax=true",
					dataType:"json", 
					context: $("#ajax_registerform .errors"), 
					success: function(data){
						ajax_register_lock=false;
						if(data.error_no==1){
							$("#ajax_registerform .errors").html(data.message);
							$("#ajax_registerform .errors").css("height","23px");
							<?php if(isset($configs['register_captcha'])&&$configs['register_captcha']=='1'){ ?>
							change_captcha('authnum_ajax_register');
							ajax_register_authnum_status=false;
							<?php } ?>
						}else{
							window.location.href=data.back_url;
//							if(typeof(js_login_user_data)!='undefined'){
//							    if(typeof(data.user_data)!='undefined'&&data.user_data!=null){
//							        js_login_user_data=data.user_data;
//							    }
//							}
						}
      				}
      		});
		}else{
			$("#ajax_registerform .errors").html("<?php echo $ld['the_two_passwords_do_not_match']?>");
			$("#ajax_registerform .errors").css("height","23px");
		}
	}else{
		$("#ajax_registerform .errors").html(js_name_pwd_not_empty);
		$("#ajax_registerform .errors").css("height","23px");
	}
}
</script>