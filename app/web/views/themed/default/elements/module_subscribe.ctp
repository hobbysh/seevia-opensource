<div class="am-g am-g-fixed" style="padding-top:10px;">
  <form class="am-form am-form-horizontal">
    <div class="am-u-lg-1">&nbsp;</div>
    <div class="am-u-sm-8"><input id="email" name="email" type="text" value="" placeholder="<?php echo $ld['please_enter_your_email_address']?>"></div>
	<div class="am-u-sm-3">
		<input id="subscribe"  type="button" class="am-btn am-btn-primary am-btn-sm" value="<?php echo $ld['subscribe']?>">
	</div>
  </form>
</div>
<script type="text/javascript">
$("#subscribe").click(function(){
	var email=$("#email").val();
	var reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w{2,4}$/;
	
	if(email!="" && reg.test(email)){
		$.ajax({
		    type: "POST",
		    url:"/newsletter/add/",
		    data:{"email":email},
			dataType:"json",
		    async: false,
		    success: function(data) {
				if(data.type==0){
					alert(data.msg);
					$("#email").val("");
				}else{
					alert(data.msg);
				}
		    }
		});
	}else{
		if(email==""){
			alert("<?php echo $ld['enter_common_email']?>");
		}else{
			alert(j_format_is_incorrect);
		}
	}
});
</script>