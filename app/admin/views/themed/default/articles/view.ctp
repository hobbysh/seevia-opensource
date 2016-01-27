<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<style>
	.am-radio input[type="radio"]{margin-left:0px;}
	#other_cats .am-selected.am-dropdown{margin-bottom:20px;}
       .am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
 
	.img_select{max-width:150px;max-height:120px;}
 
</style>

<div  class="am-g">
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-detail-menu ">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
		   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			<li><a href="#article_cantent"><?php echo $ld['article_cantent']?></a></li>
			<li><a href="#advanced_set_up"><?php echo $ld['advanced'].$ld['set_up'];?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content am-detail-view" id="accordion"  >
	<?php echo $form->create('Articles',array('action'=>'view/'.(isset($article['Article']['id'])?$article['Article']['id']:''),'enctype'=>'multipart/form-data','onsubmit'=>'return article_name_check()'));?> <input id="article_id" name="data[Article][id]" type="hidden" value="<?php echo isset($article['Article']['id'])?$article['Article']['id']:'';?>">

<!-- 编辑按钮区域 -->
<div class="btnouter am-text-right" data-am-sticky="{top:'10%'}">
<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" onclick="return cha2()" value="<?php echo $ld['d_submit'];?>" />
<input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
</div>
<!-- 编辑按钮区域 -->
	
		<!--页面传语言-->
		<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
		<input id="ArticleI18n<?php echo $k;?>Locale" name="data[ArticleI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
		<input type="hidden" name="data[TagI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale'];?>" />
		<input id="ArticleGalleryI18n<?php echo $k;?>Locale" name="data[GalleryI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
		<?php }}?>
		<div id="basic_information" class="am-panel am-panel-default">
	  		<div class="am-panel-hd" >
				<h4 class="am-panel-title">
					<?php echo $ld['basic_information']?>
				</h4>
		    </div>
		    <div class="am-panel-collapse am-collapse am-in">
	      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['article_title']?></label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">	
								<input type="text" id="article_name_<?php echo $v['Language']['locale']?>" name="data[ArticleI18n][<?php echo $k;?>][title]" value="<?php echo isset($article['ArticleI18n'][$v['Language']['locale']])?$article['ArticleI18n'][$v['Language']['locale']]['title']:'';?>" />
							</div>
							<?php if(sizeof($backend_locales)>1){?>
									<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;"><?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em></label>
							<?php }?>
						<?php }}?>
						</div>	
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['subtitle']?></label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">		
								<input type="text" id="article_subtitle_<?php echo $v['Language']['locale']?>" name="data[ArticleI18n][<?php echo $k;?>][subtitle]" value="<?php echo isset($article['ArticleI18n'][$v['Language']['locale']])?$article['ArticleI18n'][$v['Language']['locale']]['subtitle']:'';?>" />
							</div>
							<?php if(sizeof($backend_locales)>1){?>
								<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;">
									<?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em>
								</label>
							<?php }?>
						<?php }}?>
						</div>	
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $ld['category_article']?></label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<select name="data[Article][category_id]" id="ArticlesCategory" data-am-selected>
                                    <option value="0"><?php echo $ld['please_select'];?></option>
									<?php if(isset($categories_tree_A) && sizeof($categories_tree_A)>0){?>
										<?php foreach($categories_tree_A as $first_k=>$first_v){?>
									<option value="<?php echo $first_v['CategoryArticle']['id'];?>" <?php if(isset($category_id)&&$category_id == $first_v['CategoryArticle']['id']){ echo "selected";}?> ><?php echo $first_v['CategoryArticleI18n']['name'];?></option>
									<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
										<?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
									<option value="<?php echo $second_v['CategoryArticle']['id'];?>" <?php if(isset($category_id)&&$category_id == $second_v['CategoryArticle']['id']){ echo "selected";}?> >&nbsp;&nbsp;<?php echo $second_v['CategoryArticleI18n']['name'];?></option>
									<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
										<?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
									<option value="<?php echo $third_v['CategoryArticle']['id'];?>" <?php if(isset($category_id)&&$category_id == $third_v['CategoryArticle']['id']){ echo "selected";}?> >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryArticleI18n']['name'];?></option>
									<?php }}}}}}?>
								</select>
							</div>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $ld['extended_cotegory']?></label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<button type="button" class="am-btn am-btn-warning am-btn-sm am-radius" onclick="addOtherCat()" value="<?php echo $ld['add']?>" style="margin-bottom:10px;"><?php echo $ld['add']?></button>
								<?php foreach( $category_arr as $k=>$v ){?>
								<select class="all" name="article_categories_id[]">
									<option value="0"><?php echo $ld['select_categories']?></option>
									<?php if(isset($categories_tree_A) && sizeof($categories_tree_A)>0){?>
									<?php foreach($categories_tree_A as $first_k=>$first_v){?>
									<option value="<?php echo $first_v['CategoryArticle']['id'];?>"<?php if($v['ArticleCategory']['category_id'] == $first_v['CategoryArticle']['id']){ echo "selected";}?> ><?php echo $first_v['CategoryArticleI18n']['name'];?></option>
									<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
									<?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
									<option value="<?php echo $second_v['CategoryArticle']['id'];?>" <?php if($v['ArticleCategory']['category_id']== $second_v['CategoryArticle']['id']){ echo "selected";}?> >&nbsp;&nbsp;<?php echo $second_v['CategoryArticleI18n']['name'];?></option>
									<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
									<?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
									<option value="<?php echo $third_v['CategoryArticle']['id'];?>" <?php if($v['ArticleCategory']['category_id'] == $third_v['CategoryArticle']['id']){ echo "selected";}?> >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryArticleI18n']['name'];?></option>
									<?php }}}}}}?>
								</select>
								<?php }?>
								<div id="other_cats"></div>
							</div>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $ld['article_type']?></label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<select name="data[Article][type]"  data-am-selected>
									<?php foreach( $Resource_info["sub_type"] as $k=>$v ){?>
							    		<option value="<?php echo $k;?>"
									<?php if(isset($article['Article']['type'])&&$article['Article']['type'] == $k){echo "selected";}?>><?php echo $v;?>
									</option>
									<?php }?>
								</select>
							</div>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['thumbnail']?></label>	
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">					
								<input id="articleI18n_01_<?php echo $v['Language']['locale'];?>" type="text" name="data[ArticleI18n][<?php echo $k;?>][img01]" value="<?php echo isset($article['ArticleI18n'][$v['Language']['locale']])?$article['ArticleI18n'][$v['Language']['locale']]['img01']:'';?>" />
								
								<input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('articleI18n_01_<?php echo $v['Language']['locale'];?>')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>
							
								<?php if(sizeof($backend_locales)>1){?>
									<span class="lang"><?php echo $ld[$v['Language']['locale']]?></span>
								<?php }?>&nbsp;
							
								<div class="img_select" style="margin:5px;">
									<?php echo $html->image((isset($article['ArticleI18n'][$v['Language']['locale']]['img01'])&&$article['ArticleI18n'][$v['Language']['locale']]['img01']!="")?$article['ArticleI18n'][$v['Language']['locale']]['img01']:$configs['shop_default_img'],array('id'=>'show_articleI18n_01_'.$v['Language']['locale']))?>
								</div>
							</div>
						<?php }}?>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['article_importance']?></label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<label class="am-radio am-success">
									<input type="radio"  data-am-ucheck name="data[Article][importance]" value="0" <?php if((isset($article['Article']['importance'])&&$article['Article']['importance'] == 0)||!isset($article['Article']['importance'])){echo "checked";} ?> /><?php echo $ld['ordinary']?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label  class="am-radio am-success">
									<input type="radio"  data-am-ucheck name="data[Article][importance]" value="1" <?php if(isset($article['Article']['importance'])&&$article['Article']['importance'] == 1){echo "checked";} ?> /><?php echo $ld['top_article']?>
								</label>
							</div>
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3  am-form-group-label am-u-end"><em style="color:red;">*</em></label>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['valid']?></label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<label class="am-radio am-success">
									<input type="radio" data-am-ucheck name="data[Article][status]" value="1" <?php if((isset($article['Article']['status'])&&$article['Article']['status'] == 1)||!isset($article['Article']['status'])){echo "checked";} ?> /><?php echo $ld['yes']?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label class="am-radio am-success">
									<input type="radio" data-am-ucheck name="data[Article][status]" value="0" <?php if(isset($article['Article']['status'])&&$article['Article']['status'] != 1){echo "checked";} ?> /><?php echo $ld['no']?>
								</label>
							</div>
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label"><em style="color:red;">*</em></label>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['home_show']?></label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<label class="am-radio am-success">
									<input type="radio" data-am-ucheck name="data[Article][front]" value="1" <?php if((isset($article['Article']['front'])&&$article['Article']['front'] == 1)||!isset($article['Article']['front'])){echo "checked";} ?> />
									<?php echo $ld['yes']?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label class="am-radio am-success">
									<input type="radio" data-am-ucheck name="data[Article][front]" value="0" <?php if(isset($article['Article']['front'])&&$article['Article']['front'] != 1){echo "checked";} ?> />
									<?php echo $ld['no']?>
								</label>
							</div>
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label"><em style="color:red;">*</em></label>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['recommend']?></label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<label class="am-radio am-success">
									<input type="radio" data-am-ucheck  name="data[Article][recommand]" value="1" <?php if((isset($article['Article']['recommand'])&&$article['Article']['recommand'] == 1)||!isset($article['Article']['recommand'])){echo "checked";} ?> />
									<?php echo $ld['yes']?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label class="am-radio am-success">
									<input type="radio" data-am-ucheck name="data[Article][recommand]" value="0" <?php if(isset($article['Article']['recommand'])&&($article['Article']['recommand'] != 1)){echo "checked";} ?> />
									<?php echo $ld['no']?>
								</label>
							</div>
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label"><em style="color:red;">*</em></label>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['commentable']?></label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">	
								<label class="am-radio am-success">
									<input type="radio" data-am-ucheck name="data[Article][comment]" value="1" <?php if((isset($article['Article']['comment'])&&$article['Article']['comment'] == 1)||!isset($article['Article']['comment'])){echo "checked";} ?> />
									<?php echo $ld['yes']?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label class="am-radio am-success">
									<input type="radio" data-am-ucheck name="data[Article][comment]" value="0" <?php if(isset($article['Article']['comment'])&&$article['Article']['comment'] != 1){echo "checked";} ?> />
									<?php echo $ld['no']?>
								</label>
							</div>
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label"><em style="color:red;">*</em></label>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['article_title_displayed']?></label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<label class="am-radio am-success">
									<input type="radio" data-am-ucheck name="data[Article][displayed_title]" value="1" <?php if((isset($article['Article']['displayed_title'])&&$article['Article']['displayed_title'] == 1)||!isset($article['Article']['displayed_title'])){echo "checked";} ?> />
									<?php echo $ld['yes']?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label class="am-radio am-success">
									<input type="radio" data-am-ucheck name="data[Article][displayed_title]" value="0" <?php if(isset($article['Article']['displayed_title'])&&$article['Article']['displayed_title'] != 1){echo "checked";} ?> />
									<?php echo $ld['no']?>
								</label>
							</div>
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label"><em style="color:red;">*</em></label>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['article_add_time_displayed']?></label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<label class="am-radio am-success">
									<input type="radio" data-am-ucheck name="data[Article][displayed_add_time]" value="1" <?php if((isset($article['Article']['displayed_add_time'])&&$article['Article']['displayed_add_time'] == 1)||!isset($article['Article']['displayed_add_time'])){echo "checked";} ?> />
									<?php echo $ld['yes']?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label class="am-radio am-success">
									<input type="radio" data-am-ucheck name="data[Article][displayed_add_time]" value="0" <?php if(isset($article['Article']['displayed_add_time'])&&$article['Article']['displayed_add_time'] != 1){echo "checked";} ?> />
									<?php echo $ld['no']?>
								</label>
							</div>
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label"><em style="color:red;">*</em></label>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['author']?>email</label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">	
								<input id="author_email" type="text" name="data[Article][author_email]" value="<?php echo isset($article['Article']['author_email'])?$article['Article']['author_email']:'';?>" />
							</div>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['article_author']?></label>	
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>	
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">					
								<input type="text" name="data[ArticleI18n][<?php echo $k;?>][author]" value="<?php echo isset($article['ArticleI18n'][$v['Language']['locale']])?$article['ArticleI18n'][$v['Language']['locale']]['author']:'';?>"/>
							</div>
							<?php if(sizeof($backend_locales)>1){?>
								<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;"><?php echo $ld[$v['Language']['locale']]?></label>
							<?php }?>&nbsp;
						<?php }}?>
						</div>
					</div>
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['article_article_file']; ?></label>
						<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<input type="hidden" id="upload_file_id" name="data[Article][upload_file_id]" value="<?php echo isset($article['Article']['upload_file_id'])?$article['Article']['upload_file_id']:'';?>" />
								<input type="text" id="upload_file_name" name="upload_file_name" value="<?php echo isset($article['UploadFile']['name'])?$article['UploadFile']['name']:'';?>" readonly="true"/>
							</div>
						</div>
					</div>
					 <div class="am-form-group" >
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['sort']?></label>
						<div class="am-u-lg-8 am-u-md-6 am-u-sm-9">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<input type="text" class="input_sort" name="data[Article][orderby]" value="<?php echo isset($article['Article']['orderby'])?$article['Article']['orderby']:"";?>" />
							</div>
						</div>
					</div>
						
				 	<!-------------------->	
				   <div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['article_cantent']?></label>
						<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-11 am-u-md-11 am-u-sm-11">	
								 	<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				<?php if($configs["show_edit_type"]){?>
				<div class="am-form-group">
	    			  <span class="ckeditorlanguage  "><?php echo $v['Language']['name'];?> </span> 
					<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[ArticleI18n][<?php echo $k;?>][content]" rows="10" style="width:auto;height:300px;"><?php echo @$article['ArticleI18n'][$v['Language']['locale']]['content'];?></textarea>
					<script>
						var editor;
						KindEditor.ready(function(K) {
						editor = K.create('#elm<?php echo $v['Language']['locale'];?>', {width:'100%',
                        langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
						});
					</script>
				</div>
				<?php }else{?>
				<div class="am-form-group">
	    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $v['Language']['name'];?></label>
	    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
						<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[ArticleI18n][<?php echo $k;?>][content]" rows="10"><?php echo @$article['ArticleI18n'][$v['Language']['locale']]['content'];?></textarea>
						<?php echo $ckeditor->load("elm".$v['Language']['locale']); ?>
					</div>
				</div>
				<?php }?>
				<?php }}?>
							</div>
						</div>
					</div>	
					<!-------------------->				
				</div>
			</div>
		</div>
	 <div  id="advanced_set_up"class="am-panel am-panel-default" >
	  		<div class="am-panel-hd">
				<h4 class="am-panel-title">
					<?php echo $ld['advanced'].$ld['set_up'];?>
				</h4>
		    </div>
		    <div class="am-panel-collapse am-collapse am-in">
	      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
	      		 
					<div class="am-form-group">
		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['external_links']?></label>
		    			<div class="am-u-lg-9  am-u-md-9 am-u-sm-9">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
		    					<input type="text" name="data[Article][file_url]" value="<?php echo isset($article['Article']['file_url'])?$article['Article']['file_url']:'';?>" />
		    				</div>
		    			</div>
		    		</div>	
					<div class="am-form-group" >
		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['layout']?></label>
		    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">	
		    					<input type="text" name="data[Article][layout]" value="<?php echo isset($article['Article']['layout'])?$article['Article']['layout']:'';?>" />
		    				</div>
		    			</div>
		    		</div>
					<div class="am-form-group">
		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['article_template']?></label>
		    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
		    					<input type="text" name="data[Article][template]" value="<?php echo isset($article['Article']['template'])?$article['Article']['template']:'';?>" />
		    				</div>
		    			</div>
		    		</div>	
					<div class="am-form-group">
		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['routeurl']?></label>
		    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">	
		    					<input type="text" id="Route_url" onchange="checkrouteurl()" name="data[Route][url]" value="<?php echo isset($routecontent['Route']['url'])?$routecontent['Route']['url']:'';?>" /><input type="hidden" id="route_url_h" value="0">
		    				</div><br /><br /><br />(<?php echo $ld['routeurl_desc'] ?>)
		    			</div>
		    		</div>
					<div class="am-form-group">
		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['files_upload']?></label>
		    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    				<div class="am-u-lg-11 am-u-md-11 am-u-sm-11">	
								<input type="file" name="data_file" id="file" />
							</div>
							<br /><br /><em>(<?php echo $ld['only']?> pdf、doc、docx、xls、xlsx)</em>
							<input type="hidden" name="data[Article][file]" value="<?php echo isset($article['Article']['file'])?$article['Article']['file']:'';?>" />
		    			</div>
		    		</div>	
					<div class="am-form-group">
		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['article'].$ld['video']?>Id</label>
		    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
		    					<input  type="text" name="data[Article][video]" value="<?php echo isset($article['Article']['video'])?$article['Article']['video']:'';?>" />
		    				</div>
		    			</div>
		    		</div>
					<div class="am-form-group">
		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['video_upload']?></label>
		    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<input type="file" name="upload_video" id="upload_video" />
							</div>
							<br /><br /><em>(<?php echo $ld['only']?> MP4 <?php echo $ld['prod_type_format']?>)</em>
							<input type="hidden" name="data[Article][upload_video]" value="<?php echo isset($article['Article']['upload_video'])?$article['Article']['upload_video']:'';?>" />
		    			</div>
		    		</div>	
					<div class="am-form-group" >
		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['video_permissions']?></label>
		    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
							<input type="hidden" name="video_competence" value="<?php echo isset($article['Article']['video_competence'])?$article['Article']['video_competence']:'';?>" />
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
								<select name="competence" multiple="multiple" size="4" style="height:auto;">
									<?php if(isset($article['Article']['video_competence'])){
										$video_competence=explode(',',$article['Article']['video_competence']);} 
									if(isset($rank_list)){foreach($rank_list as $rank_k=>$rank_v){?>
									<option value="<?php echo $rank_v['UserRank']['id'];?>" <?php if(isset($video_competence)&&in_array($rank_v['UserRank']['id'],$video_competence))echo "selected"?>><?php echo $rank_v['UserRankI18n']['name'];?></option>
									<?php }}?>
								</select>
							</div>
		    			</div>
		    		</div>
		    		 
					<div class="am-form-group">
		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['meta_keywords']?></label>
		    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>	
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">	
		    					<input type="text" name="data[ArticleI18n][<?php echo $k;?>][meta_keywords]" value="<?php echo isset($article['ArticleI18n'][$v['Language']['locale']])?$article['ArticleI18n'][$v['Language']['locale']]['meta_keywords']:'';?>" />
		    				</div>
		    				<?php if(sizeof($backend_locales)>1){?>
		    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;"><?php echo $ld[$v['Language']['locale']]?></label>
		    				<?php }?>&nbsp;
		    			<?php }}?>	
		    			</div>
		    		</div>
		    		<div class="am-form-group" >
		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['meta_description']?></label>
		    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">	
		    					<textarea type="text" name="data[ArticleI18n][<?php echo $k;?>][meta_description]" ><?php echo isset($article['ArticleI18n'][$v['Language']['locale']])?$article['ArticleI18n'][$v['Language']['locale']]['meta_description']:'';?></textarea>
		    				</div>
	    					<?php if(sizeof($backend_locales)>1){?>
	    						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;padding-top:10px;"><?php echo $ld[$v['Language']['locale']]?></label>
	    					<?php }?>&nbsp;
		    			<?php }}?>		
		    			</div>
		    		</div>	
					<div class="am-form-group">
		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['phone_content']?></label>
		    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">	
		    					<textarea name="data[ArticleI18n][<?php echo $k;?>][content2]"><?php echo isset($article['ArticleI18n'][$v['Language']['locale']])?$article['ArticleI18n'][$v['Language']['locale']]['content2']:'';?></textarea>
		    				</div>
	    					<?php if(sizeof($backend_locales)>1){?>
	    						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;padding-top:10px;"><?php echo $ld[$v['Language']['locale']]?></label>
	    					<?php }?>&nbsp;
		    			<?php }}?>	
		    			</div>
		    		</div>	
					<div class="am-form-group">
		    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['upload_flash']?></label>
		    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">	
		    					<input type="text" id="upload_img_text_2<?php echo $k?>" name="data[ArticleI18n][<?php echo $k;?>][img02]" value="<?php echo isset($article['ArticleI18n'][$v['Language']['locale']])?$article['ArticleI18n'][$v['Language']['locale']]['img02']:'';?>" />
		    				</div>
		    				<?php if(sizeof($backend_locales)>1){?>
		    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label am-text-left" style="font-weight:normal;padding-top:3px;"><?php echo $ld[$v['Language']['locale']]?></label>
		    				<?php }?>&nbsp;
		    			<?php }}?>	
		    			</div>
		    		</div>	
		    		 
				</div>
			</div>
		</div>
								
	<?php echo $form->end();?>
	</div>
