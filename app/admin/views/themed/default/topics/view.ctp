<?php
/*****************************************************************************
 * SV-Cart 编辑专题
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
	echo $javascript->link('/skins/default/js/topic.amazeui');
?>
 
 
<style type="text/css">
	.am-popup-inner{height: 100%;overflow:hidden;padding-top: 44px;}
	.related_dt{width:100%;height:330px;overflow-y: auto;padding-left:10px;}
	.related_dt dl{float:left;text-align:left;padding:3px 5px;border:1px solid #ccc;margin:2px 5px;width:auto;display:block;white-space:nowrap;}
	.related_dt dl:hover{cursor: pointer;border: 1px solid #5eb95e;color:#5eb95e;}
	.related_dt dl:hover span{color:#5eb95e;}
	.related_dt dl span{float:none;padding:3px 2px 0px 2px;margin-right:5px;}
 	.am-form-horizontal .am-radio{padding-top:0px;;margin-top:0.5rem;display:inline;position:relative;top:5px;}
	.am-radio input[type="radio"]{margin-left:0px;}
	.am-radio, .am-checkbox{ display: inline-block;}
	div.attr_data{cursor: pointer;border: 1px solid #fff;margin:2px 0px;}
	div.attr_data:hover{border: 1px solid #5eb95e;}
	div.attr_data:hover span{cursor: pointer;}
	.am-no{color: #dd514c;cursor: pointer;}
	.img_select{max-width:150px;max-height:120px;}
	.am-ucheck-checkbox, .am-ucheck-icons, .am-ucheck-radio {
    height: 20px;
    left: 0;
    position: absolute;
    top: -3px; 
    width: 20px;}
    .topicsma table tbody tr td .am-checkbox{margin-top:0px;} 
</style>

<?php echo $form->create('Topic',array('action'=>'/view/'.(isset($this->data['Brand'])?$this->data['Brand']['id']:''),'onsubmit'=>'return topics_check();'));?>
<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div>	
	<!--左边菜单-->
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-detail-menu">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index:100; width: 15%;max-width:200px;">
		    <li><a href="#edit_topics"><?php echo $ld['edit_topics']?></a></li>
		 
			<li><a href="#gao"><?php echo  "高级设置";?></a></li>
		 
			<li><a href="#related_products"><?php echo $ld['related_products']?></a></li>
			<li><a href="#mobile_002"><?php echo $ld['mobile_002']?></a></li>
		</ul>
	</div>
	<!--内容-->
	<div class="am-panel-group admin-content am-detail-view" id="accordion" >
        <!-- 编辑按钮区域 -->
        <div class="btnouter am-text-right" data-am-sticky="{top:'10%'}">
            <button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
            <button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
        </div>
        <!-- 编辑按钮区域 -->
		<!--编辑专题-->
		<div id="edit_topics" class="am-panel am-panel-default">
	  		<div class="am-panel-hd">
				<h4 class="am-panel-title">
					<?php echo $ld['edit_topics'] ?>
				</h4>
		    </div>
		    <div class="am-panel-collapse am-collapse am-in">
	      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">		
					<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['topic_name']?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="topic_title_<?php echo $v['Language']['locale']?>" name="data[TopicI18n][<?php echo $k;?>][title]" value="<?php echo isset($this->data['TopicI18n'][$v['Language']['locale']]['title'])?$this->data['TopicI18n'][$v['Language']['locale']]['title']:'';?>" />
			    				</div>
			    				<?php if(sizeof($backend_locales)>1){?>
				    				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-view-label">
				    					<?php echo $ld[$v['Language']['locale']]?><em style="color:red;">*</em>
				    				</label>
			    				<?php }?>
		    				<?php }}?>	
		    			</div>
		    		</div>	
				 
					<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['picture_01']?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="upload_img_text_1<?php echo $v['Language']['locale']?>" name="data[TopicI18n][<?php echo $k;?>][img01]" value="<?php echo @$this->data['TopicI18n'][$v['Language']['locale']]['img01']?>" />
			    					<button type="button" class="am-btn am-btn-xs am-btn-success am-radius" value="<?php echo $ld['choose_picture']?>" onclick="select_img('upload_img_text_1<?php echo $v['Language']['locale']?>')" style="margin-top:5px;"><?php echo $ld['choose_picture']?></button>
			    					<?php if(sizeof($backend_locales)>1){?>
			    						<span class="lang"><?php echo $ld[$v['Language']['locale']]?></span>
			    					<?php }?>
									<div class="img_select" style="margin:5px;">
										<?php echo $html->image((isset($this->data['TopicI18n'][$v['Language']['locale']]['img01'])&&$this->data['TopicI18n'][$v['Language']['locale']]['img01']!="")?$this->data['TopicI18n'][$v['Language']['locale']]['img01']:$configs['shop_default_img'],array('style'=>'max-width:150px;max-height:120px;','id'=>'show_upload_img_text_1'.$v['Language']['locale']))?>
									</div>
			    				</div>
		    				<?php }}?>	
		    			</div>
		    		</div>	
					<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['mobile_001'];?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
			    					<input type="text" id="upload_img_text_2<?php echo $v['Language']['locale']?>" name="data[TopicI18n][<?php echo $k;?>][img02]" value="<?php echo @$this->data['TopicI18n'][$v['Language']['locale']]['img02']?>" />
			    					<button type="button" class="am-btn am-btn-xs am-btn-success am-radius" value="<?php echo $ld['choose_picture']?>" onclick="select_img('upload_img_text_2<?php echo $v['Language']['locale']?>')" style="margin-top:5px;"><?php echo $ld['choose_picture']?></button>
			    					<?php if(sizeof($backend_locales)>1){?>
			    						<span class="lang"><?php echo $ld[$v['Language']['locale']]?></span>
			    					<?php }?>
									<div class="img_select" style="margin:5px;">
										<?php echo $html->image((isset($this->data['TopicI18n'][$v['Language']['locale']]['img02'])&&$this->data['TopicI18n'][$v['Language']['locale']]['img02']!="")?$this->data['TopicI18n'][$v['Language']['locale']]['img02']:$configs['shop_default_img'],array('style'=>'max-width:150px;max-height:120px;','id'=>'show_upload_img_text_2'.$v['Language']['locale']))?>
									</div>
			    				</div>
			    			<?php }}?>		
		    			</div>
		    		</div>
		    		<?php if(isset($backend_locales) && sizeof($backend_locales) > 0){foreach($backend_locales as $k => $v){?>
						<input id="TopicI18n<?php echo $k;?>Locale" name="data[TopicI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo @$v['Language']['locale'];?>">
						<?php if(isset($this->data['TopicI18n'][$v['Language']['locale']])){?>
						<input id="TopicI18n<?php echo $k;?>Id" name="data[TopicI18n][<?php echo $k;?>][id]" type="hidden" value="<?php echo @$this->data['TopicI18n'][$v['Language']['locale']]['id'];?>"> <input id="TopicI18n<?php echo $k;?>TopicId" name="data[TopicI18n][<?php echo $k;?>][topic_id]" type="hidden" value="<?php echo @$this->data['Topic']['id'];?>">
					<?php }	}}?>
					<input type="hidden" name="data[Topic][id]" id="Topic_id" value="<?php echo isset($this->data['Topic']['id'])?$this->data['Topic']['id']:'';?>" />
					<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-form-group-label"><?php echo $ld['location']?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
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
		    		</div>	
					<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['valid']?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    					<label class="am-radio am-success">
		    						<input type="radio" class="radio" data-am-ucheck value="1" name="data[Topic][status]" <?php if(isset($this->data['Topic']['status'])&&$this->data['Topic']['status'] == 1){echo "checked";} ?>/><?php echo $ld['yes']?>
		    					</label>&nbsp;&nbsp;
		    					<label class="am-radio am-success ">		
									<input type="radio" class="radio" data-am-ucheck  name="data[Topic][status]" value="0" <?php if(isset($this->data['Topic']['status'])&&$this->data['Topic']['status'] == 0){echo "checked";} ?>/><?php echo $ld['no']?>
								</label>		
		    				</div>
		    			</div>
		    		</div>	
					<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['home_show']?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    					<label class="am-radio am-success">
		    						<input type="radio" name="data[Topic][front]" data-am-ucheck  value="1" <?php if(isset($this->data['Topic']['front'])&&$this->data['Topic']['front'] == 1){echo "checked";} ?> /><?php echo $ld['yes']?>
		    					</label>&nbsp;&nbsp;
		    					<label class="am-radio am-success ">	
									<input type="radio" name="data[Topic][front]" data-am-ucheck  value="0" <?php if(isset($this->data['Topic']['front'])&&$this->data['Topic']['front'] == 0){echo "checked";} ?> /><?php echo $ld['no']?>
								</label>
		    				</div>
		    			</div>
		    		</div>	
					<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['home_show'].$ld['app_qty']?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
								<input type="text" name="data[Topic][front_num]" value="<?php echo isset($this->data['Topic']['front_num'])?$this->data['Topic']['front_num']:'';?>" />
		    				</div>
		    			</div>
		    		</div>	
					<div class="am-g"style="margin-top:10px;">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4  "><?php echo $ld['sort']?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    					<?php echo isset($this->data['Topic']['orderby'])?$this->data['Topic']['orderby']:'50';?><input type="hidden" name="data[Topic][orderby]" value="<?php echo isset($this->data['Topic']['orderby'])?$this->data['Topic']['orderby']:'50';?>">
		    				</div>
		    			</div>
		    		</div>	
					<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['activity_cycle']?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
		    					<input type="text" class="am-form-field" id="start_time"  name="data[Topic][start_time]" placeholder="" data-am-datepicker="{theme:'success',locale:'<?php echo $backend_locale; ?>'}" readonly/>
		    				</div>
		    				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center">-</div>
		    				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
		    					<input type="text" class="am-form-field" id="end_time" name="data[Topic][end_time]" placeholder="" data-am-datepicker="{theme:'success',locale:'<?php echo $backend_locale; ?>'}" readonly/>
		    				</div>
		    			</div>
		    		</div>	
					<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['topics_file']?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    					<input type="text" name="data[Topic][template]" value="<?php echo isset($this->data['Topic']['template'])?$this->data['Topic']['template']:'';?>" />
		    				</div>
                            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $html->image('/admin/skins/default/img/help_icon.gif',array("onclick"=>"help_show_or_hide('help_text1')"))?></div>
                            <div class="am-cf"></div>
                            <p class="msg" style="display:none" id="help_text1"><?php echo $ld['fill_current topic_name']?></p>
		    			</div>
		    		</div>	
					<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 textarea am-view-label"><?php echo $ld['topics_style_list']?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    					<textarea name="data[Topic][css]"><?php echo isset($this->data['Topic']['css'])?$this->data['Topic']['css']:'';?></textarea>
		    				</div>
                            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $html->image('/admin/skins/default/img/help_icon.gif',array("onclick"=>"help_show_or_hide('help_text2')"))?></div>
                            <div class="am-cf"></div>
                            <p class="msg" style="display:none" id="help_text2"><?php echo $ld['fill_current topic_css_style']?></p>
		    			</div>
		    		</div>
		    			  		
		    		<!--专题介绍-->
		    			<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"> <?php echo $ld['topics_introduction']?></label>
		    		 <div class="am-u-lg-10 am-u-md-10 am-u-sm-8 am-u-end">
		    			 <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
						<?php if($configs["show_edit_type"]){?>
	      					<div class="am-form-group">
					    		<div ><span class="ckeditorlanguage  "><?php echo $v['Language']['name'];?></span></div>	
					    		<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[TopicI18n][<?php echo $k;?>][intro]" rows="10" style="width:auto;height:300px;"><?php echo isset($this->data['TopicI18n'][$v['Language']['locale']]['intro'])?$this->data['TopicI18n'][$v['Language']['locale']]['intro']:"";?></textarea>
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
	      					<div class="am-form-group">
	      						<?php echo $v['Language']['name'];?>
	      						<textarea cols="80" id="elm<?php echo $v['Language']['locale'];?>" name="data[TopicI18n][<?php echo $k;?>][intro]" rows="10"><?php echo isset($this->data['TopicI18n'][$v['Language']['locale']]['intro'])?$this->data['TopicI18n'][$v['Language']['locale']]['intro']:"";?></textarea>
								<?php echo $ckeditor->load("elm".$v['Language']['locale']); ?>
				    		</div>
	      				<?php }?>
	      			<?php }}?>
	      			</div></div>
		    		
		    		<div class="am-form-group">
	      			 <label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"> <?php echo "简单介绍";?></label>
		    		 	   <div class="am-u-lg-10 am-u-md-10 am-u-sm-8">	  	
	      			<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
	      				<?php if($configs["show_edit_type"]){?>
	      					<div class="am-form-group">
	      					 <div><span class="ckeditorlanguage  "><?php echo $v['Language']['name'];?></span></div>
				    			<textarea cols="80" id="mobile_intro<?php echo $v['Language']['locale'];?>" name="data[TopicI18n][<?php echo $k;?>][mobile_intro]" rows="10" style="width:auto;height:300px;"><?php echo isset($this->data['TopicI18n'][$v['Language']['locale']]['mobile_intro'])?$this->data['TopicI18n'][$v['Language']['locale']]['mobile_intro']:"";?></textarea>
								<script>
								var editor;
								KindEditor.ready(function(K) {
								editor = K.create("#mobile_intro<?php echo $v['Language']['locale'];?>", {
									width:'80%',
		                        langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
								});
								</script>
				    		</div>		
	      				<?php }else{?>
	      					<textarea cols="80" id="mobile_intro<?php echo $v['Language']['locale'];?>" name="data[TopicI18n][<?php echo $k;?>][mobile_intro]" rows="10"><?php echo isset($this->data['TopicI18n'][$v['Language']['locale']]['mobile_intro'])?$this->data['TopicI18n'][$v['Language']['locale']]['mobile_intro']:"";?></textarea>
							<?php echo $ckeditor->load("mobile_intro".$v['Language']['locale']); ?>
	      				<?php }?>
	      			<?php }}?>
	      				</div><div class="am-cf"></div><!---------over----------->
	      		</div>
		    		
		    		
		    		
		    					
		    					
			  </div>
			</div>
		</div>
		<!--专题介绍-->
	 
	    <!--手机专题介绍-->
 
	    	<!--高级设置-->					
	   <div id="gao" class="am-panel am-panel-default">
	  		 <div class="am-panel-hd">
				<h4 class="am-panel-title">
					<?php echo "高级设置"; ?>
				</h4>
		    </div>
		     <div class="am-panel-collapse am-collapse am-in">
	      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">		
				 
		    				<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['routeurl']?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
		    					<input type="text" id="Route_url" onchange="checkrouteurl()" name="data[Route][url]" value="<?php echo isset($routecontent['Route']['url'])?$routecontent['Route']['url']:'';?>" /><input type="hidden" id="route_url_h" value="0">
		    					&nbsp;&nbsp;(<?php echo $ld['routeurl_desc'] ?>)
		    				</div>
		    			</div>
		    		</div>	
					<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['meta_keywords']?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9"  >
			    					<input type="text" id="meta_keywords_<?php echo $v['Language']['locale']?>" name="data[TopicI18n][<?php echo $k;?>][meta_keywords]" value="<?php echo isset($this->data['TopicI18n'][$k]['meta_keywords'])?@$this->data['TopicI18n'][$k]['meta_keywords']:'';?>" />
			    				</div>
			    				<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding-top:18px;">
			    						<?php echo $ld[$v['Language']['locale']]?>
			    					</label>
			    				<?php }?>
		    				<?php }}?>	
		    			</div>
		    		</div>	
					<div class="am-form-group">
		    			<label class="aam-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label"><?php echo $ld['meta_description']?></label>
		    			<div class="am-u-lg-10  am-u-md-10 am-u-sm-8">
		    				<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9"  >
			    					<input type="text" id="meta_description_<?php echo $v['Language']['locale']?>" name="data[TopicI18n][<?php echo $k;?>][meta_description]" value="<?php echo isset($this->data['TopicI18n'][$k]['meta_description'])?@$this->data['TopicI18n'][$k]['meta_description']:'';?>" />
			    				</div>
			    				<?php if(sizeof($backend_locales)>1){?>
			    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="padding-top:18px;">
			    						<?php echo $ld[$v['Language']['locale']]?>
			    					</label>
			    				<?php }?>
		    				<?php }}?>	
		    			</div>
		    		</div>	
			    			 
				    			 
			    			 	
		    	 
		    	</div>
		 </div>	
		    				
	 </div>
	 	 
	     <!--关联商品- -->	
        <div id="related_products" class="am-panel am-panel-default">
	  		<div class="am-panel-hd">
				<h4 class="am-panel-title">
					<?php echo $ld['related_products']?>
				</h4>
		    </div>
		    <div class="am-panel-collapse am-collapse am-in">
		    	<div class="am-panel-bd am-form-detail am-form am-form-horizontal">		
		    		<div class="am-form-group">
		    			<ul class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1">
		    				<input type="hidden" id="products_id" value="">
		    				<li style="margin-top:7px;">
		    					<label class="am-u-lg-3  am-u-md-3 am-u-sm-3" style="margin-top:5px;"><?php echo $ld['category_name'];?>:</label>
		    					<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
		    						<select name="category_id" id="category_id" data-am-selected>
										<option value="0"><?php echo $ld['select_categories']?></option>
										<?php if(isset($categories_tree) && sizeof($categories_tree)>0){
													foreach($categories_tree as $first_k=>$first_v){?>
										<option value="<?php echo $first_v['CategoryProduct']['id'];?>"><?php echo $first_v['CategoryProductI18n']['name'];?></option>
										<?php 		if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){
															foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
										<option value="<?php echo $second_v['CategoryProduct']['id'];?>">&nbsp;&nbsp;<?php echo $second_v['CategoryProductI18n']['name'];?></option>
										<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){
																	foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
										<option value="<?php echo $third_v['CategoryProduct']['id'];?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryProductI18n']['name'];?></option>
										<?php }}}}}}?>
									</select>
		    					</div>
		    				</li>
		    				<li style="margin-top:7px;">
		    					<label class="am-u-lg-3  am-u-md-3 am-u-sm-3" style="margin-top:5px;"><?php echo $ld['brand'];?>:</label>
		    					<div class="am-u-lg-9  am-u-md-9 am-u-sm-9">
		    						<select id="brand_id" name="brand_id"  data-am-selected>
										<option value="0"><?php echo $ld['select_brands']?></option>
										<?php if(isset($brands_tree) && sizeof($brands_tree)>0){?>
										<?php 	foreach($brands_tree as $k=>$v){?>
										<option value="<?php echo $v['Brand']['id']?>"><?php echo $v['BrandI18n']['name']?></option>
										<?php }	}?>
									</select>
		    					</div>
		    				</li>
		    				<li style="margin-top:7px;">
		    					<label class="am-u-lg-3  am-u-md-3 am-u-sm-3 " style="margin-top:7px;"><?php echo $ld['price_range'];?></label>
		    					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 ">
		    						<input type="text" name="min_price" id="min_price" value="" onkeypress="sv_search_action_onkeypress(this,event)"/>
		    					</div>
		    					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding-top:5px;">-</div>
		    					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
		    						<input type="text" name="max_price" id="max_price" value="">	
		    					</div>
		    				</li>
		    				<li style="margin-top:7px;">
		    					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin-top:7px;"><?php echo $ld['keyword'];?>:</label>
		    					<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
		    						<input type="text" name="product_keyword" id="product_keyword"/>
		    					</div>
		    					<div class="am-u-lg-1">
		    						<button type="button" data-am-modal="{target: '#product_selectd'}" class="am-btn am-btn-success am-btn-sm am-radius" onclick="searchProducts();" value="<?php echo $ld['search']?>"><?php echo $ld['search']?></button>
		    							<!---------搜索按钮----------------> 
		    					</div>
		    				</li>
		    			</ul>
		    		</div>
		    						
		    						
                                         
		    				<!-- -------------------------添加后 删除 后  和原来就显示的商品-------------------------------------------->		 
			    		<div class="am-form-group" >
						     	 <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 "> 
						    		<h4 class="am-text-center"><?php echo $ld['topic_products']?></h4>
									<ul id="relative_product" class="am-avg-lg-5 am-avg-md-4 am-avg-sm-3  am-thumbnails">
									<?php if(isset($topicproduct) && sizeof($topicproduct)>0)foreach($topicproduct as $k=>$v){
									if (isset($v['TopicProduct'])){?>
									<li>
									<img style='width:150px;height:140px;' src="<?php echo  $v['TopicProduct']['img_detail'];?>"/><br/>
									<?php echo $v['TopicProduct']['name']?>
									<span class="am-icon-close am-no" onMouseout="onMouseout_deleteimg(this)" onmouseover="onmouseover_deleteimg(this)"  onclick="drop_topic_relation_product('<?php echo $v['TopicProduct']['product_id'];?>','<?php echo $v['TopicProduct']['topic_id']?>')"> </span>
									</li>
									<?php }}?>
									</ul>
						    	</div>
					    </div>
					     	<!-- -------------------------添加后 和原来就显示的商品 over-------------------------------------------->
			    </div>	
		    </div>		
		</div>
								
	 <!--关联文章--------------------------------------------------------------------->
		<div id="mobile_002" class="am-panel am-panel-default" >
	  		<div class="am-panel-hd">
				<h4 class="am-panel-title">
					<?php echo $ld['mobile_002'];?> 
				 </h4>
		    </div>
		    <div class="am-panel-collapse am-collapse am-in">
	      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">			
					<div class="am-form-group">
						<div class="am-u-lg-2 am-u-md-4 am-u-sm-5">
							<?php echo $this->element('category_tree_articles');?>
						</div>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-5" style="margin-top:10px;">			
							<input type="text" name="article_keyword" id="article_keyword" />
						</div>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin-top:10px;">
							<button type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="searchArticles();"  value="<?php echo $ld['search']?>"><?php echo $ld['search']?></button>
						</div>
					</div>
					<div class="am-form-group">
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-text-center">
				    		<label><?php echo $ld['option_article']?></label>
				    		<div id="article_select" class="related_dt"></div>
				    	
				    	</div>
				    			
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-12 am-text-center ">
				    		<label><?php echo "关联文章";?></label>
				    		<div id="product_select" class="mobile_003" ></div>
				    			<div id="relative_article" class="am-text-left">
									<?php $tid = isset($this->data['Topic']['id'])?$this->data['Topic']['id']:'';if(isset($topic_relation_article) && sizeof($topic_relation_article)>0){foreach($topic_relation_article as $k=>$v){?>
									<div class="am-u-lg-10 am-u-md-10 am-u-sm-10 ">
										<?php echo $article_infos[$v];?>
									</div>
									<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
										<span class="am-icon-close am-no" style="color:#dd514c;" onMouseout="onMouseout_deleteimg(this)" onmouseover="onmouseover_deleteimg(this)" onclick="drop_topic_relation_article('<?php echo $v;?>','<?php echo $tid;?>')"></span>
									</div>
								<?php }}?>
							</div>
				    	</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>	
<?php echo $form->end();?>
								
								
								
								
					<!-----------------------------------搜索弹出层----------------------------------->
						<div class="am-popup" id="product_selectd"  >
							<div class="am-popup-inner">
									<div class="am-popup-hd">
									<h4 class="am-popup-title"><?php echo $ld['option_products']?></h4>
									<span data-am-modal-close  class="am-close">&times;</span>
								         </div>
								           <div id="product_selectds" class="related_dt topicsma"></div> 
							                 <div class="am-checkbox " style='margin-left:20px; '>
							                   	   <label style='margin-top:8px; '>
						                               
							                   	   <input  onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)"  class="am-btn am-radius am-btn-success am-btn-sm" type="checkbox" data-am-ucheck /><?php echo $ld['select_all']?> </label>
							                   	   <input  class="am-btn am-btn-danger am-btn-xs" onClick="add_topic_relation_product()"  type="button" value="添加"/>
						                                
							                   </div>
							             </div>
					                      </div>	
					 	   <!-- -------------------------搜索弹出层over-------------------------------------------->
<script>
document.onmousemove=function(e)
{
 var obj = Utils.srcElement(e);
 if (typeof(obj.onclick) == 'function' && obj.onclick.toString().indexOf('listTable.edit') != -1)
 {
 obj.title = "<?php echo $ld['click_to_edit_content']?>";
 obj.style.cssText = 'background: #21964D;';
 obj.onmouseout = function(e)
 {
 this.style.cssText = '';
 }
 }
 else if (typeof(obj.href) != 'undefined' && obj.href.indexOf('listTable.sort') != -1)
 {
 obj.title = "<?php echo $ld['click_on_sorted_list']?>";
 }
}


</script>
<script type="text/javascript">
 function add_to_seokeyword(obj,keyword_id){

	var keyword_str = GetId(keyword_id).value;
	var keyword_str_arr = keyword_str.split(",");
	for( var i=0;i<keyword_str_arr.length;i++ ){
		if(keyword_str_arr[i]==obj.value){
			return false;
		}
	}
	if(keyword_str!=""){
		GetId(keyword_id).value+= ","+obj.value;
	}else{
		GetId(keyword_id).value+= obj.value;
	}
}
function topics_check(){
	var topic_title_obj = document.getElementById("topic_title_"+backend_locale);
	if(topic_title_obj.value==""){
		alert("<?php echo $ld['enter_subject_name']?>");
		return false;
	}
	return true;

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
