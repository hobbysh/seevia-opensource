<style type="text/css">
	.am-form-label{font-weight:bold;}
	.am-panel-title{font-weight:bold;}
	.a1{width:100px;}
</style>
<div>
	<?php echo $form->create('ProductSaleStatement',array('action'=>'/view/'.$product_code,'name'=>"SearchForm","type"=>"get","class"=>"am-form am-form-horizontal"));?>
		<ul class="am-avg-lg-4 am-avg-md-2 am-avg-sm-1">
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					<div class="am-dropdown checkbox" data-am-dropdown  id="check_box1">
					  	<button class="am-btn am-btn-default am-dropdown-toggle a1"  id="reffer_name" data-am-dropdown-toggle>
							<?php echo $ld['order_reffer']?><span class="am-icon-caret-down"></span>
						</button>
					  	<ul class="am-dropdown-content am-avg-lg-1 am-avg-md-1 am-avg-sm-1">
							<?php $i=0; foreach($order_type as $k=>$v){$i++;?>
								<li class="aa0" style="margin-left:10px;">
									<div class='bb00 check<?php echo $i; ?>'>
										<label class="am-checkbox am-success" style="padding-top:0px;">
											<input type="checkbox" class = 'checkbox' id="checkbox1" value="0" name="<?php echo $k; ?>"  data-am-ucheck /><span><?php echo $order_type_arr[$k];?></span>
										</label>
									</div>
								</li>
								<?php foreach ($v as $kk=>$vv) {?>
									<li class="aa0" style="margin-left:20px;">
										<label class="am-checkbox am-success" style="padding-top:0px;">
											<input type="checkbox" class = 'checkbox1' data-am-ucheck   value="<?php echo $k.":".$kk; ?>" name="box1" <?php if(in_array($k.":".$kk,$type_arr)) echo 'checked';?>><span><?php echo $vv?></span>
										</label>
									</li>	
								<?php }?>
								<li class="dd1">
							    	<div class="am-form-group" style="margin:10px 0px;padding-left:5px;">
	                                    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	                                        <label class="am-checkbox am-success" style="padding-top:0px;">
	                                            <input type="checkbox" id="select1" class="bb2" data-am-ucheck />
	                                            <?php echo $ld['select_all']?>
	                                        </label>
	                                    </div>
		                                <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
											<input type="button" class="btn am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['submit']?>" onclick="ck_checkbox()" />
	                                    </div>
	                                </div>
							    </li>		
							<?php }?>
					  	</ul>
					</div>
				</div>
			</li>
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['time_of_payment']?></label>
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
					<input type="text" name="start_date" value="<?php echo $start_date;?>" class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center">-</div>	
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
					<input type="text" name="end_date" value="<?php echo $end_date;?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"/>
				</div>
			</li>
			<li style="margin-bottom:10px;">
				<div class="am-form-group">		
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['order_code']?></label>
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
						<input type="text" name="keywords" onkeypress="if(event.keyCode==13)formsubmit()" id="keywords" value="<?php echo @$keywords?>"/>
					</div>	
				</div>
			</li>
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick='formsubmit()' value="<?php echo $ld['search']?>" />
				</div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">	
					<input type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['export']?>" onclick="export_act()" />
				</div>
			</li>
					
		</ul>
	<?php echo $form->end();?>
	<div class="am-panel-group am-panel-tree">
		<div class="am-panel am-panel-default am-panel-header">
		    <div class="am-panel-hd">
		        <div class="am-panel-title">
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['order_reffer']?></div>
					<div class="am-u-lg-2 am-u-md-5 am-u-sm-5"><?php echo $ld['order_code']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['product_code']?></div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['product_name']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['sales_quantity']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['price']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['paymengts']?></div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['time_of_payment']?></div>	
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['consignee']?></div>	
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($order_list) && sizeof($order_list)){foreach($order_list as $k=>$v){?>
			<div>
				<div class="am-panel am-panel-default am-panel-body">
					<div class="am-panel-bd">
						<div class="am-u-lg-1 am-show-lg-only">
							<?php echo $order_type_arr[$v['Order']['type']];
								  echo " - ";
							      echo isset($order_type_arr[$v['Order']['type_id']])?$order_type_arr[$v['Order']['type_id']]:$v['Order']['type_id'];?>
						</div>
						<div class="am-u-lg-2 am-u-md-5 am-u-sm-5">
							<?php echo $v['Order']['order_code']?> 
							<p><a style="border-bottom: 1px solid #21964D;" href='/admin/orders/view/<?php echo $v["Order"]["id"]?>' target="_blank"><?php echo $ld['view_station']?></a>
							<?php if($v['Order']['type']=='taobao'){?>
							 <a style="border-bottom: 1px solid #21964D;" href='http://trade.taobao.com/trade/detail/trade_item_detail.htm?bizOrderId=<?php echo $v["Order"]["order_code"]?>' target='_blank'><?php echo $ld['view_taobao']?></a>
							<?php }?></p>		
				  		</div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['OrderProduct']['product_code']?></div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $v['OrderProduct']['product_name']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $v['OrderProduct']['product_quntity']-$v['OrderProduct']['refund_quantity']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
							<?php echo ($v['OrderProduct']['product_price'])*($v['OrderProduct']['product_quntity']-$v['OrderProduct']['refund_quantity'])+$v['OrderProduct']['adjust_fee']?>
						</div>
						<div class="am-u-lg-1 am-show-lg-only">
							<?php echo isset($payment_names[$v['Order']['payment_id']])?$payment_names[$v['Order']['payment_id']]:''?>
						</div>
						<div class="am-u-lg-2 am-show-lg-only"><?php echo $v['Order']['payment_time']?></div>
						<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['Order']['consignee']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		<?php }}else{?>
			<div class="am-text-center" style="margin:50px;"><label><?php echo $ld['no_order']?></label></div>
		<?php }?>
	</div>
	<?php if(isset($order_list) && sizeof($order_list)){?>
		<div id="btnouterlist" class="btnouterlist noprint"> <?php echo $this->element('pagers')?> </div>
	<?php }?>
