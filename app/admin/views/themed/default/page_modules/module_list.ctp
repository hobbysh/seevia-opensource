<style>
	.action-span {margin-top: -25px;}
	.btnouterlist label{margin-left: -3px;}
	.btnouterlist input{position: relative;bottom: 3px;*position:static;}
</style>
<?php echo $html->css('/skins/default/css/warehouse');?>
<div class="listsearch">
	<?php echo $form->create('PageModules',array('action'=>'/module_list','name'=>"SearchForm","type"=>"get"));?>
		<label>
		<strong><?php echo $ld['pattern search']?></strong>
		<select name="page_style_code">
			<option value="-1"><?php echo $ld['please_select']?></option>
			<option value="0" <?php if(isset($page_style_code) && $page_style_code == "0"){ echo 'selected';}?>><?php echo $ld['no_style_module']?></option>
			<?php if(isset($style_list) && sizeof($style_list)>0){ foreach($style_list as $sk => $s){?>
			<option value="<?php echo $sk;?>" <?php if(isset($page_style_code) && $page_style_code == "$sk"){ echo 'selected';}?>><?php echo $s?></option>
			<?php }}?>
		</select>
		</label>
		<input type="submit" value="<?php echo $ld['search'];?>"/>
	<?php echo $form->end()?>
</div>