</div>
<script type="text/javascript">

$(function(){
	$("select[name=competence]").change(function(){
		$("input[name=video_competence]").val($(this).val());
	});
});
function article_name_check(){
	var article_name_obj = document.getElementById("article_name_"+backend_locale);
	var article_subtitle_obj = document.getElementById("article_subtitle_"+backend_locale);
	if(article_name_obj.value==""){
		alert("<?php echo $ld['enter_article_title']?>");
		return false;
	}
	if(article_subtitle_obj.value==""){
		alert("<?php echo $ld['enter_subtitle']?>");
		return false;
	}
	/*验证邮箱*/
	var email=document.getElementById("author_email");
	if(email.value!=""){
		var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/; //验证邮箱的正则表达式
		if(!reg.test(email.value))
	    {
	        alert("<?php echo $ld['enter_valid_email']?>");
	        return false;
	    }
	}
	return true;
}

//扩展分类
function addOtherCat(){
     var sel = document.createElement("SELECT");
      var selCat = document.getElementById('ArticlesCategory');

      for (i = 0; i < selCat.length; i++)
      {
          var opt = document.createElement("OPTION");
          opt.text = selCat.options[i].text;
          opt.value = selCat.options[i].value;
          if (!!(window.attachEvent && navigator.userAgent.indexOf('Opera') === -1) )
          {
              sel.add(opt);
          }
          else
          {
              sel.appendChild(opt);
          }
      }
      var conObj=document.getElementById('other_cats');
      conObj.appendChild(sel);
      $(sel).selected();
      sel.name = "article_categories_id[]";
      sel.onChange = function() {checkIsLeaf(this);};
}
function checkIsLeaf(selObj){
	if(selObj.options[selObj.options.selectedIndex].className != 'leafCat'){
		alert(goods_cat_not_leaf);
		selObj.options.selectedIndex = 0;
	}
}

