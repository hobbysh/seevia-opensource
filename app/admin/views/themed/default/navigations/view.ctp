<style type="text/css">
.am-radio input[type="radio"]{margin-left:0px;}
.btnouter{margin:50px;}
.am-form-horizontal .am-radio{padding-top:5px;margin-top:0.5rem;display:inline;position:relative;margin-top:5px;}
#a_category{margin-top:10px;}
.img_select{max-width:150px;max-height:120px;}
.am-ucheck-checkbox, .am-ucheck-icons, .am-ucheck-radio {
	height: 20px;
	left: 0;
	position: absolute;
	top:3px;
	width: 20px;
}
</style>
<div>
	<div class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-detail-menu">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index:100; width: 15%;max-width:200px;">
		    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content am-detail-view" id="accordion"  >
		<?php
				echo $form->create('navigations',array('action'=>'view/'.(empty($this->data['Navigation']['id'])?'':$this->data['Navigation']['id']),'onsubmit'=>'return nav_input_checks()','class'=>'am-form am-form-horizontal'));?>
			<input type="hidden" id='lan' value="<?php echo count($backend_locales);?>" />
			<input id="data[Navigation][id]" type="hidden" name="data[Navigation][id]" value="<?php echo empty($this->data['Navigation']['id'])?'':$this->data['Navigation']['id'];?>" />
			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
				<input name="data[NavigationI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
			<?php }}?>
			<div id="basic_information" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information'] ?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
                    <div class="am-panel-bd am-form-detail">
                	<div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['location']?></label>
    		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    		    					<select id='type' data-am-selected name="data[Navigation][type]" onChange="searchType(this.value,'<?php echo !empty($this->data['Navigation']['parent_id'])?$this->data['Navigation']['parent_id']:'0';?>',<?php echo isset($this->data['Navigation']['id'])?$this->data['Navigation']['id']:0; ?>)">
    									<option value="T" <?php if((!empty($this->data['Navigation']['type'])&&$this->data['Navigation']['type']=='T')||$nav_type=='T')echo "selected";?>><?php echo $ld['top']?></option>
    									<option value="H" <?php if((!empty($this->data['Navigation']['type'])&&$this->data['Navigation']['type']=='H')||$nav_type=='H')echo "selected";?>><?php echo $ld['help_section']?></option>
    									<option value="B" <?php if((!empty($this->data['Navigation']['type'])&&$this->data['Navigation']['type']=='B')||$nav_type=='B')echo "selected";?>><?php echo $ld['bottom']?></option>
    									<option value="M" <?php if((!empty($this->data['Navigation']['type'])&&$this->data['Navigation']['type']=='M')||$nav_type=='M')echo "selected";?>><?php echo $ld['middle']?></option>
    									<option value="PM" <?php if((!empty($this->data['Navigation']['type'])&&$this->data['Navigation']['type']=='PM')||$nav_type=='PM')echo "selected";?>><?php echo $ld['mobile_middle'] ?></option>
    									<option value="PB" <?php if((!empty($this->data['Navigation']['type'])&&$this->data['Navigation']['type']=='PB')||$nav_type=='PB')echo "selected";?>><?php echo $ld['mobile_bottom'] ?></option>
    								</select>
    		    				</div>
    		    			</div>
    		    		</div>		
    					<div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['superior_navigation']?></label>
    		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
    		    				<div id="parent_div" class="am-u-lg-9 am-u-md-9 am-u-sm-9"   >
    								<select id="searchNa_sel" name="data[Navigation][parent_id]"   onchange="searchNa(this.value)">
    									<option value="0" checked><?php echo $ld['root_navigate']?></option>
    									<?php if(isset($navigation_data) && sizeof($navigation_data)>0){?>
    									<?php foreach($navigation_data as $k=>$v){?>
    									<option value="<?php echo $v['Navigation']['id']?>" <?php if(!empty($this->data['Navigation']['parent_id'])&&$v['Navigation']['id'] == $this->data['Navigation']['parent_id']) echo "selected";?>><?php echo $v['NavigationI18n']['name']?></option>
    									<?php }}?>
    								</select>
    		    				</div>
    		    			</div>
    		    		</div>		
               		<div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['navigation_name']?></label>
    		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
    		    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9"  >
    		    					<input id="nav_name_<?php echo $v['Language']['locale'];?>" type="text" name="data[NavigationI18n][<?php echo $k;?>][name]" value="<?php echo empty($this->data['NavigationI18n'][$v['Language']['locale']]['name'])?'':$this->data['NavigationI18n'][$v['Language']['locale']]['name'];?>" />
    		    				</div>
    		    				<?php if(sizeof($backend_locales)>1){?>
    		    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em></label>
    		    				<?php }?>
    		    			<?php }}?>
    		    			</div>
    		    		</div>
    		    				
    		    					<div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['system_content']?></label>
    		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9"   >
    		    					<input type="hidden" id="Navigation" value="" />
    								<select id="data[Navigation][controller]" data-am-selected="{maxHeight:200}" name="data[Navigation][controller]" onChange="setNa()"  >
    									<option value="pages" <?php if(!empty($this->data['Navigation']['controller'])&&$this->data['Navigation']['controller']=='pages') echo "selected";?> ><?php echo $ld['home']?></option>
    									<option value="p_categories" <?php if(!empty($this->data['Navigation']['controller'])&&$this->data['Navigation']['controller']=='p_categories') echo "selected";?>><?php echo $ld['product_categories']?></option>
    									<option value="a_categories" <?php if(!empty($this->data['Navigation']['controller'])&&$this->data['Navigation']['controller']=='a_categories') echo "selected";?>><?php echo $ld['article_categories']?></option>
    									<option value="brands" <?php if(!empty($this->data['Navigation']['controller'])&&$this->data['Navigation']['controller']=='brands') echo "selected";?>><?php echo $ld['brand']?></option>
    									<option value="products" <?php if(!empty($this->data['Navigation']['controller'])&&$this->data['Navigation']['controller']=='products') echo "selected";?>><?php echo $ld['product']?></option>
    									<option value="articles" <?php if(!empty($this->data['Navigation']['controller'])&&$this->data['Navigation']['controller']=='articles') echo "selected";?>><?php echo $ld['article']?></option>
    									<option value="topics" <?php if(!empty($this->data['Navigation']['controller'])&&$this->data['Navigation']['controller']=='topics') echo "selected";?>><?php echo $ld['topics']?></option>
    									<option value="sitemaps" <?php if(!empty($this->data['Navigation']['controller'])&&$this->data['Navigation']['controller']=='sitemaps') echo "selected";?>><?php echo $ld['sitemap']?></option>
    									<option value="contacts" <?php if(!empty($this->data['Navigation']['controller'])&&$this->data['Navigation']['controller']=='contacts') echo "selected";?>><?php echo $ld['contacts_us']?></option>
    									<option value="jobs" <?php if(!empty($this->data['Navigation']['controller'])&&$this->data['Navigation']['controller']=='jobs') echo "selected";?>><?php echo $ld['recruitment_management']?></option>
    									<option value="static_pages" <?php if(!empty($this->data['Navigation']['controller'])&&$this->data['Navigation']['controller']=='static_pages') echo 'selected';?>><?php echo $ld['static_page']?></option>
    								</select>
    								<br>
    								<div id='product_div' style="display:none;margin-top:10px;">
    									<input type="text" id='p_key' style="margin-bottom:10px;" value="" /><input type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="search('p')" value="<?php echo $ld['search_products']?>" />
    								</div>
    								<div id='article_div' style="display:none;margin-top:10px;">
    									<input type="text" id="a_key" style="margin-bottom:10px;" value="" /><input type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="search('a')" value="<?php echo $ld['search_articles']?>" />
    								</div>
    								<div id='static_page_div' style="display:none;margin-top:10px;">
    									<input type="text" id="sp_key" value="" /><input type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="search('sp')" value="<?php echo $ld['search']?>" />
    								</div>
    								<div id='p_category_div' style="display:none;margin-top:10px;">
    									<select id='p_category' onChange="changeNa(this.value)">
    										<option value="0"><?php echo $ld['please_select'];?></option>
    										<?php if(isset($c_p_info)&&$c_p_info!=""&&count($c_p_info)>0){?>
    											<?php	foreach($c_p_info as $v){ ?>
    											<option value="<?php echo $v['CategoryProduct']['id'].'/'.$v['CategoryProductI18n']['name'];?>"><?php echo $v['CategoryProductI18n']['name'];?></option>
    											<?php	}?>
    										<?php }?>
    									</select>
    								</div>
    								<div id='a_category_div' style="display:none;margin-top:10px;">
    									<select id='a_category' onChange="changeNa(this.value)">
    										<option value="0"><?php echo $ld['please_select'];?></option>
    										<?php if(isset($c_a_info)&&$c_a_info!=""&&count($c_a_info)>0){?>
    											<?php	foreach($c_a_info as $v){ ?>
    											<option value="<?php echo $v['CategoryArticle']['id'].'/'.$v['CategoryArticleI18n']['name'];?>"><?php echo $v['CategoryArticleI18n']['name'];?></option>
    											<?php }?>
    										<?php }?>
    									</select>
    								</div>
    								<div id='brand_div' style="display:none" >
    									<select id='brand'   onChange="changeNa(this.value)">
    										<option value="0"><?php echo $ld['please_select'];?></option>
    									<?php if(isset($b_info)&&$b_info!=""&&count($b_info)>0){?>
    										<?php	foreach($b_info as $v){ ?>
    										<option value="<?php echo $v['Brand']['id'];?>"><?php echo $v['BrandI18n']['name'];?></option>
    										<?php	}?>
    									<?php }?>
    									</select>
    								</div>
    								<div id='topic_div' style="display:none" >
    									<select id='topic'   onChange="changeNa(this.value)">
    										<option value="0"><?php echo $ld['please_select'];?></option>
    									<?php if(isset($topic_info)&&$topic_info!=""&&count($topic_info)>0){?>
    										<?php foreach($topic_info as $v){ ?>
    										<option value="<?php echo $v['Topic']['id'];?>"><?php echo $v['TopicI18n']['title'];?></option>
    										<?php	}?>
    									<?php }?>
    									</select>
    								</div>
    								<div id='pro_div' style="display:none;" >
    									<select id='promotion'  onChange="changeNa(this.value)">
    										<option value="0"><?php echo $ld['please_select'];?></option>
    									<?php if(isset($pro_info)&&$pro_info!=""&&count($pro_info)>0){?>
    										<?php	foreach($pro_info as $v){ ?>
    										<option value="<?php echo $v['Promotion']['id'];?>"><?php echo $v['PromotionI18n']['title'];?></option>
    										<?php	}?>
    									<?php }?>
    									</select>
    								</div>
    		    				</div>
    		    			</div>
    		    		</div>		
    		    		
    		    						
    					<div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['link_address']?></label>
    		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
    		    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>	
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9"  >
    		    					<input type="text" id="NavigationUrl<?php echo $k;?>" name="data[NavigationI18n][<?php echo $k;?>][url]" value="<?php echo empty($this->data['NavigationI18n'][$v['Language']['locale']]['url'])?'':$this->data['NavigationI18n'][$v['Language']['locale']]['url'];?>"/>
    		    				</div>
    		    				<?php if(sizeof($backend_locales)>1){?>
    		    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label" ><?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em></label>
    		    				<?php }?>
    		    			<?php }}?>		
    		    			</div>
    		    		</div>		
    					<div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['description']?></label>
    		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8"  >
    		    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>	
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">
    		    					<textarea name="data[NavigationI18n][<?php echo $k;?>][description]"><?php echo empty($this->data['NavigationI18n'][$v['Language']['locale']]['description'])?'':$this->data['NavigationI18n'][$v['Language']['locale']]['description'];?></textarea>
    		    				</div>
    		    				<?php if(sizeof($backend_locales)>1){?>
    		    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em></label>
    		    				<?php }?>
    		    			<?php }}?>			
    		    			</div>
    		    		</div>		
    					<div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['upload_picture']?>01</label>
    		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
    		    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9"  >
    		    					<input name="data[NavigationI18n][<?php echo $k;?>][img01]" id="navigationI18n_01_<?php echo $v['Language']['locale'];?>" type="text" value="<?php echo isset($this->data['NavigationI18n'][$v['Language']['locale']]['img01'])?$this->data['NavigationI18n'][$v['Language']['locale']]['img01']:'';?>" />
    		    					<input type="button" class="am-btn am-btn-xs am-btn-success am-radius"  onclick="select_img('navigationI18n_01_<?php echo $v['Language']['locale'];?>')" value="<?php echo $ld['choose_picture']?>" style="margin-top:5px;"/>
    		    					
    								<div class="img_select" style="margin:5px;">
    									<?php echo $html->image((isset($this->data['NavigationI18n'][$v['Language']['locale']])&&$this->data['NavigationI18n'][$v['Language']['locale']]['img01']!="")?$this->data['NavigationI18n'][$v['Language']['locale']]['img01']:$configs['shop_default_img'],array('id'=>'show_navigationI18n_01_'.$v['Language']['locale']))?>
    								</div>
    		    				</div>
    		    				<?php if(sizeof($backend_locales)>1){?>
    		    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $ld[$v['Language']['locale']]?></label>
    		    				<?php }?>
    		    			<?php }}?>
    		    			</div>
    		    		</div>		
    					<div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['upload_picture']?>02</label>
    		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
    		    			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>	
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9"  >
    		    					<input name="data[NavigationI18n][<?php echo $k;?>][img02]" id="navigationI18n_02_<?php echo $v['Language']['locale'];?>" type="text" value="<?php echo isset($this->data['NavigationI18n'][$v['Language']['locale']]['img02'])?$this->data['NavigationI18n'][$v['Language']['locale']]['img02']:'';?>" />
    		    					<input type="button"  class="am-btn am-btn-xs am-btn-success am-radius"  onclick="select_img('navigationI18n_02_<?php echo $v['Language']['locale'];?>')" value="<?php echo $ld['choose_picture']?>" style="margin:5px;"/>
    		    				
    		    					<div class="img_select" style="margin:5px;">
    									<?php echo $html->image((isset($this->data['NavigationI18n'][$v['Language']['locale']])&&$this->data['NavigationI18n'][$v['Language']['locale']]['img02']!="")?$this->data['NavigationI18n'][$v['Language']['locale']]['img02']:$configs['shop_default_img'],array('id'=>'show_navigationI18n_02_'.$v['Language']['locale']))?>
    								</div>
    		    				</div>
    		    				<?php if(sizeof($backend_locales)>1){?>
    		    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $ld[$v['Language']['locale']]?></label>
    		    				<?php }?>
    		    			<?php }}?>
    		    			</div>
    		    		</div>
    		    		
    			    	<div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['ico']?></label>
    		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    		    					<input name="data[Navigation][icon]" type="text" id="navigation_icon" value="<?php echo isset($this->data['Navigation']['icon'])?$this->data['Navigation']['icon']:'';?>" />
    		    					<input type="button"  class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('navigation_icon')" value="<?php echo $ld['choose_picture']?>"  style="margin:5px;"/>
    								
    								<div class="img_select" style="margin:5px;">
    									<?php echo $html->image((isset($this->data['Navigation']['icon'])&&$this->data['Navigation']['icon']!="")?$this->data['Navigation']['icon']:$configs['shop_default_img'],array('id'=>'show_navigation_icon'))?>
    								</div>
    		    				</div>
    		    			</div>
    		    		</div>
    					<div class="am-form-group">
    		    				<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-radio-label"><?php echo $ld['orderby']?></label>
	    		    			<div class="am-u-lg-5 am-u-md-6 am-u-sm-6">
	    		    				<div id="order_div" class="am-g">
	    		    					<label><input type="radio" name="orderby" value="0"/><?php echo $ld['front']?></label>
	    							<label><input type="radio" name="orderby" value="1" <?php if($id==0){echo 'checked';}?>/><?php echo $ld['final']?></label>
	    							<label><input type="radio" name="orderby" value="2" /><?php echo $ld['at']?></label>
	                                             <div class="margin-top:10px;">
	    								<select id='orderby' name="orderby_sel" style="width:100px;margin-top:10px;"></select>
	                                               </div>
	    							<?php echo $ld['after']?>
	    		    				</div>
	    		    			</div>
    					</div>
    					<div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['valid']?></label>
    		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    		    					<label class="am-radio am-success">
    		    						<input type="radio" name="data[Navigation][status]" data-am-ucheck value="1" <?php if(!isset($this->data['Navigation']['status'])||$this->data['Navigation']['status'])echo "checked";?>/>
    		    						<?php echo $ld['yes']?>
    		    					</label>&nbsp;&nbsp;
    								<label class="am-radio am-success">
    									<input type="radio" name="data[Navigation][status]" data-am-ucheck  value="0" <?php if(isset($this->data['Navigation']['status'])&&!$this->data['Navigation']['status'])echo "checked";?>/>
    									<?php echo $ld['no']?>
    								</label>
    		    				</div>
    		    			</div>
    		    		</div>		
    					<div class="am-form-group">
    		    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['new_window']?></label>
    		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
    		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
    		    					<label  class="am-radio am-success">
    		    						<input type="radio" name="data[Navigation][target]" data-am-ucheck   value="_blank" <?php if(isset($this->data['Navigation']['target'])&&$this->data['Navigation']['target']=='_blank')echo "checked";?>/>
    		    						<?php echo $ld['yes']?>
    		    					</label>&nbsp;&nbsp;
    								<label class="am-radio am-success">
    									<input type="radio" name="data[Navigation][target]" data-am-ucheck   value="_self" <?php if(!isset($this->data['Navigation']['target'])||$this->data['Navigation']['target']=='_self')echo "checked";?>/>
    									<?php echo $ld['no']?>
    								</label>
    		    				</div>
    		    			</div>
    		    		</div>
    		    	 
    		    			<div  class="btnouter">		
    		    		 
    						<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
    						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
    					</div> 
                    </div>
				</div>
			</div>
		<?php echo $form->end();?>
	</div>
