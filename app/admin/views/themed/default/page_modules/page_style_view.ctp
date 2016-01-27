<?php echo $form->create('PageModule',array('action'=>'/page_style_view','onsubmit' =>'return check_page_style()'));?>
<div id="tablemain" class="tablemain">
	<input type="hidden"  name="data[PageStyle][id]" value="<?php if(isset($this->data['PageStyle'])){echo $this->data['PageStyle']['id'];} ?>" />
	<input type="hidden"  name="page_id" value="<?php if(isset($page_id)){echo $page_id;} ?>" />
	<div>
		<h2><?php echo $ld['basic_information']?></h2>
		<div class="show_border">
			<table class="alonetable">
				<tr>
					<th><?php echo $ld['style_name']?></th>
					<td><input type="text"  id="name" class="border" name="data[PageStyle][name]" value="<?php if(isset($this->data['PageStyle'])){echo $this->data['PageStyle']['name'];} ?>" /><em>*</em></td>
				</tr>
				<tr>
					<th><?php echo $ld['the_page']?></th>
					<td><select name="data[PageStyle][page_id]"><?php if(isset($page_list) && sizeof($page_list) >0){ foreach($page_list as $pk => $p){ ?><option <?php if((isset($this->data['PageStyle']) && $this->data['PageStyle']['page_id'] == $pk) || (isset($page_id) && $page_id ==$pk)){ echo 'selected';}?> value="<?php echo $pk;?>" ><?php echo $p;?></option><?php }}?></select><em>*</em></td>
				</tr>
				<tr>
					<th><?php echo $ld['template']?></th>
					<td><select name="data[PageStyle][template_code]"><?php if(isset($tem_list) && sizeof($tem_list) >0){ foreach($tem_list as $tk => $t){ ?><option <?php if(isset($this->data['PageStyle']) && $this->data['PageStyle']['template_code'] == $tk){ echo 'selected';}?> value="<?php echo $tk;?>" ><?php echo $t;?></option><?php }}?></select><em>*</em></td>
				</tr>
				<tr>
					<th><?php echo $ld['template_code']?></th>
					<td><input type="text" class="border" id="code" name="data[PageStyle][code]" value="<?php if(isset($this->data['PageStyle'])){echo $this->data['PageStyle']['code'];} ?>" /><em>*</em></td>
				</tr>
				<tr>
					<th><?php echo $ld['note2']?></th>
					<td><textarea name="data[PageStyle][remark]" cols = '50'><?php if(isset($this->data['PageStyle'])){echo $this->data['PageStyle']['remark'];} ?></textarea></td>
				</tr>
				<tr>
					<th><?php echo 'CSS';?></th>
					<td><textarea name="data[PageStyle][css]" cols = '50'><?php if(isset($this->data['PageStyle'])){echo $this->data['PageStyle']['css'];} ?></textarea></td>
				</tr>
				<tr>
					<th><?php echo $ld['valid']?></th>
					<td><input type="radio" value="1" name="data[PageStyle][status]" checked/><?php echo $ld['yes']?>
						<input type="radio" name="data[PageStyle][status]" value="0" <?php if(isset($this->data['PageStyle'])&&$this->data['PageStyle']['status'] == 0){ echo "checked"; } ?>/><?php echo $ld['no']?></td>
				</tr>
			</table>
		</div>
		<div class="btnouter">
			<input type="submit" value="<?php echo $ld['d_submit']?>" /> <input type="reset" value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
</div>
<?php echo $form->end();?>
<script>
	function check_page_style(){
		if(document.getElementById('name').value==""){
			alert('样式名称不能为空！');
			return false;
		}
		if(document.getElementById('code').value==""){
			alert('样式编码不能为空！');
			return false;
		}
		return true;
	}
</script>
