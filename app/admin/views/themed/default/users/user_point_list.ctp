<div id="user_point_list_ajaxdata">
	<table class="am-table  table-main">
		<thead>
			<tr>
				<th width="12%"><?php echo $ld['point'] ?></th>
				<th width="12%"><?php echo $ld['operate'].$ld['type'];?></th>
				<th width="12%"><?php echo $ld['operator']; ?></th>
				<th><?php echo $ld['note2']; ?></th>
				<th width="20%"><?php echo $ld['time'] ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if(isset($user_point_list)&&sizeof($user_point_list)>0){foreach($user_point_list as $v){ ?>
		<tr>
			<td><?php echo $v['UserPointLog']['point'] ?></td>
			<td><?php echo isset($point_log_type[$v['UserPointLog']['log_type']])?$point_log_type[$v['UserPointLog']['log_type']]:$v['UserPointLog']['log_type'] ?></td>
			<td><?php echo $v['UserPointLog']['admin_user'] ?></td>
			<td><?php echo $v['UserPointLog']['system_note'] ?></td>
			<td><?php echo $v['UserPointLog']['created'] ?></td>
		</tr>
		<?php }} ?>
		</tbody>
	</table>
	<div id="btnouterlist" class="btnouterlist"><?php echo $this->element('pagers'); ?></div>
</div>
<script type="text/javascript">
$("#user_point_list_ajaxdata .btnouterlist a").click(function(){
	var user_chat_list=$("#user_point_list_ajaxdata");
	var dataurl=$(this).attr("href");
	$.ajax({ url: dataurl,
			type:"POST",
			dataType:"html",
			success: function(data){
				user_chat_list.parent().html(data);
      		}
      	});
	return false;
});
</script>