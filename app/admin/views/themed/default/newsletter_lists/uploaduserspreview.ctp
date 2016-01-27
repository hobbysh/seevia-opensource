<div style="width:80%;margin:0 auto;">
	<?php echo $form->create('newsletter_lists',array('action'=>'/batch_add_user/','name'=>"theForm"));?>
	<div class="tablelist">
		<table>
			<tr>
				<th width="6%"><label><input onclick='listTable.selectAll(this,"checkbox[]")' type="checkbox" checked /><?php echo $ld['number']?></label></th>
				<?php foreach($profilefiled_info as $thk => $thv){?>
				<th><?php echo $thv['ProfilesFieldI18n']['description'];?></th>
				<?php }?>
			</tr>
			<?php if(isset($uploads_list) && sizeof($uploads_list)>0){foreach($uploads_list as $k=>$v){ if($k==0)continue;?>
			<tr>
				<td><label><input type="checkbox" name="checkbox[]" value="<?php echo $k?>" checked /><?php echo $k;?>
				<?php if(isset($discount[$k])&&$discount[$k]=="discount"){echo "<img src='/media/unfound.png'/>";} ?></label></td>
				<?php foreach($profilefiled_info as $kk => $vv){$fields_kk=explode(".",$vv['ProfileFiled']['code']);?>
				<td><input type='text' name="data[<?php echo $k?>][<?php echo $fields_kk[1]?>]" class="input_<?php echo $fields_kk[1]?>" value="<?php echo isset($v[$fields_kk[1]])?$v[$fields_kk[1]]:"";?>" /></td>
				<?php }?>
			</tr>
			<?php }}?>
		</table>
		<div id="btnouterlist" class="btnouterlist" style="margin-left:0;">
			<div>
				<label><input onclick='listTable.selectAll(this,"checkbox[]")' type="checkbox" checked /><span><?php echo $ld['select_all']?></span></label>
				<input type="submit" value="<?php echo $ld['d_submit']?>" /><input type="reset" value="<?php echo $ld['d_reset']?>" />
			</div>
		</div>
	</div>
</div>
<?php $form->end();?>
<style type="text/css">
.input_email,.input_mobile{width:100%;}
.input_status{width:100%;}
.btnouterlist label{margin-left: -3px;}
.btnouterlist input{position: relative;bottom: 3px;*position:static;}
</style>
<script type="text/javascript">
YUI().use('node',function(Y){
	Y.on('domready',function(){
		if(document.getElementById('msg')){
			var msg =document.getElementById('msg').value;
			if(msg !=""){
				alert(msg);
				var button=document.getElementById('btnouterlist');
				button.style.display="none";
			}
		}
	});
});
</script>