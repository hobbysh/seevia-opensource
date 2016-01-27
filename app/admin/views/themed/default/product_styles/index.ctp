<style type="text/css">
	.am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
	.am-checkbox input[type="checkbox"]{margin-left:0px;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
	.am-panel-title div{font-weight:bold;}
	
</style>

<div>
	<?php if($svshow->operator_privilege('product_style_add')){?>
		<div class="am-text-right am-btn-group-xs" style="margin-right:10px;margin-bottom:10px;">
			<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/product_styles/view'); ?>">
				<span class="am-icon-plus"></span><?php echo $ld['add'] ?>
			</a>
		</div>
	<?php }?>
	<form name="ProductStyleForm" onsubmit="return false;" id="ProductStyleForm" method="get" action="/admin/product_styles" accept-charset="utf-8">
		<div class="am-panel-group am-panel-tree">
			<div class=" listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title am-g">
						<div class="am-u-lg-6 am-u-md-5 am-u-sm-5 am-hide-sm-only">
							<label class="am-checkbox am-success" style="font-weight:bold;">
								<input type="checkbox" onclick="listTable.selectAll(this,&quot;checkbox[]&quot;)" data-am-ucheck/>
								<?php echo $ld['name']?>
							</label>
						</div>
<!-- 表头 -->
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-2 am-show-sm-only">
							<label   style="font-weight:bold;">
								  <?php echo $ld['name']?>
							</label>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['status']?></div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['sort']?></div>
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['operate']?></div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php if(isset($product_style) && sizeof($product_style)>0){foreach($product_style as $k=>$v){?>
			<div>
				<div class="am-panel-body ">
					<div class="am-panel-bd listtable_div_top">
						<!-- 名称 -->
						<div class="am-u-lg-6 am-u-md-5 am-u-sm-5 am-hide-sm-only" style="padding-top:2px;">
							<label class="am-checkbox am-success">
								<input type="checkbox" name="checkbox[]" value="<?php echo $v['ProductStyle']['id']?>"  data-am-ucheck />
								<?php echo $v['ProductStyleI18n']['style_name'];?>&nbsp;
							</label>
						</div>
					<div class="am-u-lg-6 am-u-md-6 am-u-sm-2 am-show-sm-only" >
							<label>
							 
								<?php echo $v['ProductStyleI18n']['style_name'];?>&nbsp;
							</label>
						</div>

						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="padding-top:3px;">
							<?php if($v['ProductStyle']['status']) {?>
								<span class="am-icon-check am-yes">&nbsp;</span>
							<?php }else{?> 
								<span class="am-icon-close am-no">&nbsp;</span>
							<?php }?>&nbsp;
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="padding-top:3px;">
							<?php if(count($product_style)==1){echo "-";}elseif($k==0){?>
								<a onclick="changeOrder('down','<?php echo $v['ProductStyle']['id'];?>','0',this)" style="cursor:pointer">&#9660;</a>
								<?php }elseif($k==(count($product_style)-1)){?>
								<a onclick="changeOrder('up','<?php echo $v['ProductStyle']['id'];?>','0',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a>
								<?php }else{?>
								<a onclick="changeOrder('up','<?php echo $v['ProductStyle']['id'];?>','0',this)" style="color:#cc0000;cursor:pointer;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $v['ProductStyle']['id'];?>','0',this)" style="cursor:pointer;">&#9660;</a>
							<?php }?>&nbsp;
						</div>
						<!-- 操作 -->
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-6 am-btn-group-xs am-action" style="padding-top:3px;">
							<?php if($svshow->operator_privilege("product_style_edit")){?>
							 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit"
							  href="<?php echo $html->url('/product_styles/view/'.$v['ProductStyle']['id']); ?>">
                       				 <span class="am-icon-pencil-square-o"></span> 
                        				 <?php echo $ld['edit']; ?>
                        			       </a>
								
						  	 <?php	}?>
							   <?php if($svshow->operator_privilege("product_style_remove")){?>
								
								
				<a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 			href="javascript:void(0);"onclick="list_delete_submit(admin_webroot+'product_styles/remove/<?php echo$v['ProductStyle']['id'] ?>');"><span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?></a>
								<?php }?>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
			<?php }}else{?>
				<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
			<?php }?>
		</div>
		<?php if(isset($product_style) && sizeof($product_style) && $svshow->operator_privilege('product_style_remove')){?>
			<div id="btnouterlist" class="btnouterlist am-hide-sm-only" style="margin-left:13px;">
				  
				 	<label  class="am-checkbox am-success">
						<input type="checkbox" onclick="listTable.selectAll(this,&quot;checkbox[]&quot;)" data-am-ucheck>
						<?php echo $ld['select_all']?>
					</label>&nbsp;&nbsp;
					<input type="button" class="am-btn am-btn-danger am-btn-sm am-radius" onclick="diachange()" value="<?php echo $ld['batch_delete']?>">
			 
			</div>
		<?php }?>
	</form>

</div>

<script>
function diachange(){
		var id=document.getElementsByName('checkbox[]');
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
		//	layer_dialog_show('请选择！！','batch_action()',3);
			if(confirm(j_please_select))
			{
				return false;
			}
		}
}
function batch_action()
{
	document.ProductStyleForm.action=admin_webroot+"product_styles/batch";
	document.ProductStyleForm.onsubmit= "";
	document.ProductStyleForm.submit();
}
function changeOrder(updown,id,next,thisbtn){
	$.ajax({
		url:admin_webroot+"/product_styles/changeorder/"+updown+"/"+id+"/"+next,
		type:"POST",
		success:function(data){
				var popcontent = document.createElement('div');
				$(popcontent).html(data);
				//var tmp = $(".am-panel-tree");
				var tmp =$(popcontent).find(".am-panel-tree").html();
				$('.am-panel-tree').html(tmp);
				$('.am-panel-tree input[type="checkbox"] ').uCheck();
		}
	});
}
</script>