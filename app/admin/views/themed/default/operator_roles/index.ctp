<style type="text/css">
.am-checkbox {margin-top:0px; margin-bottom:0px;}
.am-panel-title div{font-weight:bold;} 
.am-form-label{font-weight:bold;top:-5px; left:10px;} 
</style>
<div class="listsearch" style="margin-top:10px;">
	<?php echo $form->create('',array('action'=>'/','name'=>'SearchForm','type'=>'get','class'=>'am-form am-form-horizontal'));?>
		<div class="am-form-group">
			<label class="am-u-lg-1 am-u-md-1 am-u-sm-3 am-form-label  "><?php echo $ld['keyword'];?></label>
			<div class="am-u-lg-3 am-u-md-4 am-u-sm-4">
				<input type="text" id="role_name" placeholder="<?php echo $ld['role_role_name']?>"  name="role_name" <?php if(isset($role_name)){?>value="<?php echo $role_name;?>"<?php }?> />
			</div>
			<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
				<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"><?php echo $ld['search'];?></button>
			</div>
		</div>
	<?php echo $form->end();?>
</div>
				
<div class="am-g am-other_action">
	<div class="am-fr am-u-lg-12 am-btn-group-xs" style="text-align:right;margin-bottom:10px;margin-right:15px;">
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/roles/add/'); ?>">
			<span class="am-icon-plus"></span><?php echo $ld['add'] ?>
		</a>
	</div>
</div>
	
<?php echo $form->create('',array('action'=>''));?>
<div class="am-panel-group am-panel-tree">
	<div class="listtable_div_btm  am-panel-header">
		<div class="am-panel-hd">
			<div class="am-panel-title am-g">
				<div class="am-u-lg-2 am-u-md-5 am-u-sm-5">
					<label class="am-checkbox am-success" style="font-weight:bold;">
							<span class="am-hide-sm-only"><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck type="checkbox" /></span>
						<span><?php echo $ld['role_role_name']?></span>
					</label>
				</div>
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['role_role_num']?></div>
				<div class="am-u-lg-4 am-show-lg-only"><?php echo $ld['role_rights_summary']?></div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['operate']?></div>
			</div>
		</div>
	</div>
	
	<?php if(isset($role_list) && sizeof($role_list)>0){foreach($role_list as $k=>$v){?>
		<div>	
		<div class="listtable_div_top am-panel-body">
			<div class="am-panel-bd am-g">
				<div class="am-u-lg-2 am-u-md-5 am-u-sm-5">
					<label class="am-checkbox am-success">
						<span class="am-hide-sm-only"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['OperatorRole']['id']?>" /></span>
						<?php echo $v['OperatorRoleI18n']['name']?>
					</label>
				</div>
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 "><?php echo $v['OperatorRole']['number']?>&nbsp;</div>
				<div class="am-u-lg-4  am-show-lg-only" style="text-overflow:ellipsis;overflow:hidden;">
					<?php echo $v['OperatorRole']['actions']?>
				</div>			
				<div class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-action">
					<?php if($svshow->operator_privilege("operator_roles_edit")){?>
					 
						 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/roles/edit/'.$v['OperatorRole']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
						
				<?php 	}
					if($svshow->operator_privilege("operator_roles_remove")){?>
					 
						
							<a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:void(0);" onclick="if(confirm(j_confirm_delete)){window.location.href=admin_webroot+'roles/remove/<?php echo $v['OperatorRole']['id'] ?>';}">
						<span class="am-icon-trash-o"></span> <?php echo $ld['remove']; ?>
						</a>
				<?php 	}
					?>
						
				</div>
			</div>
		</div>
		</div>
		<?php }	}else{?>
			<div style="text-align:center;margin-top:30px;"><?php echo $ld['no_records']?></div>
		<?php }?>
</div>
				
<?php if($svshow->operator_privilege("operator_roles_remove")){?>
	<?php if(isset($role_list) && sizeof($role_list)){?>
		<div id="btnouterlist" class="btnouterlist am-form-group ">
			<div  class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-hide-sm-only">
				<label class="am-checkbox am-success" style="font-size:14px; line-height:14px;display: inline;"><input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck type="checkbox" /><?php echo $ld['select_all']?></label>&nbsp;
				<button type="button" class="am-btn am-btn-danger am-btn-sm am-radius" value="" onclick="batch_operations()" ><?php echo $ld['delete']?></button>
			</div>
			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				<?php echo $this->element('pagers')?>
			</div>
            <div class="am-cf"></div>
		</div>
	<?php }?>
<?php }?>
<?php echo $form->end();?>
<script>

function batch_operations(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if( postData=="" ){
		alert("<?php echo $ld['please_select'] ?>");
		return;
	}
	if(confirm("<?php echo $ld['confirm_delete']?>")){
		$.ajax({
			url:admin_webroot+"roles/batch_operations/",
			type:"POST",
			dataType:"json",
			data: postData,
			success:function(data){
				window.location.href = window.location.href;
			}
		});
	}
}
</script>