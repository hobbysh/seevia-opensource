<?php echo $form->create('/users',array('action'=>'login','id'=>'ajax_login_form','name'=>'user_login', 'type'=>'POST','class'=>'am-form','onsubmit'=>'return(md5Login(this));return(false);'));?>
	<input type="hidden" name="is_ajax" value="1" />
	<div class="errors"></div>
	<div class="am-other-login">
		<?php foreach($syns as $k=>$v){ ?>
		<span><a class="other-login-link <?php echo strtolower($v); ?>" href="<?php echo $html->url('/synchros/opauth/'.strtolower($v)); ?>"></a></span>
		<?php } ?>
		<?php if(isset($wechat_loginobj)&&!empty($wechat_loginobj)){
					if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){  ?>
		<span><a class="other-login-link wechat" href="<?php echo $html->url('/synchros/opauth/wechat'); ?>"></a></span>
		<?php 	}else{ ?>
		<span><a class="other-login-link wechat" href="javascript:void(0);" onclick="ajax_wechat_cresateqrimg()"></a></span>
		<?php }} ?>
		<div style="clear:both;"></div>
	</div>
  	<div class="am-form-group">
		<label><?php echo $ld['login_name'] ?></label>
		<input name="user_name" id="user_names" class="login_user_names" type="email" chkRules="nnull:<?php echo $ld['login_name'].$ld['can_not_empty']; ?> ?>" placeholder="邮箱/用户名/手机">
		<input type="hidden" class="login_user_type" name="login_type" value="" />
  	</div>
	<div class="am-form-group">
		<label><?php echo $ld['password'] ?></label>
		<input class="input1" type="password" name="password" id="login_pwd" chkRules="nnull:<?php echo $ld['login_password_empty']?>;min6:<?php echo $ld['confirm_password_can_not_be_less_than_6_digits']?>;max16:<?php echo $ld['password_up_to_16']?>" onkeydown="if (event.keyCode==13){ javascript:login();}" />
	</div>
	<?php if(isset($configs['use_captcha'])&&$configs['use_captcha']=='1'){ ?>
	<div class="am-form-group">
		<label><?php echo $ld['verify_code'] ?></label>
		<div style="height: 45px;">
			<div class='am-fl am-form-icon am-form-feedback'>
				<input type="hidden" id="ck_authnum" value="" />
				<input type="text" style="width:85px;"  class="keywordauthen am-form-field" name="data[Users][authnum]" chkRules="authnum:验证码错误" id="ajax_login_authnums" /><span></span>
			</div>
			<div class="authentication" style="float:left;width:90px;">
				<img id='authnum_ajax_login' align='absmiddle' src="/securimages/index/?1234" alt="<?php echo $ld['not_clear']?>" onclick="change_captcha('authnum_ajax_login');" /><a style="margin:0 0 0 5px;color:#65c5b3;" href="javascript:change_captcha('authnum_ajax_login');"><span class="am-icon-refresh"></span></a>
			</div>
		</div>
	</div>
	<?php } ?>
  	<div class="am-form-group" style="clear:both;margin-top:10px;">
    		<a href="javascript:void(0);" class="am-btn am-btn-primary am-btn-block reg_email" onclick="ajax_login()"><span><?php echo $ld['login'] ?></span></a>
  	</div>
	<div class="am-form-group">
		<div class="am-fl">
			<input type="checkbox" name="status" value="1" class="checkl"/><span class="check"><?php echo $ld['auto_login'] ?></span>
		</div>
		<div class="am-fr">
			<a href="javascript:void(0);" onclick="window.location.href='<?php echo $html->url('/users/forget_password'); ?>';"><?php echo $ld['forget_password'] ?></a>
		</div>
	</div>
	<div class="mashangzhuce" style="clear:both;margin-top:10px;">
		<?php echo $ld['not_yet'] ?> <a onclick="ajax_register_show()" href="javascript:void(0);"><?php echo $ld['right_now_registered'] ?>!</a>
	</div>
<?php echo $form->end();?>
<?php if(isset($wechat_loginobj)&&!empty($wechat_loginobj)){if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){ }else{?>
<div id="wechat_qrimg" class="api_wechat"></div>
<?php }} ?>
<script type="text/javascript">
$(function(){
	//更新验证码
	change_captcha('authnum_ajax_login');
	wechat_cresateqrimg();
})

var ajax_login_authnum_status=false;
<?php if(isset($configs['use_captcha'])&&$configs['use_captcha']=='0'){ ?>
ajax_login_authnum_status=true;
<?php }?>

