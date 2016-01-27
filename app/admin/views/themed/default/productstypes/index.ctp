<style type="text/css">
	.btnouterlist label{margin-left: -3px;}
	.btnouterlist input{position: relative;bottom: 3px;*position:static;}
	.am-radio, .am-checkbox {margin-top:0px;margin-bottom:0px;display:inline;vertical-align: text-top;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
	.am-panel-title div{font-weight:bold;}
</style>
<div class="" style="margin-top:10px;">
	<div class="action-span am-text-right am-btn-group-xs" style="margin-bottom:20px;">
		<?php if($svshow->operator_privilege("productstypes_add")){?>
			<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/productstypes/view/'); ?>"><span class="am-icon-plus"></span><?php echo $ld['add'] ?></a>
		<?php }?>
	</div>
	<div style="clear:both;"></div>
<?php echo $form->create('ProductType',array('action'=>'/','name'=>'ProducttypeForm','type'=>'post',"onsubmit"=>"return false;"));?>
	<div class="am-panel-group am-panel-tree">
		<div class=" listtable_div_btm t am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-hide-sm-only">
						<label class="am-checkbox am-success" style="font-weight:bold;">
							<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck />
							<?php echo $ld['product_type_name']?>			
						</label>
					</div>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-show-sm-only">
						<label   style="font-weight:bold;">
						 
							<?php echo $ld['product_type_name']?>			
						</label>
					</div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['attribute_group']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['attributes_number']?></div>
				<!--	<div class="am-u-lg-1 am-u-md-1 am-hide-sm-only"><?php echo $ld['type']?></div>-->
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['customize']?></div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status']?></div>
					<div class="am-u-lg-2 am-u-md-4 am-u-sm-3"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div> 
			</div>		
		</div>
		<?php if(isset($productstype_list) && sizeof($productstype_list)>0){foreach($productstype_list as $k=>$v){?>
		<div>
		<div class=" listtable_div_top  am-panel-body">
			<div class="am-panel-bd">
				<div class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-hide-sm-down" >&nbsp;
					<?php if($v['ProductType']['id']!=0){ ?>
					<label class="am-checkbox am-success">
						<input type="checkbox" name="checkboxes[]" value="<?php echo $v['ProductType']['id']?>" data-am-ucheck />
						<?php echo $v['ProductTypeI18n']['name']; ?>
					</label>
					<?php } else{echo $v['ProductTypeI18n']['name'];} ?>
				</div>
	              <div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-show-sm-only" >
						<label   style="font-weight:bold;">
						 
								<?php echo $v['ProductTypeI18n']['name']; ?>		
						</label>
					</div>
			      <div class="am-u-lg-2 am-show-lg-only" >&nbsp;<?php echo $v['ProductType']['group_code']?></div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"  >
					<?php echo $html->link($v['ProductType']['num'],'look/'.$v['ProductType']['id'],'',false,false); ?>
				</div>
				<div class="am-u-lg-1 am-u-md-2 am-u-sm-2" >
					<?php if($v['ProductType']['customize'] == '0'){?>
						<span class="am-icon-close am-no" style="cursor:pointer;">&nbsp;</span>
					<?php }else if($v['ProductType']['customize'] == '1'){?>
						<span class="am-icon-check am-yes " style="cursor:pointer;" >&nbsp;</span>
					<?php }?>
				</div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" >
					<?php if( $v['ProductType']['status'] == 1){ ?>
						<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'productstypes/toggle_on_typestatus',<?php echo $v['ProductType']['id'];?>)"></span>&nbsp;
					<?php }else{ ?>
						<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'productstypes/toggle_on_typestatus',<?php echo $v['ProductType']['id'];?>)"></span>&nbsp;
					<?php } ?>
				</div>
				<div class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-action" >
					<?php if($svshow->operator_privilege("productstypes_edit")){?>
                    <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('view/'.$v['ProductType']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                    <?php } ?>
				    <?php if($svshow->operator_privilege("productstypes_remove")&&$v['ProductType']['id']!=0){?>
						  <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(confirm(j_confirm_delete)){list_delete_submit(admin_webroot+'productstypes/remove/<?php echo $v['ProductType']['id']; ?>')}">
                                  <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                     </a>
						<?php 	}?>
				</div>
				<div style="clear:both;"></div>
			</div>
		</div>
		</div>
		<?php }}else{?>
		<div class="am-text-center"  style=" height:100px; background-color: #f9f9f9; padding-top:20px;"><?php echo $ld['nos_records']?></div>	
		<?php }?>
		<?php if($svshow->operator_privilege("productstypes_remove")){?>
		<?php if(isset($productstype_list) && sizeof($productstype_list)>0){ ?>
			<div id="btnouterlist" class="btnouterlist" style="margin-top:10px;">
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-show-lg-only" style="margin-left:20px;">
					<label class="am-checkbox am-success">
						<input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)" type="checkbox" data-am-ucheck />
						<?php echo $ld['select_all']?>
					</label>&nbsp;&nbsp;&nbsp;
					<input type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="remove_pt()" value="<?php echo $ld['batch_delete']?>" />
				</div>
				<div class="am-u-lg-8 am-u-md-12 am-u-sm-12">
					<?php echo $this->element('pagers')?>
				</div>
                <div class="am-cf"></div>
			</div>
		<?php }?>
		<?php }?>		
				
	</div>
<?php echo $form->end();?>
</div>

<script>
function remove_pt(){
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
		//	layer_dialog_show('确定删除?','batch_action()',5);
			if(confirm("<?php echo $ld['confirm_delete']?>"))
			{
				batch_action();
			}
		}else{
		//	layer_dialog_show('请选择！','batch_action()',3);
			if(confirm(j_please_select))
			{
				return false;
			}
		}
	}

function batch_action()
{
document.ProducttypeForm.action=admin_webroot+"productstypes/remove_batch";
document.ProducttypeForm.onsubmit= "";
document.ProducttypeForm.submit();
}
function change_state(obj,func,id){
	var ClassName=$(obj).attr('class');
	var val = (ClassName.match(/yes/i)) ? 0 : 1;
	var postData = "val="+val+"&id="+id;
	$.ajax({
		url:admin_webroot+func,
		Type:"POST",
		data: postData,
		dataType:"json",
		success:function(data){
			if(data.flag == 1){
				if(val==0){
					$(obj).removeClass("am-icon-check am-yes");
					$(obj).addClass("am-icon-close am-no");
				}
				if(val==1){
					$(obj).removeClass("am-icon-close am-no");
					$(obj).addClass("am-icon-check am-yes");
				}
			}
		
		}	
	});
}

</script>
