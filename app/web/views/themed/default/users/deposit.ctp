<?php
	$is_wechat=true;
	if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
		$is_wechat=false;
	}
?>
<div class="am-cf am-user">
<h3><?php echo $ld['user_deposit'] ?></h3>
</div>
<div class="am-u-ser-recharge">
<?php echo $form->create('/users',array('action'=>'setbalance/','class'=>'am-form am-form-horizontal','id'=>'moneyform','target'=>'_blank','type'=>'POST'));?>
	<div class="am-form-detail ">
		<div class="am-form-group user_balance_title">
          <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['uses_of_funds'] ?></label>
          <div class="am-u-lg-8 am-u-md-6 am-u-sm-6 ">
			<div class="show_balance"><?php echo $user_list['User']['balance']; ?><?php echo $ld['app_yuan'] ?></div>
		  </div>
        </div>
		<?php if(isset($configs['payment_point']) && $configs['payment_point']==1){?>
    	<div class="am-form-group">
          <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['amount'] ?></label>
          <div class="am-u-lg-8 am-u-md-6 am-u-sm-6">
			<input type="text" name="data[pay][money]" id="balance_money" value="100" maxlength="7" />
		  </div>
        </div>
    	<div class="am-form-group">
          <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label" style="padding-top:25px;"><?php echo $ld['mode_of_payment'] ?></label>
          <div class="am-u-lg-9 am-u-md-9 am-u-sm-8 am-btn-group radio-btn" data-am-button>
    			<?php if(isset($payment_list)&&sizeof($payment_list)>0){ foreach($payment_list as $k=>$v){ ?>
    			<label class="am-btn am-btn-secondary <?php echo $k==1?'am-active':''; ?>">
				    <input type="radio" class="payments pay_<?php echo $v['Payment']['code'] ?>" name="data[pay][payment_type]" <?php echo $k==1?'checked':''; ?> value="<?php echo $v['Payment']['id']; ?>"/><img src="<?php echo $v['Payment']['logo'] ?>" ><?php //echo $v['PaymentI18n']['name']; ?>
				</label>
				<?php }} ?>
    		</div>
        </div>
        <div class="am-form-group">
          <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
          <div class="am-u-lg-8 am-u-md-8 am-u-sm-12"><input class="am-btn am-btn-primary am-btn-block pay-btn" type="submit" value="<?php echo $ld['payment'] ?>" /></div>
        </div>
		<?php }?>
	</div>
<?php echo $form->end();?>
</div>
<div style="clear:both;"></div>
<div class="am-cf am-user">
	<h3><?php echo $ld['user_account_details'] ?></h3>
</div>
<div class="am-u-ser-balance-log">
	<?php if(isset($user_balance_log)&&sizeof($user_balance_log)>0){ ?>
	<table class="am-table am-table-striped am-table-hover table-main">
		  <thead>
              <tr>
                <th class="table-date"><?php echo $ld['date']; ?></th>
				<th style="min-width:44px;" class="table-type"><?php echo $ld['type']; ?></th>
				<th class="table-amount"><?php echo $ld['amount']; ?></th>
				<th class="table-desc"><?php echo $ld['remark']; ?></th>
              </tr>
          </thead>
          <tbody>
			<?php
				foreach($user_balance_log as $k=>$v){
			?>
				<tr>
					<td><?php echo $v['UserBalanceLog']['created']; ?></td>
					<td style="min-width:44px;"><?php if($v['UserBalanceLog']['log_type'] == 'O'){?>
								订单号
							<?php }elseif($v['UserBalanceLog']['log_type'] == 'B'){?>
								<?php echo $v['UserBalanceLog']['amount']>0?"充值":'扣除'; ?>
							<?php }elseif($v['UserBalanceLog']['log_type'] == 'R'){?>
								退款
							<?php }elseif($v['UserBalanceLog']['log_type'] == 'A'){?>
								管理员操作
							<?php }?></td>
					<td><?php echo $v['UserBalanceLog']['amount']; ?></td>
					<td><?php echo $v['UserBalanceLog']['system_note']; ?></td>
				</tr>
			<?php } ?>
          </tbody>
    </table>
    <?php }else{ ?>
		<p class="am-text-center"><?php echo $ld['no_record'];?></p>
	<?php } ?>
    <?php echo $this->element('pager'); ?>
</div>
		
<div class="am-modal am-modal-no-btn" tabindex="-1" id="wechat_ajax_payaction">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      <div class="am-text-center"><img src='/theme/default/images/loading.gif'></div>
    </div>
  </div>
</div>
		

<script type="text/javascript">
var is_wechat="<?php echo $is_wechat; ?>";
$(function(){
	$("#balance_money").on("keypress",function(e){
        if (e.charCode === 0) return true;  //非字符键 for firefox
        var code = (e.keyCode ? e.keyCode : e.which);  //兼容火狐 IE    
        return (code >= 48 && code <= 57)||code==46;
    });
    
    $("#balance_money").on("blur",function(e){
        var input = $(this);
        var v = $.trim(input.val());
        if(v.length>0&&v!="0"){
            var reg1 = new RegExp("^[0-9]+(.[0-9]{1})?$", "g");
            var reg2 = new RegExp("^[0-9]+(.[0-9]{2})?$", "g");
            if (!reg1.test(v)&&!reg2.test(v)) {
                alert(js_amount_format_error);
                input.val("0");
            }
        }
    });
    
    $("#moneyform").submit(function(){
    	var input = $("#balance_money");
        var v = $.trim(input.val());
        if(v.length>0&&v!="0"){
            var reg1 = new RegExp("^[0-9]+(.[0-9]{1})?$", "g");
            var reg2 = new RegExp("^[0-9]+(.[0-9]{2})?$", "g");
            if (!reg1.test(v)&&!reg2.test(v)) {
                alert(js_amount_format_error);
                input.val("0");
                return false;
            }else{
            	var typeradio=$('input[name="data[pay][payment_type]"]:checked');
			if(typeradio.length>0){
				var payment_code=$(typeradio).is(".pay_weixinpay");
				if(payment_code&&is_wechat){
					try{
						wechat_ajax_payaction();
						return false;
					}catch(Error){
						alert(Error);
						return false;
					}
				}else{
					return true;
				}
			}else{
				alert("<?php echo $ld['fill_payment_method'] ?>");
				return false;
			}
            }
        }else{
        	alert(js_amount_empty);
        	return false;
        }
    });
});

function wechat_ajax_payaction(){
	if(typeof(wechat_pay_time)!="undefined"){
		window.clearInterval(wechat_pay_time);
	}
	var post_data=$("#moneyform").serialize();
	$.ajax({
	    	url: "/users/setbalance",
	    	type: 'POST',
	    	data: post_data,
	    	dataType: 'html',
	    	success: function (result) {
	        	$("#wechat_ajax_payaction").modal({width:350,height:350});
	        	$("#wechat_ajax_payaction .am-modal-bd").html(result);
	        	
	        	$('#wechat_ajax_payaction').on('closed.modal.amui', function(){
			  	if(typeof(wechat_pay_time)!="undefined"){
					window.clearInterval(wechat_pay_time);
				}
			});
	        }
	});
}
</script>