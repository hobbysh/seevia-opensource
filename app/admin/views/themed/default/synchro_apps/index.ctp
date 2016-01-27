<style>
 .am-panel-title{font-weight:bold;}
 .am-checkbox input[type="checkbox"]{margin-left:0;}
 .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
</style>
<div>	
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;"> 
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('view/'); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a>
	</div>
	<div class="am-panel-group am-panel-tree"  id="accordion">
		<div class="listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title am-g">
					<div class="am-u-lg-3 am-u-md-4 am-u-sm-4" >
						<label class="am-checkbox am-success  am-hide-sm-down" style="font-weight:bold;">
							<input type="checkbox" value="checkbox" data-am-ucheck onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"/>
						<?php echo $ld['interface_name']?>
						</label>
	<label class="am-checkbox am-success am-show-sm-down" style="font-weight:bold;"><?php echo $ld['interface_name']?></label>
					</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-4"><?php echo $ld['status']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-hide-sm-only"><?php echo $ld['type']?></div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['create_time']?></div>
					<div class="am-u-lg-3 am-u-md-4 am-u-sm-4"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(count($data)>0){foreach($data as $k=>$v){?>
			<div>
				<div class="listtable_div_top am-panel-body">
					<div class="am-panel-bd am-g">
						<div class="am-u-lg-3  am-u-md-4 am-u-sm-4 ">
							<label class="am-checkbox am-success  am-hide-sm-down">
								<input type="checkbox" name="checkboxes[]" value="<?php echo $v['UserApp']['id']?>" data-am-ucheck />
								<?php echo $v['UserApp']['name']?>&nbsp;
							</label>
								 <label class="am-checkbox am-success  am-show-sm-down">
							       <?php echo $v['UserApp']['name']?>&nbsp;
							</label>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-4">
							<?php if(isset($v['UserApp']['status']) && $v['UserApp']['status'] == '1'){?>
							<?php echo $ld['enabled']?>
							<?php }else{?>
							<?php echo $ld['disable']?>
							<?php }?>&nbsp;
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-hide-sm-only"><?php echo $v['UserApp']['type']?>&nbsp;</div>
						<div class="am-u-lg-2 am-show-lg-only"><?php echo $v['UserApp']['created']?>&nbsp;</div>
						<div class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-action">
							<?php if($svshow->operator_privilege("synchro_apps_edit")){?>
							 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/synchro_apps/view/'.$v['UserApp']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
			 	<?php }?>
			 	<?php if($svshow->operator_privilege("synchro_apps_delete")){?>
			 <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'synchro_apps/remove/<?php echo $v['UserApp']['id'] ?>');">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
										<?php }?>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		<?php }}else{?>
			    <div class="no_data_found"><?php echo $ld['no_data_found']?></div>
				
			</div>
		<?php }?>
	</div>
	<?php if(isset($data) && sizeof($data)>0){?>
		<div id="btnouterlist" class="btnouterlist" >
			<div class="am-u-lg-3 am-u-md-4 am-hide-sm-down" style="margin-left:7px"> 
			    <label class="am-checkbox am-success">
					<input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck type="checkbox" />
					<?php echo $ld['select_all']?>
				</label>&nbsp;&nbsp;
				<button type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="batch_delete()" value="<?php echo $ld['delete']?>"> <?php echo $ld['batch_delete']?></button>
			</div>
			<div class="am-u-lg-8 am-u-md-7 am-u-sm-12">
				<?php echo $this->element('pagers')?>
			</div>
		</div>
	<?php }?>
</div>	
	
<script type="text/javascript">
function batch_delete(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		$.ajax({
			url:admin_webroot+"synchro_apps/removeall/",
			type:"POST",
			data:postData,
			dataType:"json",
			success:function(data){
				
				window.location.href = window.location.href;
			}		
		});
 
	}
}
</script>