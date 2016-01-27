<style type="text/css">
label{font-weight:normal;}
[class*="am-u-"] + [class*="am-u-"]:last-child{ float: left;}
.am-form-horizontal .am-radio{padding-top: 0;position:relative;top:5px;}
.am-radio, .am-checkbox{display: inline-block;}
 .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"] {padding-left:0;margin-left:0px;}
 .am-radio input[type="radio"]{margin-left:0px;}
 input[type="text"]{width:auto;}
.img_select{max-width:150px;max-height:120px;}
</style>
<div> 
	<!--左边菜单-->
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-detail-menu">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index:100; width: 15%;max-width:200px;">
			<?php if(isset($config_groups)&&sizeof($config_groups)>0){ $i=0;foreach($config_groups as $sub_k=>$sub_group){ ?>
				<li>
					<a href="#<?php if(isset($resource_list[$sub_k])){echo $resource_list[$sub_k];}else{echo $sub_k;}?>">
						<?php if(isset($resource_list[$sub_k])){echo $resource_list[$sub_k];}else{echo $sub_k;}?>
					</a>
				</li>
			<?php }}?>
		</ul>
	</div>
	<!--内容-->
	<input type="hidden" id="type" value="<?php if(isset($_GET['type'])&&$_GET['type']!=""){echo $_GET['type'];}else {echo "0";}?>">
	<div class="am-panel-group admin-content am-detail-view" id="accordion" >
		<?php if(isset($config_groups)&&sizeof($config_groups)>0){ $i=0;foreach($config_groups as $sub_k=>$sub_group){ ?>
			<div id="<?php if(isset($resource_list[$sub_k])){echo $resource_list[$sub_k];}else{echo $sub_k;}?>" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title" >
						<?php if(isset($resource_list[$sub_k])){echo $resource_list[$sub_k];}else{echo $sub_k;}?>
					</h4>
			    </div>
				<div class="am-panel-collapse am-collapse am-in" >
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
						<?php if(is_array($sub_group)&&sizeof($sub_group)>0){foreach($sub_group as $sub_group_info){ ?>
						
							<?php if($sub_group_info['Config']['type']=='nav_select'){ ?>
				      			<div class="am-form-group" style="">
					    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-group-label" ><?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name']?></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php  foreach($backend_locales as $k=>$v){if(isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']) && !empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])){ ?>
					    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" >
											<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
										    <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>">
											<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
											<select name="data[<?php echo $i;?>][value]" data-am-selected>
												<option value="0" <?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']=='0')echo "selected";?>><?php echo $ld['none']?></option>
												<option value="T" <?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']=='T')echo "selected";?>><?php echo $ld['top']?></option>
												<option value="H" <?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']=='H')echo "selected";?>><?php echo $ld['help_section']?></option>
												<option value="B" <?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']=='B')echo "selected";?>><?php echo $ld['bottom']?></option>
												<option value="M" <?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']=='M')echo "selected";?>><?php echo $ld['middle']?></option>
											</select>
											<?php
												if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) != ""){
												echo $html->link(" ", "javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));}?>
												<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;">
													<em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em>
												</span> 
					    				</div>
										<?php if(sizeof($backend_locales)>1){?>
											<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left">	
												<?php echo $ld[$v['Language']['locale']];?>
											</label>		
										<?php }?>
					    				<?php $i++;}} ?>
					    			</div>
					    		</div>		
		      				<?php } ?>
		      							
		      				<?php if ($sub_group_info['Config']['type'] == "text") {?>
								<?php if(($sub_group_info['Config']['code']=='water_text'||$sub_group_info['Config']['code']=='watermark_transparency')){continue;}?>		
	      						<div class="am-form-group" >
					    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;">
					    				<?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name']?>
					    			</label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php  foreach($backend_locales as $k=>$v){ ?>
					    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
					    					<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
											<input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>"> 
											<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>"> 
											<input type="text" id="<?php echo $sub_group_info['Config']['code']?><?php echo $v['Language']['locale'];?>" name="data[<?php echo $i; ?>][value]" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']; ?>" />
										
											<?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) !=""){echo 
											$html->link(" ","javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));}?>
											<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;">
												<em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em>
											</span>
					    				</div>
					    				<?php if(sizeof($backend_locales)>1){?>
						    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="padding-top:20px;">
												<?php echo $ld[$v['Language']['locale']];?>
							    			</label>
										<?php }?>
					    				<?php $i++; }?>
					    			</div>
					    		</div>		
		      				<?php }?>			
		      				
						      				
							<?php if ($sub_group_info['Config']['type'] == "send_email_test"){?>
		      					<div class="am-form-group"  >
					    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">
					    				<?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name']?>
					    			</label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6" >
					    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
											<input type="text" id="email_addr" />
											<input type="button" value="<?php echo $ld['send_test_email']?>" onclick="test_email()" />
					    				</div>
					    			</div>
					    		</div>
							<?php $i++;} ?>
		      				
		      				<?php if ($sub_group_info['Config']['type'] == "webroot") {?>
		      					<div class="am-form-group" >
					    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" >
					    				<?php echo isset($sub_group_info['ConfigI18n'][$backend_locale])?$sub_group_info['ConfigI18n'][$backend_locale]['name']:""?>
					    			</label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php 	foreach ($backend_locales as $k => $v) {?>
					    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    					<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>"><input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>"> <input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>"> <input id="webroot<?php echo $k;?>"type="text"  name="data[<?php echo $i; ?>][value]" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']!=""){echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'];}else{echo "HOME";} ?>"  />&nbsp;
					    					<?php
											if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) !=""){
												echo $html->link(" ", "javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));}?>
											<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;"><em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em></span>
					    				</div>
					    				<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left">
					    						<?php echo $ld[$v['Language']['locale']];?>
					    					</label>
					    				<?php }?>
					    				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $this->element('select_homepage');?></div>
					    				<?php $i++;}?>	
					    			</div>
					    		</div>
		      				<?php } ?>
		      				
		      				<?php if ($sub_group_info['Config']['type'] == "textarea" && $sub_group_info['Config']['code'] !='all_share' ){ ?>
		      					<div class="am-form-group"  >
					    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">
					    				<?php echo isset($sub_group_info['ConfigI18n'][$backend_locale])?$sub_group_info['ConfigI18n'][$backend_locale]['name']:""?>
					    			</label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php 	foreach ($backend_locales as $k => $v) {?>
					    					<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>"> <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>"> <input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
						    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
												<textarea name="data[<?php echo $i; ?>][value]" ><?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']; ?></textarea>
											</div>
											<?php
											if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) !=""){
											echo $html->link(" ", "javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));}?>
											<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;">
												<em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em>
											</span>
					    				<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" >	
					    						<?php echo $ld[$v['Language']['locale']];?>
					    					</label>
					    				<?php }?>
					    				<?php $i++;}?>	
					    			</div>
					    		</div>
		      				<?php } ?>
		      				
		      				<?php if ($sub_group_info['Config']['type'] == "radio") { ?>
		      					<div class="am-form-group"  >
		      					<?php if($sub_group_info['Config']['code']=='watermark_location'){continue;}	?>
					    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;">
					    				<?php echo isset($sub_group_info['ConfigI18n'][$backend_locale])?$sub_group_info['ConfigI18n'][$backend_locale]['name']:""?>
					    			</label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php 	foreach ($backend_locales as $k => $v) { ?>
					    					<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
										    <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>">
									    	<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
					    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    					<ul class="am-avg-lg-3 am-avg-md-1 am-avg-sm-1">
											<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['options']) && is_array($sub_group_info['ConfigI18n'][$v['Language']['locale']]['options'])){
											$options = $sub_group_info['ConfigI18n'][$v['Language']['locale']]['options'];
											foreach ($options as $option) {
											$text = explode(":", $option);
											if (@$text[1] != "") {?>
											<li>
												<label class="am-radio am-success">
													<input type="radio" data-am-ucheck  name="data[<?php echo $i; ?>][value]" value="<?php echo $text[0]; ?>" <?php if (@$text[0] == $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])echo 'checked'; ?>/>
													<font><?php if (@$text[1]) { echo $text[1];} ?></font>
												</label>&nbsp;&nbsp;&nbsp;&nbsp;
											</li>
											<?php	}}} ?>
											</ul>
					    				</div>
					    				<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="padding-top:17px;">		
					    						<?php echo $ld[$v['Language']['locale']];?>
					    					</label>		
					    				<?php }?>
					    				<?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) !=""){echo $html->link(" ", "javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));}?>
												<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;">
													<em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em>
												</span>
					    				<?php $i++;}?>			
					    			</div>
					    		</div>
		      				<?php }?>
		      				
		      				
		      				<?php if ($sub_group_info['Config']['type'] == "map") { ?>
		      					<div class="am-form-group" >
					    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">
					    				<?php echo isset($sub_group_info['ConfigI18n'][$backend_locale])?$sub_group_info['ConfigI18n'][$backend_locale]['name']:"";?>
					    			</label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php 	foreach ($backend_locales as $k => $v) { ?>
					    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    					<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
										    <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>">
									    	<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
				    						<?php $position=isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])?$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']:'0';?>
											<input type="text" id="<?php echo $sub_group_info['Config']['code']?><?php echo $v['Language']['locale'];?>" name="data[<?php echo $i; ?>][value]" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']; ?>" />
											<a class="show_map" href="javascript:void(0);"><?php echo $ld['shop_map']?></a>
											<?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) !=""){
											echo $html->link(" ", "javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));}?>
											<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;">
												<em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em>
											</span>
					    				</div>
					    				<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left">	
					    						<?php echo $ld[$v['Language']['locale']];?>
					    					</label>
							    		<?php }?>
					    				<?php $i++;}?>			
					    			</div>
					    		</div>
		      				<?php } ?>
		      				
		      				<?php if ($sub_group_info['Config']['type'] == "image") {?>
		      					<?php if($sub_group_info['Config']['code']=='watermark_file'){continue;}?>
		      					<div class="am-form-group" >
					    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;">
					    				<?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name']?>
					    			</label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php 	foreach ($backend_locales as $k => $v) { ?>
					    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
											<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
											<input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>">
											<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
											<input name="data[<?php echo $i; ?>][value]" id="upload_img_text_<?php echo $v['Language']['locale'].'_'.$i;?>" type="text" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']; ?>" />
											<button type="button" class="am-btn am-btn-xs am-btn-success am-radius" value="<?php echo $ld['choose_picture']?>" onclick="select_img('upload_img_text_<?php echo $v['Language']['locale'].'_'.$i;?>')" style="margin-top:5px;"><?php echo $ld['choose_picture']?></button>
											<?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) !=""){
													echo $html->link(" ","javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));}?>
											<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;">
												<em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em>
											</span>
											<div class="img_select" style="margin:5px;">
												<?php echo $html->image((isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']!="")?$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']:$configs['shop_default_img'],array('id'=>'show_upload_img_text_'.$v['Language']['locale'].'_'.$i));?>
											</div>
					    				</div>
					    				<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="padding-top:20px;">	
					    						<?php echo $ld[$v['Language']['locale']];?>
					    					</label>		
					    				<?php }?>
					    				<?php $i++;}?>				
					    			</div>
					    		</div>
		      				<?php } ?>
		      				
		      				<?php if ($sub_group_info['Config']['type'] == "checkbox") {?>
		      					<div class="am-form-group" >
					    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:13px;">
					    				<?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name']?>
					    			</label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php 	foreach ($backend_locales as $k => $v) { ?>
					    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
											<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>"> 
											<input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>"> 
											<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
											<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['options']) && !empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['options'])) {
											$checkoptions = explode(';', $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']);
											$options = $sub_group_info['ConfigI18n'][$v['Language']['locale']]['options'];
											if(sizeof($options)>1){	foreach ($options as $option) {$text = explode(":", $option);
											if (@$text[1] != "") { ?>
											<label class="am-checkbox am-success ">	
												<input type="checkbox" data-am-ucheck  name="data[<?php echo $i; ?>][value][]" value="<?php echo $text[0]; ?>" <?php if (in_array($text[0], $checkoptions))echo 'checked'; ?>/>
											</label>
											<?php if (@$text[1]){echo $text[1];	}}}}else{?>
											<label class="am-checkbox am-success ">
												<input type="checkbox" data-am-ucheck  name="data[<?php echo $i; ?>][value]" value="1"
												<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']==1) echo 'checked' ?>/>
											</label>
											<?php }}?>
											<?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) != ""){echo $html->link(" ", "javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));}?>
											<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;">
												<em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em>
											</span>
					    				</div>
					    				<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="padding-top:12px;">		
					    						<?php echo $ld[$v['Language']['locale']];?>
					    					</label>		
					    				<?php }?>
					    				<?php $i++;}?>				
					    			</div>
					    		</div>
		      				<?php } ?>
		      				
		      				
		      				<?php if ($sub_group_info['Config']['type'] == "file") {?>
		      					<div class="am-form-group">
					    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" >
					    				<?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name'];?>
					    			</label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php foreach ($backend_locales as $k => $v) {?>
					    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
											<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
											<input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>">
											<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
											<input type="file"  name="data[<?php echo $i; ?>][value]" id="data[<?php echo $i; ?>][value]" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']; ?>" onchange="checkUploadFile(this,'data[<?php echo $i; ?>][value]')"/>
											&nbsp;
											<?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) !=""){
														echo $html->link(" ","javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));}?>
											<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;">
												<em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em>
											</span>
											<p>
												<?php if(isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])){?>
													<?php if (!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])){?>
														<img src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/favicon.ico'?>" width="20" height="20" />
												<?php }}?>
											</p>
					    				</div>
					    				<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left">	
					    						<?php echo $ld[$v['Language']['locale']];?>
					    					</label>		
					    				<?php }?>
					    				<?php $i++;}?>				
					    			</div>
					    		</div>
		      				<?php } ?>	
		      				
		      				<?php if($sub_group_info['Config']['type']=="select"){?>
		      					<?php if(($sub_group_info['Config']['code']=='water_text_font'||$sub_group_info['Config']['code']=='water_text_size'))continue;?>
		      					<div class="am-form-group" >
					    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:17px;">
					    				<?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name']?>
					    			</label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<?php foreach($backend_locales as $k=>$v){if(isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']) && !empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])){?>
										<?php if($sub_group_info['Config']['code']=="home_controller" && $v['Language']['locale']=="eng"){ echo "<div></div>";continue;}?>
					    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
											<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
										 	<input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>">
										 	<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
											<select id="data<?php echo $i;?>" name="data[<?php echo $i;?>][value]" data-am-selected>
											 	<?php if(isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['options']) && !empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['options'])){$options = explode(';',$sub_group_info['ConfigI18n'][$v['Language']['locale']]['options']);foreach($options as $option){$text =explode(":",$option);?>
											 	<option value="<?php echo $text[0];?>" <?php if($text[0]==$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']) echo 'selected';?>><?php if(@$text[1]){ echo $text[1];} ?></option>
										 	 	<?php }} ?>
										 	</select>
											<?php if($sub_group_info['Config']['code']=="home_controller" && isset($home_url)){?>
												<input id="home_url" disabled="disabled" name="home_url" type="text" value="<?php echo '/'.$home_url['Route']['controller'].'/'.$home_url['Route']['action'].'/'.($home_url['Route']['model_id']=='home'?'':$home_url['Route']['model_id']);?>"/>
											<?php }?>
											
											<?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) != ""){
												echo $html->link(" ", "javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));
											}?>
											<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;">
												<em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em>
											</span> 
											<?php if($sub_group_info['Config']['code']=="home_controller" && $sub_group_info['ConfigI18n'][$v['Language']['locale']]['locale']=="chi"){?>
												<input id="Navigation" type="hidden" value="" />
												<input id="NavigationUrl" name="Route[url]"  type="hidden" value="" />
												<input id="route_controller" name="Route[controller]" type="hidden" value=""/>
												<div id='product_div' style="display:none">
													<input type="text" id='p_key' value="" /><input type="button" onclick="search('p')" value="<?php echo $ld['search_products']?>" />
												</div>
												<div id='article_div' style="display:none">
													<input type="text" id="a_key" value="" /><input type="button" onclick="search('a')" value="<?php echo $ld['search_articles']?>" />
												</div>
												<div id='static_page_div' style="display:none">
													<input type="text" id="sp_key" value="" /><input type="button" onclick="search('sp')" value="<?php echo $ld['search']?>" />
												</div>
												<select id='p_category' style="display:none" onChange="changeNa(this.value)">
													<option value="0"><?php echo $ld['please_select'];?></option>
													<?php if(isset($c_p_info)&&$c_p_info!=""&&count($c_p_info)>0){?>
													  <?php	foreach($c_p_info as $cpv){ ?>
													  <option value="<?php echo $cpv['CategoryProduct']['id'].'/'.$cpv['CategoryProductI18n']['name'];?>"><?php echo $cpv['CategoryProductI18n']['name'];?></option>
													  <?php	}?>
													<?php }?>
												</select>
												<select id='a_category' style="display:none" onChange="changeNa(this.value)">
													<option value="0"><?php echo $ld['please_select'];?></option>
													<?php if(isset($c_a_info)&&$c_a_info!=""&&count($c_a_info)>0){?>
													<?php	foreach($c_a_info as $cav){ ?>
														<option value="<?php echo $cav['CategoryArticle']['id'].'/'.$cav['CategoryArticleI18n']['name'];?>"><?php echo $cav['CategoryArticleI18n']['name'];?></option>
													<?php }?>
													<?php }?>
												</select>
												<div id='brand_div' style="display:none" >
													<select id='brand'  onChange="changeNa(this.value)">
														<option value="0"><?php echo $ld['please_select'];?></option>
														<?php if(isset($b_info)&&$b_info!=""&&count($b_info)>0){?>
															<?php	foreach($b_info as $bv){ ?>
																<option value="<?php echo $bv['Brand']['id'];?>"><?php echo $bv['BrandI18n']['name'];?></option>
															<?php	}?>
														<?php }?>
													</select>
												</div>
												<div id='topic_div' style="display:none" >
													<select id='topic'  onChange="changeNa(this.value)">
														<option value="0"><?php echo $ld['please_select'];?></option>
															<?php if(isset($topic_info)&&$topic_info!=""&&count($topic_info)>0){?>
																<?php	foreach($topic_info as $tv){ ?>
																	<option value="<?php echo $tv['Topic']['id'];?>"><?php echo $tv['TopicI18n']['title'];?></option>
																<?php	}?>
															<?php }?>
													</select>
												</div>
												<div id='pro_div' style="display:none" >
													<select id='promotion'  onChange="changeNa(this.value)">
														<option value="0"><?php echo $ld['please_select'];?></option>
															<?php if(isset($pro_info)&&$pro_info!=""&&count($pro_info)>0){?>
																<?php	foreach($pro_info as $pv){ ?>
																	<option value="<?php echo $pv['Promotion']['id'];?>"><?php echo $pv['PromotionI18n']['title'];?></option>
																<?php	}?>
															<?php }?>
													</select>
												</div>
											<?php }?>
					    				</div>
					    				<?php if(sizeof($backend_locales)>1){?>
					    					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="padding-top:20px;">
					    						<?php echo $ld[$v['Language']['locale']];?>
					    					</label>
					    				<?php }?> 
					    				<?php $i++;}}?>					
					    			</div>
					    		</div>
		      				<?php }?>
		      			<?php }} ?>
		      			<div class="btnouter"  style="margin-top:50px;">
							<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
							<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
						</div>	
		      		</div>
		      	</div>
		      	
		      				
			</div>
		<?php }} ?>
	</div>
