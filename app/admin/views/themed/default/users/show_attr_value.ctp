<style type="text/css">
#select_style{clear:both;}
#user_style_pop .tablemain td {
    border: 1px solid #d8d8d8;
}
#user_style_editinfo{padding-top: 2rem;}
</style>
<input class="style_user_id" type="hidden" name="data[UserStyle][user_id]" value="" />
<input class="style_attr_code" type="hidden" name="data[UserStyle][attribute_code]" value="" />

<div id="user_style_editinfo">
	<table class="am-table">
		<?php if(isset($show_attr_list)){foreach($show_attr_list as $k=>$v){if($v['attr_type']!='2')continue; ?>
<?php if(isset($user_style_value_data_list[$v['attribute_id']]) && ($v['default_value']!=$user_style_value_data_list[$v['attribute_id']])){$flag=true;}?>
		<tr class='type_attr_list'>
			<td width="15%"><?php echo $v['attr_name']; ?></td>
			<td width="15%"><span class="default_value"><?php echo $v['default_value']; ?></span>
				<input class="default_value" type="hidden" value="<?php echo $v['default_value']; ?>" />
				<input class="attr_value" type="hidden" name="data[UserStyleValue][<?php echo $k;?>][attribute_value]" value="<?php echo isset($user_style_value_data_list[$v['attribute_id']])?$user_style_value_data_list[$v['attribute_id']]:$v['default_value'];?>" />
				<input class="attr_id" type="hidden" name="data[UserStyleValue][<?php echo $k;?>][attribute_id]" value="<?php echo $v['attribute_id']; ?>" />
			</td>
			<td width="35%"><?php if(isset($v['select_value']) && $v['attr_type']=='2'){
				if(is_string($v['select_value'])){
				$select_value_arr=explode("\r\n",$v['select_value']);}else{$select_value_arr=array();}if(sizeof($select_value_arr)){ ?>
				<select id="attr_edit_value_<?php echo $k; ?>" class="select_change">
					<?php foreach($select_value_arr as $kk=>$vv){
						$user_style_value=isset($user_style_value_data_list[$v['attribute_id']])?$user_style_value_data_list[$v['attribute_id']]:0;
						$attr_type_txt=$vv;
						if($vv>0){$attr_type_txt='增加'.$vv;}else if($vv<0){$attr_type_txt='减少'.($vv*-1);}else{$attr_type_txt='无改动';}
						$sel_txt="";
						if($user_style_value){
							$difference=$user_style_value-$attrvaluelist[$v['StyleTypeGroupAttributeValue']['attribute_id']];
							if($difference==$vv){$sel_txt=" selected='selected'";}
						}else if($vv==0){
							$sel_txt=" selected='selected'";
						}
					?>
						<option value="<?php echo trim($vv); ?>" <?php echo $sel_txt; ?>><?php echo $attr_type_txt; ?></option>
					<?php } ?>
						</select>
					<?php }}else if(isset($v['select_value']) && $v['attr_type']=='1'){
			 	 	$select_value_arr=$v['select_value'];if(sizeof($select_value_arr)>1){
			 	 	$user_style_value=isset($user_style_value_data_list[$v['attribute_id']])?$user_style_value_data_list[$v['attribute_id']]:0;
			 ?><select id="attr_edit_value_<?php echo $k; ?>" class="select_default_change">
		 				<?php foreach($select_value_arr as $kk=>$vv){ if(trim($kk)=="")continue;
		 					$sel_txt="";
							if($user_style_value===$kk){
								$sel_txt=" selected='selected'";
							}
		 			?>
		 					<option value="<?php echo trim($kk); ?>" <?php echo $sel_txt; ?>><?php echo trim($vv); ?></option>
		 				<?php } ?>
			 		</select>
			 		<?php }}else{ ?>
			 		<input class="attr_value_input" type="text" name="data[UserStyleValue][<?php echo $k;?>][attribute_value]" value="<?php echo isset($user_style_value_data_list[$v['attribute_id']])?$user_style_value_data_list[$v['attribute_id']]:$v['default_value'];?>" />
			 	<?php } ?></td>
			<td><span class="change_value" style="<?php if(isset($flag) && $flag){echo 'display:inline';}else{echo 'display:none';} ?>"><?php echo isset($user_style_value_data_list[$v['attribute_id']])?$user_style_value_data_list[$v['attribute_id']]:'';?></span></td>
		</tr>
		<?php }} ?>
	</table>