</div>


<script type="text/javascript">
function nav_input_checks(){
	var nav_name_obj = document.getElementById("nav_name_"+backend_locale);
	
	if(nav_name_obj.value==""){
		alert("<?php echo $ld['enter_navigation_name']?>");
		return false;
	}
	return true;
	
}
function setNa(){
	var nav = document.getElementById("data[Navigation][controller]").value;
	var num = document.getElementById("lan").value;
	if(nav=='pages'){	
		for(var i=0;i<num;i++){
			document.getElementById("NavigationUrl"+i).value='/';
		}
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category_div").style.display='none';
		document.getElementById("a_category_div").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='p_categories'){
		document.getElementById("Navigation").value='P/categories/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category_div").style.display='block';
		document.getElementById("a_category_div").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
		$("#p_category").selected({maxHeight: 300});
		$("#p_category").css("margin-top","10px");
	}
	if(nav=='a_categories'){
		document.getElementById("Navigation").value='A/categories/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category_div").style.display='none';
		document.getElementById("a_category_div").style.display='block';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
		$("#a_category").selected({maxHeight: 300});
		$("#a_category").css("margin-top","10px");
		
	}
	if(nav=='brands'){
		document.getElementById("Navigation").value='/brands/view/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category_div").style.display='none';
		document.getElementById("a_category_div").style.display='none';
		document.getElementById("brand_div").style.display='block';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';brand
		$("#brand").selected({maxHeight: 300});
		$("#brand_div").css("margin-top","10px");
	}
	if(nav=='topics'){
		document.getElementById("Navigation").value='/topic/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category_div").style.display='none';
		document.getElementById("a_category_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='block';
		document.getElementById("pro_div").style.display='none';
		$("#topic").selected({maxHeight: 300});
		$("#topic_div").css("margin-top","10px");
	}
	if(nav=='promotions'){
		document.getElementById("Navigation").value='/brands/view/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category_div").style.display='none';
		document.getElementById("a_category_div").style.display='none';
		document.getElementById("brand_div").style.display='block';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='block';
		$("#promotion").selected({maxHeight: 300});
		$("#product_div").css("margin-top","10px");
	}	
	if(nav=='products'){
		document.getElementById("Navigation").value='/products/';
		document.getElementById("product_div").style.display='block';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category_div").style.display='none';
		document.getElementById("a_category_div").style.display='none';
		document.getElementById("brand_div").style.display='none';
	}
	if(nav=='articles'){
		document.getElementById("Navigation").value='/articles/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='block';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category_div").style.display='none';
		document.getElementById("a_category_div").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='static_pages'){
		document.getElementById("Navigation").value='/pages/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='block';
		document.getElementById("p_category_div").style.display='none';
		document.getElementById("a_category_div").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='sitemaps'){
		var url="/sitemaps/";
		changeUrl(url);
		document.getElementById("Navigation").value='/sitemaps/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category_div").style.display='none';
		document.getElementById("a_category_div").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='contacts'){
		var url="/contacts/";
		changeUrl(url);
		document.getElementById("Navigation").value='/contacts/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category_div").style.display='none';
		document.getElementById("a_category_div").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='jobs'){
		var url="/jobs/";
		changeUrl(url);
		document.getElementById("Navigation").value='/jobs/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category_div").style.display='none';
		document.getElementById("a_category_div").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
}

function changeNa(id){
	var num = document.getElementById("lan").value;
	var nav = document.getElementById("Navigation").value;
	var url = nav+id;
	if(id!=0&&url !=""){
		changeUrl(url);
		return;
	}
}

function search(a){ 
	if(a=='p'){
		var key= document.getElementById("p_key").value;
	}else if(a=='sp'){
		var key= document.getElementById("sp_key").value;
	}else{
		var key= document.getElementById("a_key").value;
	}
	var num = document.getElementById("lan").value;
    $.ajax({
		url:"/admin/navigations/search/"+a+'/'+key,
		type:"POST",
		data:{},
		dataType:"json",
		success:function(data){
            try{	
				if(data.flag==1){
					if(a=='p'){
                        $("#product_div").html(data.cat);
                        $("#product_div select").selected({maxHeight: 300});
                        $("#product_div input[type='button']").addClass("am-btn am-btn-success am-btn-sm am-radius");
						if(data.status==1){
                            
						}
					}else if(a=='sp'){
                        $("#static_page_div").html(data.cat);
                        $("#static_page_div select").selected({maxHeight: 300});
                        $("#static_page_div input[type='button']").addClass("am-btn am-btn-success am-btn-sm am-radius");
						if(data.status==1){
                            
						}
					}else{
                        $("#article_div").html(data.cat);
                        $("#article_div select").selected({maxHeight: 300});
                        $("#article_div input[type='button']").addClass("am-btn am-btn-success am-btn-sm am-radius");
						if(data.status==1){
                            
						}
					}
				}
				if(data.flag==2){ 
					alert(data.message);
				}
			}catch (e){
				alert("<?php echo $ld['object_transform_failed']?>");
				alert(data);
			}
		}
	});
}
function changeUrl(url){
	var num = document.getElementById("lan").value;
	$.ajax({
		url:"/admin/navigations/changeUrl/",
		type:"POST",
		data:{'url':url},
		dataType:"json",
		success:function(data){
				if(data.flag==1){
					for(var i=0;i<num;i++){
						//alert(document.getElementById("NavigationUrl"+i));
						document.getElementById("NavigationUrl"+i).value=data.url;
					}
				}
				if(data.flag==2){ 
					alert(data.message);
				}
		}
	});
}
function searchNa(id){
	var a=document.getElementById("data[Navigation][id]").value;
	var type=document.getElementById("type").value;
        $("#order_div").html('');
	$.ajax({
		url:"/admin/navigations/searchNa/"+type+'/'+id,
		type:"POST",
		data:{},
		dataType:"json",
		success:function(data){
				if(data.flag==1){
                    var datahtml="<label><input  type='radio' name='orderby' value='0'/><?php echo $ld['front'];?></label>　<label><input type='radio' name='orderby' value='1' /><?php echo $ld['final'];?></label>　<label><input type='radio' name='orderby' value='2'/><?php echo $ld['at'];?></label><select id='orderby' name='orderby_sel' style='display:none;'></select><?php echo $ld['after'] ?>";
                    $("#order_div").html(datahtml);
                    var node = document.getElementById("orderby");
                    var optiondata=data.select_data;
                    for(var i=0;i <optiondata.length;i++){
                        var option=document.createElement("option");
                        node.appendChild(option);
                        option.value=optiondata[i]['id'];
                        option.text=optiondata[i]['value'];
                        if(optiondata[i]['id']==id){
                            option.selected=true;
                        }
                    }
                    $("#orderby").selected({maxHeight: 300});
                    $("input[name='orderby']").parent().addClass("am-radio am-success am-form-group-label");
                    $("input[name='orderby']").uCheck();
				}else if(data.flag==0){
                    $("#order_div").html("<label class='am-form-group-label'>"+data.datahtml+"</label>");
                }
				if(data.flag==2){ 
					alert(data.message);
				}
		}
	});
}

function searchType(type,id,this_id){
    $("#parent_div").html("<select onchange='searchNa(this.value)' name='data[Navigation][parent_id]' style='display:none;' id='searchNa_sel'></select>");
	var node = document.getElementById("searchNa_sel");
        node.innerHTML="";
	$.ajax({
		url:"/admin/navigations/searchtype/"+type+'/'+id+'/'+this_id,
		type:"POST",
		data:{},
		dataType:"json",
		success:function(data){
			if(data.flag==1){
                var optiondata=data.select_data;
                for(var i=0;i <optiondata.length;i++){
                    var option=document.createElement("option");
                    option.value=optiondata[i]['id'];
                    option.text=optiondata[i]['value'];
                    if(optiondata[i]['id']==id){
                        option.selected=true;
                    }
                    node.appendChild(option);
                }
                $(node).selected({maxHeight: 300});
			}
			if(data.flag==2){ 
				alert(data.message);
			}
		}
	});
}
<?php if($id!=0){?>
//window.load=searchType(document.getElementById("type").value,<?php echo !empty($this->data['Navigation']['parent_id'])?$this->data['Navigation']['parent_id']:'0';?>,<?php echo !empty($this->data['Navigation']['id'])?$this->data['Navigation']['id']:'0';?>);
window.load=searchNa(document.getElementById("searchNa_sel").value);
<?php }else{?>
	searchType("<?php echo $nav_type; ?>")
<?php } ?>
</script>
