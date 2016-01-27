<script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
<div class="am-container am-login">
	<h3 class="am-text-primary"><?php echo $ld['member_login'] ?></h3><hr />
	<div class="am-other-login">
		<?php foreach($syns as $k=>$v){ ?>
			<span><a class="other-login-link <?php echo strtolower($v); ?>" href="<?php echo $html->url('/synchros/opauth/'.strtolower($v)); ?>"></a></span>
		<?php } ?>
		<span style="display:none;"><a class="other-login-link wechat" href="javascript:void(0);"></a></span>
		<span id="qqLoginBtn"></span>
		<div style="clear:both;"></div>
	</div>
<?php echo $form->create('/users',array('action'=>'login','id'=>'login_form','class'=>'am-form am-form-horizontal','name'=>'user_login','type'=>'POST','onsubmit'=>'return(check_form(this));'));?>
	<div class="am-form-detail">	
		<div class="am-form-group" style="<?php echo $messege_error!=''?'margin-bottom:0;':''; ?>">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['login_name'] ?></label>
          <div class="am-u-lg-7 am-u-md-8 am-u-sm-8"><input type="text" name="user_name" id="user_names" placeholder="<?php echo $ld['email'].'/'.$ld['user_id'].'/'.$ld['mobile']; ?>" /><input type="hidden" id="login_type" name="login_type" value="user_sn" /></div>
        </div>
    	<?php if($messege_error!=""){ ?>
    	<div class="am-form-group">
        	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label messege_error">&nbsp;</label>
        	<div class="am-u-sm-9"><font color='red'><?php echo $messege_error; ?></font></div>
        </div>
        <?php } ?>
    	<div class="am-form-group">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['password'] ?></label>
          <div class="am-u-lg-7 am-u-md-8 am-u-sm-8"><input type="password" name="password"  id="password" chkRules="nnull:<?php echo $ld['login_password_empty']?>;min4:<?php echo $ld['confirm_password_can_not_be_less_than_6_digits']?>;max16:<?php echo $ld['password_up_to_16']?>" /></div>
        </div>
		<?php if(isset($configs['use_captcha'])&&$configs['use_captcha']=='1'){ ?>
		<div class="am-form-group" style="margin-bottom:0px;">
          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['please_enter_the_code'] ?></label>
	      <div class="am-u-sm-2 keywordauthen am-form-icon am-form-feedback">
	        <input type="hidden" id="ck_authnum" value="" />
	        <input type="text" class="am-form-field" name="data[Users][authnum]" id="authnums" chkRules="authnum:验证码错误" /><span style="right:10px;"></span>
		  </div>
		  <div class="am-u-sm-5 authentication">
			<img id='authnum_login_img' align='absmiddle' src="/securimages/index/?1234" alt="<?php echo $ld['not_clear']?>" onclick="change_captcha('authnum_login_img');" /><a href="javascript:change_captcha('authnum_login_img');"><?php echo $ld['not_clear']?></a><em><font color="red">*</font></em>
		  </div>
	    </div>
        <div class="am-form-group" style="margin-bottom:0;display:none;">
        	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:0px;">&nbsp;</label>
        	<div class="am-u-sm-9 authnum_msg">&nbsp;</div>
        </div>
		<?php } ?>
        <div class="am-form-group am-remember-me">
          <div class="am-u-sm-9 am-form-label"><label class="remember-me"><input type="checkbox" name="status" value="1" />&nbsp;<?php echo $ld['auto_login'] ?></label></div>
        </div>
    	<div class="am-form-group">
          <div class="am-u-sm-9 am-form-label">
    		<input class="am-btn am-btn-primary am-btn-sm am-fl" name="login" type="submit" value="<?php echo $ld['login'] ?>" />
    		<input type="button" class="am-btn am-btn-default am-btn-sm am-fr" onclick="window.location.href='<?php echo $html->url('/users/forget_password'); ?>';" value="<?php echo $ld['forget_password'] ?> ^_^? " />
   		  </div>
        </div>
    	<div class="am-form-group">
          <div class="am-u-sm-9 am-form-label">
        	<?php echo $ld['not_yet'] ?> <a id="sun" onclick="ajax_register_show()" href="javascript:void(0);"><?php echo $ld['right_now_registered'] ?>!</a>
          </div>
        </div>
	</div>
