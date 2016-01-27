<div class="am-panel-group syn_callback" id="accordion">
    <div class="am-panel am-panel-default <?php echo isset($action_code)&&$action_code!='auto_bind'?'am-hide':''; ?>">
        <div class="am-panel-hd">
          <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#auto_bind'}">自动登录<span class="am-callback-icon am-fr <?php echo isset($action_code)&&$action_code!='auto_bind'?'am-icon-angle-double-down':'am-icon-angle-double-up'; ?>"></span></h4>
        </div>
        <div id="auto_bind" class="am-panel-collapse am-collapse <?php echo (isset($action_code)&&$action_code=='auto_bind')||!isset($action_code)?'am-in':''; ?>">
          <div class="am-panel-bd">
            <div class="am-cf am-g am-g-fixed">
                <div class="am-u-lg-5 am-u-md-12 am-u-sm-12">
        			<figure data-am-widget="figure" class="am am-figure am-figure-default" data-am-figure="{ pureview:1}">
            			<img alt="" data-rel="<?php echo $response['auth']['info']['image']!=''&&$response['auth']['info']['image']!='/'?$response['auth']['info']['image']:'/theme/default/img/no_head.png'; ?>" src="<?php echo $response['auth']['info']['image']!=''&&$response['auth']['info']['image']!='/'?$response['auth']['info']['image']:'/theme/default/img/no_head.png'; ?>">
          			</figure>
        		</div>
                <div class="am-u-lg-5 am-u-md-12 am-u-sm-12">
        			<div class="am-text-center">来自<?php echo isset($userAppNames[$response['auth']['provider']])?$userAppNames[$response['auth']['provider']]:$response['auth']['provider']; ?>的<?php echo $response['auth']['info']['name']; ?>，你好!</div>
        			<?php echo $form->create('/synchros',array('action'=>'apibind','id'=>'api_fast_login_form','name'=>'api_fast_login_form','type'=>'POST'));?>
        			<input type="hidden" name="data[type]" value="fast_login" />
        			<input type="hidden" name="data[u_id]" value="<?php echo isset($u_id)?$u_id:''; ?>" />
        			<input type="hidden" name="data[api_type]" value="<?php echo $response['auth']['provider']; ?>" />
        			<input type="hidden" name="data[oauth_token]" value="<?php echo $response['auth']['credentials']['token']; ?>" />
        			<input type="hidden" name="data[user_name]" value="<?php echo isset($response['auth']['info']['name'])&&$response['auth']['info']['name']!=''?$response['auth']['info']['name']:''; ?>">
        			<input type="hidden" name="data[img]" value="<?php echo $response['auth']['info']['image']!=''&&$response['auth']['info']['image']!='/'?$response['auth']['info']['image']:'/theme/default/img/no_head.png'; ?>" />
        			<div class="am-form-detail">
        				<div class="am-form-group">
        		          <label class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-form-label"><?php printf($ld['api_fast_login_desc'],$configs['shop_title']); ?></label>
        		        </div>
        		        <div class="am-form-group">
        		          <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-form-label"><input class="am-btn fast_login_btn" id="fast_login_btn" type="button" value="<?php echo isset($userAppNames[$response['auth']['provider']])?$userAppNames[$response['auth']['provider']]:$response['auth']['provider']; ?>自动登录" /></div>
        		        </div>
        			</div>
        			<?php echo $form->end();?>
        		</div>
            </div>
          </div>
        </div>
    </div>
    
    <div class="am-panel am-panel-default <?php echo isset($action_code)&&$action_code!='api_bind'?'am-hide':''; ?>">
        <div class="am-panel-hd">
          <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#api_bind'}"><?php echo $ld['api_login_desc1']; ?><span class="am-callback-icon am-fr <?php echo isset($action_code)&&$action_code=='auto_bind'?'am-icon-angle-double-up':'am-icon-angle-double-down'; ?>"></span></h4>
        </div>
        <div id="api_bind" class="am-panel-collapse am-collapse <?php echo isset($action_code)&&$action_code=='api_bind'?'am-in':''; ?>">
          <div class="am-panel-bd">
            <?php echo $form->create('/synchros',array('action'=>'apibind','id'=>'api_login_form','class'=>'am-form am-form-horizontal','name'=>'api_user_login','type'=>'POST'));?>
    		<input type="hidden" id="login_type" name="data[login_type]" value="user_sn" />
			<input type="hidden" name="data[type]" value="login" />
			<input type="hidden" name="data[u_id]" value="<?php echo isset($u_id)?$u_id:''; ?>" />
			<input type="hidden" name="data[api_type]" value="<?php echo $response['auth']['provider']; ?>" />
			<input type="hidden" name="data[oauth_token]" value="<?php echo $response['auth']['credentials']['token']; ?>" />
    		<div class="am-form-detail">
    			<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['login_name'] ?>:</label>
					<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
						<input type="text" name="data[email]" id="api_log_email" value="" placeholder="<?php echo $ld['email'].'/'.$ld['user_id'].'/'.$ld['mobile']; ?>" />
					</div>
				</div>
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['password'] ?>:</label>
					<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
						<input type="password" name="data[password]" id="api_log_password" value="" />
					</div>
				</div>
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
					<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
						<input class="am-btn am-btn-primary am-btn-sm am-fl" type="submit" value="<?php echo '绑定账号' ?>" />
					</div>
				</div>
    		</div>
    		<?php echo $form->end();?>
          </div>
        </div>
    </div>
    
    <div class="am-panel am-panel-default <?php echo (isset($response['auth']['provider'])&&($response['auth']['provider']=='wechat')||($response['auth']['provider']=='Wechat'))||(isset($action_code)&&$action_code!='api_register')?'am-hide':''; ?>">
        <div class="am-panel-hd">
          <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#api_register'}"><?php echo $ld['api_register_desc1']; ?><span class="am-callback-icon am-fr <?php echo isset($action_code)&&$action_code=='api_register'?'am-icon-angle-double-up':'am-icon-angle-double-down'; ?>"></span></h4>
        </div>
        <div id="api_register" class="am-panel-collapse am-collapse <?php echo isset($action_code)&&$action_code=='api_register'?'am-in':''; ?>">
          <div class="am-panel-bd">
            <?php echo $form->create('/synchros',array('action'=>'apibind','id'=>'api_register_form','class'=>'am-form am-form-horizontal','name'=>'api_register_login','type'=>'POST'));?>
    			<input type="hidden" name="data[type]" value="register" />
    			<input type="hidden" name="data[u_id]" value="<?php echo isset($u_id)?$u_id:''; ?>" />
    			<input type="hidden" name="data[api_type]" value="<?php echo $response['auth']['provider']; ?>" />
    			<input type="hidden" name="data[oauth_token]" value="<?php echo $response['auth']['credentials']['token']; ?>" />
    			<input type="hidden" name="data[user_name]" value="<?php echo isset($response['auth']['info']['name'])&&$response['auth']['info']['name']!=''?$response['auth']['info']['name']:''; ?>">
    			<input type="hidden" name="data[user_nickname]" value="<?php echo isset($response['auth']['info']['nickname'])&&$response['auth']['info']['nickname']!=''?$response['auth']['info']['nickname']:''; ?>">
    			<input type="hidden" name="data[img]" value="<?php echo $response['auth']['info']['image']; ?>" />
    			<div class="am-form-detail">
        			<div class="am-form-group">
    					<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['e-mail'] ?>:</label>
    					<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
    						<input type="text" name="data[email]" id="api_reg_email" value="" /><em>*</em>
    					</div>
    				</div>
    				<div class="am-form-group">
    					<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['password'] ?>:</label>
    					<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
    						<input type="password" name="data[password]" id="api_reg_password" value="" /><em>*</em>
    					</div>
    				</div>
    				<div class="am-form-group">
    					<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['confirm_password'] ?>:</label>
    					<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
    						<input type="password" name="data[confirm_password]" id="api_reg_confirm_password"  value="" /><em>*</em>
    					</div>
    				</div>
    				<div class="am-form-group">
    					<label class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-form-label">&nbsp;</label>
    					<div class="am-u-lg-8 am-u-md-8 am-u-sm-9">
    						<input class="am-btn am-btn-primary am-btn-sm am-fl" type="submit" value="<?php echo $ld['submit_register'] ?>" />
    					</div>
    				</div>
        		</div>
    		<?php echo $form->end();?>
          </div>
        </div>
    </div>
