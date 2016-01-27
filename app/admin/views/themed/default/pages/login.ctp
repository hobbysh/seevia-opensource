<script src="/plugins/AmazeUI/js/md5.js" type="text/javascript"></script>
<div class="am-login ">
	<h2 style="color:#5eb95e;"><?php 
		 if(!empty($configs['admin_detail'])){echo $configs['admin_detail'];}else{echo $configs['shop_name']."&nbsp;-&nbsp;".$ld['ecommerce_plaform'];}
	?></h2><hr >
	<?php echo $form->create('/users',array('action'=>'login','id'=>'login_form','class'=>'am-form am-form-horizontal ','name'=>'user_login','type'=>'POST','onsubmit'=>'return(check_form(this));'));?>
    <?php if(isset($backend_locales) && sizeof($backend_locales)>1){?>
    <div class="am-form-detail">
		<div class="am-form-group" style="<?php echo isset($login_error)&&$login_error!=''?'margin-bottom:0;':''; ?>">
		  <label class="am-u-lg-3 am-u-md-3 am-u-sm-1 am-form-label">&nbsp;</label>
          <div class="am-u-lg-5 am-u-md-5 am-u-sm-10">
    	<!--用户名输入框-->
    		 <div class="am-input-group am-input-group-success">
			  <span class="am-input-group-label"><i class="am-icon-buysellads"></i></span>
			  <select  name="locale" id="locale" onchange="change_locale(this)" style="height:38px;">
                <?php foreach($backend_locales as $v){ ?><option value="<?php echo $v['Language']['locale']?>" <?php if(isset($backend_locale)&&$v['Language']['locale']==$backend_locale){echo 'selected';}?>><span class="lang"><?php echo $v['Language']['name']?></span></option><?php } ?>
              </select>
			</div>
    	  </div>
        </div>
	</div>
    <?php }else{ ?><input type="hidden" name="locale" id="locale" value="<?php echo $backend_locales[0]['Language']['locale']?>"/>
	<?php }?>
	<div class="am-form-detail">
		<div class="am-form-group" style="<?php echo isset($login_error)&&$login_error!=''?'margin-bottom:0;':''; ?>">
		  <label class="am-u-lg-3 am-u-md-3 am-u-sm-1 am-form-label">&nbsp;</label>
          <div class="am-u-lg-5 am-u-md-5 am-u-sm-10">
    	<!--用户名输入框-->
    		 <div class="am-input-group am-input-group-success">
			  <span class="am-input-group-label"><i class="am-icon-user"></i></span>
			  <input type="text" id="operator_id" class="am-form-field" placeholder="<?php echo $ld['login_id'] ?>">
			</div>
    	  </div>
        </div>
	</div>
	
	<div class="am-form-detail">
		<div class="am-form-group">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-1 am-form-label">&nbsp;</label>
          <div class="am-u-lg-5 am-u-md-5 am-u-sm-10">
        <!--密码输入框-->
        	<div class="am-input-group ">
			  <span class="am-input-group-label"><i class="am-icon-lock"></i></span>
			  <input type="password" class="am-form-field" id="operator_pwd" placeholder="<?php echo $ld['login_password'] ?>">
			</div>
         </div>
       </div>
	</div>
	
	<div class="am-form-detail captcha" id="vcode" style="<?php echo isset($count_login)&&$count_login>=2?'':'display:none;';?>">
		<div class="am-form-group">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-1 am-form-label">&nbsp;</label>
          <div class="am-u-lg-3 am-u-md-3 am-u-sm-6 am-form-icon am-form-feedback yanzhengma">

          	
          	<input type="text" class="am-form-field" chkRules="authnum:验证码错误" maxlength="4" id="authnum" name="captcha_num" /><input type="hidden" value="" id="ck_login_authnum" /><span style="right:20px;"></span></div>
          <div class="am-u-lg-5 am-u-md-5 am-u-sm-3 yanzhengmaimg"><img id="authnum_img" onclick="change_captcha()" align='absmiddle' src="/securimages/index/?1234" /><a style="color:#5eb95e" href="javascript:change_captcha();"><i class="am-icon-refresh"></i></a></div>
        </div>
	    <div class="am-form-group" style="margin-bottom:0;display:none;">
	    	<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:0px;">&nbsp;</label>
	    	<div class="am-u-lg-5 am-u-md-5 am-u-sm-7 authnum_msg">&nbsp;</div>
	    </div>
	</div>
	
	<div class="am-form-detail">
		<div class="am-form-group" style="margin-bottom:0;margin-top: 1.5rem;">
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-1 am-form-label">&nbsp;</label>
          <div class="am-u-lg-5 am-u-md-7 am-u-sm-7"><label class="remember-Me am-checkbox am-success" >
  <input type="checkbox" id="cookie_session" name="cookie_session" value="1" data-am-ucheck>&nbsp;<span style="color:#555;"><?php echo $ld['login_auto']?></span></label></div>
        </div>
	</div>
	<div class="am-form-detail">
		<div class="am-form-group">
          <label class="am-u-lg-3 am-u-md-3 am-u-sm-1 am-form-label">&nbsp;</label>
          <div class="am-u-lg-5 am-u-md-8 am-u-sm-9">
     		<button type="button" id="login_check_id" data-am-loading="{spinner: 'circle-o-notch',loadingText: '<?php echo $ld['loading'] ?>'}" class="am-btn am-btn-success am-btn-xl am-radius" onclick="ajax_login_check()"><?php echo $ld['login_btn']?></button>
        </div>
        </div>
	</div>
	<?php echo $form->end();?>
