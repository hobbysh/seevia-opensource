<style type="text/css">
	.am-radio input[type="radio"]{margin-left:0px;}
	.btnouter{margin:10px;}
	.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
	.img_select{max-width:150px;max-height:120px;}
</style>
<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-g">
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
			<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			<li><a href="#detail_description"><?php echo $ld['detail_description']?></a></li>
			<li><a href="#advanced_set_up"><?php echo $ld['advanced'].'&nbsp;'.$ld['set_up']?></a></li>
		</ul>
	</div>
	<?php echo $form->create('ArticleCategory',array('action'=>'view/'.(isset($this->data['CategoryArticle']['id'])?$this->data['CategoryArticle']['id']:""),'onsubmit'=>'return articlename_input_checks();'));?> <!-- 编辑按钮区域 -->
<div class="btnouter am-text-right" data-am-sticky="{top:'10%'}">
<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" onclick="return cha2()" value="<?php echo $ld['d_submit'];?>" />
<input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
</div>
<!-- 编辑按钮区域 -->
						 
		<input id="CategoryArticle_id" name="data[CategoryArticle][id]" type="hidden" value="<?php echo isset($this->data['CategoryArticle']['id'])?$this->data['CategoryArticle']['id']:"";?>">
		<input name="data[CategoryArticle][orderby]" type="hidden" value="<?php echo isset($this->data['CategoryArticle']['orderby'])?$this->data['CategoryArticle']['orderby']:'';?>"/>		
		<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
		<input name="data[CategoryArticleI18n][<?php echo $k;?>][locale]" id="CategoryArticleI18n<?php echo $k;?>Locale"  type="hidden" value="<?php echo $v['Language']['locale'];?>">
		<?php }}?>
		<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
			<div id="basic_information" class="am-panel am-panel-default">
			    <div class="am-panel-hd">
				    <h4 class="am-panel-title">
						<?php echo $ld['basic_information'] ?>
					</h4>
				</div>
				<div class="am-panel-collapse am-collapse am-in">
				    <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
						<div class="am-form-group">
						    <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label">
						    	<?php echo $ld['system_type']?>
						    </label>
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<select id="brand_code_list" name="data[CategoryArticle][sub_type]" data-am-selected>
				                                    <?php foreach( $Resource_info["sub_type"] as $k=>$v){ ?>
				                                        <option value="<?php echo $k;?>" <?php echo isset($this->data['CategoryArticle']['sub_type'])&&$k==$this->data['CategoryArticle']['sub_type']?"selected":"";?> ><?php echo $v;?></option>
				                                    <?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="am-form-group">
						    <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label">
						    	<?php echo $ld['higher_category']?>
						    </label>
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
									<select id="CategoryParentId" name="data[CategoryArticle][parent_id]" data-am-selected="" onchange="searchAc(this.value)" >
										<option value="0"><?php echo $ld['root']?></option>
										<?php if(isset($categories_tree) && sizeof($categories_tree)>0){foreach($categories_tree as $k=>$v){?>
										<option value="<?php echo $v['CategoryArticle']['id'];?>" <?php echo isset($this->data['CategoryArticle']['parent_id'])&&$v['CategoryArticle']['id']==$this->data['CategoryArticle']['parent_id']?"selected":"";?> ><?php echo $v['CategoryArticleI18n']['name'];?></option>
										<?php if(isset($v['SubCategory']) && sizeof($v['SubCategory'])>0){foreach($v['SubCategory'] as $kk=>$vv){?>
										<option value="<?php echo $vv['CategoryArticle']['id'];?>" <?php echo isset($this->data['CategoryArticle']['parent_id'])&&$vv['CategoryArticle']['id']==$this->data['CategoryArticle']['parent_id']?"selected":"";?> >|-- <?php echo $vv['CategoryArticleI18n']['name'];?></option>
										<?php if(isset($vv['SubCategory']) && sizeof($vv['SubCategory'])>0){foreach($v['SubCategory'] as $kkk=>$vvv){?>
										<?php }}}}}}?>
									</select>
								</div>
							</div>
						</div>			
					
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label">
								<?php echo $ld['category_name']?>
							</label>					
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
							<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){?><?php foreach ($backend_locales as $k => $v){?>	
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">			
									<input id="article_name_<?php echo $v['Language']['locale'];?>" name="data[CategoryArticleI18n][<?php echo $k;?>][name]" type="text" value="<?php echo isset($this->data['CategoryArticleI18n'][$v['Language']['locale']]['name'])?$this->data['CategoryArticleI18n'][$v['Language']['locale']]['name']:'';?>">
								</div>
								<?php if(sizeof($backend_locales)>1){?>
									<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;">
										<?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em></label>
								<?php }?>
							
							<?php }}?>
							</div>	
						</div>			
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label">
								<?php echo $ld['display']?>
							</label>
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<label class="am-radio am-success">
										<input type="radio" name="data[CategoryArticle][status]" data-am-ucheck
										 value="1" <?php echo !isset($this->data['CategoryArticle']['status'])||(isset($this->data['CategoryArticle']['status'])&&$this->data['CategoryArticle']['status']==1)?"checked":"";?> >
										<?php echo $ld['yes']?>
									</label>&nbsp;&nbsp;
									<label class="am-radio am-success">
										<input name="data[CategoryArticle][status]" id="CategoryStatus" type="radio" data-am-ucheck
										 value="0" <?php echo isset($this->data['CategoryArticle']['status'])&&$this->data['CategoryArticle']['status']==0?"checked":"";?> />
										<?php echo $ld['no']?>
									</label>
								</div>
							</div>
						</div>			
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label">
								<?php echo $ld['show_new']?>
							</label>
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<label class="am-radio am-success">
										<input type="radio" name="data[CategoryArticle][new_show]" data-am-ucheck
										 value="1" <?php echo !isset($this->data['CategoryArticle']['new_show'])||(isset($this->data['CategoryArticle']['new_show'])&&$this->data['CategoryArticle']['new_show']==1)?"checked":"";?> />
										<?php echo $ld['yes']?>
									</label>&nbsp;&nbsp;
									<label class="am-radio am-success">
										<input name="data[CategoryArticle][new_show]" type="radio" data-am-ucheck
										 value="0" <?php echo isset($this->data['CategoryArticle']['new_show'])&&$this->data['CategoryArticle']['new_show']==0?"checked":"";?> />
										<?php echo $ld['no']?>
									</label>
								</div>
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label">
								<?php echo $ld['menu_icon']?>
							</label>
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">		
									<input name="data[CategoryArticle][img01]" type="text" id="category_01" value="<?php echo isset($this->data['CategoryArticle']['img01'])?$this->data['CategoryArticle']['img01']:'';?>" />
										
									<input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('category_01')" value="<?php echo $ld['choose_picture']?>" style="margin-top:5px;"/>
									
									<div class="img_select" style="margin:5px;">
										<?php echo $html->image((isset($this->data['CategoryArticle']['img01'])&&$this->data['CategoryArticle']['img01']!="")?$this->data['CategoryArticle']['img01']:$configs['shop_default_img'],array('id'=>'show_category_01'))?>
									</div>
								</div>
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label">
								<?php echo $ld['parent_category_image']; ?>
							</label>
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input name="data[CategoryArticle][img02]" type="text" id="category_02" value="<?php echo isset($this->data['CategoryArticle']['img02'])?$this->data['CategoryArticle']['img02']:'';?>" />
									
									<input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('category_02')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>
					
									<div class="img_select" style="margin:5px;">
										<?php echo $html->image((isset($this->data['CategoryArticle']['img02'])&&$this->data['CategoryArticle']['img02']!="")?$this->data['CategoryArticle']['img02']:$configs['shop_default_img'],array('id'=>'show_category_02'))?>
									</div>
								</div>
							</div>
						</div>	

						<div class="am-form-group" >
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label-text">
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
										<label class="am-radio am-success" >
											<input type="radio" name="orderby" value="2" data-am-ucheck/><?php echo $ld['at']?>
										</label>
							</div>
						</div>

						<div class="am-form-group">
							<div class="am-u-lg-2 am-u-md-3 am-u-sm-3">&nbsp;</div>
							<div class="am-u-lg-4 am-u-md-7 am-u-sm-7">
								<select id='orderby' name="orderby_sel" data-am-selected>
									<option value=" " selected></option>
								    </select>
							</div>
							<div class="am-u-sm-2 am-u-md-3 am-hide-lg-only">&nbsp;</div>
							<label class="am-u-lg-3 am-u-md-9 am-u-sm-7" style="font-weight:normal;"><?php echo $ld['after']?></label>		
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
				<div class="am-panel-collapse am-collapse am-in">	
					<div class="am-panel-bd ">
					 <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label"> </label>
							<div class="am-u-lg-10  am-u-md-9 am-u-sm-9">			
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0)
							{foreach ($backend_locales as $k => $v){?>
							<?php	if($configs["show_edit_type"]){?>
							<div class="am-form-group">
								<div ><span class="ckeditorlanguage  "><?php echo $v['Language']['name'];?></span></div>
								<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[CategoryArticleI18n][<?php echo $k;?>][detail]" rows="10" style="width:auto;height:300px;"><?php echo isset($this->data['CategoryArticleI18n'][$v['Language']['locale']]['detail'])?$this->data['CategoryArticleI18n'][$v['Language']['locale']]['detail']:'';?></textarea>
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
							<div class="btnouter">
								<span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span>
								<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[CategoryArticleI18n][<?php echo $k;?>][detail]" rows="10">
								<?php echo isset($this->data['CategoryArticleI18n'][$v['Language']['locale']]['detail'])?$this->data['CategoryArticleI18n'][$v['Language']['locale']]['detail']:'';?>
								</textarea>
								<?php echo $ckeditor->load("elm".$v['Language']['locale']); ?>
							</div>		
							<?php }?>
						<?php }} ?>
							
						</div> <div class="am-cf"></div> <!----------文本域over--------->
						 <label class="am-u-lg-2 am-u-md-3 am-u-sm-3  am-form-group-label"> </label>
								<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
									 
								</div><div class="am-cf"></div>		
					</div>
				</div>
			</div>
						
			<div id="advanced_set_up" class="am-panel am-panel-default">
				<div class="am-panel-hd">
				    <h4 class="am-panel-title">
						<label><?php echo $ld['advanced'].'&nbsp;'.$ld['set_up'];?></label>
					</h4>
				</div>
				<div class="am-panel-collapse am-collapse am-in">
			    	<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label">
								<?php echo $ld['meta_keywords']?>
							</label>
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
							<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">
									<input name="data[CategoryArticleI18n][<?php echo $k;?>][meta_keywords]" type="text" value="<?php echo isset($this->data['CategoryArticleI18n'][$v['Language']['locale']]['meta_keywords'])?$this->data['CategoryArticleI18n'][$v['Language']['locale']]['meta_keywords']:'';?>">
								</div>	
									<?php if(sizeof($backend_locales)>1){?>
										<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;padding-top:3px;">
											<?php echo $ld[$v['Language']['locale']]?></label>
									<?php }?>&nbsp;
									
							<?php }}?>	
							</div>
						</div>
						<div class="am-form-group" >
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label">
								<?php echo $ld['meta_description']?>
							</label>
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
							<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">
									<textarea name="data[CategoryArticleI18n][<?php echo $k;?>][meta_description]"><?php echo isset($this->data['CategoryArticleI18n'][$v['Language']['locale']]['meta_description'])?$this->data['CategoryArticleI18n'][$v['Language']['locale']]['meta_description']:'';?></textarea>
								</div>		
									<?php if(sizeof($backend_locales)>1){?>
										<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;padding-top:11px;"><?php echo $ld[$v['Language']['locale']]?></label>
									<?php }?>
							<?php }}?>	
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label">CODE</label>
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input name="data[CategoryArticle][code]" type="text" value="<?php echo isset($this->data['CategoryArticle']['code'])?$this->data['CategoryArticle']['code']:'';?>" />
								</div>
							</div>
						</div>		
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label">
								<?php echo   $ld['article_template']?>
							</label>
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input type="text" name="data[CategoryArticle][template]" value="<?php echo isset($this->data['CategoryArticle']['template'])?$this->data['CategoryArticle']['template']:'';?>" />
								</div>
							</div>
						</div>		
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label">
								<?php echo $ld['layout']?>
							</label>
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input type="text" name="data[CategoryArticle][layout]" value="<?php echo isset($this->data['CategoryArticle']['layout'])?$this->data['CategoryArticle']['layout']:'';?>" />
								</div>
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label">
								<?php echo $ld['routeurl']?>
							</label>
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input type="text" id="Route_url" onchange="checkrouteurl()" name="data[Route][url]" value="<?php echo isset($routecontent['Route']['url'])?$routecontent['Route']['url']:'';?>" placeholder="(<?php echo $ld['routeurl_desc'] ?>)" /><input type="hidden" id="route_url_h" value="0">
								</div>
							 
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label">
								<?php echo $ld['jump_address']?>
							</label>
							<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input type="text" name="data[CategoryArticle][link]" value="<?php echo isset($this->data['CategoryArticle']['link'])?$this->data['CategoryArticle']['link']:'';?>"  placeholder="(<?php echo $ld['page_url_desc'] ?>)"/>
								</div>
								 
							</div>
						</div>		
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['classification_tree_type'];?></label>
							 <div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<label class="am-radio am-success">
										<input id="CategoryStatus" name="data[CategoryArticle][tree_show_type]" data-am-ucheck type="radio" value="0" <?php echo isset($this->data['CategoryArticle']['tree_show_type'])&&$this->data['CategoryArticle']['tree_show_type']==0?"checked":"";?> ><?php echo $ld['type_top'];?>
									</label>&nbsp;&nbsp;
									<label class="am-radio am-success">
										<input type="radio" name="data[CategoryArticle][tree_show_type]" data-am-ucheck  value="1" <?php echo !isset($this->data['CategoryArticle']['tree_show_type'])||(isset($this->data['CategoryArticle']['tree_show_type'])&&$this->data['CategoryArticle']['tree_show_type']==1)?"checked":"";?> ><?php echo $ld['same_level'];?>
									</label>&nbsp;&nbsp;
									<label class="am-radio am-success">
										<input type="radio" name="data[CategoryArticle][tree_show_type]" data-am-ucheck  value="2" <?php echo  isset($this->data['CategoryArticle']['tree_show_type']) && $this->data['CategoryArticle']['tree_show_type']==2?"checked":"";?> ><?php echo $ld['sub_grade'];?>
									</label>
								</div>
							</div>
						</div>
			 	 	
					</div>
				</div>
			</div>
		</div>
	<?php echo $form->end();?>
</div>
<script type="text/javascript">
function articlename_input_checks(){
	var article_name_obj = document.getElementById("article_name_"+backend_locale);

	if(article_name_obj.value==""){
		alert("<?php echo $ld['enter_article_category_name']?>");
		return false;
	}
	return true;

}

function productcat_input_checks(){
	var productcat_name_obj = document.getElementById("productcat_name_"+backend_locale);
	
	if(productcat_name_obj.value==""){
		alert("<?php echo $ld['enter_category_name']?>");
		return false;
	}
	return true;
}
</script>
