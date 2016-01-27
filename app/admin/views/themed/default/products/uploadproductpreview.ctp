<style type="text/css">
.btnouterlist label{margin-left: -3px;}
.btnouterlist input{position: relative;bottom: 3px;*position:static;}
.am-radio, .am-checkbox{display:inline-block;margin-top:0px;}
.product_quantity,.product_purchase_price,.product_market_price,.product_shop_price,.product_product_style_id,.product_meta_keywords,.product_weight,.product_brand_id,.product_recommand_flag,.product_forsale,.product_alone,.product_min_buy,.product_max_buy,.product_promotion_price,.product_promotion_status,.product_frozen_quantity,.product_product_type_id,.product_size_code,.product_color_code,.product_color,.product_height,.product_size_code1,.product_promotion_date,.product_comm_attr1,.product_comm_attr2,.product_comm_attr3,.product_size{width:80px;}
</style>
<?php echo $form->create('products',array('action'=>'/batch_add_products/','name'=>"theForm"));
	if(isset($category_id)){echo "<input value='".$category_id."' type='hidden' name='category_id' />";}?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
	<table id="t1" class="am-table  table-main">
		<tr>
			<th><label class="am-checkbox am-success" style="font-weight:bold;">
				<input onclick='listTable.selectAll(this,"checkbox[]")' type="checkbox" checked data-am-ucheck /><?php echo $ld['number']?></label></th>
			<?php foreach($profilefiled_info as $thk => $thv){?>
				<th><?php echo $thv['ProfilesFieldI18n']['description'];?></th>
			<?php }?>
			<!--循环属性标题-->
		     <?php if(isset($titer_arr)&&$titer_arr!=""){foreach($show_attr_code_arr as $k => $v){ ?>
			<?php if(in_array($v,$titer_arr)){echo"<th>"; echo $v;echo "</th>";}?>
			<?php } }?>
		</tr>
		<?php if(isset($uploads_list) && sizeof($uploads_list)>0){foreach($uploads_list as $k=>$v){ if($k==0)continue; ?>
		<tr>
			<td><label class="am-checkbox am-success">
				<input type="checkbox" name="checkbox[]" value="<?php echo $k?>" checked data-am-ucheck /><?php echo $k;?></label></td>
			<?php foreach($profilefiled_info as $kk => $vv){
                    $fields_kk=explode(".",$vv['ProfileFiled']['code']);
                    $fields_desc=$vv['ProfilesFieldI18n']['description'];
                    $data_name=isset($key_code[$fields_desc])?$key_code[$fields_desc]:'';
            ?>
			<td><input type='text' class="user_<?php echo $fields_kk[1]?>" name="data[<?php echo $k?>][<?php echo $fields_kk[1]?>]" value="<?php echo isset($v[$fields_kk[1]])?$v[$fields_kk[1]]:"";?>" /></td>
			<?php }?>
			<?php  if(isset($titer_arr)&&$titer_arr!=""){foreach($show_attr_code_arr as $attrk => $attrv){ 
	   	        if(in_array($attrv,$titer_arr)){ ?>  
	   	        <?php  if(in_array($attrv,$titer_arr)){  echo"<td>";}?>
	   	        <input type='text' class='product_<?php echo $attrv; ?>' name='data[<?php echo $k;?>][ProductAttribute][<?php echo $attrv; ?>]'   value='<?php echo isset($v[$attrv])?htmlspecialchars($v[$attrv]):''; ?>'/>   
	   	        <?php  if(in_array($attrv,$titer_arr)){  echo"</td>";}?>
	   	         <?php }} ?>
		  </tr>
		<?php }}}?>
	</table>
	<div id="btnouterlist" class="btnouterlist">
		<div>
			<label class="am-checkbox am-success" style="font-weight:bold;">
				<input onclick='listTable.selectAll(this,"checkbox[]")' type="checkbox" checked data-am-ucheck />
				<?php echo $ld['select_all']?>
			</label>&nbsp;
			<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" />
			<input type="reset"  class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
</div>
<?php $form->end();?>
<script>
	$(function(){
		if(document.getElementById('msg')){
			var msg =document.getElementById('msg').value;
            if(msg !=""){
                alert(msg);
                var button=document.getElementById('btnouterlist');
                button.style.display="none";
            }
		}
	});
</script>