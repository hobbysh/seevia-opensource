<style type="text/css">
 .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
 .am-yes{color:#5eb95e;}
 .am-no{color:#dd514c;}
 .am-panel-title div{font-weight:bold;}
</style>
<div>
	<?php echo $form->create('Material',array('action'=>'/','name'=>"Material","class"=>"am-form am-form-horizontal","type"=>"get"));?>
		<div class="am-form-group">
			<label class="am-u-lg-1 am-u-md-1 am-u-sm-3 am-form-label am-text-center" style="font-weight:bold;"><?php echo $ld['keyword'];?></label>&nbsp;
			<div  class="am-u-lg-2 am-u-md-3 am-u-sm-6">
			  <input type="text" name="material_keywords" value="<?php echo @ $material_keywords;?>" placeholder="<?php echo $ld['code']?>/<?php echo $ld['name']?>"/>
			</div>
			<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"style="top:2px;">
				<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"  onclick="search_user()" ><?php echo $ld['search'];?></button>
			</div>
  		</div>
	<?php echo $form->end();?>
	<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<?php if($svshow->operator_privilege('material_add')){?>
			<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/materials/view/'); ?>"><span class="am-icon-plus"></span> <?php echo $ld['add'] ?></a>
		<?php }?>
	</div>
	<?php echo $form->create('Material',array('action'=>'/','name'=>'Material','type'=>'get'));?>
		<div class="am-panel-group am-panel-tree">
			<div class=" listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title am-g">
						<div class="am-u-lg-3 am-u-md-2 am-hide-sm-only">
							<label class="am-checkbox am-success" style="font-weight:bold;">
								<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"  data-am-ucheck/>
								<?php echo $ld['code']?>
							</label>
						</div>
						<div class="am-u-lg-3 am-u-md-2 am-u-sm-3"><?php echo $ld['name']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['quantity']?></div>
						<div class="am-u-lg-1 am-u-md-1 am-hide-sm-only"><?php echo $ld['unit']?></div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['status']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-4"><?php echo $ld['operate']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		<?php if(isset($result) && sizeof($result)>0){foreach($result as $key=>$value){?>
			<div>
				<div class=" listtable_div_top am-panel-body">
					<div class="am-panel-bd am-g">
						<div class="am-u-lg-3 am-u-md-2 am-hide-sm-only" >
							<label class="am-checkbox  am-success">
								<input type="checkbox" name="checkboxes[]" value="<?php echo $value['Material']['id']?>"   data-am-ucheck/>
								<?php echo $value['Material']['code'] ?>&nbsp;
							</label>
						</div>
						<div class="am-u-lg-3 am-u-md-2 am-u-sm-3" ><?php echo $value['MaterialI18n']['name'];?>&nbsp;</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $value['Material']['quantity'];?>&nbsp;</div>
						<div class="am-u-lg-1 am-u-md-1 am-hide-sm-only" > <?php echo $value['Material']['unit'];?>&nbsp;</div>
						<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
							<?php if($value['Material']['status'])echo $html->image('yes.gif');else echo $html->image('no.gif');?>
						</div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-btn-group-xs am-action" >
								
							<?php if($svshow->operator_privilege("material_edit")){?>
						<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/materials/view/'.$value['Material']['id']); ?>">
                                   <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                                   </a>
                    				  <?php }?>
								
							  <?php if($svshow->operator_privilege("material_remove")){?>
							  	<a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'materials/remove/<?php echo $value['Material']['id']; ?>')">
                                  <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                     </a>
							  	  
							  	  
							  	  	  
							<?php  }?>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php }  }else{?> 
							
					 	<div  class="no_data_found"><?php echo $ld['no_data_found'];?></div>
							
					  <?php } ?>
					 
		</div>
		<?php if(isset($result) && sizeof($result)){?>
				<div id="btnouterlist" class="btnouterlist am-hide-sm-only">
				    <div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
						<label style="margin-right:10px;float:left;margin-top:5px;margin-left:7px;" class="am-checkbox am-success ">
							<input type="checkbox" onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)" data-am-ucheck/>
							<?php echo $ld['select_all'];?>
						</label> 
						<button type="button" class="am-btn am-btn-danger am-btn-sm am-radius" value="" onclick="batch_operations()" ><?php echo $ld['batch_delete']?></button>
				     </div>
					<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    <?php echo $this->element('pagers')?>
					</div>
	                 
				</div>
		<?php }?>
	<?php echo $form->end();?>
	
</div> 
 
<script type="text/javascript">
function batch_operations(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if( postData=="" ){
		alert("<?php echo $ld['please_select']?>");
		return;
	}
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		$.ajax({
			url:admin_webroot+"Materials/batch_operations/",
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