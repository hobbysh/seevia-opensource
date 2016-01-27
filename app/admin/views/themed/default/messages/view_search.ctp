
<ul class="tablemenu">
	<li><?php echo $ld['message_detail']?></li>
	<li><?php echo $ld['reply_message']?></li>
</ul>
<?php echo $form->create('Message',array('action'=>'view_search/'.$usermessage['UserMessage']['id']));?>
<div class="tablemain">
	<div>
		<h2><?php echo $ld['message_detail']?></h2>
		<div class="show_border">
			<table class="alonetable">
				<tr>
					<th><?php echo $ld['message_products']?></th>
					<td colspan="3"><?php echo $usermessage['UserMessage']['msg_title']?></td>
				</tr>
				<tr>
					<th><?php echo $ld['message_content']?></th>
					<td colspan="3"><?php echo $usermessage['UserMessage']['msg_content']?></td>
				</tr>
				<tr>
					<th><?php echo $ld['the_message_user']?></th>
					<td><?php echo $usermessage['UserMessage']['user_name']?></td>
				</tr>
				<tr>
					<th><?php echo $ld['message_time']?></th>
					<td><?php echo $usermessage['UserMessage']['created']?></td>
				</tr>
			</table>
			<input type="hidden" name="data[UserMessage][parent_id]" value="<?php echo $usermessage['UserMessage']['id']; ?>">
		</div>
	</div>
	<div>
		<h2><?php echo $ld['reply_message']?></h2>
		<div class="show_border">
			<table class="alonetable">
				<tr>
					<th><?php echo $ld['administrator']?></th>
					<td colspan="3"><input type="text" name="data[UserMessage][user_name]" style="width:220px;"value="<?php echo $admin['name']?>" readonly/></td>
				</tr>
				<tr>
					<th><?php echo $ld['reply_content']?></th>
					<td colspan="3"><textarea name="data[UserMessage][msg_content]" style="width:353px;overflow-y:scroll;height:62px;"></textarea></td>
				</tr>
				<?php if( isset( $restore ) ){?>
				<tr>
					<td>&nbsp;</td>
					<td colspan="3"><?php echo $ld['note_message_replied']?></td>
				</tr>
				<?php }?>
			</table>
		</div>
		<div class="btnouter">
			<input type="submit" onclick="return cha2()" value="<?php echo $ld['d_submit']?>" /> <input type="reset" value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
	<!--<div class="btnouter"><input type="submit" onclick="return cha2()" value="<?php echo $ld['d_submit']?>" /><input type="reset" value="<?php echo $ld['d_reset']?>" /></div>-->
</div>
<?php echo $form->end();?> 