</div>

<script type="text/javascript">
$(function(){
	$("#data2").change();
});

$(".show_map").click(function(){
  var position=$(this).parent().find("input[type=text]").val();
  var map_locale=$(this).parent().find("input[type=text]").attr("id");
  window.open(admin_webroot+"/maps?position="+position+"&map_locale="+map_locale, 'newwindow', 'height=600, width=1024, top=0, left=0, toolbar=no, menubar=yes, scrollbars=yes,resizable=yes,location=no, status=no');
});

function test_email(){
	YUI().use("io",function(Y) {
		var email_addr = document.getElementById('email_addr');
		var smtp_pass = document.getElementById('mail-password'+backend_locale);
		var smtp_host = document.getElementById('mail-smtp'+backend_locale);
		var smtp_user = document.getElementById('mail-account'+backend_locale);
		var smtp_port = document.getElementById('mail-port'+backend_locale);
		var smtp_ssl = 0;
		var mail_service = 1;
//		if(!mail_service.checked){
//			if(mail_service.value==1){
//				mail_service_value = 0;
//			}else{
//				mail_service_value = 1;
//			}
//		}else{
			mail_service_value = mail_service;
//		}
//
//		if(!smtp_ssl.checked){
//			if(smtp_ssl.value==1){
//				smtp_ssl_value = 0;
//			}else{
//				smtp_ssl_value = 1;
//
//			}
//		}else{
			smtp_ssl_value = smtp_ssl;
//		}

		var sUrl = admin_webroot+"configvalues/test_email/"+email_addr.value+"/"+smtp_host.value+"/"+smtp_user.value+"/"+smtp_port.value+"/"+mail_service_value+"/"+smtp_ssl_value;

		var cfg = {
			method: "POST",
			data: "smtp_pass="+smtp_pass.value
		};
		var request = Y.io(sUrl, cfg);//开始请求

		var handleSuccess = function(ioId, o){
			try{
				eval('result='+o.responseText);
			}catch (e){
				alert(o.responseText);
			}
			if(o.responseText=='true'){
				alert("<?php echo $ld['congratulations_message_successfully_sent']?> "+document.getElementById('email_addr').value);
			}else{
				alert(o.responseText);
			}
		}
		var handleFailure = function(ioId, o){
			alert("<?php echo $ld['asynchronous_request_failed']?>");
		}
		Y.on('io:success', handleSuccess);
		Y.on('io:failure', handleFailure);
	});
}

