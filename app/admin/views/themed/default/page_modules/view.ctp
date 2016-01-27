<?php echo $form->create('PageModule',array('action'=>'/view'));?>
<div id="tablemain" class="tablemain">
	<input type="hidden"  name="data[Page][id]" value="<?php if(isset($this->data['Page'])){echo $this->data['Page']['id'];} ?>" />
	<div>
		<h2><?php echo $ld['basic_information']?></h2>
			<table class="alonetable">
				<tr>
					<th><?php echo $ld['page_name']?></th>
					<td><input type="text"  id="code" class="border" name="data[Page][name]" value="<?php if(isset($this->data['Page'])){echo $this->data['Page']['name'];} ?>" /><em>*</em></td>
				</tr>
				<tr>
					<th><?php echo $ld['controller']?></th>
					<td><input type="text"  id="file_name" class="border" name="data[Page][controller]" value="<?php if(isset($this->data['Page'])){echo $this->data['Page']['controller'];} ?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['method']?></th>
					<td><input type="text"  id="file_name" class="border" name="data[Page][action]" value="<?php if(isset($this->data['Page'])){echo $this->data['Page']['action'];} ?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['code']?></th>
					<td><input type="text"  id="file_name" class="border" name="data[Page][default_page_style_code]" value="<?php if(isset($this->data['Page'])){echo $this->data['Page']['default_page_style_code'];} ?>" /></td>
				</tr>
				<tr>
					<th><?php echo $ld['valid']?></th>
					<td><input type="radio" value="1" name="data[Page][status]" checked/><?php echo $ld['yes']?>
						<input type="radio" name="data[Page][status]" value="0" <?php if(isset($this->data['Page'])&&$this->data['Page']['status'] == 0){ echo "checked"; } ?>/><?php echo $ld['no']?></td>
				</tr>
			</table>
		</div>
		<div class="btnouter">
			<input type="submit" value="<?php echo $ld['d_submit']?>" /> <input type="reset" value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
	</div>
</div>

<?php echo $form->end();?>