<?php echo $form->end();?>
	<hr />
</div>

<!-- wechat登录弹窗 start -->
<div class="am-popup" id="wechat-login">
  <div class="am-popup-inner api_wechat" id="qrimg">
    
  </div>
</div>
<!-- wechat登录弹窗 end -->

<style type="text/css">
ol, ul, li, p, h2, h3, h4, h5, h6, dl, dt, dd, form, input, fieldset, select, textarea, object, embed{margin:0;padding:0;}
li{list-style:none;}
</style>
<script type="text/javascript">
function fgsb(){
	$("#newemail .error").html("&nbsp;");
	var aa=$("#nemail").val();
	if(aa!=""){
	//做提交步骤
		$.ajax({ url: "<?php echo $html->url('/users/forget_password'); ?>",type:"POST",data:{'email':aa,'is_ajax':'1'},success: function(data){
				var result=JSON.parse(data);
				if(result.code==0){
					$("#newemail .error").html(result.forget_error);
				}else{
					$("#newemail").hide();
					$("#forget_error").html(result.result);
					$("#forget_error").show();
				}
		}});
	}
}
//点击忘记密码出来的效果
$(".forget_pwd").click(function(){
	$("#login_title").hide();
	$("#olddenglu").hide();
	$("#forget_error").hide();
	$("#forgetpas").show();
	$("#newemail").show();
	$("#newemail .error").html("&nbsp;");
});
$(".close").click(function(){
	$("#forgetpas").hide();
	$("#newemail").hide();
	$("#login_title").show();
	$("#olddenglu").show();
});

/*
	微信登录
*/
$(".wechat .alipay_go").on("click",function(){
	/*
		判断当前开启的弹窗，记录class名称
	*/
	if($(".dialog_denglu").css("display")=="block"){
		this_dialog_show=".denglu";
		$(".dialog_denglu .close").click();
	}else if($(".dialog_zhuce").css("display")=="block"){
		this_dialog_show=".zhuce";
		$(".dialog_zhuce .close").click();
	}
	$(".dialog_wechat .show_wecaht").click();
});

</script>

<script type="text/javascript">
var login_userid="user_names";
change_captcha('authnum_login_img',true);
$(function(){
	auto_check_form("login_form",false);
	$("#"+login_userid).blur(function(){
		var email_reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; //验证邮箱的正则表达式
		var mobile_reg=/^1[3-9]\d{9}$/;
		var login_type="";
		if($(this).val()!=""){
			if(email_reg.test($(this).val())){
				login_type="email";
			}else if(mobile_reg.test($(this).val())){
				login_type="mobile";
			}else{
				login_type="user_sn";
			}
	    }
	    $(this).next().val(login_type);
	});
	
	<?php if(isset($wechat_loginobj)&&!empty($wechat_loginobj)){ ?>
		<?php if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')!==false){ ?>
			$(".other-login-link.wechat").parent().css("display","block");
			$(".other-login-link.wechat").click(function(){
				window.location.href="<?php echo $html->url('/synchros/opauth/wechat'); ?>";
			});
		<?php }else{ ?>
			cresateqrimg();
	<?php }} ?>
})

/*
	微信登录
*/
function cresateqrimg(){
	var obj = new WxLogin({
      id:"qrimg", 
      appid: "<?php echo isset($wechat_loginobj['appid'])?$wechat_loginobj['appid']:''; ?>", 
      scope: "snsapi_login,snsapi_userinfo", 
      redirect_uri: "<?php echo isset($wechat_loginobj['redirect_uri'])?$wechat_loginobj['redirect_uri']:''; ?>",
      state: "<?php echo isset($wechat_loginobj['state'])?$wechat_loginobj['state']:''; ?>"
    });
	$(".am-other-login .wechat").parent().css("display","block");
	//绑定弹窗显示
	$(".am-other-login .wechat").on("click",function(){
		$('#wechat-login').modal("toggle");
	});
}
</script>