<style type="text/css">
.btnouterlist label{margin-left: -3px;}
.btnouterlist input{position: relative;bottom: 3px;*position:static;}
.am-radio, .am-checkbox{display:inline-block;margin-top:0px;}
.user_status,.user_sex,.user_birthday,.user_country,.user_province,.user_city{width:80px;}
</style>
<?php echo $form->create('attributes',array('action'=>'/csv_add/','name'=>"theForm"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
	<table id="t1" class="am-table  table-main">
		<tr>
			<th>
				<label class="am-checkbox am-success" style="font-weight:bold;">
					<input onclick='listTable.selectAll(this,"checkbox[]")' type="checkbox" checked  checked data-am-ucheck />
				 </label>
		       <?php foreach($fieldarray as  $thv){?>
			<th><?php echo $thv;?></th>
			<?php }?>
			</th>
		
		</tr>
		<?php if(isset($data_list) && sizeof($data_list)>0){foreach($data_list as $k=>$v){ if($k==0)continue;?>
		<tr>
			<td>
				<label class="am-checkbox am-success">
					<input type="checkbox" name="checkbox[]" value="<?php echo $k?>" checked  checked data-am-ucheck /> 
					<?php if(isset($discount[$k])&&$discount[$k]=="discount"){echo "<img src='/media/unfound.png'/>";} ?>
				</label>
			</td>
					
			<?php foreach($key_arr as $kk => $vv){?>
		 
				<td><input type='text' class="user_<?php echo""?>" name="data[<?php echo $k?>][<?php echo $key_arr[$kk]?>]" value="<?php echo isset($v[$vv])?$v[$vv]:"";?>" />
				</td>
			<?php }?>
						  
		</tr>
		<?php }}?>
	</table>
	<div id="btnouterlist" class="btnouterlist" style="margin-left:0;">
		<div>
			<label class="am-checkbox am-success" style="font-weight:bold;">
				<input onclick='listTable.selectAll(this,"checkbox[]")' type="checkbox" checked  checked data-am-ucheck />
				<?php echo $ld['select_all']?>
			</label>&nbsp;
	                 	  <input type="hidden" value="2" name="sub2"/>
			<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" />
			<input type="reset"  class="am-btn am-btn-success am-radius am-btn-sm"  value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
</div>
<?php $form->end();?>
<script type="text/javascript">
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