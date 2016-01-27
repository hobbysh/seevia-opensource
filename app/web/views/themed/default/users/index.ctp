<div class="am-cf am-user">
	<h3><?php echo $ld['user_center'] ?></h3><hr/>
</div>
<div class="am-u-ser-index">
	<table class="am-table admin-content-table">
		<tr>
			<td><h4 style="color:#0e90d2;"><?php echo sprintf($ld['user_welcome'],"");echo isset($user_list["User"]["name"])?$user_list["User"]["name"]:$user_list["User"]["user_sn"];?></h4>
				<table class="am-table admin-content-table user-info-table" style="margin-left:16px;">
					<tr >
						<td><?php echo $ld["member_level"] ?>: <span class="colorblue"><?php echo isset($user_list["User"]["rank_name"])?$user_list["User"]["rank_name"]:$ld["ordinary_members"];?></span></td>
					<?php if(constant("Product")=="AllInOne"){ ?>
						<td><?php echo $ld["consume_this_month"] ?>: <span class="colorred"><?php echo $svshow->price_format($order_month_count,$configs['price_format']);?></span></td>
					<?php } ?>
					</tr>
					<tr>
						<td><?php echo $ld['account_balance'] ?>: <span class="colorred"><?php echo $svshow->price_format($user_list['User']["balance"],$configs['price_format']);?></span></td>
					<?php if(constant("Product")=="AllInOne"){ ?>
						<td><?php echo $ld["total_consumption"] ?>: <span class="colorred"><?php echo $svshow->price_format($order_all_count,$configs['price_format']);?></span></td>
					<?php } ?>
					</tr>
					<tr>
						<td><?php echo $ld["account_points"] ?>: <span><?php echo $user_list["User"]["point"]?>
							</span></td>
					<?php if(constant("Product")=="AllInOne"){ ?>
						<td></td>
					<?php } ?>
					</tr>
				</table>
				<?php if(constant("Product")=="AllInOne"){ ?>
                <hr >
				<table class="am-table admin-content-table" style="margin-left:16px;">
					<tr>
						<td><b><?php echo $ld["reminder"] ?></b></td>
						<td><a href="/orders/?payment_status=0"><?php echo $ld['unpaid_orders'] ?> (<?php echo $pay_orderscount;?>)</a></td>
						<td><?php echo $ld['unconfirmed_orders'] ?> (<?php echo $receiving_orderscount;?>)</td>
						<td><?php echo $ld["reviews_of_goods_to_be"] ?> (<a class="colorblue" href="javascript:void(0);<?php //echo $html->url('/comments'); ?>"><?php echo sizeof($pro_comments);?></a>)</td>
					</tr>
				</table>
			    <?php } ?>
			</td>
		</tr>
	</table>
	<?php if(!empty($my_orders)){?>
		<h4 style="margin-top:16px;color:#0e90d2;"><?php echo $ld['recent_orders'] ?></h4>
        <div class="order_table">
		<table class="am-table admin-content-table" >
			<tr>
				<th ><?php echo $ld["order_no."] ?><br /><?php echo $ld["order_time"] ?><br /><?php echo $ld['consignee'] ?></th>
				<th><?php echo $ld["payment_method"] ?><br /><?php echo $ld["order_status"] ?><br /><?php echo $ld['order_total'] ?></th>		
				<th class="am-text-center"><?php echo $ld["operation"] ?></th>
			</tr>
			<?php foreach($my_orders as $k=>$v){ ?>
		
			<tr>
		 
				<td ><?php echo $svshow->link($v['Order']['order_code'],'/orders/view/'.$v['Order']['id']);?><br /><?php echo date("Y-m-d",strtotime($v['Order']['created']));?><br /><?php echo $v['Order']['consignee'];?></td>
				
				<td style="white-space:nowrap;"><?php echo $v["Order"]["payment_name"];?>
					<br />
					<?php if($v['Order']['status']==2){ ?>
					<?php echo $ld['order_canceled']?>
					<?php  }elseif($v['Order']['payment_status']==0){?>
					<?php if($v['Order']['payment_is_cod']==1 && $v['Order']['shipping_status']==1){echo $ld['order_shipped'];}else{ echo $ld['order_unpaid'];}?>
					<?php }elseif($v['Order']['status']==1 && $v['Order']['shipping_status']==0 && $v['Order']['payment_status']==2){ ?>
					<?php echo $ld['order_processing']?>
					<?php }elseif($v['Order']['status']==1 && $v['Order']['shipping_status']==1 && $v['Order']['payment_status']==2){ ?>
					<?php echo $ld['order_shipped']?>
					<?php }elseif($v['Order']['status']==1 && $v['Order']['shipping_status']==2 && $v['Order']['payment_status']==2){ ?>
					<?php echo $ld['order_complete']?>
					<?php }elseif($v['Order']['status']==1 && $v['Order']['shipping_status']==3 && $v['Order']['payment_status']==2){ ?>
					<?php echo $ld['order_processing']?>
					<?php }elseif($v['Order']['status']==4){echo $ld['product_returns'];}elseif($v['Order']['shipping_status']==3){echo $ld['order_processing'];}?><br />
					<?php echo $svshow->price_format($v['Order']['need_paid'],$configs['price_format']);?>
				</td>	
				<td>
						<?php echo $form->create('/balances',array('action'=>'balance_deposit2','id'=>'payform'.$k,'style'=>'text-align:center;','type'=>'POST'));?>
						<span class="colorblue"><?php echo $svshow->link($ld["details"],'/orders/view/'.$v['Order']['id']);?></span><br />
						<?php if($v['Order']['status']==1 && $v['Order']['shipping_status']==1 && $v['Order']['payment_status']==2){ ?>
						<span class="colorblue"> <?php //echo $svshow->link($ld["confirm_receipt"],'/orders/receiving_order/'.$v['Order']['id']);?></span><br />
						<?php  }else if($v['Order']['status']!=2 && $v['Order']['shipping_status']!=2 && $v['Order']['payment_status']!=2 ){ ?>
						<span class="colorblue"> <?php echo $svshow->link($ld["cancel_order"],'/orders/cancle_order/'.$v['Order']['id']);?></span><br />
						<?php $currency_code='';
								if($v['Order']['order_currency']=='Euro'){
									$currency_code='EUR';
								}else if($v['Order']['order_currency']=='Dollar'){
									$currency_code='USD';
								}else if($v['Order']['order_currency']=='Pound'){
									$currency_code='GBP';
								}else if($v['Order']['order_currency']=='CA_Dollar'){
									$currency_code='CAD';
								}else if($v['Order']['order_currency']=='AU_Dollar'){
									$currency_code='AUD';
								}else if($v['Order']['order_currency']=='Francs'){
									$currency_code='CHF';
								}else if($v['Order']['order_currency']=='hk'){
									$currency_code='HKD';
								}else if($v['Order']['order_currency']=='CNY'){
									$currency_code='CNY';
								}
							?>
							
						
						<?php if($v['Order']['payment_is_cod']== 0){ ?><span class="colorblue"> <?php echo $svshow->link($ld['pay_now2'],"javascript:document.getElementById('payform".$k."').submit();",array('class'=>'red_txt'));?></span><?php } ?>
						<?php }?>
						<input name='amount_num' type='hidden' value="<?php echo $v['Order']['need_paid'];?>">
						<input name='payment_id' type='hidden' value="<?php echo $v['Order']['payment_id'];?>">
						<input type='hidden' name='cmd' value='_xclick'/>
						<input type='hidden' name='business' value='order@idealhere.com'/>
						<input type='hidden' name='item_name' value='<?php echo $v['Order']['order_code'];?>'/>
						<input type='hidden' name='amount' value='<?php echo $v['Order']['total'];?>'/>
						<input type='hidden' name='currency_code' value='<?php echo $v['Order']['order_currency'];?>'/>
						<input type='hidden' name='return' value='<?php echo $server_host;?>/'/>
						<input type='hidden' name='invoice' value='<?php echo $v['Order']['id'];?>'/>
						<input type='hidden' name='charset' value='utf-8'/>
						<input type='hidden' name='no_shipping' value='1'/>
						<input type='hidden' name='no_note' value='1' />
						<input type='hidden' name='notify_url' value='<?php echo $server_host;?>/'/>
						<input type='hidden' name='rm' value='2'/>
						<input type='hidden' name='cancel_return' value='<?php echo $server_host;?>/'/>
					<?php echo $form->end();?></td> 	
			</tr>
			<?php }?>
		</table>
        </div>
	<?php } ?>
	<?php if(isset($pro_like)&&sizeof($pro_like)>0){?>
		<h4 style="margin-top:16px;color:#0e90d2;"><?php echo $ld['huess_you_like'] ?></h4>
		<div class="am-g am-tab-panel" style="border-top:1px solid #ddd;padding-top:15px;">
			<?php foreach($pro_like as $k=>$v){ ?>
					<div class="am-u-lg-3 am-u-md-4 am-u-sm-6 detail-mb">
			        <h3 class="detail-h3">
			            <a href="<?php echo $html->url('/products/'.$v['Product']['id']); ?>" title="<?php echo $v['ProductI18n']['name'] ?>"><img src="<?php echo $v['Product']['img_detail']!=''?$v['Product']['img_detail']:$configs['products_default_image']; ?>" alt=""></a>
			        </h3>
			        <p class="detail-p"><a title="<?php echo $v['ProductI18n']['name'] ?>" href="<?php echo $html->url('/products/'.$v['Product']['id']); ?>"><?php echo $v['ProductI18n']['name'] ?></a></p>
			    </div>
				<?php } ?>
		</div>
	<?php } ?>
</div>