</div>

<script type="text/javascript">

	
	$("#select1").click(function(){
		$(".aa0 input[type='checkbox']").prop("checked",$(this).prop('checked'));
	});
	$("#checkbox1").click(function(){
		$(".aa0 input[type='checkbox']").prop("checked",$(this).prop('checked'));
	});
	
	function ck_checkbox(){
	    var dropdown = $('#check_box1'),
	        data = dropdown.data('amui.dropdown');
	    if(data.active){
	        dropdown.dropdown('close');
	    }
	    var str=document.getElementsByName("box1");
	    var leng=str.length;
	    var chestr="";
	    for(var i=0;i<leng;i++){
	        if(str[i].checked == true)
	        {
	            chestr+=str[i].value+",";
	        };
	    };
	    return chestr;
	}
	
	
	function formsubmit(){
		var st = document.getElementsByName('start_date')[0].value,
			ed = document.getElementsByName('end_date')[0].value,
			kw=document.getElementById('keywords').value;
		var ta=ck_checkbox();
		ta = ta.substring(ta,ta.length-1);
		var str = "?st="+st+"&ed="+ed+"&kw="+kw+"&ta="+ta;
		window.location.href=encodeURI("/admin/product_sale_statements/view/<?php echo $product_code;?>"+str);
	}
	function export_act(){
		var st = document.getElementsByName('start_date')[0].value,
			ed = document.getElementsByName('end_date')[0].value,
			kw=document.getElementById('keywords').value;
		var ta=checkbox();
		ta = ta.substring(ta,ta.length-1);
		var str = "?st="+st+"&ed="+ed+"&kw="+kw+"&ta="+ta;
		url = "/admin/product_sale_statements/view/<?php echo $product_code;?>/export/"+str;
		window.location.href=encodeURI(url);
	}


</script>