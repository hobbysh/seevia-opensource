
<style type="text/css">
	.am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;display: inline;vertical-align:text-top;}
	.am-checkbox input[type="checkbox"]{margin-left:0px;width:0px; }
 	.am-checkbox{margin-left:10px;}
	@media only screen and (max-width:1024px){#right_list{padding-left:15px;padding-right:15px;}}
	@media only screen and (max-width:641px){.article_select{margin-bottom:10px;}}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
	.am-panel-title div{font-weight:bold;}
	.am-panel-child{border:1px solid #ddd;}
	.am-panel-subchild{border:1px solid #ddd;}
    
    .am-form-label{font-weight:bold;margin-left:0px;}
   .am-form-label-text{font-weight:bold;margin-left:0px;}
    .btnouterlist .am-fl{margin-right:3px;}
</style>
<div class="" style="margin-top:10px;margin-left:10px;">
	<?php echo $form->create('Article',array('action'=>'/','class'=>'am-form am-form-horizontal','name'=>'SArticleForm','type'=>'get'));?>
	<div class="listsearch">
	<div class="am-form-group">
		<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1 am-thumbnails am-form am-form-horizontal">
			<li  >
                <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['category_article']?></label>
				<div class="am-u-lg-9 am-u-md-9 am-u-sm-8  "><!--article_select-->
					<select class="all" name="article_cat" id="article_cat" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
						<option value="0"><?php echo $ld['all_data']?></option>
						<?php if(isset($categories_tree) && sizeof($categories_tree)>0){?>
							<?php foreach($categories_tree as $first_k=>$first_v){?>
						<option value="<?php echo $first_v['CategoryArticle']['id'];?>" <?php if($article_cats == $first_v['CategoryArticle']['id'] ){echo "selected";}?> > <?php echo $first_v['CategoryArticleI18n']['name'];?>
						</option>
						<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
							<?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
						<option value="<?php echo $second_v['CategoryArticle']['id'];?>" <?php if($article_cats == $second_v['CategoryArticle']['id'] ){echo "selected";}?> >
							|--<?php echo $second_v['CategoryArticleI18n']['name'];?>
						</option>
						<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
							<?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
						<option value="<?php echo $third_v['CategoryArticle']['id'];?>" <?php if($article_cats == $third_v['CategoryArticle']['id'] ){echo "selected";}?> > 	|----<?php echo $third_v['CategoryArticleI18n']['name'];?>
						</option>
						<?php }}}}}}?>
					</select>		
				</div>
            </li>
            <li style="margin:0px;">
                <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['article_type']?></label>
				<div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
					<select name="article_cat_type" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
						<option value=""><?php echo $ld['all_data']?></option>
						<?php foreach( $Resource_info["sub_type"] as $k=>$v ){?>
						<option value="<?php echo $k;?>" <?php if("$article_cat_type" == "$k"){echo "selected";}?>><?php echo $v;?></option> <?php }?>
					</select> 
				</div>
			</li>
			<li>
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['keyword']?></label>
				<div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
    		    	<input type="text" placeholder="<?php echo '标题/'.'副标题'.'/描述';?>" name="title" value="<?php echo @$titles?>" ></div>
			</li>
					<li>
			 <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><input  type="button" value="<?php echo $ld['advanced_search']?> "class="am-btn am-btn-xs am-btn-default  am-form-label am-left"  onclick="sv_advanced_search(this,'advanced_article')">			
					</label>
			  <div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
					<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" style="margin-top:3px;" onclick="search_article()"  value="<?php echo $ld['search'];?>"><?php echo $ld['search'];?></button>
					
				</div>
		    </li>
        
		</ul>
	        	 
	 
		 
	</div>
	<div class="am-form-group"  id="advanced_article" style="display:none;">
		<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1 am-thumbnails am-form am-form-horizontal">
			<li>
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['recommend']?></label>
				<div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
					<select name="recommand" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}">
						<option value="-1" selected><?php echo $ld['all_data']?></option>
						<option value="1"><?php echo $ld['yes']?></option>
						<option value="0"><?php echo $ld['no']?></option>
					 </select>
				</div>
			</li>
			<li>
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label">
					<?php echo $ld['home_show']?>
				</label>
				<div class="am-u-lg-9 am-u-md-9 am-u-sm-8">
					<select name="front" data-am-selected>
						<option value="-1" selected><?php echo $ld['all_data']?></option>
						<option value="1"><?php echo $ld['yes']?></option>
						<option value="0"><?php echo $ld['no']?></option>
					</select>
				</div>
			</li>
			<li>
				<label class="label_calendar am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">
					<?php echo $ld['posted_time']?>
				</label>
				<div class="am-u-lg-4 am-u-md-3 am-u-sm-4">
					<input class="" type="text" data-am-datepicker="{theme:'success',locale:'<?php echo $backend_locale; ?>'}" readonly  name="start_date" value="<?php echo $start_date;?>"  placeholder="From"/>
				</div>
				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-hide-sm-only am-text-center"><em>-</em></label>
				<div class="am-u-lg-4 am-u-md-3 am-u-sm-4 am-u-end">
					<input class="" type="text" data-am-datepicker="{theme:'success',locale:'<?php echo $backend_locale; ?>'}" readonly  name="end_date" value="<?php echo $end_date;?>"  placeholder="End"/>
				</div>
			</li>
		</ul>
	</div>
	</div>
	<?php echo $form->end();?>
                    



	<div class="am-other_action am-text-right" style="margin-bottom:10px;">
        <div class="am-show-lg-only am-u-md-3 am-u-sm-3 am-btn-group-xs">
		<?php //echo $html->link($ld['manage_articles_categories'],"/article_categories/",array("class"=>'am-btn am-btn-default am-seevia-btn-view'))."&nbsp;"; ?>&nbsp;</div>
        <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-btn-group-xs am-fr">
        
                      <a  class="am-btn am-btn-default am-left am-btn am-btn-xs" href="<?php echo $html->url('/article_categories/'); ?>">文章分类管理</a> 
                     
		<?php
			if($svshow->operator_privilege("articles_upload")){
				echo $html->link($ld['bulk_upload_article'],"/articles/uploadarticles",array("class"=>"am-btn am-btn-default am-seevia-btn-view"))."&nbsp;";}
			if($svshow->operator_privilege("articles_add")){;?>
			<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url("view/?article_cat=".$article_cats); ?>" style="margin-right:10px;">
				<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
			</a> 
		<?php }?>
        </div>
        <div class="am-cf"></div>
	</div>
	<!--左边菜单-->
	<div class="am-u-lg-3  am-u-md-3  am-hide-sm-only">	
		<div class="am-panel-group am-panel-tree" id="accordion" >
			<div class="listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
			      	<div class="am-panel-title">
						 <div class="am-u-lg-6 am-u-md-8 am-u-sm-8"><?php echo $ld['category_article'];?></div>
						 <div class="am-u-lg-6 am-u-md-4 am-u-sm-4 am-btn-group-xs">
			<?php if($svshow->operator_privilege("article_categories_view")){?>
			      
			 	   <a class="am-btn am-btn-default am-btn-xs am-text-secondary" href="<?php echo $html->url('/article_categories/'); ?>">
                             <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                            </a>
			  <?php }?>
			       </div>
						 <div style="clear:both;"></div>
			      	</div>
			    </div>
			</div>
		<!--一级菜单-->
			<?php if(isset($categories_tree) && sizeof($categories_tree)>0){?><?php foreach($categories_tree as $k=>$v) {?>
			<div>
				<div class="listtable_div_top am-panel-body" >
				    <div class="am-panel-bd fuji">
						<div class="am-u-lg-6 am-u-md-8 am-u-sm-8 ">
							<span data-am-collapse="{parent: '#accordion', target: '#action_<?php echo $v['CategoryArticle']['id']?>'}" class="<?php echo (isset($v['SubAction'])&&!empty($v['SubAction']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;
							<?php echo $html->link("{$v['CategoryArticleI18n']['name']}","/articles/?article_cat={$v['CategoryArticle']['id']}",array("style"=>" color:black;"),false,false);?>
						</div>
						<div class="am-u-lg-4 am-u-md-4 am-u-sm-4 seolink am-action ">
							<?php  $preview_url=$svshow->seo_link_path(array('type'=>'AC','id'=>$v['CategoryArticle']['id'],'name'=>$v['CategoryArticleI18n']['name'],'sub_name'=>$ld['preview'],"style"=>"float:right;")); ?>
							<a class="am-btn   am-btn-xs am-text-secondary  am-seevia-btn-view"  target='_blank' href="<?php echo $preview_url; ?>"> <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?></a>
							
						</div>
						<div style="clear:both"></div>
					</div>
				</div>
		<!--二级菜单-->
				<?php if(isset($v['SubCategory'])&& sizeof($v['SubCategory'])>0){?>
					<div class="am-panel-collapse am-collapse am-panel-child" id="action_<?php echo $v['CategoryArticle']['id']?>"  >
						<?php foreach ($v['SubCategory'] as $kk=>$vv){?>
							<div class="am-panel-bd am-panel-childbd actionn_<?php echo $vv['CategoryArticle']['id']?>">
								<div class="am-u-lg-6 am-u-md-8 am-u-sm-8 ">	
									<span data-am-collapse="{parent: '#action_<?php echo $v['CategoryArticle']['id']; ?>', target: '#actionn_<?php echo $vv['CategoryArticle']['id']?>'}" class="<?php echo (isset($vv['SubAction']) && !empty($vv['SubAction']))?"am-icon-plus":"am-icon-minus";?>" style="margin-left:15px;"></span>&nbsp;
									<?php echo $html->link("{$vv['CategoryArticleI18n']['name']}","/articles/?article_cat={$vv['CategoryArticle']['id']}",array( "class"=>"am-btn  am-btn-xs am-text-secondary  am-seevia-btn-view"),false,false);?>
								</div>
								<div class="am-u-lg-4 am-u-md-4 am-u-sm-4 seolink am-action">	
									<?php  $preview_url=$svshow->seo_link_path(array('type'=>'AC','id'=>$vv['CategoryArticle']['id'],'name'=>$vv['CategoryArticleI18n']['name'],'sub_name'=>$ld['preview'])); ?>
										<a class="am-btn mt  am-btn-xs am-text-secondary  am-seevia-btn-view"  target='_blank' href="<?php echo $preview_url; ?>"> <span class="am-icon-eye"></span> <?php echo $ld['preview']; ?></a>
									
								</div>
								<div style="clear:both"></div>
							</div>
						<!--三级菜单-->	
							<?php if(isset($vv['SubCategory'])&& sizeof($vv['SubCategory'])>0){?>
								<div class="am-panel-collapse am-collapse am-panel-subchild" id="actionn_<?php echo $vv['CategoryArticle']['id']?>">	
									<?php foreach($vv['SubCategory'] as $kkk=>$vvv){?>
										<div class="am-panel-bd am-panel-childbd">
											<div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-left:15px;">	
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $html->link("{$vvv['CategoryArticleI18n']['name']}","/articles/?article_cat={$vvv['CategoryArticle']['id']}",array("style"=>"text-decoration:underline;color:black;"),false,false);?>
											</div>
											<div class="am-u-lg-4 am-u-md-4 am-u-sm-4 seolink">	
												<?php echo $svshow->seo_link(array('type'=>'AC','id'=>$vvv['CategoryArticle']['id'],'name'=>$vvv['CategoryArticleI18n']['name'],'sub_name'=>$ld['preview'])); ?>
											</div>
											<div style="clear:both"></div>
										</div>
									<?php }?>
								</div>
							<?php }?>
						<?php } ?>
					</div>
				<?php }?>
			</div>
			<?php }} else{ ?> 
			<div>
				<div  class="no_data_found"><?php echo $ld['no_data_found']?></div>
			</div>
			<?php }?>
		</div>
	</div>
	<!--右边列表-->
	<div class="am-u-lg-9 am-u-md-9 am-u-sm-12" id="right_list">
	<?php echo $form->create('Article',array('action'=>'/','name'=>'ArticleForm','type'=>'get',"onsubmit"=>"return false;"));?>
		<div class="am-panel-group am-panel-tree" style="margin-top:5px;">
			<div class=" listtable_div_btm am-panel-header">
				<div class="am-panel-hd">
					<div class="am-panel-title am-g">
						<div class="am-u-lg-2 am-u-md-3 am-u-sm-3  ">
							<label class="am-checkbox am-success am-hide-sm-only" style="font-weight:bold;"><input onclick='listTable.selectAll(this,"checkbox[]")' type="checkbox" data-am-ucheck/>
							<?php echo $ld['article_title']?>
							</label>
			                          <label class="am-show-sm-only" style="font-weight:bold;"> 
							<?php echo $ld['article_title']?>
							</label>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $ld['category_article']?></div>
						<div class="am-u-lg-2 am-u-md-2  am-hide-sm-only"><?php echo $ld['article_type']?></div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-2"><?php echo $ld['valid']?></div>
						<div class="am-u-lg-1 am-hide-md-down"><?php echo $ld['recommend']?></div>
						<div class="am-u-lg-1 am-hide-md-down"><?php echo $ld['sort']?></div>
						<div class="am-u-lg-3 am-u-md-4 am-u-sm-4"><?php echo $ld['operate']?></div>
						<div style="clear:both"></div>
					</div>
				</div>
			</div>
			
			<?php if(isset($article) && sizeof($article)>0){foreach($article as $k=>$v ){?>
			<div>
			<div class=" listtable_div_top am-panel-body">	
				<div class="am-panel-bd am-g">
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-3 ">
						<label class="am-checkbox am-success am-hide-sm-only">
							<input type="checkbox" name="checkbox[]" value="<?php echo $v['Article']['id']?>" data-am-ucheck/>
				                      <span style="text-overflow:ellipsis;overflow:hidden;" onclick="javascript:listTable.edit(this, 'articles/update_article_title/', <?php echo $v['Article']['id']?>)" ><?php echo $v['ArticleI18n']['title'] ?>&nbsp;</span>
						</label>
				             <label class="am-show-sm-only">
						 <span style="text-overflow:ellipsis;overflow:hidden;" onclick="javascript:listTable.edit(this, 'articles/update_article_title/', <?php echo $v['Article']['id']?>)" ><?php echo $v['ArticleI18n']['title'] ?>&nbsp;</span>
						</label>
					</div>			
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $v['Article']['category']?>&nbsp;</div>		
					<div class="am-u-lg-2  am-u-md-2 am-hide-sm-only"><?php echo @$Resource_info["sub_type"][$v['Article']['type']]?>&nbsp;</div>		
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-2">
						<?php if( $v['Article']['status'] == 1){?>
						<!--<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "articles/toggle_on_status", '.$v["Article"]["id"].')'))?>-->
						
						<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'articles/toggle_on_status',<?php echo $v['Article']['id'];?>)"></span>
						<?php }else{?>
						<!--<?php echo $html->image('no.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "articles/toggle_on_status", '.$v["Article"]["id"].')'));?>-->
						
						<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'articles/toggle_on_status',<?php echo $v['Article']['id'];?>)">&nbsp;</span>
						<?php }?>
					</div>		
					<div class="am-u-lg-1 am-u-md-1 am-hide-md-down">
						<?php if( $v['Article']['recommand'] == 1){?>
						<!--<?php echo $html->image('yes.gif',array('style'=>'cursor:pointer;','onclick'=>'listTable.toggle(this, "articles/toggle_on_recommand", '.$v["Article"]["id"].')'))?>-->
						
						<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'articles/toggle_on_recommand',<?php echo $v['Article']['id'];?>)"></span>
						<?php }else{?>
						<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'articles/toggle_on_recommand',<?php echo $v['Article']['id'];?>)">&nbsp;</span>
						<?php  }?>
					</div>		
					<div class="am-u-lg-1  am-u-md-1  am-hide-md-down">
						<span onclick="javascript:listTable.edit(this, 'articles/update_article_orderby/', <?php echo $v['Article']['id']?>)"><?php echo $v['Article']['orderby']?>&nbsp;</span>
					</div>
					<div class="am-u-lg-3 am-u-md-4 am-u-sm-4 seolink am-btn-group-xs am-action">
						<?php	if($v['Article']['type']=='V'){$preview_url =$svshow->seo_link_path(array('type'=>'V','id'=>$v['Article']['id'],'name'=>$v['ArticleI18n']['title'],'sub_name'=>$ld['preview']));
						}else{
						$preview_url= $svshow->seo_link_path(array('type'=>'A','id'=>$v['Article']['id'],'name'=>$v['ArticleI18n']['title'],'sub_name'=>$ld['preview']));
						}?>
						   <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-view" target='_blank' href="<?php echo $preview_url; ?>">
                				<span class="am-icon-eye"></span> <?php echo $ld['preview']; ?>
            				</a>
						<?php if($svshow->operator_privilege("articles_edit")){?>
						<a class="am-btn am-btn-default am-btn-xs am-seevia-btn-edit am-text-secondary" href="<?php echo $html->url('/articles/view/'.$v['Article']['id']); ?>">
                           <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit'];  ?>
                            </a>
					 <?php  }?>
					<?php 	if($svshow->operator_privilege("articles_remove")){?>
						<a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'articles/remove/<?php echo $v['Article']['id'] ?>');">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
				 <?php }	?>
					</div>	
				</div>
			</div>
			</div>
			<?php }}else{?>
			<div>
				<div  class="no_data_found"><?php echo $ld['no_data_found']?></div>
			</div>
			<?php }?>
		</div>
		<?php if($svshow->operator_privilege("articles_batch")){?>
		<?php if(isset($article) && sizeof($article)){?>
		<div id="btnouterlist" class="btnouterlist am-form-group ">
			<div class="am-u-lg-6 am-u-md-5 am-u-sm-12 am-hide-sm-only">
                <div class="am-fl"style="margin-left:7px;">
				    <label class="am-checkbox am-success"><input type="checkbox" onclick='listTable.selectAll(this,"checkbox[]")' data-am-ucheck ><?php echo $ld['select_all']; ?></label>
                </div>
                <div class="am-fl">
    				<select name="act_type" id="act_type" data-am-selected="{btnWidth:'150px'}" onchange="operate_change(this)">
    					<option value="0"><?php echo $ld['all_data']?></option>
    					<option value="delete"><?php echo $ld['batch_delete']?></option>
    					<option value="sub_type"><?php echo $ld['transfer_article_type']?></option>
    					<option value="a_category"><?php echo $ld['transfer_article_category']?></option>
    					<option value="a_status"><?php echo $ld['log_batch_change_status']?></option>
    					<option value="a_f_status"><?php echo $ld['log_batch_home_show']?></option>
    					<option value="a_c_status"><?php echo $ld['log_batch_recommended']?></option>
    				</select>
                </div>
                <div class="am-fl" style="display:none">
    				<select name="article_cat_o" id="article_cat_o" data-am-selected="{btnWidth:'150px'}">
    					<option value="0"><?php echo $ld['select_categories']?></option>
    					<?php if(isset($categories_tree) && sizeof($categories_tree)>0){?>
    					<?php foreach($categories_tree as $first_k=>$first_v){?>
    					<option value="<?php echo $first_v['CategoryArticle']['id'];?>"><?php echo $first_v['CategoryArticleI18n']['name'];?></option>
    					<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?>
    					<?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
    					<option value="<?php echo $second_v['CategoryArticle']['id'];?>">|--<?php echo $second_v['CategoryArticleI18n']['name'];?></option>
    					<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?>
    					<?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
    					<option value="<?php echo $third_v['CategoryArticle']['id'];?>">|----<?php echo $third_v['CategoryArticleI18n']['name'];?></option>
    					<?php }}}}}}?>
    				</select>
                </div>
                <div class="am-fl" style="display:none">
    				<select name="sub_type" id="sub_type" data-am-selected="{btnWidth:'150px'}">
    					<?php foreach($Resource_info["sub_type"] as $k=>$v){?>
    					<option value="<?php echo $k;?>"><?php echo $v;?></option>
    					<?php }?>
    				</select>
                </div>
                <div class="am-fl" style="display:none">
    				<select name="is_yes_no" id="is_yes_no" data-am-selected="{btnWidth:'150px'}">
    					<option value="1"><?php echo $ld['yes']?></option>
    					<option value="0"><?php echo $ld['no']?></option>
    				</select>
                </div>
                <div class="am-fl">
				    <button class="am-btn am-btn-danger am-radius am-btn-sm" type="button" onclick="diachange()" value="<?php echo $ld['submit']?>" /><?php echo $ld['submit']; ?></button>
                </div>
			</div>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
				<?php echo $this->element('pagers');?>
			</div>
            <div class="am-cf"></div>
		</div>
		<?php }?>
		<?php }?>
	<?php echo $form->end();?>
	</div>	