$("#ajax_login_form #ajax_login_authnums").blur(function(){
    var authnum_msg="Error";
	var authnum_val=$(this).val();
	var ck_auth_num=$(this).parent().parent().find("input[id=ck_authnum]").length;
	if(authnum_val.length==0){
		$(this).parent().removeClass("am-form-success");
		$(this).parent().removeClass("am-form-error");
		$(this).parent().addClass("am-form-warning");
		$(this).parent().find("span").removeClass("am-icon-times").removeClass("am-icon-check");
		$(this).parent().find("span").addClass("am-icon-warning").css("display","block");
		ajax_login_authnum_status=false;
	}else if(ck_auth_num>0){
		var ck_auth=$(this).parent().parent().find("input[id=ck_authnum]").val();
		if(ck_auth.trim().length>0){
			if(authnum_val.toLowerCase()!=ck_auth){
	    			$(this).parent().removeClass("am-form-success");
	    			$(this).parent().removeClass("am-form-warning");
	    			$(this).parent().addClass("am-form-error");
	    			$(this).parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-check");
	    			$(this).parent().find("span").addClass("am-icon-times").css("display","block");
	    			ajax_login_authnum_status=false;
			}else{
	    			$(this).parent().removeClass("am-form-error");
	    			$(this).parent().removeClass("am-form-warning");
	    			$(this).parent().addClass("am-form-success");
	    			$(this).parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-times");
	    			$(this).parent().find("span").addClass("am-icon-check").css("display","block");
				authnum_msg="";
				ajax_login_authnum_status=true;
			}
		}
	}
});

var ajax_login_lock=false;
function ajax_login(){
	if(ajax_login_lock){return false;}
	var authnums="";
	if($("#ajax_login_form #login_pwd").val()!="" && $("#ajax_login_form .login_user_names").val()!=""){
		if(ajax_login_authnum_status){
			var login_user_names=$("#ajax_login_form .login_user_names").val();
			var email_reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; //验证邮箱的正则表达式
			var mobile_reg=/^1[3-9]\d{9}$/;
			var login_type="";
			if(email_reg.test(login_user_names)){
				login_type="email";
			}else if(mobile_reg.test(login_user_names)){
				login_type="mobile";
			}else{
				login_type="user_sn";
			}
			$("#ajax_login_form .login_user_type").val(login_type);
			$(".errors").html("&nbsp;");
			$(".errors").css("height","0px");
			authnums=$("#ajax_login_authnums").val();
			ajax_login_lock=true;
			$.ajax({
				 url: "<?php echo $html->url('/users/login'); ?>",
				 type:"POST",
				 data:$("#ajax_login_form").serialize(),
				 dataType:"json",
				 context: $("#ajax_login_form .errors"), 
				 success: function(data){
				 	 ajax_login_lock=false;
					if(data.error_no==1){
						$("#ajax_login_form .errors").html(data.message);
						$("#ajax_login_form .errors").css("height","23px");
						<?php if(isset($configs['use_captcha'])&&$configs['use_captcha']=='1'){ ?>
							change_captcha('authnum_ajax_login');
							$("#ajax_login_form #ajax_login_authnums").parent().removeClass("am-form-error").removeClass("am-form-warning").removeClass("am-form-success");
							$("#ajax_login_form #ajax_login_authnums").parent().find("span").removeClass("am-icon-warning").removeClass("am-icon-times").removeClass("am-icon-check");
							ajax_login_authnum_status=false;
						<?php } ?> 
					}else{
						window.location.href=data.back_url;
//		                    if(typeof(js_login_user_data)!='undefined'){
//		                        if(typeof(data.user_data)!='undefined'&&data.user_data!=null){
//		                            js_login_user_data=data.user_data;
//		                        }
//		                    }
					}
	  			}
	  		});
  		}else{
  			$("#ajax_login_form .errors").html("验证码错误").css("height","23px");
  		}
	}else{
		$("#ajax_login_form .errors").html(js_name_pwd_not_empty);
		$("#ajax_login_form .errors").css("height","23px");
	}
}

function wechat_cresateqrimg(){
	if(document.getElementById("wechat_qrimg")){
		var obj = new WxLogin({
		      id:"wechat_qrimg", 
		      appid: "<?php echo isset($wechat_loginobj['appid'])?$wechat_loginobj['appid']:''; ?>", 
		      scope: "snsapi_login,snsapi_userinfo", 
		      redirect_uri: "<?php echo isset($wechat_loginobj['redirect_uri'])?$wechat_loginobj['redirect_uri']:''; ?>",
		      state: "<?php echo isset($wechat_loginobj['state'])?$wechat_loginobj['state']:''; ?>"
		    });
	}
}

function ajax_wechat_cresateqrimg(){
	$("#ajax_login_form").hide();
	$("#wechat_qrimg").show();
}
</script>