</div>
<style type="text/css">
.am-login {
    margin: 2em auto;
    max-width: 750px;
    min-width: 260px;
    padding: 0 0.5rem;
}
.captcha{
	 line-height: 3.2rem;
     vertical-align: middle;
}
.am-login .am-checkbox{padding-top:0px;}
.am-form-group .yanzhengma{padding-top:10px;padding-left:49px;}
.am-form-group .yanzhengmaimg{padding-top:10px;}
</style>
<script type="text/javascript">
$(function(){
	<?php if(isset($count_login)&&$count_login>2){?>
		get_captcha_number();
	<?php } ?>
	
	document.getElementById("operator_id").focus();
	document.onkeydown = function(evt){
		var evt = window.event?window.event:evt;
		if(evt.keyCode==13){
			var UserName = document.getElementById('operator_id').value;
			var UserPassword = document.getElementById('operator_pwd').value;
			if(document.getElementById('vcode').style.display=="block"){
				var UserCaptcha = document.getElementById('authnum').value;
				if(UserName != "" && UserPassword != "" && UserCaptcha != ""){
					ajax_login_check();
				}
			}else{
				if(UserName != "" && UserPassword != ""){
					ajax_login_check();
				}
			}
		}
	}
})
    
//后台执行登入
function ajax_login_check(){
	var btn = $("#login_check_id");
	btn.button('loading');
	
	var cookie_session = document.getElementById("cookie_session");//获取cookie对象
	var operator_pwd =document.getElementById("operator_pwd").value;
	operator_pwd=hex_md5(operator_pwd);
	var operator_id =document.getElementById("operator_id").value;
	if(document.getElementById("locale")){
		var locale = document.getElementById("locale").value;
	}else{
		var locale="eng";
	}
	cookie_session = (document.getElementById("cookie_session").checked)?1:0;
	var postData={'operator_pwd':operator_pwd,'operator':operator_id,'cookie_session':cookie_session};
	if(document.getElementById("authnum")){
		var authnum = document.getElementById("authnum").value;
		if(document.getElementById('vcode').style.display!="none"){
			var ck_login_authnum=document.getElementById('ck_login_authnum').value;
			if(ck_login_authnum==authnum.toLowerCase()){
				postData={'operator_pwd':operator_pwd,'operator':operator_id,'cookie_session':cookie_session,'authnum':authnum};
			}else{
				alert('verification code error');
				btn.button('reset');
				return false;
			}
		}
	}
	$.ajax({ url:admin_webroot+"operators/ajax_login/",
			type:"POST",
			dataType:"json",
			data: postData,
			success: function(data){
				try{
					if(data.code=="0"){
						if(data.url==""||data.url=="/pages/home"){
							window.location.href= admin_webroot+"pages/home";
						}else{
							window.location.href=data.url;
						}
					}else{
                        var count_login=data.count_login;
						btn.button('reset');
						alert(data.message);
						if(document.getElementById("authnum")&&count_login>=2){
							document.getElementById('vcode').style.display="block";
						}
						show_login_captcha();
					}
				}catch(e){
					alert(data);
				} 
			}
		});
}

//聚焦，获取验证码
function show_login_captcha(){
	if(document.getElementById("authnum")){
		document.getElementById('vcode').style.display=="block";
		document.getElementById("authnum").value = "";
		change_captcha();
	}
}


//点击，获取验证码
function change_captcha(){
	if(document.getElementById("authnum")){
		document.getElementById("authnum_img").src = admin_webroot+"/authnums/get_authnums/?"+Math.random();
		setTimeout("get_captcha_number();",2000);
	}
}

//获取验证码值
function get_captcha_number(){
	$.ajax({ url:admin_webroot+"/authnums/get_authnumber/?"+Math.random(),
			type:"POST",
			dataType:"html",
			data: {},
			success: function(data){
				if(document.getElementById("ck_login_authnum")){
					document.getElementById("ck_login_authnum").value=data;
				}
			}
		});
}

function change_locale(obj){
	window.location.href="<?php $admin_webroot;?>?backend_locale="+obj.value;
}
</script>