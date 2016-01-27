<style>
	.action-span {margin-top: -25px;}
</style>
<?php echo $html->css('/skins/default/css/warehouse');?>
<div class="listsearch">
	<?php echo $form->create('PageAction',array('action'=>'/view/'.$id,'name'=>"SeearchForm","type"=>"get"));?>
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
<?php if($svshow->operator_privilege("page_types_add")){?>
<p class="action-span">
	<?php echo $html->link("新增模块页面","page_action_view/0?type_id=".$id,array("class"=>"addbutton"),'',false,false);?>
</p>
<?php }?>
<div class="tablelist tablebang">
<?php echo $form->create('PageAction',array('action'=>'/removeAll/{$id}','name'=>'PageActionForm','type'=>'get',"onsubmit"=>"return false;"));?>
	<table id="foldtablelist" class="foldtablelist">
		<thead>
			<tr>
				<th class="thcode"><label><input type="checkbox" onclick="listTable.selectAll(this,'checkboxes[]')"></input><?php echo $ld['number']?></lable></th>
				<th><?php echo $ld['page_name']?></th>
				<th><?php echo $ld['controller']?></th>
				<th><?php echo $ld['method']?></th>
				<th style="width:70px;"><?php echo $ld['status']?></th>
				<th><?php echo $ld['operate']?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			if(isset($pageaction_list)&&sizeof($pageaction_list)>0){
				foreach($pageaction_list as $k=>$v){
		?>
			<tr>
				<td>
					<label><input type="checkbox" name="checkboxes[]" value="<?php echo $v['PageAction']['id']?>" /><?php echo $v['PageAction']['id']?></lable>
				</td>
				<td align="center"><?php echo $v['PageAction']['name'];?></td>
				<td align="center"><?php echo $v['PageAction']['controller'];?></td>
				<td align="center"><?php echo $v['PageAction']['action'];?></td>
				<td align="center">
				<?php if($v['PageAction']['status']==1){?>
					<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_page_status", '.$v["PageAction"]["id"].')')) ?>
					<?php }elseif($v['PageAction']['status'] == 0){?>
					<?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_actions/toggle_on_page_status", '.$v["PageAction"]["id"].')'))?>
					<?php }?>
				</td>
				<td>
					<?php
					if(isset($pagetype_info)&&$pagetype_info['PageType']['page_type']=='1'&&$svshow->operator_privilege("page_types_edit")){
					echo $html->link($ld['preview'],$server_host."/{$v['PageAction']['controller']}/{$v['PageAction']['action']}?is_mobile=1");
				}else{
					echo $html->link($ld['preview'],$server_host."/{$v['PageAction']['controller']}/{$v['PageAction']['action']}?is_mobile=0");
				}
				if($svshow->operator_privilege("page_types_edit")){
					echo $html->link($ld['edit'],"/page_actions/page_action_view/{$v['PageAction']['id']}?type_id={$id}");
				}
				if($svshow->operator_privilege("page_types_remove")){
				echo $html->link($ld['delete'],"javascript:;",array("onclick"=>"if(confirm('确认删除该页面样式吗?')){list_delete_submit('{$admin_webroot}page_actions/remove/{$v['PageAction']['id']}');}"));
				}
					?>
				</td>
			</tr>
		<?php
				}
			}else{
				$noo=1;
		?>
			<tr>
				<td colspan='6' style="text-align:center;height:30px;">没有模块页面！</td>
			</tr>
		<?php
			}
		?>
		</tbody>
	</table>
	<div id="btnouterlist" class="btnouterlist" style="<?php if(isset($noo)&&$noo==1){echo 'display:none';} ?>">
		<?php if($svshow->operator_privilege("page_types_remove")){?>
	    <div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
	        <label>
	            <input type="checkbox" onclick="listTable.selectAll(this,'checkboxes[]')"></input>
	            <span><?php echo $ld['select_all'] ?></span>
	        </label>
	        <input type="button" onclick="removeAll()" value="<?php echo $ld['batch_delete'] ?>" />
	    </div>
	    <?php }?>
        <div class="am-u-lg-6 am-u-md-12 am-u-sm-12"><?php echo $this->element('pagers')?></div>
        <div class="am-cf"></div>
    </div>
<?php echo $form->end();?>
</div>
<script type="text/javascript">
function removeAll()
{
	var ck=document.getElementsByName('checkboxes[]');
	var j=0;
	for(var i=0;i<=parseInt(ck.length)-1;i++)
	{
		if(ck[i].checked)
		{
			j++;
		}
	}
	if(j>=1){
		if(confirm('确认删除？'))
		{
			document.PageActionForm.action=admin_webroot+"page_actions/removeAll/<?php echo $id; ?>";
			document.PageActionForm.onsubmit= "";
			document.PageActionForm.submit();
		}
	}
}
</script>