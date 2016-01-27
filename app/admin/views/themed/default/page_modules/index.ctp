<style>
	.action-span {margin-top: -25px;}
</style>
<?php echo $html->css('/skins/default/css/warehouse');?>
<div class="listsearch">
	<?php echo $form->create('PageModules',array('action'=>'/','name'=>"SeearchForm","type"=>"get"));?>
		<label>
			<strong><?php echo $ld['keyword'];?></strong>
			<input type="text" name="keywords" id="keywords" value="<?php echo isset($keywords)?$keywords:'';?>" />
		</label>
		<label>
			<strong><?php echo $ld['status'];?></strong>
			<select name="status">
				<option value=""><?php echo $ld['please_select']?></option>
				<option value="1" <?php if(@$status=="1"){echo "selected";}?>><?php echo $ld['valid']?></option>
				<option value="0" <?php if(@$status=="0"){echo "selected";}?>><?php echo $ld['invalid']?></option>
			</select>
		</label>
		<input type="submit" value="<?php echo $ld['search'];?>"/>
	<?php echo $form->end()?>
</div>
<p class="action-span"><?php if($svshow->operator_privilege("modules_add")){echo $html->link($ld['add_page'],"view/",array("class"=>"addbutton"),'',false,false);}?></p>
<div id="tablelist" class="tablelist tablebang">
<?php echo $form->create('PageAction',array('action'=>'/','name'=>'PageForm','type'=>'get',"onsubmit"=>"return false;"));?>
	<table id="foldtablelist" class="foldtablelist">
		<thead>
			<tr>
			<th class="thcode"><?php echo $ld['page_name']?></th>
			<th><?php echo $ld['controller']?></th>
			<th><?php echo $ld['method']?></th>
			<th><?php echo $ld['status']?></th>
			<th><?php echo $ld['operate']?></th>
			</tr>
		</thead>
		<tbody>
		<?php if(isset($page_list) && sizeof($page_list)>0){foreach($page_list as $k=>$v){?>
			<td><?php echo $v['PageAction']['name'];?></td>
			<td><?php echo $v['PageAction']['controller'];?></td>
			<td><?php echo $v['PageAction']['action'];?></td>
			<td><?php if($v['PageAction']['status']==1){?>
				<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_modules/toggle_on_page_status", '.$v["PageAction"]["id"].')')) ?>
				<?php }elseif($v['PageAction']['status'] == 0){?>
				<?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_modules/toggle_on_page_status", '.$v["PageAction"]["id"].')'))?>
				<?php }?>
			</td>
			<td>
			<?php
				echo $html->link($ld['view_page_style'],"/page_modules/page_style_list/{$v['PageAction']['id']}");
				echo $html->link($ld['edit'],"/page_modules/view/{$v['PageAction']['id']}");
				echo $html->link($ld['delete'],"javascript:;",array("onclick"=>"if(confirm('确认删除该页面吗？（删除页面信息，页面样式，样式模块）')){list_delete_submit('{$admin_webroot}page_modules/remove/{$v['PageAction']['id']}');}"));
			?>
			</td>
		</tr>
	   <?php }}else{?>
		<tr>
			<td><?php echo $ld['no_page_data']?></td>
		</tr>
		<?php }?>
		</tbody>
	</table>
	<?php echo $form->end();?>
</div>