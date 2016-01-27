<table class="am-table">
	<?php if(isset($show_attr_list)){foreach($show_attr_list as $k=>$v){ ?>
<?php if(isset($user_style_value_data_list[$v['attribute_id']]) && ($v['default_value']!=$user_style_value_data_list[$v['attribute_id']])){$flag=true;}?>
	<tr class='type_attr_list'>
		<td width="20%"><?php echo $v['attr_name']; ?></td>
		<td width="15%"><span class="default_value"><?php echo $v['default_value']; ?></span>
			<input class="default_value" type="hidden" value="<?php echo $v['default_value']; ?>" />
			<input class="attr_value" type="hidden" name="data[UserStyleValue][<?php echo $k;?>][attribute_value]" value="<?php echo isset($user_style_value_data_list[$v['attribute_id']])?$user_style_value_data_list[$v['attribute_id']]:$v['default_value'];?>" />
			<input class="attr_id" type="hidden" name="data[UserStyleValue][<?php echo $k;?>][attribute_id]" value="<?php echo $v['attribute_id']; ?>" />
		</td>
		<td width="35%"><?php if(isset($v['select_value'])&&$v['attr_type']=='2'){
			$select_value_arr=explode("\r\n",$v['select_value']);if(sizeof($select_value_arr)>1){?>
			<select id="attr_edit_value_<?php echo $k; ?>" class="select_change">
				<?php foreach($select_value_arr as $kk=>$vv){
					$user_style_value=isset($user_style_value_data_list[$v['attribute_id']])?$user_style_value_data_list[$v['attribute_id']]:0;
					$attr_type_txt=$vv;
					if($vv>0){$attr_type_txt='增加'.$vv;}else if($vv<0){$attr_type_txt='减少'.($vv*-1);}else{$attr_type_txt='无改动';}
					$sel_txt="";
					if($user_style_value){
						$difference=$user_style_value-$attrvaluelist[$v['attribute_id']];
						if($difference==$vv){$sel_txt=" selected='selected'";}
					}else if($vv==0){
						$sel_txt=" selected='selected'";
					}
				?>
					<option value="<?php echo trim($vv); ?>" <?php echo $sel_txt; ?>><?php echo $attr_type_txt; ?></option>
				<?php } ?>
			</select>
		 <?php }}else if(isset($v['select_value']) && $v['attr_type']=='1'){
		 	 	$select_value_arr=explode("\r\n",$v['select_value']);if(sizeof($select_value_arr)>1){
		 	 	$user_style_value=isset($user_style_value_data_list[$v['attribute_id']])?$user_style_value_data_list[$v['attribute_id']]:0;
		 ?>
		 		<select id="attr_edit_value_<?php echo $k; ?>" class="select_default_change">
	 				<?php foreach($select_value_arr as $kk=>$vv){ if(trim($vv)=="")continue;
	 					$sel_txt="";
						if($user_style_value==trim($vv)){
							$sel_txt=" selected='selected'";
						}
	 			?>
	 					<option value="<?php echo trim($vv); ?>" <?php echo $sel_txt; ?>><?php echo trim($vv); ?></option>
	 				<?php } ?>
		 		</select>
		 	<?php }}else{ ?>
		 		<input class="attr_value_input" type="text" name="data[UserStyleValue][<?php echo $k;?>][attribute_value]" value="<?php echo isset($user_style_value_data_list[$v['attribute_id']])?$user_style_value_data_list[$v['attribute_id']]:$v['default_value'];?>" />
		 	<?php }?></td>
		<td><span class="change_value"><?php echo isset($user_style_value_data_list[$v['attribute_id']])?$user_style_value_data_list[$v['attribute_id']]:"";?></span></td>
	</tr>
	<?php }} ?>
</table>
<script type="text/javascript">
$(function(){
	$(".type_attr_list .select_change").change(function(){
		var change_value=Number($(this).val());
		var TR=$(this).parent().parent();
		var hidinput=Number(TR.find("input.default_value").val());
		TR.find("span.change_value").html(hidinput+change_value);
		TR.find(".attr_value").val(hidinput+change_value);
	});
	
	$(".type_attr_list .select_default_change").change(function(){
		var change_value=$(this).val();
		var TR=$(this).parent().parent();
		TR.find("span.change_value").html(change_value);
		TR.find(".attr_value").val(change_value);
	});
	
	$(".type_attr_list .attr_value_input").change(function(){
		var change_value=$(this).val();
		var TR=$(this).parent().parent();
		TR.find("span.change_value").html(change_value);
		TR.find(".attr_value").val(change_value);
	});
})
</script>