</div>



<script type="text/javascript">
var api_reg_email_check=false;
var api_reg_email_checkmsg="<?php echo $ld['e-mail_empty'] ?>";
var api_reg_password_check=false;
var api_reg_password_checkmsg="<?php echo $ld['login_password_empty']?>";
var api_reg_confirm_password_check=false;
var api_reg_confirm_password_checkmsg="<?php echo $ld['confirm_password_can_not_be_empty']?>";

$(function(){
    $('#auto_bind').on('open.collapse.amui', function() {
        $(this).parent().find(".am-callback-icon").removeClass("am-icon-angle-double-down");
        $(this).parent().find(".am-callback-icon").addClass("am-icon-angle-double-up");
    }).on('close.collapse.amui', function() {
        $(this).parent().find(".am-callback-icon").removeClass("am-icon-angle-double-up");
        $(this).parent().find(".am-callback-icon").addClass("am-icon-angle-double-down");
    });
    
    $('#api_bind').on('open.collapse.amui', function() {
        $(this).parent().find(".am-callback-icon").removeClass("am-icon-angle-double-down");
        $(this).parent().find(".am-callback-icon").addClass("am-icon-angle-double-up");
    }).on('close.collapse.amui', function() {
        $(this).parent().find(".am-callback-icon").removeClass("am-icon-angle-double-up");
        $(this).parent().find(".am-callback-icon").addClass("am-icon-angle-double-down");
    });
    
    $('#api_register').on('open.collapse.amui', function() {
        $(this).parent().find(".am-callback-icon").removeClass("am-icon-angle-double-down");
        $(this).parent().find(".am-callback-icon").addClass("am-icon-angle-double-up");
    }).on('close.collapse.amui', function() {
        $(this).parent().find(".am-callback-icon").removeClass("am-icon-angle-double-up");
        $(this).parent().find(".am-callback-icon").addClass("am-icon-angle-double-down");
    });
    
	$("#fast_login_btn").click(function(){
		$("#api_fast_login_form").submit();
	});
	
	$("#api_register_form").submit(function(){
		$("#api_reg_password").blur();
		$("#api_reg_confirm_password").blur();
		if(api_reg_email_check==false){
			alert(api_reg_email_checkmsg);
			return false;
		}else if(api_reg_password_check==false){
			alert(api_reg_password_checkmsg);
			return false;
		}else if(api_reg_confirm_password_check==false){
			alert(api_reg_confirm_password_checkmsg);
			return false;
		}
		return true;
	});
	
	$("#api_reg_email").blur(function(){
		if(api_reg_email_check==false){
			var value=$(this).val();
			if(value!=""){
				var reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w{2,4}$/;
				if(reg.test(value)){
					$.ajax({
		             type: "POST",
		             url: "/synchros/checkdata",
		             data: {type:'email','value':value},
		             dataType: "json",
		             success: function(data){
		             		if(data.code==1){
		             			api_reg_email_check=true;
		             		}else{
		             			api_reg_email_check=false;
		             			api_reg_email_checkmsg=data.msg;
		             		}
		             	}
		         	});
		        }else{
		        	api_reg_email_check=false;
		        	api_reg_email_checkmsg="<?php echo $ld['e-mail_incorrectly'] ?>";
		        }
			}else{
				api_reg_email_check=false;
				api_reg_email_checkmsg="<?php echo $ld['e-mail_empty'] ?>";
			}
		}
	});
	
	$("#api_reg_email").change(function(){
		api_reg_email_check=false;
	});
	
	$("#api_reg_password").blur(function(){
		if(api_reg_password_check==false){
			var value=$(this).val();
			var value_length=value.replace(/[^\x00-\xff]/g,"**").length;
			if(value_length==0){
				api_reg_password_check=false;
		        api_reg_password_checkmsg="<?php echo $ld['login_password_empty'] ?>";
			}else if(value_length>0&&value_length<6){
				api_reg_password_check=false;
		        api_reg_password_checkmsg="<?php echo $ld['confirm_password_can_not_be_less_than_6_digits'] ?>";
			}else{
				api_reg_password_check=true;
				api_reg_password_checkmsg="";
			}
		}
	});
	
	$("#api_reg_password").change(function(){
		api_reg_password_check=false;
		if(api_reg_confirm_password_check){
			api_reg_confirm_password_check=false;
			api_reg_confirm_password_checkmsg="<?php echo $ld['the_two_passwords_do_not_match'] ?>";
		}
	});
	
	$("#api_reg_confirm_password").blur(function(){
		if(api_reg_confirm_password_check==false){
			var value=$(this).val();
			var value_length=value.replace(/[^\x00-\xff]/g,"**").length;
			var api_reg_password=$("#api_reg_password").val();
			if(value_length==0){
				api_reg_confirm_password_check=false;
		        api_reg_confirm_password_checkmsg="<?php echo $ld['confirm_password_can_not_be_empty'] ?>";
			}else if(value_length>0&&value_length<6){
				api_reg_confirm_password_check=false;
		        api_reg_confirm_password_checkmsg="<?php echo $ld['confirm_password_can_not_be_less_than_6_digits'] ?>";
			}else if(api_reg_password!=value){
				api_reg_confirm_password_check=false;
		        api_reg_confirm_password_checkmsg="<?php echo $ld['the_two_passwords_do_not_match'] ?>";
			}else{
				api_reg_confirm_password_check=true;
				api_reg_confirm_password_checkmsg="";
			}
		}
	});
	
	$("#api_reg_confirm_password").change(function(){
		api_reg_confirm_password_check=false;
	});
	
	$("#api_login_form").submit(function(){
		var email=$("#api_log_email").val();
		var password=$("#api_log_password").val();
		if(email!=""&&password!=""){
			var email_reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; //验证邮箱的正则表达式
			var mobile_reg=/^1[3-9]\d{9}$/;
			var login_type="user_sn";
			if(email_reg.test(email)){
				login_type="email";
			}else if(mobile_reg.test(email)){
				login_type="mobile";
			}else{
				login_type="user_sn";
			}
			$("#api_login_form #login_type").val(login_type);
			var postdata=$(this).serialize();
			$.ajax({
	             type: "POST",
	             url: "/synchros/apibind",
	             data: postdata,
	             dataType: "json",
	             success: function(data){
	             		if(data.code==1){
                            alert('绑定成功');
	             			window.location.href=data.msg;
	             		}else{
	             			alert(data.msg);
	             		}
	             	}
	         	});
	        return false;
		}
		return false;
	});
})
</script>