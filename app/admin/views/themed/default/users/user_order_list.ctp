<table class="am-table  table-main" id="user_order_ajaxdata">
    <tbody>
	<?php if(isset($order_list)&&sizeof($order_list)>0){foreach($order_list as $v){ ?>
		<tr>
			<th width="10%"><?php echo $ld['order_code'] ?></th>
			<th width="40%"><?php echo $ld['orders_time'] ?></th>
			<th width="25%"><?php echo $ld['consignee'] ?></th>
			<th width="15%"><?php echo $ld['order_status'] ?></th>
		</tr>
		<tr>
			<td><?php echo $v['Order']['order_code'] ?></td>
			<td><?php echo $v['Order']['created'] ?></td>
			<td><?php echo $v['Order']['consignee'] ?></td>
			<td><?php if( $v['Order']['status']!=1 ){?>
					<?php echo $Resource_info["order_status"][$v['Order']['status']];?>
					<?php }elseif( $v['Order']['payment_status']==0 &&$v['Order']['paymenttype']==1){?>
					<?php echo $Resource_info["shipping_status"][$v['Order']['shipping_status']];?>
					<?php }elseif( $v['Order']['payment_status']!=2 &&$v['Order']['shipping_status']==0){?>
					<?php echo $Resource_info["payment_status"][$v['Order']['payment_status']];?>
					<?php }else{?>
					<?php echo $Resource_info["shipping_status"][$v['Order']['shipping_status']];?>
					<?php }?></td>
		</tr>
		<tr>
			<th colspan="2"><?php echo $ld['name'] ?></th>
			<th><?php echo $ld['products_number'] ?></th>
			<th><?php echo $ld['price'] ?></th>
		</tr>
		<?php if(isset($order_product_info[$v['Order']['id']])){foreach($order_product_info[$v['Order']['id']] as $vv){ ?>
		<tr>
			<td><?php echo empty($pro_list[$vv['OrderProduct']['product_id']])?"":$svshow->seo_link(array('type'=>'P','id'=>$vv['OrderProduct']['product_id'],'name'=>'','sub_name'=>$vv['OrderProduct']['product_name'],'img'=>$pro_list[$vv['OrderProduct']['product_id']],'style'=>'width: 100px;')); ?></td>
			<td><?php echo $svshow->seo_link(array('type'=>'P','id'=>$vv['OrderProduct']['product_id'],'name'=>$vv['OrderProduct']['product_name'],'sub_name'=>$vv['OrderProduct']['product_name'])); ?><br /><?php echo $vv['OrderProduct']['product_code']; ?><br /><?php echo $vv['OrderProduct']['product_attrbute']; ?>
			</td>
			<td><?php echo $vv['OrderProduct']['product_quntity']; ?></td>
			<td><?php echo $vv['OrderProduct']['product_price']; ?></td>
		</tr>
		<?php }} ?>
	<?php }}else{ ?>
		<tr>
			<td colspan="5"><?php echo $ld['no_orders']?></td>
		</tr>
	<?php } ?>
   	</tbody>
</table>
<div id="btnouterlist" class="btnouterlist"><?php echo $this->element('pagers'); ?></div>
<script type="text/javascript">
$(".turn_page a").click(function(){
	var dataurl=$(this).attr("href");
	$.ajax({ url: dataurl,
			type:"POST",
			dataType:"html",
			success: function(data){
				$("#user_order_ajaxdata").parent().html(data);
      		}
      	});
	return false;
});
</script>