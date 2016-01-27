<?php echo $javascript->link('/skins/default/js/calendar/language/'.$backend_locale);
echo $javascript->link('/skins/default/js/calendar/calendar');echo $html->css('/skins/default/css/calendar/calendar');?>

<style type="text/css">
	#tablemain .consignee_info td{width:40%;}
	td.pname img {
    height: 150px;
    width: 150px;
}
</style>
<!--隐藏信息-->
<?php echo $form->create('Cronjob',array('action'=>'edit/'.$cronjob_info["Cronjob"]["id"],'name'=>'userformedit'));?> 
	<input id="id" type="hidden" name="data[Cronjob][id]" value="<?php echo $cronjob_info['Cronjob']['id'];?>">
<div id="tablemain" class="tablemain">
	<div>
		<h2>定时器信息</h2>
		<div class="show_border">
			<table class="consignee_info">
				<tbody>
					<tr>
						<td><?php echo $ld['task_name']?></td>
						<td><input type="text" id="task_name"  maxlength="60" name="data[Cronjob][task_name]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['task_name']:'';?>" /></td>
					</tr>
					<tr>
						<td><?php echo $ld['interval_time']?></td>
						<td><input type="text" id="interval_time"  maxlength="60" name="data[Cronjob][interval_time]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['interval_time']:'';?>" /></td>
					</tr>
					<tr>
						<td><?php echo $ld['app_code']?></td>
						<td><input type="text" id="app_code"  maxlength="60" name="data[Cronjob][app_code]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['app_code']:'';?>" /></td>
					</tr>
					<tr>
						<td><?php echo $ld['param01']?></td>
						<td><input type="text" id="param01"  maxlength="60" name="data[Cronjob][param01]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['param01']:'';?>" /></td>
					</tr>
					<tr>
						<td><?php echo $ld['param02']?></td>
						<td><input type="text" id="param02"  maxlength="60" name="data[Cronjob][param02]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['param02']:'';?>" /></td>
					</tr>
					<tr><td><?php echo $ld['remark']?></td>
						<td><input type="text" id="remark"  maxlength="60" name="data[Cronjob][remark]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['remark']:'';?>" /></td>
					</tr>
					<!--定时器状态-->
					<tr>
						<td></td>
						<td><label><input type="checkbox" name="data[Cronjob][status]" id="status" value="1"  <?php if(isset($cronjob_info['Cronjob']['status'])&&$cronjob_info['Cronjob']['status']==1){echo "checked";}?> /><?php echo $ld['status']?></label></td>
					</tr>
				</tbody>
			</table>
			<div class="btnouter">
				<input type="submit"  value="<?php echo $ld['d_submit'];?>" /><input type="reset" value="<?php echo $ld['d_reset']?>" />
			</div>
		</div>
	</div>

	



</div>

<?php echo $form->end();?>

	