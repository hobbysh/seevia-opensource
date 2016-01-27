<script src="/plugins/json2.js" type="text/javascript"></script>
<style type="text/css">
 .am-radio, .am-checkbox{margin-top:0px;margin-bottom:0px;display: inline-block;vertical-align: text-top;}
 .am-checkbox, .am-radio{margin-bottom:0px;}
 .am-radio input[type="radio"]{margin-left:0px;}
 .am-form-horizontal .am-radio{padding-top:0px;}
</style>
<div>
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
	    	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		</ul>
	</div>
	<?php echo $form->create('StyleTypeGroup',array('action'=>'view/'.(isset($style_type_group)?$style_type_group['StyleTypeGroup']['id']:""),'name'=>'StyleTypeGroupForm','onsubmit'=>'','class'=>'am-form-horizontal'));?>
		<input type="hidden" id="data[StyleTypeGroup][id]"  name="data[StyleTypeGroup][id]" value="<?php echo isset($style_type_group['StyleTypeGroup']['id'])?$style_type_group['StyleTypeGroup']['id']:0 ?>" />
		<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;overflow:visible;">
			<div id="basic_information" class="am-panel am-panel-default">
				<div class="am-panel-hd">
						<h4 class="am-panel-title">
							<label><?php echo $ld['basic_information']?></label>
						</h4>
			    </div>
				<div class="am-panel-collapse am-collapse am-in">
					<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">版型规格值</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><div>
								<input type="text" id="product_group_name" name="data[StyleTypeGroup][group_name]" value="<?php echo isset($style_type_group['StyleTypeGroup']['group_name'])?$style_type_group['StyleTypeGroup']['group_name']:'';?>" />
							</div></div>
							<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-view-label am-text-left"><em style="color:red;">*</em></label>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label">版型</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<?php if(!empty($style_list)){?>
									<select name="data[StyleTypeGroup][style_id]" id="product_style_id" data-am-selected>
										<option value="0"><?php echo $ld['please_select']?></option>
										<?php if(isset($style_list) && sizeof($style_list)>0){?>
										<?php foreach($style_list as $k=>$v){?>
										<option value="<?php echo $v['ProductStyle']['id']?>" <?php if(isset($style_id)&&$style_id == $v['ProductStyle']['id']){echo "selected";}?>><?php echo $v['ProductStyleI18n']['style_name']?></option>
										<?php }}?>
									</select>
								<?php }?>
							</div>		
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-form-group-label">属性组</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="hidden" id="attribute_code" name="data[StyleTypeGroup][attribute_code]" value="<?php echo isset($style_type_group['StyleTypeGroup']['attribute_code'])?$style_type_group['StyleTypeGroup']['attribute_code']:'';?>" />
								<?php if(!empty($type_list)){?>
									<select name="data[StyleTypeGroup][type_id]" id="product_type_id" onchange="change_type(this)" data-am-selected >
										<option value="0"><?php echo $ld['please_select']?></option>
										<?php if(isset($type_list) && sizeof($type_list)>0){?>
										<?php foreach($type_list as $k=>$v){?>
										<option value="<?php echo $v['ProductType']['id']?>" <?php if(isset($style_type_group['StyleTypeGroup']['type_id']) && $style_type_group['StyleTypeGroup']['type_id'] == $v['ProductType']['id']){echo "selected";}?>><?php echo $v['ProductTypeI18n']['name']?></option>
										<?php }}?>
									</select>
								<?php }?>
							</div>		
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $ld['sort']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><div>
								<input class="input_sort" type="text" value="<?php echo isset($style_type_group['StyleTypeGroup']['orderby'])?$style_type_group['StyleTypeGroup']['orderby']:'50';?>" name="data[StyleTypeGroup][orderby]">
							</div></div>		
						</div>

						<div class="am-g" style="margin-top:10px;">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['status']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<label class="am-radio am-success">
									<input type="radio" name="data[StyleTypeGroup][status]" data-am-ucheck value="1" <?php if((isset($style_type_group)&&$style_type_group['StyleTypeGroup']['status']==1)||!isset($style_type_group)){?> checked<?php }?>><?php echo $ld['yes']?>
								</label>
								<label class="am-radio am-success">
									<input type="radio" name="data[ProductStyle][status]" data-am-ucheck value="0" <?php if(isset($style_type_group)&&$style_type_group['StyleTypeGroup']['status']==0){?> checked<?php }?>><?php echo $ld['no']?>
								</label>
							</div>		
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-1 am-u-md-3 am-u-sm-3 am-form-label" ></label>
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
								<div id="show_type_attr" style="<?php if(isset($style_type_group_attr) && sizeof($style_type_group_attr)>0){echo '';}else{echo 'display:none';}?>">
									<?php if(isset($style_type_group_attr) && sizeof($style_type_group_attr)>0){?>
										<?php foreach($attr_group as $ak=>$av){?>
											<?php foreach($style_type_group_attr as $k=>$v){?>
												<?php if($av['Attribute']['id']==$v['StyleTypeGroupAttributeValue']['attribute_id']){?>
												<div>
													<ul class="am-avg-lg-2">
														<li>
															<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" ><?php echo $av['AttributeI18n']['name'];?></label>
															<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
																<input type='text' name="data[StyleTypeGroupAttributeValue][<?php echo $ak;?>][default_value]" value="<?php echo $v['StyleTypeGroupAttributeValue']['default_value'];?>" />
															</div>
														</li>
														<li>
															<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">可选值</label>
															<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
																<textarea class='select_value' name="data[StyleTypeGroupAttributeValue][<?php echo $ak;?>][select_value]"><?php echo $v['StyleTypeGroupAttributeValue']['select_value'];?></textarea>
															</div>
															<input type='hidden' name="data[StyleTypeGroupAttributeValue][<?php echo $ak;?>][attribute_code]" value="<?php echo $v['StyleTypeGroupAttributeValue']['attribute_code'];?>" />
															<input type='hidden' name="data[StyleTypeGroupAttributeValue][<?php echo $ak;?>][attribute_id]" value="<?php echo $v['StyleTypeGroupAttributeValue']['attribute_id'];?>" />
														</li>
													</ul>
												</div>
												<?php }?>
											<?php }?>
										<?php }?>
									<?php }?>
								</div>
							</div>		
						</div>
					</div>
					<div class="btnouter">
						<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
					</div>				
				</div>
			</div>
		</div>
	<?php echo $form->end();?>