</div>
<div id="user_group" class="user_group">
	<table class="am-table">
		<tr>
			<td width="20%"><?php echo $ld['user_template'].$ld['name']; ?></td>
			<td><input type="text" name="data[UserStyle][user_style_name]" id="user_style_name" value="<?php echo isset($user_style_data)?$user_style_data['UserStyle']['user_style_name']:''; ?>" style="width:50%;" /></td>
		</tr>
		<tr>
			<td width="20%"><?php echo $ld['set_default']; ?></td>
			<td><label style="margin-right:10px;"><input type="radio" name="data[UserStyle][default_status]" value="1" <?php if(isset($user_style_data['UserStyle']['default_status']) && $user_style_data['UserStyle']['default_status'] == 1 ){ echo "checked"; }else{echo "checked";} ?> />&nbsp;<?php echo $ld['yes']?></label>
				<label style="margin-right:10px;"><input type="radio" name="data[UserStyle][default_status]" value="0" <?php if(isset($user_style_data['UserStyle']['default_status']) && $user_style_data['UserStyle']['default_status'] == 0 ){ echo "checked"; } ?> />&nbsp;<?php echo $ld['no']?></label></td>
		</tr>
        <tr>
			<td width="20%"><?php echo $ld['note2']; ?></td>
			<td><input type="text" name="data[UserStyle][remark]" value="<?php echo isset($user_style_data)?$user_style_data['UserStyle']['remark']:''; ?>" style="width:50%;" /></td>
		</tr>
	</table>
</div>
<div class="btnouter">
<!--	<input type="button" value="<?php echo '另存为'; ?>" onclick="order_product_attr_data_save('save_as')" />-->
	<input type="button" value="<?php echo $ld['save'] ?>" onclick="user_style_save(this)" class="am-btn am-btn-success am-radius am-btn-sm" />
</div>
<script type="text/javascript">
$(function(){
	
	$(".type_attr_list .select_change").change(function(){
		var change_value=Number($(this).val());
		var TR=$(this).parent().parent();
		var hidinput=Number(TR.find("input.default_value").val());
		TR.find("span.change_value").html(hidinput+change_value).css("display","inline");
		TR.find(".attr_value").val(hidinput+change_value);
	});
	
	$(".type_attr_list .select_default_change").change(function(){
		var change_value=$(this).val();
		var TR=$(this).parent().parent();
		TR.find("span.change_value").html(change_value).css("display","inline");
		TR.find(".attr_value").val(change_value);
	});
	
	$(".type_attr_list .attr_value_input").change(function(){
		var change_value=$(this).val();
		var TR=$(this).parent().parent();
		TR.find("span.change_value").html(change_value);
		TR.find(".attr_value").val(change_value);
	});
})

function user_style_save(e){
	$(e).attr('disabled',"true");
	var user_style_name=$.trim($("#user_style_name").val());
	$(".style_user_id").val($("#user_id").val());
	var attr_code=$("#user_style_pop input[type=radio][name='group_name']:checked").parent().find(".attr_code").val();
	$(".style_attr_code").val(attr_code);
	if(user_style_name.length==0){
		alert('请先设置用户模板名称');$(e).removeAttr('disabled');return false;
	}
	var PostData=$("#update_user_style").serialize();
	$.ajax({url: "/admin/users/update_user_style/",
			type:"POST",
			data:PostData,
			dataType:"html",
			success: function(data){
				try{
					$("#style .table-main").html(data);
					$(".am-close").click();
				}catch (e){
					alert(j_object_transform_failed);
				}
	  		}
	  	});
}
</script>