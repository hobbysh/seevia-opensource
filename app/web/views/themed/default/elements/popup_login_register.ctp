<script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
<div class="am-modal am-modal-no-btn ajax_login_register" tabindex="-1" id="ajax_login_register">
  <div class="am-modal-dialog">
    <div class="am-modal-hd"><font><?php echo $ld['login'] ?></font>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      	
    </div>
  </div>
</div>
<style type="text/css">
.ajax_login_register .am-form-group{text-align:left;}
.ajax_login_register .api_wechat{display:none;}
.ajax_login_register .am-other-login{margin:0 auto 1rem;}
@media only screen
and (max-width : 640px){
	.ajax_login_register .am-form-group{margin-bottom:0.5rem;}
}
</style>
<script type="text/javascript">
function ajax_login_show(){
	var login_title="<?php echo $ld['login'] ?>";
	$.ajax({
		url:"<?php echo $html->url('/users/ajax_login'); ?>",
		type:'post',
		dataType:'html',
		success:function(data){
			$("#ajax_login_register .am-modal-hd font").html(login_title);
			$("#ajax_login_register .am-modal-bd").html(data);
			if(!$("#ajax_login_register").is(".am-modal-active")){
				$("#ajax_login_register").modal({'width':300});
			}
		}
	});
}

function ajax_register_show(){
	var register_title="<?php echo $ld['register'] ?>";
	$.ajax({
		url:"<?php echo $html->url('/users/ajax_register'); ?>",
		type:'post',
		dataType:'html',
		success:function(data){
			$("#ajax_login_register .am-modal-hd font").html(register_title);
			$("#ajax_login_register .am-modal-bd").html(data);
			if(!$("#ajax_login_register").is(".am-modal-active")){
				$("#ajax_login_register").modal({'width':300});
			}
		}
	});
}
</script>