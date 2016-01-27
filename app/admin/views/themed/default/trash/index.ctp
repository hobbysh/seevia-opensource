<?php
    echo $javascript->link('/skins/default/js/product');
?>
<style type="text/css">
	.am-radio, .am-checkbox{display: inline-block;}
 	.am-panel-title div{font-weight:bold;}
  
 	.am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align: text-top;}
 	.am-form-horizontal{padding-top: 6px;}
</style>

<div>
	<?php echo $form->create('',array('action'=>'/','name'=>'searchtrash','type'=>"get","class"=>"am-form am-form-horizontal"));?>
	<div>
		<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-form-label">类型</label>
				<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
				<?php if(!empty($categories_tree)){?>
					<select class="all" name="category_id" id="category_id" data-am-selected="{btnWidth:'100%',noSelectedText:'<?php echo $ld['all_data'] ?> '}">
						<option value="0"><?php echo $ld['all_data']?></option>
						<?php if(isset($categories_tree) && sizeof($categories_tree)>0){?>
						<?php foreach($categories_tree as $first_k=>$first_v){?>
						<option value="<?php echo $first_v['CategoryProduct']['id'];?>" <?php if($category_id == $first_v['CategoryProduct']['id']){?>selected<?php }?>><?php echo $first_v['CategoryProductI18n']['name'];?></option>
						<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
						<?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
						<option value="<?php echo $second_v['CategoryProduct']['id'];?>" <?php if($category_id == $second_v['CategoryProduct']['id']){?>selected<?php }?>>&nbsp;&nbsp;<?php echo $second_v['CategoryProductI18n']['name'];?></option>
						<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
						<?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
						<option value="<?php echo $third_v['CategoryProduct']['id'];?>" <?php if($category_id == $third_v['CategoryProduct']['id']){?>selected<?php }?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryProductI18n']['name'];?></option>
						<?php }}}}}}?>
					</select>
				<?php }?>
				</div>
					<li style="margin-bottom:10px;">
						<label class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-form-label-text"><?php echo $ld['product']?></label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">	
							<input type="text"  class="am-form-field am-radius"  placeholder="<?php echo $ld['number']?>/<?php echo $ld['sku']?>/<?php echo $ld['name']?>"  name="keywords" id="keywords" value="<?php echo isset($keywords1)?$keywords1:'';?>"/>
						</div>
					</li>

			<li style="margin-bottom:10px;">
				<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label-text"><?php echo $ld['last_modified']?></label>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type="text" class="am-form-field" name="date" value="<?php echo @$date?>"  placeholder="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly/>
				</div>
				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-fl am-text-center" style="padding-top:6px"><em>-</em></label>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type="text" class="am-form-field" name="date2" value="<?php echo @$date2?>"  placeholder="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly/>
				</div>
			</li>
		
				 <li><label class="am-u-lg-2 am-u-md-4 am-u-sm-4 am-form-label"> </label>
				   <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
					<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>" />
				</div>
			</li>
		</ul>
	</div>
	<?php echo $form->end();?>
	<div class="am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<?php if($svshow->operator_privilege('products_list')){?>
			<a class="am-btn am-btn-default am-btn-sm am-radius" href="<?php echo $html->url('/products/'); ?>"><?php echo $ld['products_list'] ?></a>
		<?php }?>
	</div>
		<?php echo $form->create('',array('action'=>'/',"name"=>"ProForm",'onsubmit'=>"return false"));?>
			<div class="am-panel-group am-panel-tree ">
				<div class="am-panel-header listtable_div_btm">
					<div class="am-panel-hd">
						<div class="am-panel-title am-g">
		               	             <div class="am-hide-sm-only  am-u-lg-2 am-u-md-2  ">
								<label class="am-checkbox am-success" style="font-weight:bold;">
									<input type="checkbox"  value="checkbox" onclick='listTable.selectAll(this,"checkbox[]")' data-am-ucheck/>
									<?php echo $ld['sku']?>
								</label>
							</div>
							<div class="am-u-lg-4 am-u-md-4 am-u-sm-5"><?php echo $ld['name']?></div>
							<div class="am-u-lg-1 am-u-md-2 am-u-sm-3"><?php echo $ld['price']?></div>
							<div class="am-u-lg-2 am-u-md-2 am-hide-sm-only"><?php echo $ld['last_modified']?></div>
							<div class="am-u-lg-3 am-u-md-2 am-u-sm-4"><?php echo $ld['operate']?></div>
							<div style="clear:both;"></div>
						</div>
					</div>
				</div>
				<?php if(isset($products_list) && sizeof($products_list)>0){foreach($products_list as $k=>$v){?>
					<div>
						<div class="am-panel-body">
							<div class="am-panel-bd am-g  listtable_div_top">
						          <div class="am-u-lg-2 am-u-md-2   am-hide-sm-only">
									<label class="am-checkbox am-success">
										<input type="checkbox" name="checkbox[]" value="<?php echo $v['Product']['id']?>"  data-am-ucheck/>
										<?php echo $v['Product']['code']?>
									</label>
								</div>
								<div class="am-u-lg-4 am-u-md-4 am-u-sm-5" style="word-wrap:break-word;"><?php echo $v['ProductI18n']['name']?>&nbsp;</div>
									<div class="am-u-lg-1 am-u-md-2 am-u-sm-3"><?php echo $v['Product']['format_shop_price']?>&nbsp;</div>
								      <div class="am-u-lg-2 am-u-md-2 am-hide-sm-only"><?php echo $v['Product']['modified']?>&nbsp;</div>
								    
								     <div class="am-u-lg-3 am-u-md-2 am-u-sm-4 am-btn-group-xs am-action">
									<?php
										if($svshow->operator_privilege("products_trash")){
                                            echo $html->link($ld['resume_products'],"javascript:;",array("class"=>"am-btn am-radius am-btn-default am-btn-sm ","onclick"=>"if(confirm('{$ld['confirm_to_resume']}')){list_huanyuan_submit('{$v['Product']['id']}');}"));
										} ?> 
									      <?php if($svshow->operator_privilege("products_recycle_bin")){?>
										     <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete"  	href="javascript:;" onclick="list_delete_submit(admin_webroot+'trash/com_delete/<?php echo $v['Product']['id']; ?>');"> <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?></a>
									      
								       	<?php 	}?>
								            
								    </div>
											
								<div style="clear:both;"></div>
							</div>
						</div>
					</div>
				<?php }}else{?>
						<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
				<?php }?>
			</div>
            <?php if(isset($products_list) && sizeof($products_list)>0){?>
			<div id="btnouterlist" class="btnouterlist" style="overflow:visible;">
				
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6  am-hide-sm-only">
                                 <div class="am-u-md-9 am-g">
					<label class="am-checkbox am-success am-fl"style="margin-top:7px;">
						<input type="checkbox"  value="checkbox" onclick='listTable.selectAll(this,"checkbox[]")' data-am-ucheck/>
						<?php echo $ld['select_all']?>
					</label>
  						<div class="am-u-md-8">
						      <select name="act_type" id="act_type" data-am-selected>
								<option value="0"><?php echo $ld['all_data']?></option>
								<option value="rev"><?php echo $ld['batch_reduction_products']?></option>
								<option value="del"><?php echo $ld['batch_delete']?></option>
							</select>
						</div> 
                                   </div>
                   
					<input type="button" class="am-u-md-3 am-u-lg-1  am-btn am-btn-danger am-btn-sm am-radius" value="<?php echo $ld['submit']?>" onclick="diachange()" id="change_button"/>
				</div>
				 <div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
					<?php echo $this->element('pagers',array('cache'=>'+0 hour'));?>
				</div>
				<div style="clear:both;"></div>
			</div>
		    <?php }?>
		<?php echo $form->end();?>
	</div>
 