//	alert(document.getElementById("type").value);
/*window.onload=function(){
	var type=document.getElementById("type").value;
	var classs=document.getElementById("tablemain");
	if(type=="1"){

		classs.children[0].className ="tableContent1";
		classs.children[1].className ="tableContent2 show";
	}

	for(var i=0;i<classs.children.length;i++){
		classs.children[i].id="tableContent"+(i+1);
	}
	hash();
}*/
function change_position(){
hash();
}
function hash(){
	var classs=document.getElementById("tablemain");
for(var i=0;i<classs.children.length;i++){
		var str=classs.children[i].className;
		if(str.length>15){
		
			window.location.hash=classs.children[i].id;
		}
	}
}
 function show_intro(url) {
 	 
 		window.location.href=admin_webroot+"configvalues/index/"+url;
 }
function checkUploadFile(arg,obj){
	var fileName
	var fileSize
	var bro = getBroswerType()

	if(bro.firefox){
		fileSize = arg.files.item(0).fileSize
	}else if(bro.ie){
		var image=new Image()

		image.dynsrc=arg.value
		fileSize = image.fileSize

	}
	if(lastname(obj))
	{
		if(fileSize > 500*1024){
			arg.value=""
			alert("附件大小超出500K")
		}
	}
}
function lastname(obj){
	var filepath = document.getElementById(obj).value;
	var re = /(\\+)/g;
	var filename=filepath.replace(re,"#");
	//对路径字符串进行剪切截取
	var one=filename.split("#");
	//获取数组中最后一个，即文件名
	var two=one[one.length-1];
	//再对文件名进行截取，以取得后缀名
	var three=two.split(".");
	 //获取截取的最后一个字符串，即为后缀名
	var last=three[three.length-1];
	//添加需要判断的后缀名类型
	var tp ="jpg";
	//返回符合条件的后缀名在字符串中的位置
	var rs=tp.indexOf(last);
	//如果返回的结果大于或等于0，说明包含允许上传的文件类型
	if(rs>=0){
	 return true;
	 }else{
	 alert("您选择的上传文件不是有效的图片文件！");
	 document.getElementById(obj).value="";
	 return false;
	  }
}
	function getBroswerType(){
	var Sys = {};
	var ua = navigator.userAgent.toLowerCase();
	var s;
	(s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
	(s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
	(s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
	(s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
	(s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;

	return Sys
}

$('iframe').load(function(){
    $('iframe').contents().find('input#submit_position').bind('click',function(e) {
       position = $('iframe').contents().find('input#position').val();
       $('input#shop_map'+backend_locale).val(position);
    });
});
</script>