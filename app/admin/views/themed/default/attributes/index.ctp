<style type="text/css">
	.btnouterlist label{margin-left: -3px;}
	.btnouterlist input{position: relative;bottom: 3px;*position:static;}
	.am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
	.am-checkbox input[type="checkbox"]{margin-left:0px;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
	.am-panel-title div{font-weight:bold;}
	 
</style>
<div >
<?php echo $form->create('Attributes',array('class'=>'am-form am-form-horizontal','action'=>'/','id'=>'AttributesSeearchForm','name'=>'AttributesSeearchForm','type'=>'get'));?>
	<div>
		<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1  ">
			<li style="margin-bottom:10px;"><!----->
    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="font-weight:bold;"><?php echo $ld['attribute']?></label>
    			<div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
    				<input type="text" name="keywords" id="attr_keywords" class="am-form-field am-radius am-input-sm" value="<?php echo @$attr_keywords; ?>" placeholder="<?php echo $ld['attribute_name']?>/<?php echo $ld['attribute_code']?>"/>
    			</div>
			</li><!---1-->

			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="font-weight:bold;">
					<?php echo $ld['attribute_type']?>
				</label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
					<select name="attr_type" id="attr_type" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
						  <option value=""><?php echo $ld['all_data']?></option>
						<?php if(isset($Resource_info['property_type'])&&sizeof($Resource_info['property_type'])>0){ foreach($Resource_info['property_type'] as $k=>$v){?>
							
						<option value="<?php echo $k; ?>" <?php echo @$attr_type==$k?"selected":'' ?>><?php echo $v; ?></option>
						<?php }} ?>
					</select>
				</div>
			</li>
			<li style="margin-bottom:10px;"><!---2-->
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="font-weight:bold;">
					<?php echo $ld['product_type']?>
				</label>
				<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
					<select name="productstype" id="productstype" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
						<option value="" selected><?php echo $ld['all_data']?></option>
						<?php if(isset($productstype_list)&&sizeof($productstype_list)>0){ foreach($productstype_list as $v){?>
						<option value="<?php echo $v['ProductType']['id']; ?>" <?php echo @$productstype==$v['ProductType']['id']?"selected":'' ?>><?php echo $v['ProductTypeI18n']['name']; ?></option>
						<?php }} ?>
					</select>
				</div>
			</li>

               <li style="margin-bottom:10px;">
				<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="font-weight:bold;"></label>
				<div class="am-u-lg-9 am-u-md-8 am-u-sm-8 ">
				<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius"  value=""><?php echo $ld['search'];?></button>
				</div>
			</li>
         </ul>
	</div>
						
<?php echo $form->end();?>
</div><br/>
                        
<div class="am-g action-span am-btn-group-xs" style="margin-bottom:10px;margin-right:10px;">
	<div  class="am-fr am-u-lg-12 am-text-right  am-btn-group-xs" >
	      
				<?php if($svshow->operator_privilege("attribute_add")){echo $html->link($ld['bulk_upload'].$ld['attribute'],'/attributes/doload_csv_example', array("class"=>"am-btn am-btn-default am-btn-sm "),'',false,false);
			}?>&nbsp;
	
		<?php if($svshow->operator_privilege("productstypes_view")){echo $html->link($ld['product_type_management'],'/productstypes/',
		array("class"=>"am-btn am-btn-default am-btn-sm "),'',false,false);
			}?>&nbsp;
		<?php if($svshow->operator_privilege("attribute_add")){?>
			<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/attributes/view/0'); ?>"><span class="am-icon-plus"></span> <?php echo $ld['add'] ?></a>
		<?php   }?>
	</div>
</div>
<?php echo $form->create('Attributes',array('action'=>'/','name'=>'AttributesForm','type'=>'post',"onsubmit"=>"return false;"));?>
	<div class="am-panel-group am-panel-tree">
		<div class=" listtable_div_btm   am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title am-g">
					<div class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-hide-sm-only">
						<label class="am-checkbox am-success" style="font-weight:bold;">
							<input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/>
							<?php echo $ld['attribute_name']?>
						</label>
					</div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['attribute_code']?></div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['attribute_input_type']?></div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['attribute_type']?></div>
					<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['optional_attribute']?></div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-2"><?php echo $ld['status']?></div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-4"><?php echo $ld['operate']?></div>
				</div>
			</div>
		</div>
		<?php if(isset($attribute_list) && sizeof($attribute_list)>0){foreach($attribute_list as $k=>$v ){ ?>
		<div>
			<div class=" listtable_div_top am-panel-header am-panel-body">
				<div class="am-panel-bd am-g">
					<div class=" am-u-lg-3 am-u-md-3 am-u-sm-3 am-hide-sm-only" >
						<label class="am-checkbox am-success">
							<input type="checkbox" name="checkboxes[]" value="<?php echo $v['Attribute']['id']?>" data-am-ucheck />
							<?php echo $v['AttributeI18n']["name"]?>&nbsp;
						</label>
					</div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $v['Attribute']["code"]?>&nbsp;</div>
					<div class="am-u-lg-2 am-show-lg-only" >&nbsp;
						<?php if($v['Attribute']["attr_input_type"]==0){ echo $ld['text_input'];}?>
						<?php if($v['Attribute']["attr_input_type"]==1){ echo $ld['select_from_list'];}?>
						<?php if($v['Attribute']["attr_input_type"]==2){ echo $ld['attribute_type_textarea'];}?>
						<?php if($v['Attribute']["attr_input_type"]==3){ echo $ld['upload_oneself'];}?>
						<?php if($v['Attribute']["attr_input_type"]==4){ echo $ld['date'];}?>
					</div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2"  >
						<?php echo $Resource_info["property_type"][$v['Attribute']["type"]]?>&nbsp;
					</div>
					<div class="am-u-lg-1 am-show-lg-only" >&nbsp;
						<?php if($v['Attribute']["attr_type"]==0){?>
							<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'attributes/toggle_on_attrtype',<?php echo $v['Attribute']['id'];?>)">&nbsp;</span>
						 <?php }?>
						<?php if($v['Attribute']["attr_type"]==1){?>
						<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'attributes/toggle_on_attrtype',<?php echo $v['Attribute']['id'];?>)"></span>
						<?php }?>
						<?php if($v['Attribute']["attr_type"]==2){?>
						<?php echo $ld['custom'] ?>&nbsp;
						<?php }?>
					</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-2" >&nbsp;
						<?php if ($v['Attribute']['status'] == 1){?>
						<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'attributes/toggle_on_status',<?php echo $v['Attribute']['id'];?>)">&nbsp;</span>
							
						<?php }elseif($v['Attribute']['status'] == 0){?>
						<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'attributes/toggle_on_status',<?php echo $v['Attribute']['id'];?>)">&nbsp;</span>

						<?php }?>
					</div>
				    <div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-action" >
    			        <?php if($svshow->operator_privilege("attribute_edit")){ ?>
                        <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/attributes/'.$v['Attribute']['id']); ?>">
                            <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                        </a>
                        <?php } ?>
    					
    						   <?php if($svshow->operator_privilege('brands_remove')){ ?>
                          <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'attributes/remove/<?php echo $v['Attribute']['id']; ?>')">
                            <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                          </a>
                        <?php } ?>
					</div>
				</div>
			</div>
		</div>
			<?php }}else{?>
			<div  class="no_data_found"><?php echo $ld['no_data_found']?></div>
			<?php }?>
	</div>
		
	<?php if(isset($attribute_list) && sizeof($attribute_list)>0){ ?>
		<div id="btnouterlist" class="am-hide-sm-only ">
			  <div style="margin-left:14px;"class="am-u-lg-4 am-u-md-3 am-u-sm-3  " >	
				<label class="am-checkbox am-success">
					<input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)" type="checkbox" data-am-ucheck>
					<?php echo $ld['select_all']?>
				</label>&nbsp;&nbsp;
				<button type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="remove_attr()"><?php echo $ld['batch_delete']?></button>
			  </div>
			<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">		
				<?php echo $this->element('pagers')?>
			</div>
			<div class="am-cf"></div>
		</div>
	<?php }?>
<?php echo $form->end();?>
    
<script type="text/javascript">
function remove_attr(){
	var id=document.getElementsByName('checkboxes[]');
	var j=0;
	for(var i=0;i<=parseInt(id.length)-1;i++ ){
		if(id[i].checked){
			j++;
		}
	}
	if( j>=1 ){
		if(confirm("<?php echo $ld['confirm_delete']?>"))
		{
			document.AttributesForm.action=admin_webroot+"attributes/removeAll/";
			document.AttributesForm.onsubmit= "";
			document.AttributesForm.submit();
		}
	}else{
		if(confirm(j_please_select))
		{
			return false;
		}
	}
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