<script>
function list_huanyuan_submit(id){
	$.ajax({
		url:admin_webroot+"trash/revert/"+id,
		type:"POST",
		dataType:"json",
		success:function(data){
			if(data.flag==1){
				window.location.reload();
			}
			if(data.flag==2){
				alert(data.message);
			}
		}
	});
}
function operate_change(){
	var a=document.getElementById("act_type");
	if(a.value!='0'){
		for(var j=0;j<a.options.length;j++){
			if(a.options[j].selected){
				var vals = a.options[j].text ;
			}
		}
		layer_dialog_show('<?php echo $ld['submit']?>'+vals+'?','operate_select()',5);
	}
}

function batch_action()
{
	document.ProForm.action=admin_webroot+"trash/batch";
	document.ProForm.onsubmit= "";
	document.ProForm.submit();
}

function operate_select(){//删除

	var id=document.getElementsByName('checkbox[]');
	var i;
	var j=0;
	for( i=0;i<=parseInt(id.length)-1;i++ ){
		if(id[i].checked){
			j++;
		}
	}
	if( j<1 ){
		return false;
	}else{
		batch_action();
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
		//	layer_dialog_show('<?php echo $ld['submit']?>'+vals+'?','batch_action()',5);
			if(confirm('<?php echo $ld['submit']?>'+vals+'?'))
			{
				batch_action();
			}
		}else{
		//	layer_dialog_show('请选择！','batch_action()',3);
			if(confirm(j_please_select))
			{
				batch_action();
			}
		}
	}
	}
</script>