</div>

<script>
function change_type(e){
	//alert($(e).val());
	var type_id=$(e).val();
	if(type_id !=0){
		$.ajax({
	        cache: true,
	        type: "POST",
	        url:"/admin/style_type_groups/show_attribute",
	        data:{"type_id":type_id},
	        success: function(data) {
	            var result= JSON.parse(data);
				if(result['flag']==1){
					var attr_html="";
					$.each(result['type_list'],function(i,item){
						attr_html+="<div><ul class='am-avg-lg-2 am-avg-md-2 am-avg-sm-2'><li style='margin-bottom:10px;'><label class='am-u-lg-4 am-u-md-5 am-u-sm-6 am-form-label'>"+item['AttributeI18n']['name']+"</label><div class='am-u-lg-6 am-u-md-6 am-u-sm-6'><input type='text' name=\"data[StyleTypeGroupAttributeValue]["+i+"][default_value]\" value='"+item['AttributeI18n']['default_value']+"' /></div></li>"+"<li style='margin-bottom:10px;'><label class='am-u-lg-4 am-u-md-5 am-u-sm-6 am-form-label am-text-right' style='padding-top:15px;'>可选值</label><div class='am-u-lg-6 am-u-md-6 am-u-sm-6'><textarea class='select_value' name=\"data[StyleTypeGroupAttributeValue]["+i+"][select_value]\"></textarea></div></li></ul>"+"<input type='hidden' name=\"data[StyleTypeGroupAttributeValue]["+i+"][attribute_code]\" value='"+item['Attribute']['code']+"' /><input type='hidden' name=\"data[StyleTypeGroupAttributeValue]["+i+"][attribute_id]\" value='"+item['Attribute']['id']+"' /></div>";
					});
					$("#show_type_attr").html(attr_html);
					$("#show_type_attr").show();
				}else{
					alert("<?php echo $ld['delete_failure'];?>");
				}
	        }
	    });
	}else{
		$("#show_type_attr").html("");
	}
}
</script>