</div>
<script>
function sv_advanced_search(obj,advanced_id){
	document.getElementById(advanced_id).style.display = "block";
//	obj.style.display = "none";
}
function operate_change(obj){
	if(obj.value=="delete"){
        $("#sub_type").parent().hide();
        $("#article_cat_o").parent().hide();
        $("#is_yes_no").parent().hide();
	}
	if(obj.value=="sub_type"){
        $("#sub_type").parent().show();
        $("#article_cat_o").parent().hide();
        $("#is_yes_no").parent().hide();
	}
	if(obj.value=="a_category"){
        $("#sub_type").parent().hide();
        $("#article_cat_o").parent().show();
        $("#is_yes_no").parent().hide();
	}
	if(obj.value=="a_status"||obj.value=="a_f_status"||obj.value=="a_c_status"){
        $("#sub_type").parent().hide();
        $("#article_cat_o").parent().hide();
        $("#is_yes_no").parent().show();
	}

	if(obj.value=="0"){
        $("#sub_type").parent().hide();
        $("#article_cat_o").parent().hide();
        $("#is_yes_no").parent().hide();
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
		//	layer_dialog_show('确定'+vals+'?','batch_action()',5);
			if(confirm("<?php echo $ld['submit']?>"+' '+vals+'?'))
			{
				batch_action();
			}
		}else{
		//	layer_dialog_show('请选择！！','batch_action()',3);
			if(confirm(j_please_select))
			{
				batch_action();
			}
		}
	}
}

function batch_action()
{
document.ArticleForm.action=admin_webroot+"articles/batch";
document.ArticleForm.onsubmit= "";
document.ArticleForm.submit();
}
function search_article()
{
document.SArticleForm.action=admin_webroot+"articles/";
document.SArticleForm.onsubmit= "";
document.SArticleForm.submit();
}

</script>
<script type="text/javascript">
function update_article(art_id){
	YAHOO.example.container.wait.show();
	var sUrl = admin_webroot+"articles/update_article/"+art_id+"/?status=1";
	var request = YAHOO.util.Connect.asyncRequest('POST', sUrl, back_update_article);
}
var update_article_Success = function(o){
	if( o.responseText == 1 ){
		layer_dialog();
		layer_dialog_show("<?php echo $ld['update_successful']?>","",3);
	}
	YAHOO.example.container.wait.hide();
}
var update_article_Failure = function(o){
	YAHOO.example.container.wait.hide();
}
var back_update_article ={
	success:update_article_Success,
	failure:update_article_Failure,
	timeout : 30000,
	argument: {}
};
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
