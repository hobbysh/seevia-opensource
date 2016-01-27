<?php ob_start();?>
	<?php if(isset($pro_data)&&sizeof($pro_data)>0){foreach($pro_data as $k=>$v){ ?>
	<tr>
		<td><input type="checkbox" class="sku_pro_ck" value="<?php echo $k; ?>" /></td>
		<td><?php echo $v['name'] ?><input type="hidden" class="sku_pro_name_value" value="<?php echo $v['name'] ?>" /></td>
		<td><?php echo $v['code'] ?><input type="hidden" class="sku_pro_code_value" value="<?php echo $v['code']; ?>" /></td>
		<td><?php echo $v['shop_price'] ?><input type="hidden" class="sku_pro_shop_price_value" value="<?php echo $v['shop_price'] ?>" /></td>
		<td><?php echo $v['quantity'] ?><input type="hidden" class="sku_pro_quantity_value" value="<?php echo $v['quantity'] ?>" /></td>
		<?php foreach($sku_attr_codelist as $kk=>$vv){ ?>
		<td><?php echo $v[$vv]['value'] ?><input type="hidden" id="<?php echo $k.'_'.$kk ?>_value" class="pro_data_attr_<?php echo $kk; ?>" value="<?php echo $v[$vv]['value'] ?>" /><input type="hidden" id="<?php echo $k.'_'.$kk ?>_id" value="<?php echo $v[$vv]['id'] ?>" /></td>
		<?php } ?>
	</tr>
<?php } ?>
<?php }else{ ?>
	<tr>
		<td colspan="<?php echo (int)(5+sizeof($sku_attr_codelist)); ?>" class="no_data_found"><?php echo $ld['no_data_found']; ?></td>
	</tr>
<?php } ?>
<?php
$out1 = ob_get_contents();ob_end_clean();  
$result=array("flag"=>'1',"pro_data_html"=>$out1);
echo json_encode($result);
?>