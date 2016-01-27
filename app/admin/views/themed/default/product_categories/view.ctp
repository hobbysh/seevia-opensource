<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<style type="text/css">
  .am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
 
  .img_select{max-width:150px;max-height:120px;}
  .am-form-horizontal .am-checkbox{padding-top:0px;margin-top: 0.5em;}
  .original_price{margin-bottom:0.1em;}
  .original_price .am-icon-plus,.original_price .am-icon-minus{cursor: pointer;}
  .original_attr{margin-bottom:0.5em;}
  .original_attr .am-icon-plus,.original_attr .am-icon-minus{cursor: pointer;}
  .btnouter{margin-top:30px;margin-bottom:20px;}
</style>
<div class="am-g">
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-detail-menu">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index:100;width: 15%;max-width:200px;">
			<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			<li><a href="#detail_description"><?php echo $ld['detail_description']?></a></li>
			<li><a href="#advanced_set_up"><?php echo $ld['advanced'].'&nbsp;'.$ld['set_up']?></a></li>
			<li><a href="#product_top_description"><?php echo $ld['product_top_description']?></a></li>
			<li><a href="#product_bottom_description"><?php echo $ld['product_bottom_description']?></a></li>
		</ul>
	</div>
	<?php echo $form->create('ProductCategory',array('action'=>'view/'.(isset($this->data['CategoryProduct']['id'])?$this->data['CategoryProduct']['id']:""),'name'=>'ProductCategoryForm','onsubmit'=>'return productcat_input_checks();'));?>
						 
		<input id="data[CategoryProduct][id]" name="data[CategoryProduct][id]" type="hidden" value="<?php echo isset($this->data['CategoryProduct']['id'])?$this->data['CategoryProduct']['id']:"";?>">
		<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
		<input name="data[CategoryProductI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
		<?php }}?>
		<div class="am-panel-group   am-detail-view" id="accordion" >
			<div id="basic_information" class="am-panel am-panel-default">
			    <div class="am-panel-hd">
				    <h4 class="am-panel-title">
						<?php echo $ld['basic_information'] ?>
					</h4>
				</div>
				<div class="am-panel-collapse am-collapse am-in">
				    <div class="am-panel-bd am-form-detail am-form am-form-horizontal">

						<div class="am-form-group">
						    <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label">
						    	<?php echo $ld['higher_category']?>
						    </label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<select id="CategoryParentId" name="data[CategoryProduct][parent_id]"  data-am-selected="{maxHeight:150}">
									<option value="0"><?php echo $ld['root']?></option>
									<?php if(isset($categories_tree) && sizeof($categories_tree)){foreach($categories_tree as $k=>$v){//第一层 ?>
									<option value="<?php echo $v['CategoryProduct']['id'];?>" <?php echo isset($this->data['CategoryProduct']['parent_id'])&&$v['CategoryProduct']['id']==$this->data['CategoryProduct']['parent_id']?"selected":"";?> ><?php echo $v['CategoryProductI18n']['name'];?></option>
									<?php if(isset($v['SubCategory']) && sizeof($v['SubCategory'])>0){foreach($v['SubCategory'] as $kk=>$vv){//第二层?>
									<option value="<?php echo $vv['CategoryProduct']['id'];?>" <?php echo isset($this->data['CategoryProduct']['parent_id'])&&$vv['CategoryProduct']['id']==$this->data['CategoryProduct']['parent_id']?"selected":"";?> >|-- <?php echo $vv['CategoryProductI18n']['name'];?></option>
									<?php if(isset($vv['SubCategory']) && sizeof($vv['SubCategory'])>0){foreach($v['SubCategory'] as $kkk=>$vvv){//第二层 ?>
									<?php }}}}}}?>
								</select>
							</div>
						</div>


						<div class="am-form-group">
 						    <label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-group-label" style="margin-top:18px;">
						    	<?php echo $ld['category_name']?>
						    </label>
                                            	<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					        <?php if(isset($backend_locales) && sizeof($backend_locales)>0)?>
							<?php {foreach ($backend_locales as $k => $v){?>
								<div class="am-u-lg-10 am-u-md-9 am-u-sm-9" style="margin-top:10px;padding:0px;">
									<input id="productcat_name_<?php echo $v['Language']['locale'];?>" name="data[CategoryProductI18n][<?php echo $k;?>][name]" type="text" value="<?php echo isset($this->data['CategoryProductI18n'][$v['Language']['locale']]['name'])?$this->data['CategoryProductI18n'][$v['Language']['locale']]['name']:'';?>" />
								</div>
							
								<?php if(sizeof($backend_locales)>1){?>	
									<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left am-form-label-text" style="font-weight:normal;padding-top:10px;">
										<?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em>
									</label>
								<?php }?>
							<?php }}?>

						  </div>
						</div>


                            <?php if(empty($this->data['CategoryProduct']['id'])){?>			
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  " style="font-weight:bold;padding-top:7px;">
								<?php echo $ld['sort']?>
							</label>
							<div  class="am-u-lg-3 am-u-md-4 am-u-sm-3">
								<label class="am-radio am-success">
									<input type="radio" name="orderby" value="0" data-am-ucheck/><?php echo $ld['front']?>
								</label>&nbsp;&nbsp;
								<label class=" am-radio am-success">
									<input checked type="radio" name="orderby" value="1" data-am-ucheck/>
									<?php echo $ld['final']?>
								</label>&nbsp;&nbsp;
								<label class="am-radio am-success"  style="font-weight:normal;">
									<input type="radio" name="orderby" value="2" data-am-ucheck/><?php echo $ld['at']?>
								</label>
							</div>
						</div>
						<div class="am-form-group" >
							<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">&nbsp;</div>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<select id='orderby' name="orderby_sel" data-am-selected="{font-size:8px;}">
									<option>Product</option>
								</select>
							</div>
							<div class="am-u-sm-3 am-u-md-3 am-hide-lg-only">&nbsp;</div>
							<label class="am-u-lg-3 am-u-md-9 am-u-sm-7" style="padding-top:15px;"><?php echo $ld['after']?></label>		
						</div>
						<?php }?>

						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label">
								<?php echo $ld['location']?>
							</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<input type="hidden" name="data1[Navigation][status]" value="1" />
								<select name="data1[Navigation][type]" data-am-selected>
									<option value="0"><?php echo $ld['none']?></option>
									<option value="T" <?php if(!empty($ninfo)&&$ninfo['Navigation']['type']=='T')echo "selected";?>><?php echo $ld['top']?></option>
									<option value="H" <?php if(!empty($ninfo)&&$ninfo['Navigation']['type']=='H')echo "selected";?>><?php echo $ld['help_section']?></option>
									<option value="B" <?php if(!empty($ninfo)&&$ninfo['Navigation']['type']=='B')echo "selected";?>><?php echo $ld['bottom']?></option>
									<option value="M" <?php if(!empty($ninfo)&&$ninfo['Navigation']['type']=='M')echo "selected";?>><?php echo $ld['middle']?></option>
								</select>
							</div>
						</div>

			
						<div class="am-form-group" >
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-radio-label" style="margin-top:6px;"><?php echo $ld['display']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<label class="am-radio am-success" style="padding-top:2px">
									<input type="radio" name="data[CategoryProduct][status]" data-am-ucheck
									 value="1" <?php echo !isset($this->data['CategoryProduct']['status'])||(isset($this->data['CategoryProduct']['status'])&&$this->data['CategoryProduct']['status']==1)?"checked":"";?> >
									<?php echo $ld['yes']?>
								</label>&nbsp;&nbsp;
								<label class="am-radio am-success"  style="margin-top:7px;padding-top:2px;">
									<input name="data[CategoryProduct][status]" type="radio" data-am-ucheck
									 value="0" <?php echo isset($this->data['CategoryProduct']['status'])&&$this->data['CategoryProduct']['status']==0?"checked":"";?> />
									<?php echo $ld['no']?>
								</label>
							</div>
						</div>			
						<div class="am-form-group" >
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-radio-label" style="margin-top:7px;">
								<?php echo $ld['show_new']?>
							</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<label class="am-radio am-success" style="padding-top:2px;">
									<input type="radio" name="data[CategoryProduct][new_show]" data-am-ucheck
									 value="1" <?php echo !isset($this->data['CategoryProduct']['new_show'])||(isset($this->data['CategoryProduct']['new_show'])&&$this->data['CategoryProduct']['new_show']==1)?"checked":"";?> />
									<?php echo $ld['yes']?>
								</label>&nbsp;&nbsp;
								<label class="am-radio am-success" style="padding-top:2px;">
									<input name="data[CategoryProduct][new_show]" type="radio" data-am-ucheck
									 value="0" <?php echo isset($this->data['CategoryProduct']['new_show'])&&$this->data['CategoryProduct']['new_show']==0?"checked":"";?> />
									<?php echo $ld['no']?>
								</label>
							</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-group-label"><?php echo $ld['product_categories_show_info']?></label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<?php
									$show_info_str=isset($this->data['CategoryProduct']['show_info'])?$this->data['CategoryProduct']['show_info']:'';
									$show_info=split(';',$show_info_str);
								?>
								<label class="am-radio am-success" style="padding-top:2px;"><input type="checkbox" name="data[CategoryProduct][show_info][]" value="new_arrival" data-am-ucheck <?php if(in_array('new_arrival',$show_info)){echo "checked";} ?> /><?php echo $ld['new_arrival']; ?></label>
								<label class="am-radio am-success" style="padding-top:2px;"><input type="checkbox" name="data[CategoryProduct][show_info][]" value="recommend" data-am-ucheck <?php if(in_array('recommend',$show_info)){echo "checked";} ?> /><?php echo $ld['recommend']; ?></label>
								<label class="am-radio am-success" style="padding-top:2px;"><input type="checkbox" name="data[CategoryProduct][show_info][]" value="selling" data-am-ucheck <?php if(in_array('selling',$show_info)){echo "checked";} ?> /><?php echo $ld['selling']; ?></label>
								<label class="am-radio am-success" style="padding-top:2px;"><input type="checkbox" name="data[CategoryProduct][show_info][]" value="sub_categories_product" data-am-ucheck <?php if(in_array('sub_categories_product',$show_info)){echo "checked";} ?> /><?php echo $ld['sub_categories_product']; ?></label>
							</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-group-label">
								<?php echo $ld['sort_by']?>
							</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<select name="data[CategoryProduct][home_show_order]" data-am-selected>
									<option value="new_arrival" <?php if(isset($this->data['CategoryProduct']['home_show_order'])&&$this->data['CategoryProduct']['home_show_order'] == "new_arrival"){?>selected<?php }?>><?php echo $ld['prod_sort_time_desc']?></option>
									<option value="price" <?php if(isset($this->data['CategoryProduct']['home_show_order'])&&$this->data['CategoryProduct']['home_show_order'] == "price"){?>selected<?php }?>><?php echo $ld['prod_sort_price_desc']?></option>
									<option value="sales" <?php if(isset($this->data['CategoryProduct']['home_show_order'])&&$this->data['CategoryProduct']['home_show_order'] == "sales"){?>selected<?php }?>><?php echo $ld['prod_sort_sale_desc']?></option>
								</select>
							</div>
					   </div>
						<div><!---------->
						 	<div class="btnouter" >
							<button type="submit" class="mt am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
							<button type="reset" class="mt am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
					 	     </div>            
					 </div>
						<div style="clear:both;"></div>
					</div>		
				</div>	
			</div>
									
			<div id="detail_description" class="am-panel am-panel-default">
				<div class="am-panel-hd">
				       <h4 class="am-panel-title">
						<?php echo $ld['detail_description']?>
					</h4>
				</div>
 <!-------------文本域1---------------->			
				<div class="am-panel-collapse am-collapse am-in">	
					<div class="am-panel-bd">
					    	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"></label>				
					               <div  class="am-u-lg-9 am-u-md-8 am-u-sm-8">
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0)
							{foreach ($backend_locales as $k => $v){?>
							<?php	if($configs["show_edit_type"]){?>
								<div class="am-form-group">
									<div ><span class="ckeditorlanguage  "><?php echo $v['Language']['name'];?></span></div>
									<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[CategoryProductI18n][<?php echo $k;?>][detail]" rows="10" style="width:auto;height:300px;"><?php echo isset($this->data['CategoryProductI18n'][$v['Language']['locale']]['detail'])?$this->data['CategoryProductI18n'][$v['Language']['locale']]['detail']:'';?></textarea>
									<script>
									var editor;
									KindEditor.ready(function(K) {
									editor = K.create('#elm<?php echo $v['Language']['locale'];?>', {
								  width:'80%',
			                        langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
									});
									</script>
								</div>
							<?php }else{?>
							<div class="btnouter" >
								<span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span>
								<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[CategoryProductI18n][<?php echo $k;?>][detail]" rows="10">
								<?php echo isset($this->data['CategoryProductI18n'][$v['Language']['locale']]['detail'])?$this->data['CategoryProductI18n'][$v['Language']['locale']]['detail']:'';?>
								</textarea>
								<?php echo $ckeditor->load("elm".$v['Language']['locale']); ?>
							</div>		
							<?php }?>
						<?php }} ?>
					</div>
					<div class="am-cf"></div>
					 		
							<div class="btnouter"  >
							        
					 <button type="submit" class="mt am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button> 
					 <button type="reset" class="mt am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
						 
						   </div>  	
					</div>
				</div>
			</div>
 <!-------------文本域结束---------------->		
			<div id="advanced_set_up" class="am-panel am-panel-default">
				<div class="am-panel-hd">
				    <h4 class="am-panel-title">
						<label><?php echo $ld['advanced'].'&nbsp;'.$ld['set_up'];?></label>
					</h4>
				</div>
				<div class="am-panel-collapse am-collapse am-in">
			    	<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label">
								<?php echo $ld['url']?>
							</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
							   <input type="text" name="data[CategoryProduct][link]" value="<?php echo isset($this->data['CategoryProduct']['link'])?$this->data['CategoryProduct']['link']:'';?>"/>
							 </div></div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label "  >
								<?php echo $ld['routeurl']?>
							</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input type="text" id="Route_url" onchange="checkrouteurl()" name="data[Route][url]" value="<?php echo isset($routecontent['Route']['url'])?$routecontent['Route']['url']:'';?>" />
									<input type="hidden" id="route_url_h" value="0">
								</div>
								  <div style="padding-top:8px;">(<?php echo $ld['routeurl_desc'] ?>)</div>
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-group-label">
								<?php echo $ld['parent_category_image']?>
							</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input name="data[CategoryProduct][img02]" type="text" id="category_02" value="<?php echo isset($this->data['CategoryProduct']['img02'])?$this->data['CategoryProduct']['img02']:'';?>" />
									
									<input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('category_02')" value="<?php echo $ld['choose_picture']?>" style="margin-top:5px;" />
								
									<div class="img_select" style="margin:5px;">
										<?php echo $html->image((isset($this->data['CategoryProduct']['img02'])&&$this->data['CategoryProduct']['img02']!="")?$this->data['CategoryProduct']['img02']:$configs['shop_default_img'],array('id'=>'show_category_02'))?>
									</div>
								</div>
							</div>
						</div>		
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label " style="margin-top:18px;">
								<?php echo   $ld['menu_icon']?>
							</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6" >
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
									<input type="text" name="data[CategoryProduct][img01]" id="category_01" value="<?php echo isset($this->data['CategoryProduct']['img01'])?$this->data['CategoryProduct']['img01']:'';?>" />
										
									<input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('category_01')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;" />
								
									<div class="img_select" style="margin:5px;">
										<?php echo $html->image((isset($this->data['CategoryProduct']['img01'])&&$this->data['CategoryProduct']['img01']!="")?$this->data['CategoryProduct']['img01']:$configs['shop_default_img'],array('id'=>'show_category_01'))?>
									</div>
								</div>
							</div>
						</div>		
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-group-label"  style="margin-top:17px;">CODE</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input name="data[CategoryProduct][code]" type="text" value="<?php echo isset($this->data['CategoryProduct']['code'])?$this->data['CategoryProduct']['code']:'';?>" />
								</div>
							</div>
						</div>			
						<div class="am-form-group" style="margin-top:18px;">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-group-label">
								<?php echo $ld['meta_keywords']?>
							</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">
									<input name="data[CategoryProductI18n][<?php echo $k;?>][meta_keywords]" type="text" value="<?php echo isset($this->data['CategoryProductI18n'][$v['Language']['locale']]['meta_keywords'])?$this->data['CategoryProductI18n'][$v['Language']['locale']]['meta_keywords']:'';?>">
								</div>
								<?php if(sizeof($backend_locales)>1){?>
									<label class="am-u-lg-2 am-u-md-3 am-u-sm-3  am-form-group-label" style="font-weight:normal;">
										<?php echo $ld[$v['Language']['locale']]?>
									</label>
								<?php }?>&nbsp;
							<?php }}?>
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label">
								<?php echo $ld['meta_description']?>
							</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">
									<textarea name="data[CategoryProductI18n][<?php echo $k;?>][meta_description]">
										<?php echo isset($this->data['CategoryProductI18n'][$v['Language']['locale']]['meta_description'])?$this->data['CategoryProductI18n'][$v['Language']['locale']]['meta_description']:"";?>
									</textarea>
								</div>
								<?php if(sizeof($backend_locales)>1){?>
									<label class="am-u-lg-1 am-u-md-1 am-u-sm-3   am-form-group-label " style="font-weight:normal;padding-top:12px;">
										<?php echo $ld[$v['Language']['locale']]?>
									</label>
								<?php }?>
							<?php }}?>
							</div>
						</div>
                        <div class="am-form-group">
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-radio-label">
								<?php echo $ld['open_screening']?>
							</label>
                            <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><div>
                                <label class="am-checkbox am-success"><input type="checkbox" data-am-ucheck name="data[CategoryFilter][status]" value="1" <?php if(isset($filer_status)&&$filer_status=="1"){?>checked<?php }?> id="open_filter" onclick="filter_open(this)"><?php echo $ld['open_screening_properties']?></label>
                            </div></div>
                        </div>
                        <div id="is_filter" class="am-form-group" style="display:none;">
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-4  " style="padding-top:18px;">价格区间</label>
                            <div class="am-u-lg-6 am-u-md-9 am-u-sm-8" id="add_price">
                                <div class="original_price am-text-center">
                                    <label class="am-u-lg-1 am-u-md-1 am-u-sm-1  am-text-center" style="padding-top:6px;">[<span class="am-icon-plus" onclick="add_price()"></span>]</label>
                                    <div class="am-u-lg-5 am-u-md-5 am-u-sm-5" ><input  type="text"  class="text" value="<?php if(isset($first_price[0])){echo $first_price[0];}?>" name="data[CategoryProduct][start_price][]" ></div>
                                    <label class="am-u-lg-1 am-u-md-1 am-u-sm-1   am-text-center" style="padding-top:6px;">-</label>
                                   
                        </div> <div class="am-u-lg-5 am-u-md-5 am-u-sm-5" style="margin-top:0px;"><input  type="text" class="text" value="<?php if(isset($first_price[1])){ echo $first_price[1];}?>" name="data[CategoryProduct][end_price][]" ></div><div class="am-cf"></div>
                                </div>
                            </div>
                        <div id="is_attr" class="am-form-group" style="display:none;">
                            <input type="hidden" id="show_control" <?php if(isset($clone_attr) && sizeof($clone_attr)>0){ ?>value="1"<?php }else{?>value="0"<?php }?>/>
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-group-label" >筛选属性</label>
                            <div class="am-u-lg-6 am-u-md-6 am-u-sm-6" id="original_attr" >
                                <?php if(isset($clone_attr) && sizeof($clone_attr)>0){ $clone_attr_count=0;
                                        foreach($clone_attr as $kk=>$vv){$clone_attr_count++;?>
                                <div class="original_attr am-text-center"  >
                                    <?php if($clone_attr_count==1){ ?>
                                    <label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-center"  >[<span class="am-icon-plus" onclick="add_attr()"></span>]</label><?php }else{ ?>
                                    <label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-center"  >[<span class="am-icon-minus" onclick="del_attr(this)"></span>]</label>
                                    <?php } ?>
                                    <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
                                        <select name="attr_type[]" onchange="attr_filter(this)">
    										<option value="0"><?php echo $ld['please_select']; ?></option>
    										<?php foreach($product_type as $k=>$v){ ?>
        									<option value="<?php echo  $v['ProductType']['id']?>" <?php if($v['ProductType']['id']== $check_id[$kk]){echo 'selected';}?>><?php echo $v['ProductTypeI18n']['name'] ?></option>
        									<?php } ?>
        								</select>
                                    </div>
                                    <div class="am-u-lg-5 am-u-md-5 am-u-sm-5" ><?php echo $vv; ?></div>
                                    <div class='am-cf'></div>
                                </div>
                                <?php }}else{ ?>
                                <div class="original_attr am-text-center">
                                    <label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-center" style="margin-left:0px;">[<span class="am-icon-plus" onclick="add_attr()"></span>]</label>
                                    <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
                                        <select name="attr_type[]" onchange="attr_filter(this)">
    										<option value="0"><?php echo $ld['please_select']; ?></option>
    										<?php foreach($product_type as $k=>$v){ ?>
        									<option value="<?php echo  $v['ProductType']['id']?>"><?php echo $v['ProductTypeI18n']['name'] ?></option>
        									<?php } ?>
        								</select>
                                    </div>
                                    <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
                                        <select name="data[CategoryProduct][attr_filter][]" onchange="check_filter(this)">
        									<option value="0"><?php echo $ld['please_select']; ?></option>
        								</select>
                                    </div>
                                    <div class='am-cf'></div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?><?php }}?>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-4  am-form-group-label">
								<?php echo $ld['layout']?>
							</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input type="text" name="data[CategoryProduct][layout]" value="<?php echo isset($this->data['CategoryProduct']['layout'])?$this->data['CategoryProduct']['layout']:'';?>" />
								</div>
							</div>
						</div>
							
					 <div class="am-form-group">
			  
						<div  class="btnouter">
							  <button type="submit" class="mt am-btn am-btn-success am-btn-sm am-radius" value="" >
								<?php echo $ld['d_submit'];?></button>
						 	 <button style="margin-top:5px;" type="reset"  class="am-btn-sm am-btn am-btn-default am-radius" value=""  >
								<?php echo $ld['d_reset']?></button>
						 </div>
					  
				      </div>
				      			
					</div>
				</div>
			</div>
			<div id="product_top_description" class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['product_top_description']?>
					</h4>
			    </div>
				<div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
					<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){
					if($configs["show_edit_type"]){?>	
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3  ">
						 </label>
						     <div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
								<div class="am-form-group ">
								     	<span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span>
									<textarea cols="80" id="elm1<?php echo $v['Language']['locale'];?>" name="data[CategoryProductI18n][<?php echo $k;?>][top_detail]" rows="10" style="width:auto;height:300px;"><?php echo isset($this->data['CategoryProductI18n'][$v['Language']['locale']]['top_detail'])?$this->data['CategoryProductI18n'][$v['Language']['locale']]['top_detail']:'';?></textarea>
									<script>
									var editor;
									KindEditor.ready(function(K) {
										editor = K.create('#elm1<?php echo $v['Language']['locale'];?>', {
						      width:'80%',
			                        	langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
									});
									</script>
								</div>
					            </div><div class="am-cf"></div>
						<?php }else{?>		
						<div class="am-form-group">
							<span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span>
							<textarea cols="80" id="elm1<?php echo $v['Language']['locale'];?>" name="data[CategoryProductI18n][<?php echo $k;?>][top_detail]" rows="10">
							<?php echo isset($this->data['CategoryProductI18n'][$v['Language']['locale']]['top_detail'])?$this->data['CategoryProductI18n'][$v['Language']['locale']]['top_detail']:'';?>
							</textarea>
							<?php echo $ckeditor->load("elm1".$v['Language']['locale']); ?>
						</div>			
						<?php }?>				
						<?php }}?>
					  
								 <div class="btnouter" >
							             
										<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="" >
											<?php echo $ld['d_submit'];?></button>
										<button type="reset"  class="am-btn am-btn-default am-btn-sm am-radius" value=""  >
										 <?php echo $ld['d_reset']?></button>
							               
								</div>
							 
					</div>
				
				</div>
			</div>
			<div id="product_bottom_description" class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['product_bottom_description']?>
					</h4>
			    </div>
 <!------------------------------------------------------------------->
			    <div class="am-panel-collapse am-collapse am-in">
			      	<div class="am-panel-bd am-form-detail am-form am-form-horizontal"> 
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){if($configs["show_edit_type"]){?>
					             <label class="am-u-lg-2 am-u-md-3 am-u-sm-3  "> </label>
							     <div class="am-u-lg-10 am-u-md-9 am-u-sm-9 "  >
									  <div class="am-form-group">
											 <span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span>
											<textarea cols="80" id="elm2<?php echo $v['Language']['locale'];?>" name="data[CategoryProductI18n][<?php echo $k;?>][foot_detail]" rows="10" style="width:auto;height:300px;"><?php echo isset($this->data['CategoryProductI18n'][$v['Language']['locale']]['foot_detail'])?$this->data['CategoryProductI18n'][$v['Language']['locale']]['foot_detail']:'';?></textarea>
											<script>
												var editor;
												KindEditor.ready(function(K) {
												 editor = K.create('#elm2<?php echo $v['Language']['locale'];?>', {
									       width:'80%',
						                        	langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
												});
											</script>	
										</div>
							    </div><div class="am-cf"></div>
							    			
							<?php }else{?>	
							<div class="am-form-group">
								<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">
									<?php echo $v['Language']['name'];?>
								</label>
								 <div class="am-u-lg-6 am-u-md-7 am-u-sm-9">
									<textarea cols="80" id="elm2<?php echo $v['Language']['locale'];?>" name="data[CategoryProductI18n][<?php echo $k;?>][foot_detail]" rows="10"><?php echo isset($this->data['CategoryProductI18n'][$v['Language']['locale']]['top_detail'])?$this->data['CategoryProductI18n'][$v['Language']['locale']]['foot_detail']:'';?></textarea>
									<?php echo $ckeditor->load("elm2".$v['Language']['locale']); ?>
								</div>			
							</div>
								
								
						<?php }?>
						<?php }}?> 
					</div>
	 <!----------------------------button--------------------------------------->
 		                     
							 <div class="btnouter " style="margin-top:15px;margin-bottom:30px;" >
						             
						              <button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="" >
								    <?php echo $ld['d_submit'];?></button>
								<button type="reset"  class="am-btn am-btn-default am-btn-sm am-radius" value=""  >
								    <?php echo $ld['d_reset']?></button>
						             
						              </div>
				 
				</div>
			</div>
		</div>
	<?php echo $form->end();?>
</div>
<script type="text/javascript">
$(function(){
    if($("#open_filter").prop("checked")){
        $("#is_filter").show();
        $("#is_attr").show();
    }else{
        $("#is_filter").hide();
        $("#is_attr").hide();
    }
})
function productcat_input_checks(){
	var productcat_name_obj = document.getElementById("productcat_name_"+backend_locale);
	if(productcat_name_obj.value==""){
		alert("<?php echo $ld['enter_category_name']?>");
		return false;
	}
	return true;
}

function filter_open(obj){
    if(obj.checked){
        $("#is_filter").show();
        $("#is_attr").show();
    }else{
        $("#is_filter").hide();
        $("#is_attr").hide();
        $("#is_filter input[type=text]").val("");
        $("#is_attr select option").prop("select",false);
    }
}

function add_price(){
    var add_price_html="<label class='am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-center' style='margin-left:0px;'>[<span onclick='del_price(this)' class='am-icon-minus'></span>]</label><div class='am-u-lg-5 am-u-md-5 am-u-sm-5'><input type='text' name='data[CategoryProduct][start_price][]' value=''></div><label class='am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-center' style='margin-left:0px;'>-</label><div class='am-u-lg-5 am-u-md-5 am-u-sm-5'><input type='text' name='data[CategoryProduct][end_price][]' value=''></div><div class='am-cf'></div>";
    var add_price_div = document.createElement("div");
    add_price_div.innerHTML=add_price_html;
    $(add_price_div).prop("class",'original_price am-text-center');
    $("#add_price").append(add_price_div);
}

function del_price(obj){
    $(obj).parent().parent().remove();
}

function add_attr(){
    var product_type_option=$("select[name='attr_type[]']").html();
    var original_attr_html="<label class='am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-center' style='margin-left:0px;'>[<span onclick='del_price(this)' class='am-icon-minus'></span>]</label><div class='am-u-lg-5 am-u-md-5 am-u-sm-5'><select onchange='attr_filter(this)' name='attr_type[]'>"+product_type_option+"</select></div><div class='am-u-lg-5 am-u-md-5 am-u-sm-5'><select onchange='check_filter(this)' name='data[CategoryProduct][attr_filter][]'><option value='0'>"+j_please_select+"</option></select></div><div class='am-cf'></div>";
    var original_attr_div = document.createElement("div");
    original_attr_div.innerHTML=original_attr_html;
    $(original_attr_div).prop("class",'original_attr am-text-center');
    $(original_attr_div).find("select option").prop("select",false);
    $("#original_attr").append(original_attr_div);
}

function del_attr(obj){
    $(obj).parent().parent().remove();
}

function attr_filter(obj){
    var product_type_id=obj.value;
    var attr_filter_div=$(obj).parent().parent().find("div:eq(1)");
        $(attr_filter_div).html("<select onchange='check_filter(this)' name='data[CategoryProduct][attr_filter][]'><option value='0'>"+j_please_select+"</option></select>");
    if(product_type_id!="0"){
        filter_show(product_type_id,obj);
    }
}

function filter_show(product_type_id,obj){
    $.ajax({
        url:admin_webroot+"product_categories/getattr/"+product_type_id,
        Type:"POST",
        data: {},
        dataType:"json",
        success:function(data){
            var attr_filter_div=$(obj).parent().parent().find("div:eq(1)");
            var attr_filter=$(attr_filter_div).find("select");
            if(data.flag=='1'){
                $(data.attr_list).each(function(index,item){
                    $("<option></option>").val(item['Attribute']['id']).text(item['AttributeI18n']['name']).appendTo($(attr_filter));
                });
            }
        }
    });
}
</script>
