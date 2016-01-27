<?php
	ob_start();
?>
<?php if($svshow->operator_privilege("flashs_add")){?>
<p class="action-span">
	<?php echo $html->link("新增模块","page_module_view/0?action_id=".$id,array("class"=>"addbutton"),'',false,false);?>
</p>
<?php }?>
<input type="hidden" id="type_id" value="<?php echo isset($id)?$id:''; ?>">
<table id="foldtablelist" class="foldtablelist">
	<thead>
		<tr>
			<th><?php echo $ld['module_name']?></th>
			<th><?php echo $ld['module_title']?></th>
	        <th><?php echo $ld['module_code']?></th>
			<th><?php echo $ld['module_location']?></th>
			<th><?php echo $ld['module_width']?></th>
			<th><?php echo $ld['module_height']?></th>
			<th><?php echo $ld['module_float']?></th>
			<th class="thsort"><?php echo $ld['sort']?></th>
			<th class="thicon"><?php echo $ld['status']?></th>
			<th><?php echo $ld['operate']?></th>
		</tr>
	</thead>
	<tbody>
<?php
	if(isset($pagemodule_list)&&sizeof($pagemodule_list)>0){
		foreach($pagemodule_list as $k=>$v){
?>
	<tr class="tr0">
		<td><span class="<?php echo (isset($v['SubPageModule']) && sizeof($v['SubPageModule'])>0)?"foldbtn":"foldbtnnone";?>" id="<?php echo $v['PageModule']['id']?>"></span><?php echo $v['PageModuleI18n']['name']; ?></td>
		<td align="center"><?php echo $v['PageModuleI18n']['title']; ?></td>
		<td align="center"><?php echo $v['PageModule']['code']; ?></td>
		<td align="center"><?php echo $v['PageModule']['position']; ?></td>
		<td align="center"><?php echo $v['PageModule']['width']; ?></td>
		<td align="center"><?php echo $v['PageModule']['height']; ?></td>
		<td align="center">
			<?php if($v['PageModule']['float']==0){echo $ld['module_float_in_entire_row'];}elseif($v['PageModule']['float']==1){echo $ld['module_left_floating'];}elseif($v['PageModule']['float']==2){echo $ld['module_right_floating'];}?>
		</td>
		<td align="center">
			<?php if(count($pagemodule_list)==1){echo "-";}elseif($k==0){?>
             <a onclick="changeOrder('down','<?php echo $v['PageModule']['id'];?>','0',this)">&#9660;</a>
            <?php }elseif($k==(count($pagemodule_list)-1)){?>
             <a onclick="changeOrder('up','<?php echo $v['PageModule']['id'];?>','0',this)" style="color:#cc0000;">&#9650;</a>
            <?php }else{?>
             <a onclick="changeOrder('up','<?php echo $v['PageModule']['id'];?>','0',this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $v['PageModule']['id'];?>','0',this)">&#9660;</a>
            <?php }?>
		</td>
		<td align="center" style="padding:10px 0px;"><?php if($v['PageModule']['status']==1){?>
	<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$v["PageModule"]["id"].')')) ?>
	<?php }elseif($v['PageModule']['status'] == 0){?>
	<?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$v["PageModule"]["id"].')'))?>
	<?php }?>
		</td>
		<td>
			<?php
				if($svshow->operator_privilege("page_types_edit")){
					echo $html->link($ld['edit'],"/page_actions/page_module_view/{$v['PageModule']['id']}");
				}
				if($svshow->operator_privilege("page_types_reomve")){
				echo $html->link($ld['remove'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}page_actions/module_remove/{$v['PageModule']['id']}/{$id}';}"));
				}
			?>
		</td>
	</tr>
	
	<?php if(isset($v['SubPageModule']) && sizeof($v['SubPageModule'])>0){
			foreach($v['SubPageModule'] as $kk=>$vv){
	?>
	<tr class="tr1">
		<td><span class="<?php echo (isset($vv['SubPageModule']) && sizeof($vv['SubPageModule'])>0)?"foldbtn":"foldbtnnone";?>" id="<?php echo $vv['PageModule']['id']?>"></span><?php echo $vv['PageModuleI18n']['name']; ?></td>
		<td align="center"><?php echo $vv['PageModuleI18n']['title']; ?></td>
		<td align="center"><?php echo $vv['PageModule']['code']; ?></td>
		<td align="center"><?php echo $vv['PageModule']['position']; ?></td>
		<td align="center"><?php echo $vv['PageModule']['width']; ?></td>
		<td align="center"><?php echo $vv['PageModule']['height']; ?></td>
		<td align="center">
			<?php if($vv['PageModule']['float']==0){echo $ld['module_float_in_entire_row'];}elseif($vv['PageModule']['float']==1){echo $ld['module_left_floating'];}elseif($vv['PageModule']['float']==2){echo $ld['module_right_floating'];}?>
		</td>
		<td align="center">
			<?php if(count($v['SubPageModule'])==1){echo "-";}elseif($kk==0){?>
             <a onclick="changeOrder('down','<?php echo $v['PageModule']['id'];?>','next',this)">&#9660;</a>
            <?php }elseif($kk==(count($v['SubPageModule'])-1)){?>
             <a onclick="changeOrder('up','<?php echo $vv['PageModule']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a>
            <?php }else{?>
             <a onclick="changeOrder('up','<?php echo $vv['PageModule']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vv['PageModule']['id'];?>','next',this)">&#9660;</a>
            <?php }?>
		</td>
		<td align="center" style="padding:10px 0px;"><?php if($vv['PageModule']['status']==1){?>
	<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$vv["PageModule"]["id"].')')) ?>
	<?php }elseif($vv['PageModule']['status'] == 0){?>
	<?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$vv["PageModule"]["id"].')'))?>
	<?php }?>
		</td>
		<td>
			<?php
				if($svshow->operator_privilege("page_types_edit")){
					echo $html->link($ld['edit'],"/page_actions/page_module_view/{$vv['PageModule']['id']}");
				}
				if($svshow->operator_privilege("page_types_remove")){
				echo $html->link($ld['remove'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}page_actions/module_remove/{$vv['PageModule']['id']}/{$id}';}"));
				}
			?>
		</td>
	</tr>
	<?php
			if(isset($vv['SubPageModule']) && sizeof($vv['SubPageModule'])>0){
				foreach($vv['SubPageModule'] as $kkk=>$vvv){
	?>
	<tr class="tr2">
		<td><span class="<?php echo (isset($vvv['SubPageModule']) && sizeof($vvv['SubPageModule'])>0)?"foldbtn":"foldbtnnone";?>" id="<?php echo $vvv['PageModule']['id']?>"></span><?php echo $vvv['PageModuleI18n']['name']; ?></td>
		<td align="center"><?php echo $vvv['PageModuleI18n']['title']; ?></td>
		<td align="center"><?php echo $vvv['PageModule']['code']; ?></td>
		<td align="center"><?php echo $vvv['PageModule']['position']; ?></td>
		<td align="center"><?php echo $vvv['PageModule']['width']; ?></td>
		<td align="center"><?php echo $vvv['PageModule']['height']; ?></td>
		<td align="center">
			<?php if($vvv['PageModule']['float']==0){echo $ld['module_float_in_entire_row'];}elseif($vvv['PageModule']['float']==1){echo $ld['module_left_floating'];}elseif($vvv['PageModule']['float']==2){echo $ld['module_right_floating'];}?>
		</td>
		<td align="center">
			<?php if(count($vv['SubPageModule'])==1){echo "-";}elseif($kkk==0){?>
             <a onclick="changeOrder('down','<?php echo $vvv['PageModule']['id'];?>','next',this)">&#9660;</a>
            <?php }elseif($kkk==(count($vv['SubPageModule'])-1)){?>
             <a onclick="changeOrder('up','<?php echo $vvv['PageModule']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a>
            <?php }else{?>
             <a onclick="changeOrder('up','<?php echo $vvv['PageModule']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vvv['PageModule']['id'];?>','next',this)">&#9660;</a>
            <?php }?>
		</td>
		<td align="center" style="padding:10px 0px;"><?php if($vvv['PageModule']['status']==1){?>
	<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$vvv["PageModule"]["id"].')')) ?>
	<?php }elseif($vvv['PageModule']['status'] == 0){?>
	<?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_status", '.$vvv["PageModule"]["id"].')'))?>
	<?php }?>
		</td>
		<td>
			<?php
				if($svshow->operator_privilege("page_types_edit")){
					echo $html->link($ld['edit'],"/page_actions/page_module_view/{$vvv['PageModule']['id']}");
				}
				if($svshow->operator_privilege("page_types_remove")){
				echo $html->link($ld['remove'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}page_actions/module_remove/{$vvv['PageModule']['id']}/{$id}';}"));
				}
			?>
		</td>
	</tr>
	<?php
				}
			}
		}
		}
	}
}
?>
	</tbody>
</table>
<?php
$out1 = ob_get_contents();
ob_end_clean(); 
$result=array("content"=>$out1);
die(json_encode($result));
?>