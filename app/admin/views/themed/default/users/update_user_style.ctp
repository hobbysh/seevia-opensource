<thead>
  <tr style="background:#E9F8D9;">
	<th>模板名称<?php //echo $ld['quantity']?></th>
	<th>用户版型<?php //echo $ld['product'];?></th>
	<th>规格<?php //echo $ld['code']?></th>
	<th>属性组<?php //echo $ld['name']?></th>
	<th>是否默认<?php //echo $ld['price']?></th>
	<th><?php echo $ld['operate']?></th>
  </tr>
</thead>
<tbody>
	<?php if(isset($user_style_list)){foreach($user_style_list as $k=>$v){ ?>
	<tr >
		<td><?php echo $v['UserStyle']['user_style_name']; ?></td>
		<td><?php echo $v['UserStyle']['style_name']; ?></td>
		<td><?php echo $v['UserStyle']['attribute_code']; ?></td>
		<td><?php echo $v['UserStyle']['attr_name']; ?></td>
		<td><?php if($v['UserStyle']['default_status'])echo $html->image('yes.gif');else echo $html->image('no.gif');?></td>
		<td><a href="javascript:void(0);" id="<?php echo $v['UserStyle']['id']?>" class="am-btn am-btn-default am-radius am-btn-sm edit_user_style"><?php echo $ld["edit"]?></a><a href="javascript:void(0);" id="<?php echo $v['UserStyle']['id']?>" class="am-btn am-btn-default am-radius am-btn-sm delete_user_style"><?php echo $ld["delete"]?></a></td>
	</tr>
	<?php }} ?>
</tbody>

