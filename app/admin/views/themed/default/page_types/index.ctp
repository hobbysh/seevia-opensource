<style>
	.action-span {margin-top: -25px;}
</style>
<?php echo $html->css('/skins/default/css/warehouse');?>
<div class="listsearch">
	<?php echo $form->create('PageType',array('action'=>'/','name'=>"SeearchForm","type"=>"get"));?>
		<label>
			<strong><?php echo $ld['type'];?></strong>
			<select name="type">
				<option value=""><?php echo $ld['please_select']?></option>
				<option value="1" <?php if(@$type1=="1"){echo "selected";}?>><?php echo $ld['mobilephone']?></option>
				<option value="0" <?php if(@$type1=="0"){echo "selected";}?>><?php echo $ld['computer']?></option>
			</select>
		</label>
		<label>
			<strong><?php echo $ld['status'];?></strong>
			<select name="status">
				<option value=""><?php echo $ld['please_select']?></option>
				<option value="1" <?php if(@$status=="1"){echo "selected";}?>><?php echo $ld['valid']?></option>
				<option value="0" <?php if(@$status=="0"){echo "selected";}?>><?php echo $ld['invalid']?></option>
			</select>
		</label>
		<label>
			<strong><?php echo $ld['keyword'];?></strong>
			<input type="text" name="keywords" id="keywords" value="<?php echo isset($keywords)?$keywords:'';?>" />
		</label>
		<input type="submit" value="<?php echo $ld['search'];?>"/>
	<?php echo $form->end()?>
</div>
<p class="action-span"><?php if($svshow->operator_privilege("page_types_add")){echo $html->link($ld['add_module'],"view/",array("class"=>"addbutton"),'',false,false);}?></p>
<div id="tablelist" class="tablelist tablebang">
<?php echo $form->create('PageType',array('action'=>'/','name'=>'PageForm','type'=>'get',"onsubmit"=>"return false;"));?>
	<table id="foldtablelist" class="foldtablelist">
		<thead>
			<tr>
			<th style="text-align:center;"><?php echo $ld['type'];?></th>
			<th style="text-align:center;" class="thcode"><?php echo $ld['code']?></th>
			<th><?php echo $ld['module_type']?></th>
			<th style="width:70px;"><?php echo $ld['status']?></th>
			<th style="text-align:center;"><?php echo $ld['operate']?></th>
			</tr>
		</thead>
		<tbody>
		<?php if(isset($page_list) && sizeof($page_list)>0){foreach($page_list as $k=>$v){?>
		<tr>
			<td style="text-align:center;"><?php if($v['PageType']['page_type']==1){?>
				<?php echo $ld['mobilephone']?>
				<?php }elseif($v['PageType']['page_type'] == 0){ ?>
				<?php echo $ld['computer']?>
				<?php }?>
				</td>
			<td style="text-align:center;"><?php echo $v['PageType']['code'];?></td>
			<td style="text-align:center;"><?php echo $v['PageType']['name'];?></td>
			<td style="text-align:center;"><?php if($v['PageType']['status']==1){?>
				<?php echo $html->image('yes.gif') ?>
				<?php }elseif($v['PageType']['status'] == 0){?>
				<?php echo $html->image('no.gif')?>
				<?php }?>
			</td>
			<td style="text-align:left;">
			<?php
				//echo $html->link($ld['view_page_style'],"/page_actions/{$v['PageType']['id']}");
				if($svshow->operator_privilege("page_types_reomve")){
				echo $html->link($ld['edit'],"/page_types/view/{$v['PageType']['id']}");
				if($v['PageType']['status']==0){echo $html->link($ld['delete'],"javascript:;",array("onclick"=>"if(confirm('确认删除该模块吗？（删除模块信息，页面样式，样式模块）')){list_delete_submit('{$admin_webroot}page_types/remove/{$v['PageType']['id']}');}"));}
				}
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