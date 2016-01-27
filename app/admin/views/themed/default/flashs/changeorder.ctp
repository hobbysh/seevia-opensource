<div class="am-panel am-panel-default am-panel-header">
	<div class="am-panel-hd">
		<div class="am-panel-title am-g">
			<div class="am-u-lg-1 am-show-lg-only">	
				<label class="am-checkbox am-success">
					<input type="checkbox" name="checkbox" data-am-ucheck value="checkbox" onclick='listTable.selectAll(this,"checkboxes[]")'/>
					<?php echo $ld['number']?>
				</label>
			</div>
			<div class="am-u-lg-3 am-u-md-6 am-u-sm-6"><?php echo $ld['picture']?></div>
			<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['type']?></div>
			<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['page']?></div>
			<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['language']?></div>
			<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['valid']?></div>
			<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['sort']?></div>
			<div class="am-u-lg-2 am-u-md-4 am-u-sm-4"><?php echo $ld['operate']?></div>
			<div style="clear:both"></div>
		</div>	
	</div>
</div>
<?php if(isset($flash_image_data) && sizeof($flash_image_data)>0){foreach($flash_image_data as $k=>$v){?>
<?php// pr($flash_image_data);?>
	<div>
		<div class="am-panel am-panel-default am-panel-body">
			<div class="am-panel-bd am-g">
				<div class="am-u-lg-1 am-show-lg-only">
					<label class="am-checkbox am-success">
						<input type="checkbox" name="checkboxes[]" value="<?php echo $v['FlashImage']['id']?>" data-am-ucheck />
						<?php echo $v['FlashImage']['id']?>
					</label>
				</div>
				<div class="am-u-lg-3 am-u-md-6 am-u-sm-6">
					<?php if($v['FlashImage']['image']){?>
						<img src="<?php echo $v['FlashImage']['image']?>" style="height:50px;padding:8px 0 0" >
					<?php }?>
				</div>
				<div class="am-u-lg-1 am-show-lg-only">
					<?php echo $flash_info['Flashe']['type']=='0'?$ld['computer']:$ld['mobile']; ?>
				</div>
				<div class="am-u-lg-1 am-show-lg-only">
					<?php echo @$Resource_info["flashtypes"][$flash_info["Flashe"]["page"]];?>
				</div>
				<div class="am-u-lg-2 am-show-lg-only">
					<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach($backend_locales as $vv){ if($vv['Language']['locale']==$v['FlashImage']['locale']){echo $vv['Language']['name'];} }}?>
				</div>
				<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
					<?php if ($v['FlashImage']['status'] == 1){?>
						<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'flashs/toggle_on_status',<?php echo $v['FlashImage']['id'];?>)"></span>
					<?php }elseif($v['FlashImage']['status'] == 0){?>
						<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'flashs/toggle_on_status',<?php echo $v['FlashImage']['id'];?>)">&nbsp;</span>										
					<?php }?>
				</div>
				<div class="am-u-lg-1 am-show-lg-only">
					<?php if(count($flash_image_data)==1){echo "-";}elseif($k==0){?>
						<a onclick="changeOrder('down','<?php echo $v['FlashImage']['id'];?>','0',this)">&#9660;</a>
					<?php }elseif($k==(count($flash_image_data)-1)){?>
						<a onclick="changeOrder('up','<?php echo $v['FlashImage']['id'];?>','0',this)" style="color:#cc0000;">&#9650;</a>
					<?php }else{?>
						<a onclick="changeOrder('up','<?php echo $v['FlashImage']['id'];?>','0',this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $v['FlashImage']['id'];?>','0',this) ">&#9660;</a>
					<?php }?>
				</div>
				<div class="am-u-lg-2 am-u-md-4 am-u-sm-4">
					<?php if($svshow->operator_privilege("flashs_edit")){echo $html->link($ld['edit'],"/flashs/view/{$v['FlashImage']['id']}",array("class"=>"am-btn am-btn-success am-btn-sm am-radius")).'&nbsp;';}
						if($svshow->operator_privilege("flashs_remove")){echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-danger am-btn-sm am-radius","onclick"=>"list_delete_submit('{$admin_webroot}flashs/remove/{$v['FlashImage']['id']}')"));}?>
				</div>
				<div style="clear:both"></div>
			</div>
		</div>
	</div>
<?php }}else{?>
	<div style="margin:50px;text-align:center;">
		<div><?php echo $ld['no_circle_image']?></div>
	</div>
<?php }?>	
	
<!--<table>
		<thead>
			<tr>
				<th class="thcode"><label><input type="checkbox" name="checkbox" value="checkbox" onclick='listTable.selectAll(this,"checkboxes[]")'/><span><?php echo $ld['number']?></span></label></th>
				<th><?php echo $ld['picture']?></th>
				<th width="200px"><?php echo $ld['type']?></th>
				<th width="200px">页面</th>
				<th><?php echo $ld['language']?></th>
				<th class="thicon"><?php echo $ld['valid']?></th>
				<th class="thsort"><?php echo $ld['sort']?></th>
				<th><?php echo $ld['operate']?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($flash_image_data) && sizeof($flash_image_data)>0){foreach($flash_image_data as $k=>$v){?>
			<tr>
				<td><label><input type="checkbox" name="checkboxes[]" value="<?php echo $v['FlashImage']['id']?>" /><span><?php echo $v['FlashImage']['id']?></span></label></td>
				<td><?php if($v['FlashImage']['image']){?><img src="<?php echo $v['FlashImage']['image']?>" style="height:50px;padding:8px 0 0" ><?php }?></td>
				<td><?php echo $flash_info['Flashe']['type']=='0'?'电脑':'手机'; ?></td>
				<td><?php echo @$Resource_info["flashtypes"][$flash_info["Flashe"]["page"]];?></td>
				<td><?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach($backend_locales as $vv){ if($vv['Language']['locale']==$v['FlashImage']['locale']){echo $vv['Language']['name'];} }}?></td>
				<td><?php if ($v['FlashImage']['status'] == 1){?>
					<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "flashs/toggle_on_status", '.$v["FlashImage"]["id"].')')) ?>
					<?php }elseif($v['FlashImage']['status'] == 0){?>
					<?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "flashs/toggle_on_status", '.$v["FlashImage"]["id"].')'))?>
					<?php }?></td>
				<td>
					<?php if(count($flash_image_data)==1){echo "-";}elseif($k==0){?>
					<a onclick="changeOrder('down','<?php echo $v['FlashImage']['id'];?>')">&#9660;</a>
					<?php }elseif($k==(count($flash_image_data)-1)){?>
					<a onclick="changeOrder('up','<?php echo $v['FlashImage']['id'];?>')" style="color:#cc0000;">&#9650;</a>
					<?php }else{?>
					<a onclick="changeOrder('up','<?php echo $v['FlashImage']['id'];?>')" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $v['FlashImage']['id'];?>','0','') ">&#9660;</a>
					<?php }?>
				</td>
				<td><?php
					if($svshow->operator_privilege("flashs_edit")){
					echo $html->link($ld['edit'],"/flashs/view/{$v['FlashImage']['id']}");
					}
					if($svshow->operator_privilege("flashs_remove")){
					echo $html->link($ld['delete'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete_circle_image']}')){list_delete_submit('{$admin_webroot}flashs/remove/{$v['FlashImage']['id']}');}"));
					}
				?></td>
			</tr>
			<?php }}else{?>
			<tr>
				<td><?php echo $ld['no_circle_image']?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>-->