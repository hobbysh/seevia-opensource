<?php echo $html->css('/skins/default/css/warehouse');?>
<p class="action-span"><?php if($svshow->operator_privilege("modules_add")){echo $html->link($ld['add_new_style'],"page_style_view/?page_id=".$id,array("class"=>"addbutton"),'',false,false);}?></p>
<div id="tablelist" class="tablelist tablebang">
	<table id="foldtablelist" class="foldtablelist">
		<thead>
			<tr>
			<th class="thcode"><?php echo $ld['style_name']?></span></label></th>
			<th><?php echo $ld['template']?></th>
			<th><?php echo $ld['code']?></th>
			<th><?php echo $ld['note2']?></th>
			<th><?php echo $ld['status']?></th>
			<th><?php echo $ld['operate']?></th>
			</tr>
		</thead>
		<tbody>
		<?php if(isset($page_style_list) && sizeof($page_style_list)>0){foreach($page_style_list as $k=>$v){?>
			<td><?php echo $v['PageStyle']['name'];?></td>
			<td><?php echo isset($tem[$v['PageStyle']['template_code']])?$tem[$v['PageStyle']['template_code']]:'N/A';?></td>
			<td><?php echo $v['PageStyle']['code'];?></td>
			<td><?php echo $v['PageStyle']['remark'];?></td>
			<td><?php if($v['PageStyle']['status']==1){?>
				<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_modules/toggle_on_page_style_status", '.$v["PageStyle"]["id"].')')) ?>
				<?php }elseif($v['PageStyle']['status'] == 0){?>
				<?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_modules/toggle_on_page_style_status", '.$v["PageStyle"]["id"].')'))?>
				<?php }?>
			</td>
			<td>
			<?php
				echo $html->link($ld['view_style_module'],"/page_modules/module_list/{$v['PageStyle']['code']}");
				echo $html->link($ld['edit'],"/page_modules/page_style_view/{$v['PageStyle']['id']}");
				echo $html->link($ld['delete'],"javascript:;",array("onclick"=>"if(confirm('确认删除该样式吗？（删除页面样式，样式模块）')){list_delete_submit('{$admin_webroot}page_modules/page_style_remove/{$v['PageStyle']['id']}');}"));
			?>
			</td>
		</tr>
	   <?php }}else{?>
		<tr>
			<td><?php echo $ld['no_page_style_data']?></td>
		</tr>
		<?php }?>
		</tbody>
	</table>
</div>