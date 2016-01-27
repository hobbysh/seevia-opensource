<style>
.jbxx .am-form-group{margin-bottom:10px;}
</style>
<div class="am-g admin-content  am-user">
	<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
	  <ul class="am-list admin-sidebar-list">
	    <li><a data-am-collapse="{parent: '#accordion'}" href="#basic_information"><?php echo $ld['basic_information']?></a></li>
	  </ul>
	</div>
<?php echo $form->create('/users',array('action'=>'add','id'=>'user_edit_form','name'=>'user_edit','type'=>'POST','onsubmit'=>"return payment_check();"));?>
	<input type="hidden" name="data[User][id]" id="user_id" value="0" />
	<div class="am-panel-group admin-content" id="accordion">
	  <div class="am-panel am-panel-default">
	    <div class="am-panel-hd">
	      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#basic_information'}"><?php echo $ld['basic_information'] ?></h4>
	    </div>
	    <div id="basic_information" class="am-panel-collapse am-collapse am-in">
	      <div class="am-panel-bd am-form-detail am-form am-form-horizontal jbxx">
	        	<div class="am-form-group">
		          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['user_name'] ?></label>
		          <div class="am-u-lg-5 am-u-md-8 am-u-sm-8"><input type="text" name="data[User][user_sn]" onchange="check_user_sn(this)" id="user_sn" ></div>
		        </div>
				<div class="am-form-group">
		          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['real_name'] ?></label>
		          <div class="am-u-lg-5 am-u-md-8 am-u-sm-8"><input type="text" name="data[User][first_name]" id="user_first_name" value=""></div>
		        </div>
		        <div class="am-form-group">
		          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['member_name'] ?></label>
		          <div class="am-u-lg-5 am-u-md-8 am-u-sm-8"><input type="text" name="data[User][name]" value="" id="user_name" /></div>
		        </div>
		        <div class="am-form-group">
		          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['email'] ?></label>
		          <div class="am-u-lg-5 am-u-md-8 am-u-sm-8"><input type="text" id="user_email" name="data[User][email]" value=""/></div>
		        </div>
		        <div class="am-form-group">
		          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['mobile'] ?></label>
		          <div class="am-u-lg-5 am-u-md-8 am-u-sm-8"><input type="text" id="user_mobile" name="data[User][mobile]" value=""/></div>
		        </div>
		        <div class="am-form-group">
		          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['gender'] ?></label>
		          <div class="am-u-lg-5 am-u-md-8 am-u-sm-8">
		            <label class="am-radio-inline"><input type="radio" name="data[User][sex]" value="0" checked/><?php echo $ld['secrecy']?></label>
						<label class="am-radio-inline"><input type="radio" name="data[User][sex]" value="1"/><?php echo $ld['male']?></label>
						<label class="am-radio-inline"><input type="radio" name="data[User][sex]" value="2"/><?php echo $ld['female']?></label>
		          </div>
		        </div>
		       	<div class="am-form-group">
		          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['birthday'] ?></label>
		          <div class="am-u-lg-5 am-u-md-8 am-u-sm-8"><input type="date" class="am-form-field am-input-sm" name="data[User][birthday]" value="" /></div>
		        </div>
		       	<div class="am-form-group">
		          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['discount'] ?></label>
		          <div class="am-u-lg-5 am-u-md-8 am-u-sm-8"><input type="text" id="admin_note2" name="data[User][admin_note2]" value="" /></div>
		        </div>
		       	<div class="am-form-group">
		          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['note2'] ?></label>
		          <div class="am-u-lg-5 am-u-md-8 am-u-sm-8"><textarea id="user_admin_note" name="data[User][admin_note]" /></textarea></div>
		        </div>
		       	
		       	<div class="am-form-group">
		          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
		          <div class="am-u-lg-5 am-u-md-8 am-u-sm-8">&nbsp;</div>
		        </div>
		       	
		       	<div class="am-form-group">
		          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['new_password'] ?></label>
		          <div class="am-u-lg-5 am-u-md-8 am-u-sm-8"><input type="password" id="user_new_password" name="data[User][new_password]" value="<?php if(isset($configs['password-defult'])&&$configs['password-defult']!=""){echo $configs['password-defult'];}else{echo '123456';}?>" style="float:left;" /><em style="position: relative;top:10px;color:red;" >*</em><div style="clear:both;"><?php echo $ld['initial_password'];?>:<?php if(isset($configs['password-defult'])&&$configs['password-defult']!=""){echo $configs['password-defult'];}else{echo '123456';}?></div>
		           </div>
		        </div>
		       	<div class="am-form-group">
		          <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['confirm_password_again'] ?></label>
		          <div class="am-u-lg-5 am-u-md-8 am-u-sm-8"><input type="password" id="user_new_password2" name="data[User][new_password2]" value="<?php if(isset($configs['password-defult'])&&$configs['password-defult']!=""){echo $configs['password-defult'];}else{echo '123456';}?>" style="float:left;"/><em style="position: relative;top:10px;color:red;">*</em></div>
		        </div>
	      </div>
		  <div class="btnouter">
				<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button><button type="reset" class="am-btn am-btn-success am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
		  </div>
	    </div>
	  </div>
	</div>
	<?php echo $form->end();?>
</div>
<style type="text/css">
.am-g.admin-content{margin:0 auto;}
.am-form-label{text-align:right;}
.am-form .am-form-group input[type="text"],.am-form .am-form-group input[type="password"],.am-form .am-form-group input[type="date"],.am-form .am-form-group select,.am-form .am-form-group textarea{width:96%;}
.am-form .am-form-group:last-child{margin-bottom:0;}
</style>
<script type="text/javascript">
var user_sn_check=false;
					
function check_user_sn(obj){
	user_sn_check=false;
	var user_sn=obj.value;
	if(user_sn==""){return false;}
	$.ajax({url: "/admin/users/check_user_sn_exist/",
		type:"POST",
		data:{'user_sn':user_sn},
		dataType:"json",
		success: function(data){
			try{
				if(data.code==1){
					user_sn_check=true;
				}else{
					alert(data.msg);
				}
			}catch (e){
				alert(j_object_transform_failed);
			}
  		}
  	});
	
}
function payment_check(){
	if(document.getElementById('user_sn').value==''){
		alert("<?php echo $ld['fill_in_user_name']?>");
		return false;
	}
	if(user_sn_check==false){
		alert("<?php echo $ld['username_already_exists']?>");
		return false;
	}
	if(document.getElementById('user_name').value==''){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['nickname']); ?>");
		return false;
	}
	if(document.getElementById('user_email').value==''&&document.getElementById('user_mobile').value==""){
		alert("邮箱和手机必须填一项！");
		return false;
	}
	if(document.getElementById('user_mobile').value!=""){
		var mobile=document.getElementById('user_mobile').value;
		if(!/^1[3-9]\d{9}$/.test(mobile)){
			alert("手机格式不正确");return false;
		}
	}
	var email=document.getElementById('user_email').value;
	if(email!=""){
		var myreg =/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; //验证邮箱的正则表达式
		if(!myreg.test(email)){
 	 		alert("<?php echo $ld['note'];?><?php echo $ld['enter_valid_email']?>");
 			return false;
 		}
	}
	if(document.getElementById('user_new_password').value==''){
		alert("<?php echo $ld['note'];?><?php echo $ld['please_fill_user_password']?>");
		return false;
	}
	if(document.getElementById('user_new_password').value!=document.getElementById('user_new_password2').value){
		alert("<?php echo $ld['note'];?><?php echo $ld['password_different']?>");
		return false;
	}
	return true;
}
</script>