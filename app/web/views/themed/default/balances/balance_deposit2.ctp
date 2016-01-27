<?php
    if(isset($wechatpay_type)&&isset($url2)){
        if($wechatpay_type){
?>
<div style="text-align: center;" id="wrapper">
	<div style="margin-left: 10px;color:#556B2F;font-size:30px;font-weight: bolder;">扫码支付</div><br/>
	<img alt="扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data=<?php echo urlencode($url2);?>" style="width:150px;height:150px;"/>
</div>
<script>
	function check_order(){
		$.ajax({
        	url: "/balances/check_order",
        	type: 'POST',
        	data: {'order_id': <?php echo $order_code;?>},
        	dataType: 'text',
        	success: function (result) {
        		if(result!="NO"){
        			if(typeof(wechat_pay_time)!="undefined"){
					window.clearInterval(wechat_pay_time);
				}
        			var msg = '你的订单:'+<?php echo $order_code;?>+'&nbsp;支付成功';
        			var back_url = '/orders/view/'+result;
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
	
	var wechat_pay_time=window.setInterval("check_order()",3000);
</script>
<?php }else{ ?>

<div class="am-g am-text-center"  id="wrapper">
    <i class="am-icon-spinner am-icon-pulse am-icon-lg"></i>
</div>
<script type="text/javascript">
callpay();
function jsApiCall()
{
	WeixinJSBridge.invoke(
		'getBrandWCPayRequest',
		<?php echo $url2; ?>,
		function(res){
            if(res.err_msg == "get_brand_wcpay_request:ok") {
			    check_order();
            }else{
                window.location.href="<?php echo $html->url('/orders/'); ?>";
            }
		}
	);
}

function callpay()
{
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

function check_order(){
	$.ajax({
    	url: "/balances/check_order",
    	type: 'POST',
    	data: {'order_id': <?php echo $order_code;?>},
    	dataType: 'text',
    	success: function (result) {
    		if(result!="NO"){
    			var msg = '你的订单:'+<?php echo $order_code;?>+'&nbsp;支付成功';
    			var back_url = '/orders/view/'+result;
    			var html='<div id="sidebarbox"><div class="error" style="height:200px;"><ul><li>&nbsp;&nbsp;<a href="'+back_url+'" class="ojb">'+msg+'</a></li></ul></div></div>';
    			$("#wrapper").html(html);
    			window.setTimeout('load("' + back_url + '")',2000); 
    		}
        }
	});
}
</script>
<?php  }?>
<?php }else if(isset($pay_form_txt)&&$pay_form_txt!=""){ ?>
<div id="payform_show">
	<?php echo $pay_form_txt; ?>
</div>
<script type="text/javascript">
var pay_url=$("#payform_show form").prop('action');
var pay_data=$("#payform_show form").serialize();
var pay_link=pay_url+"&"+pay_data;
var tmpForm = $("<form action='/pages/redirect_link' method='get'><input type='hidden' value='"+pay_link+"' name='redirect_link_url'/></form>");
$("#payform_show").append(tmpForm);
tmpForm.submit();
</script>
<?php }else{ ?>
<script type="text/javascript">
window.location.href="/";
</script>
<?php } ?>