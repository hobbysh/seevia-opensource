<style type="text/css">
	.btnouterlist label{margin-left: -3px;}
	.btnouterlist input{position: relative;bottom: 3px;*position:static;}
</style>
<p class="action-span"><?php echo $html->link($ld['message_management'],"/messages/",'',false,false);?></p>
<div id="tablelist" class="tablelist tablenobang">
	<?php echo $form->create('',array('action'=>'',"type"=>"get",'name'=>'UserForm','onsubmit'=>"return false"));?>
	<table>
		<thead>
			<tr>
				<th class="thcode"><input type="checkbox" name="chkall" value="checkbox" onclick='listTable.selectAll(this,"checkboxes[]")' /><?php echo $ld['number']?></th>
				<th><?php echo $ld['user_name']?></th>
				<th><?php echo $ld['member_level']?></th>
				<th><?php echo $ld['meessage_title']?></th>
				<th><?php echo $ld['meessage_objects']?></th>
				<th class="thtype"><?php echo $ld['type']?></th>
				<th class="thdate"><?php echo $ld['message_time']?></th>
				<th><?php echo $ld['operate']?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($UserMessage_list) && sizeof($UserMessage_list)>0){?>
			<?php foreach($UserMessage_list as $k=>$v){ ?>
			<tr>
				<td><input type="checkbox" name="checkboxes[]" value="<?php echo $v['UserMessage']['id'] ?>" /><?php echo $v['UserMessage']['id'] ?></td>
				<td><span><?php echo $v['UserMessage']['name'] ?></span></td>
				<td><?php echo $v['UserMessage']['rank'] ?></td>
				<td><span><?php echo $html->link("{$v['UserMessage']['msg_title']}","/messages/view_search/{$v['UserMessage']['id']}",'',false,false);?></span></td>
				<td><?php echo @$Resource_info["type"][$v['UserMessage']['type']]?>
					<?php if( $v['UserMessage']['type'] == "P"){?>
					：<?php echo @$products_list[$v['UserMessage']['value_id']]?>
					<?php }else if($v['UserMessage']['type'] == "O"){?>
					：<?php echo @$order_list[$v['UserMessage']['value_id']]["Order"]["order_code"]?>
					<?php }else{?>
					<?php echo $ld['unknown_object']?>
					<?php }?></td>
				<td><?php echo $v['UserMessage']['type'] == "P"?$ld['product_questions']:$ld['message'];?>
					<!--<?php //echo $Resource_info["msg_type"][$v['UserMessage']['msg_type']] ?>--></td>
				<td><?php echo $v['UserMessage']['created'] ?></td>
				<td><?php
						echo $html->link($ld['edit'],"/messages/view_search/{$v['UserMessage']['id']}");
						echo $html->link($ld['remove'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}messages/remove/{$v['UserMessage']['id']}';}"));
					?></td>
			</tr>
			<?php } }?>
		</tbody>
	</table>
	<div id="btnouterlist" class="btnouterlist">
		<?php if(isset($UserMessage_list) && sizeof($UserMessage_list)>0){?>
		<input type="hidden" name="search" value="search">
		<div>
			<label><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" value="checkbox"><span><?php echo $ld['select_all']?></span></label>
			<select name="act_type" style="display:none">
				<option value="delete"><?php echo $ld['delete']?></option>
			</select>
			<input type="button" onclick="batch_action()" value="<?php echo $ld["delete"];?>" /> &nbsp; &nbsp; <input type="button" value="<?php echo $ld["export"];?>" onclick="export_act()" />
		</div>
		<?php }?>
		<?php echo $this->element('pagers')?>
	</div>
	<?php if(isset($ex_page)){ ?>
	<input type="hidden" id="url" value="/messages/search/unprocess?page=<?php echo $ex_page;?>&export=export" />
	<?php }else{ ?>
	<input type="hidden" id="url" value="/messages/search/unprocess?export=export" />
	<?php } ?>
	<?php echo $form->end();?>
</div>
<script type="text/javascript">
function batch_action()
{
document.UserForm.action=admin_webroot+"messages/batch";
document.UserForm.onsubmit= "";
document.UserForm.submit();
}
function export_act(){
	var url=document.getElementById("url").value;
	window.location.href=admin_webroot+url;
}
</script>
