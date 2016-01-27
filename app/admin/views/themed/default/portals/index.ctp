<div class="listsearch">
	<?php echo $form->create('Portal',array('action'=>'/index','name'=>"SeearchForm","type"=>"get",'class'=>'am-form am-form-horizontal'));?>
    <ul class="am-avg-lg-4 am-avg-md-2 am-avg-sm-1">
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['title']; ?></label>
            <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                <input type="text" name="keywords" id="keywords" value="<?php echo isset($keywords)?$keywords:'';?>" />
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['type']; ?></label>
            <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                <select name="selecttype" data-am-selected="{noSelectedText:'<?php echo $ld['all_types']; ?>'}">
    				<option value=""><?php echo $ld['all_types']; ?></option>
    				<option value="iframe" <?php echo isset($selecttype)&&$selecttype=='iframe'?'selected':'' ?>>Iframe</option>
    				<option value="html" <?php echo isset($selecttype)&&$selecttype=='html'?'selected':'' ?>>html</option>
    			</select>
            </div>
        </li>
        <li>
            <div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
                <input type="submit" class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>"/>
            </div>
        </li>
    </ul>
	<?php echo $form->end()?>
</div>
<div class="am-text-right am-btn-group-xs" style="clear:both;margin:10px auto;">
    <a class="am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/portals/view/0'); ?>">
        <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
    </a>
</div>
<div id="tablelist" class="tablelist">
	<table class="am-table  table-main">
		<thead>
			<tr>
				<th width="10%"><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck /><b> <?php echo $ld['number'];?></b> </label></th>
				<th><?php echo $ld['title'];?></th>
				<th><?php echo $ld['type'];?></th>
    			<th><?php echo $ld['default'].$ld['list'];?></th>
    			<th><?php echo $ld['status']?></th>
    			<th><?php echo $ld['operate']; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php if(isset($portal_list)&&sizeof($portal_list)>0){ foreach($portal_list as $k=>$v){ ?>
			<tr>
				<td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Portal']['id']?>" /><?php echo $v['Portal']['id'];?></td>
				<td><?php echo $v['Portal']['name']; ?></td>
				<td><?php echo $v['Portal']['type']; ?></td>
				<td><?php echo $ld['list'];?><?php echo str_replace('list','',$v['Portal']['default_list']); ?></td>
				<td>
					<?php
						if($v["Portal"]["status"]==1){
							echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "portals/toggle_on_status", '.$v["Portal"]["id"].')'));
						}else{
							echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "portals/toggle_on_status", '.$v["Portal"]["id"].')'));
						}
					?>
				</td>
				<td class="am-action">
                         <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/portals/view/'.$v['Portal']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                    	<a class="am-btn am-btn-default am-btn-xs am-text-danger   am-seevia-btn-delete" href="javascript:void(0);" onclick="if(confirm(j_confirm_delete)){list_delete_submit(admin_webroot+'/portals/remove/<?php echo $v['Portal']['id'] ?>');}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?>
                      </a>
				</td>
			</tr>
		<?php }}else{ ?>
			<tr>
				<td colspan="6" class="no_data_found"><?php echo $ld['no_data_found']; ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
    <?php if(isset($portal_list)&&sizeof($portal_list)>0){  ?>
	<div id="btnouterlist" class="btnouterlist">
		<div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
            <div class="am-fl">
	            <label class="am-checkbox am-success"><input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?></label></div>&nbsp;
            <div class="am-fl">
	            <input type="button"  class="am-btn am-btn-sm am-btn-danger am-btn-radius"  onclick="removeAll()" value="<?php echo $ld['batch_delete']; ?>" /></div>
	    </div>
		<div class="am-u-lg-6 am-u-md-12 am-u-sm-12">
            <?php echo $this->element('pagers')?>
        </div>
        <div class="am-cf"></div>
	</div>
    <?php } ?>
</div>
<style type="text/css">
.ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    text-transform: capitalize;
    white-space: nowrap;
}
</style>
<script>
function removeAll(){
	var select_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<select_check.length;i++){
		if(select_check[i].checked){
			postData+="&checkboxes[]="+select_check[i].value;
		}
	}
	if(postData==""){
		alert('请先选择要删除的对象');
		return;
	}
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		YUI().use("io",function(Y) {
			var sUrl = admin_webroot+"portals/removeAll/";//访问的URL地址
			var cfg = {
				method: "POST",
				data: postData
			};
			var request = Y.io(sUrl, cfg);//开始请求
			var handleSuccess = function(ioId, o){
				var responseText = o.responseText;
				if(responseText!=null){
					var result=eval("("+responseText+")");
					alert(result.message);
				}
				window.location.href = window.location.href;
			}
			var handleFailure = function(ioId, o){
				//alert("操作失败");
			}
			Y.on('io:success', handleSuccess);
			Y.on('io:failure', handleFailure);
		});
	}
}
</script>