function show_select_div(){
	popOpen("upload_file");
}
function check_file(id,name){
	document.getElementById('upload_file_id').value=id;
	document.getElementById('upload_file_name').value=name;
	document.getElementById('remove_button').className="";
	btnClose();
}
function remove_file(){
	document.getElementById('upload_file_name').value ='';
	document.getElementById('upload_file_id').value ='';
	document.getElementById('remove_button').className="status";
}
	//后台选择图片用
	function travel_select_img(id_str){
		window.open (admin_webroot+'image_spaces/select_image/'+id_str+"/?type=article", 'newwindow', 'height=600, width=1024, top=0, left=0, toolbar=no, menubar=yes, scrollbars=yes,resizable=yes,location=no, status=no');
	}

function checkrouteurl(){
	var route_url = document.getElementById("Route_url").value;
	if(route_url!=""){
		YUI().use("io",function(Y) {
		var rUrl = "/admin/routes/select_route_url/";//访问的URL地址
		var rfg = {
			method: "POST",
			data:"route_url="+route_url
		};
		var request = Y.io(rUrl, rfg);//开始请求
		var newhtml = "";
		var handleSuccess = function(ioId, o){
			try{
				eval('var result='+o.responseText);
			}catch(e){
				alert("<?php echo $ld['object_transform_failed']?>");
				alert(o.responseText);
			}
			if(result.type==1){
				alert(result.message);
				document.getElementById("route_url_h").value=1;
			}else{
				document.getElementById("route_url_h").value=0;
			}
		}
		var handleFailure = function(ioId, o){
			alert("<?php echo $ld['asynchronous_request_failed']?>");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
		});
	}
}
</script>