<p class="action-span"><?php if($svshow->operator_privilege("modules_add")){echo $html->link($ld['add_module'],"module_view/0/".$code,array("class"=>"addbutton"),'',false,false);}?></p>
<input type="hidden" name="code" id="code" value="<?php echo isset($code)?$code:''?>">
<div id="tablelist" class="tablelist tablebang">
<?php echo $form->create('PageModule',array('action'=>'/','name'=>'PageModuleForm','type'=>'get',"onsubmit"=>"return false;"));?>
	<table id="foldtablelist" class="foldtablelist">
		<thead>
			<tr>
			<th class="thcode"><label><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" /><span><?php echo $ld['module_name']?></span></label></th>
			<th><?php echo $ld['module_title']?></th>
	        <th><?php echo $ld['module_page_coding']?></th>
	        <th><?php echo $ld['module_code']?></th>
			<th><?php echo $ld['module_location']?></th>
			<th><?php echo $ld['module_title']?></th>
			<th><?php echo $ld['module_width']?></th>
			<th><?php echo $ld['module_height']?></th>
			<th><?php echo $ld['module_float']?></th>
			<?php if($code != ""){?>
			<th class="thsort"><?php echo $ld['sort']?></th>
			<?php }?>
			<th class="thicon"><?php echo $ld['status']?></th>
			<th><?php echo $ld['operate']?></th>
			</tr>
		</thead>
		<tbody>
			<?php if(isset($modules_tree) && sizeof($modules_tree)>0){foreach($modules_tree as $k=>$v){?>
			<tr class="tr0">
				<td><span class="<?php echo (isset($v['SubPageModule']) && sizeof($v['SubPageModule'])>0)?"foldbtn":"foldbtnnone";?>" id="<?php echo $v['PageModule']['id']?>"></span><label><input type="checkbox" name="checkboxes[]" value="<?php echo $v['PageModule']['id']?>" /><?php echo $v['PageModuleI18n']['name'];?></label></td>
				<td><?php echo $v['PageModuleI18n']['title'];?></td>
				<td><?php echo $v['PageModule']['code'];?></td>
				<td><?php echo $v['PageModule']['code'];?></td>
				<td><?php foreach( $module_position as $mpk=>$mpv ){if($v['PageModule']['position']==$mpk){echo $mpv;}}?></td>
				<td><?php foreach( $module_types as $mtk=>$mtv ){if($v['PageModule']['type']==$mtk){echo $mtv;}}?></td>
				<td><?php echo $v['PageModule']['width'];?></td>
				<td><?php echo $v['PageModule']['height'];?></td>
				<td><?php if($v['PageModule']['float']==0){echo $ld['module_float_in_entire_row'];}elseif($v['PageModule']['float']==1){echo $ld['module_left_floating'];}elseif($v['PageModule']['float']==2){echo $ld['module_right_floating'];}?></td>
				<?php if($code != ""){?>
				<td class=""><?php if(count($modules_tree)==1){echo "-";}elseif($k==0){?>
                     <a onclick="changeOrder('down','<?php echo $v['PageModule']['id'];?>','0',this)">&#9660;</a>
                     <?php }elseif($k==(count($modules_tree)-1)){?>
                     <a onclick="changeOrder('up','<?php echo $v['PageModule']['id'];?>','0',this)" style="color:#cc0000;">&#9650;</a>
                     <?php }else{?>
                     <a onclick="changeOrder('up','<?php echo $v['PageModule']['id'];?>','0',this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $v['PageModule']['id'];?>','0',this)">&#9660;</a>
                     <?php }?>
                </td>
                 <?php }?>
				<td><?php if($v['PageModule']['status']==1){?>
					<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_modules/toggle_on_status", '.$v["PageModule"]["id"].')')) ?>
					<?php }elseif($v['PageModule']['status'] == 0){?>
					<?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_modules/toggle_on_status", '.$v["PageModule"]["id"].')'))?>
					<?php }?>
				</td>
				<td>
				<?php
					if($svshow->operator_privilege("page_modules_edit")){
					echo $html->link($ld['edit'],"/page_modules/module_view/{$v['PageModule']['id']}");
					}
					if($svshow->operator_privilege("page_modules_remove")){
					echo $html->link($ld['remove'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}page_modules/module_remove/{$v['PageModule']['id']}';}"));
					}
				?></td>
			</tr>
			<!--scoend cat-->
			<?php if(isset($v['SubPageModule']) && sizeof($v['SubPageModule'])>0){foreach($v['SubPageModule'] as $kk=>$vv){?>
			<tr class="tr1">
				<td><span class="<?php echo (isset($vv['SubPageModule']) && sizeof($vv['SubPageModule'])>0)?"foldbtn":"foldbtnnone";?>" id="<?php echo $vv['PageModule']['id']?>"></span><label><input type="checkbox" name="checkboxes[]" value="<?php echo $vv['PageModule']['id']?>" /><?php echo $vv['PageModuleI18n']['name'];?></label></td>
				<td><?php echo $vv['PageModuleI18n']['title'];?></td>
				<td><?php echo $v['PageModule']['code'];?></td>
				<?php if($code != ""){?>
				<td><?php echo $vv['PageModule']['code'];?></td>
				<?php }?>
				<td><?php foreach( $module_position as $mpk=>$mpv ){if($vv['PageModule']['position']==$mpk){echo $mpv;}}?></td>
				<td><?php foreach( $module_types as $mtk=>$mtv ){if($vv['PageModule']['type']==$mtk){echo $mtv;}}?></td>
				<td><?php echo $vv['PageModule']['width'];?></td>
				<td><?php echo $vv['PageModule']['height'];?></td>
				<td><?php if($vv['PageModule']['float']==0){echo $ld['module_float_in_entire_row'];}elseif($vv['PageModule']['float']==1){echo $ld['module_left_floating'];}elseif($vv['PageModule']['float']==2){echo $ld['module_right_floating'];}?></td>
				<td><?php
						if(count($v['SubPageModule'])==1){echo "-";}
						elseif($kk==0){
							?><a onclick="changeOrder('down','<?php echo $vv['PageModule']['id'];?>','next',this)">&#9660;</a><?php
						}elseif($kk==(count($v['SubPageModule'])-1)){
							?><a onclick="changeOrder('up','<?php echo $vv['PageModule']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a><?php
						}else{
							?><a onclick="changeOrder('up','<?php echo $vv['PageModule']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vv['PageModule']['id'];?>','next',this)">&#9660;</a><?php
						}
				?></td>
				<td><?php if($vv['PageModule']['status']==1){?>
					<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_modules/toggle_on_status", '.$vv["PageModule"]["id"].')')) ?>
					<?php }elseif($vv['PageModule']['status'] == 0){?>
					<?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_modules/toggle_on_status", '.$vv["PageModule"]["id"].')'))?>
					<?php }?>
				</td>
				<td>
				<?php
					if($svshow->operator_privilege("modules_edit")){
					echo $html->link($ld['edit'],"/page_modules/module_view/{$vv['PageModule']['id']}");
					}
					if($svshow->operator_privilege("modules_remove")){
					echo $html->link($ld['remove'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}page_modules/module_remove/{$vv['PageModule']['id']}';}"));
					}
				?></td>
			</tr>
			<!--three cat-->
			<?php if(isset($vv['SubPageModule']) && sizeof($vv['SubPageModule'])>0){foreach($vv['SubPageModule'] as $kkk=>$vvv){?>
			<tr class="tr2">
				<td><span class="foldbtnnone" id="<?php echo $vvv['PageModule']['id']?>"></span><label><input type="checkbox" name="checkboxes[]" value="<?php echo $vvv['PageModule']['id']?>" /><?php echo $vvv['PageModuleI18n']['name'];?></label></td>
				<td><?php echo $vvv['PageModuleI18n']['title'];?></td>
				<td><?php echo $v['PageModule']['code'];?></td>
				<?php if($code != ""){?>
				<td><?php echo $vvv['PageModule']['code'];?></td>
				<?php }?>
				<td><?php foreach( $module_position as $mpk=>$mpv ){if($vvv['PageModule']['position']==$mpk){echo $mpv;}}?></td>
				<td><?php foreach( $module_types as $mtk=>$mtv ){if($vvv['PageModule']['type']==$mtk){echo $mtv;}}?></td>
				<td><?php echo $vvv['PageModule']['width'];?></td>
				<td><?php echo $vvv['PageModule']['height'];?></td>
				<td><?php if($vvv['PageModule']['float']==0){echo $ld['module_float_in_entire_row'];}elseif($vvv['PageModule']['float']==1){echo $ld['module_left_floating'];}elseif($vvv['PageModule']['float']==2){echo $ld['module_right_floating'];}?></td>
				<td><?php if(count($vv['SubPageModule'])==1){echo "-";}elseif($kkk==0){?>
					<a onclick="changeOrder('down','<?php echo $vvv['PageModule']['id'];?>','next',this)">&#9660;</a>
					<?php }elseif($kkk==(count($vv['SubPageModule'])-1)){?>
					<a onclick="changeOrder('up','<?php echo $vvv['PageModule']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a>
					<?php }else{?>
					<a onclick="changeOrder('up','<?php echo $vvv['PageModule']['id'];?>','next',this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vvv['PageModule']['id'];?>','next',this)">&#9660;</a>
					<?php }
				?></td>
				<td><?php if($vvv['PageModule']['status']==1){?>
					<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_modules/toggle_on_status", '.$vvv["PageModule"]["id"].')')) ?>
					<?php }elseif($vvv['PageModule']['status'] == 0){?>
					<?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "page_modules/toggle_on_status", '.$vvv["PageModule"]["id"].')'))?>
					<?php }?>
				</td>
				<td>
				<?php
					if($svshow->operator_privilege("modules_edit")){
					echo $html->link($ld['edit'],"/page_modules/module_view/{$vvv['PageModule']['id']}");
					}
					if($svshow->operator_privilege("modules_remove")){
					echo $html->link($ld['remove'],"javascript:;",array("onclick"=>"if(confirm('{$ld['confirm_delete']}')){window.location.href='{$admin_webroot}page_modules/module_remove/{$vvv['PageModule']['id']}';}"));
					}
				?></td>
			</tr>
			<?php }}}}}}else{?>
			<tr>
				<td><?php echo $ld['no_module_data']?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<?php if(isset($modules_tree) && sizeof($modules_tree)){?>
	<div id="btnouterlist" class="btnouterlist">
		<div>
			<label><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" value="checkbox"><span><?php echo $ld['select_all']?></span></label>
			<select name="act_type" id="act_type" onchange="operate_change(this)">
				<option value="0"><?php echo $ld['please_select']?>...</option>
				<?php if($svshow->operator_privilege('modules_remove')){?>
				<option value="delete"><?php echo $ld['delete']?></option>
				<?php }?>
				<option value="a_status"><?php echo $ld['valid_status']?></option>
			</select>
			<select style="display:none" name="is_yes_no" id="is_yes_no">
				<option value="1"><?php echo $ld['yes']?></option>
				<option value="0"><?php echo $ld['no']?></option>
			</select>
			<input type="button" onclick="diachange()" value="<?php echo $ld['submit']?>" />
		</div>
		<?php //echo $this->element('pagers')?>
	</div>
	<?php }?>
	<?php echo $form->end();?>
</div>

<script>
function operate_change(obj){
	if(obj.value=="delete"){
		document.getElementById("is_yes_no").style.display="none";
	}
	if(obj.value=="a_status"){
		document.getElementById("is_yes_no").style.display="inline";
	}
	if(obj.value=="0"){
		document.getElementById("is_yes_no").style.display="none";
	}
}
function diachange(){
	var a=document.getElementById("act_type");
	if(a.value!='0'){
		for(var j=0;j<a.options.length;j++){
			if(a.options[j].selected){
				var vals = a.options[j].text ;
			}
		}
		var id=document.getElementsByName('checkboxes[]');
		var i;
		var j=0;
		var image="";

		for( i=0;i<=parseInt(id.length)-1;i++ ){
			if(id[i].checked){
				j++;
			}
		}
		if( j>=1 ){
			if(confirm("<?php echo $ld['submit']?>"+vals+'?'))
			{
				batch_action();
			}
		}else{
			if(confirm(j_please_select))
			{
				batch_action();
			}
		}
	}
}

function batch_action()
{
var code = document.getElementById("code").value;
document.PageModuleForm.action=admin_webroot+"page_modules/batch/"+code;
document.PageModuleForm.onsubmit= "";
document.PageModuleForm.submit();
}
function changeOrder(updown,id,next,thisbtn){
	var code = document.getElementById("code").value;
	changeHtml(thisbtn);
 	YUI().use("io",function(Y) {
		var sUrl = "/admin/page_modules/changeorder/"+updown+"/"+id+"/"+next+"/"+code;//访问的URL地址
		var cfg = {
				method: 'POST'
		};
		var request = Y.io(sUrl, cfg);//开始请求
		var handleSuccess = function(ioId, o){
			try{
				var node = Y.one('#tablelist');
				var popcontent = document.createElement('div');
				popcontent.innerHTML = o.responseText;
				var tmp = outerHTML(popcontent.getElementsByTagName('table')[0].parentNode);
				node.set('innerHTML',tmp);
			}catch (e){
				alert("<?php echo $ld['object_transform_failed']?>");
				alert(o.responseText);
			}
			inita();
			rowClick(id);
		}
		var handleFailure = function(ioId, o){
			alert("<?php echo $ld['asynchronous_request_failed']?>");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});
}
</script>