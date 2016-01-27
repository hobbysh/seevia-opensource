<?php
    $payment_flag=true;
    if($payment_code=="weixinpay"&&isset($pay_url)&&$wechatpay_type){
        $payment_flag=false;
?>
<div style="text-align: center;" id="wrapper">
	<div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">扫码支付</div><br/>
	<img alt="扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($pay_url);?>" style="width:150px;height:150px;"/>
</div>
<?php
    }else if($payment_code=="weixinpay"&&isset($pay_url)){
        $payment_flag=false;
?>
<div class="am-g am-text-center">
    <i class="am-icon-spinner am-icon-pulse am-icon-lg"></i>
</div>
<script type="text/javascript">
callpay();
function jsApiCall(){
	WeixinJSBridge.invoke(
		'getBrandWCPayRequest',
		<?php echo $pay_url; ?>,
		function(res){
	            if(res.err_msg == "get_brand_wcpay_request:ok") {
			    	checkwechatpay();
	            }else{
                		window.location.href="<?php echo $html->url('/users/deposit'); ?>";
	            }
		}
	);
}

function callpay(){
	if (typeof WeixinJSBridge == "undefined"){
	    if( document.addEventListener ){
	        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
	    }else if (document.attachEvent){
	        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
	        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
	    }
	}else{
	    jsApiCall();
	}
}

function checkwechatpay(){
	$.ajax({
    	url: "/users/checkwechatpay",
    	type: 'POST',
    	data: {'payment_api_id': <?php echo $payment_api_id;?>},
    	dataType: 'text',
    	success: function (result) {
            if(result=='1'){
                var msg = '充值成功';
    			var back_url = '/users/deposit/';
    			var html='<div id="sidebarbox"><div class="error" style="height:200px;"><ul><li>&nbsp;&nbsp;<a href="" class="ojb">'+msg+'</a></li></ul></div></div>';
    			$("#wrapper").html(html);
    			window.setTimeout('load("' + back_url + '")',2000); 
            }
        }
	});
}
</script>
<?php
    }else if(isset($pay_url)){
        echo $pay_url;
    }else{
        $payment_flag=false;
    }
?>
<?php if($payment_flag){ ?>
<style type="text/css">
form{display:none;}
</style>
<script src="/plugins/AmazeUI/js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#payform input[type=submit]").click();
	});
</script>
<?php }else if(isset($payment_api_id)){  ?>
<script type="text/javascript">
function checkwechatpay(){
	$.ajax({
	    	url: "/users/checkwechatpay",
	    	type: 'POST',
	    	data: {'payment_api_id': <?php echo $payment_api_id;?>},
	    	dataType: 'text',
	    	success: function (result) {
	            if(result=='1'){
	            	if(typeof(wechat_pay_time)!="undefined"){
					window.clearInterval(wechat_pay_time);
				}
	                	var msg = '充值成功';
	    			var back_url = '/users/deposit/';
	    			var html='<div class="am-text-center" style="padding:30px 0px;">'+msg+'</div>';
	    			$("#wechat_ajax_payaction .am-modal-bd").html(html);
	    			window.setTimeout('load("' + back_url + '")',2000); 
	            }
	      }
	});
}

function load(URL){
	var host=window.location.host;
	window.location = "http://"+host+URL;
}

var wechat_pay_time=window.setInterval("checkwechatpay()",3000);
</script>
<?php } ?>