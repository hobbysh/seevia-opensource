<style type="text/css">
label{font-weight:normal;}
[class*="am-u-"] + [class*="am-u-"]:last-child{ float: left;}
.am-form-horizontal .am-radio{padding-top: 0;position:relative;top:5px;}
.am-radio, .am-checkbox{display: inline-block;}
.am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"] {padding-left:0;}
.am-panel-group{margin-bottom: 1rem;}
.img_select{max-width:150px;max-height:120px;}

</style>
<div >
	<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu">
	  	<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
			<?php $i=0; $n = 1; foreach($group_codes as $groups_k=>$groups){?>
	    	<li><a href="#configvalue_<?php echo $groups;?>"><?php if(isset($config_group_codes) && sizeof($config_group_codes)>0){echo $config_group_codes[$groups];}?></a></li>
	    	<?php }?>
	  	</ul>
	</div>
	<div class="admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-detail-view" id="accordion" style="width:83%;float:right;">
	<?php $i=0; $n = 1; foreach($group_codes as $groups_k=>$groups){?>
 	<div id="configvalue_<?php echo $groups; ?>" class="am-panel am-panel-default" style="margin-bottom:5px;">
    		<div class="am-panel-hd">
      			<h4 class="am-panel-title" >
      				<?php if(isset($config_group_codes) && sizeof($config_group_codes)>0){echo $config_group_codes[$groups];}?>
      			</h4>
    		</div>
			<?php  echo $form->create('Configvalue', array('action' => 'edit/'.$groups, 'enctype' => "multipart/form-data","class"=>"am-form am-form-horizontal")); ?>
		    <div class="am-panel-collapse am-collapse am-in">
		      	<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
				<?php if(isset($config_groups[$groups]) && sizeof($config_groups[$groups])>0){foreach($config_groups[$groups] as $sub_k=>$sub_group){ ?>	
						<div class="am-panel-group" id="<?php echo $sub_k; ?>">
							<div class="am-panel am-panel-default">
							<div class="am-panel-hd">
								<h4 class="am-panel-title" data-am-collapse="{parent: '#<?php echo $sub_k; ?>', target: '#<?php if(isset($config_group_codes) && sizeof($config_group_codes)>0){echo $config_group_codes[$groups];}?>_<?php echo isset($config_sub_group_codes[$sub_k])?$config_sub_group_codes[$sub_k]:$sub_k; ?>'}">
									<?php echo isset($config_sub_group_codes[$sub_k])?$config_sub_group_codes[$sub_k]:$sub_k; ?>
								</h4>
						    </div>
						<div id="<?php if(isset($config_group_codes) && sizeof($config_group_codes)>0){echo $config_group_codes[$groups];}?>_<?php echo isset($config_sub_group_codes[$sub_k])?$config_sub_group_codes[$sub_k]:$sub_k; ?>" class="am-panel-collapse am-collapse am-in">
      						<div class="am-panel-bd">		
						<div id="<?php echo $sub_k; ?>_content">
						<!--子分组循环start -->
						<?php if(is_array($sub_group)&&sizeof($sub_group)>0){?>
						<div class="alonetable">
						<?php 	foreach($sub_group as $sub_group_info){ ?> 
							<!-- 循环遍历，判断类型 start -->
							
					<?php if($sub_group_info['Config']['type']=='nav_select'){ ?>
					<div class="am-form-group">
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" ><?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name']?></label>
						<?php  foreach($backend_locales as $k=>$v){if(isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']) && !empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])){ ?>
							<div class="am-u-lg-6 am-u-md-7 am-u-sm-8">
								<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
							    <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>">
								<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
								<select name="data[<?php echo $i;?>][value]">
									<option value="0" <?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']=='0')echo "selected";?>><?php echo $ld['none']?></option>
									<option value="T" <?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']=='T')echo "selected";?>><?php echo $ld['top']?></option>
									<option value="H" <?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']=='H')echo "selected";?>><?php echo $ld['help_section']?></option>
									<option value="B" <?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']=='B')echo "selected";?>><?php echo $ld['bottom']?></option>
									<option value="M" <?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']=='M')echo "selected";?>><?php echo $ld['middle']?></option>
								</select>
								<?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?>
								<?php
									if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) != ""){
									echo $html->link(" ", "javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",
									array('escape'=>false,'class'=>'helpbtn'));}?>
									<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;">
									<em>
									<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?>
										</em>
									</span> 
							</div>
						<?php $i++;}} ?>
					</div>
					<?php } ?>
							
				<?php if ($sub_group_info['Config']['type'] == "text") {?>
			    <div class="am-form-group">
					<?php if(($sub_group_info['Config']['code']=='water_text'||$sub_group_info['Config']['code']=='watermark_transparency')){continue;}?>
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name']?></label>
					<div class="am-u-lg-7 am-u-md-7 am-u-sm-8">
						<?php  foreach($backend_locales as $k=>$v){ ?>
							<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
							<input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>"> 
							<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>"> 
							<div class="am-u-lg-10 am-u-md-8 am-u-sm-8" style="margin-bottom:10px;">
								<input type="text" id="<?php echo $sub_group_info['Config']['code']?><?php echo $v['Language']['locale'];?>" name="data[<?php echo $i; ?>][value]" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']; ?>" />
							</div>
							<?php if(sizeof($backend_locales)>1){?>
							<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="margin-top:12px;margin-left:0px;">
								<?php echo $ld[$v['Language']['locale']];?>
							</label>
							<?php }?>
							<?php
								if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) !=""){
									echo $html->link(" ","javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));
								}
							?>
							<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;">
								<em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em>
							</span>
						<?php $i++;} ?>
					</div>
				</div>	
				<?php }  ?>

				<?php if ($sub_group_info['Config']['type'] == "send_email_test"){?>
			    <div class="am-form-group" >
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" >
						<?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name']?>
					</label>
					<div class="am-u-lg-6 am-u-md-7 am-u-sm-8" > 
						<input type="text" id="email_addr" />
						<input type="button" class="am-btn am-btn-xs am-btn-success am-radius" value="<?php echo $ld['send_test_email']?>" onclick="test_email()" />
					</div>
				</div>				
				<?php $i++;} ?>

				<?php if ($sub_group_info['Config']['type'] == "webroot") {?>
			    <div class="am-form-group" >
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"  >
							<?php echo isset($sub_group_info['ConfigI18n'][$backend_locale])?
							$sub_group_info['ConfigI18n'][$backend_locale]['name']:""?>
						</label>
					<?php 	foreach ($backend_locales as $k => $v) {?>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-8" >
							<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>"><input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>"> <input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>"> <input id="webroot<?php echo $k;?>"type="text"  name="data[<?php echo $i; ?>][value]" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']!=""){echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'];}else{echo "HOME";} ?>"  />
							<?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?>
							<?php
								if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) !=""){
									echo $html->link(" ", "javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));
								}?>
							<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;">
							<em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em>
							</span>
						</div>
					<?php $i++;}?>
						<div><?php echo $this->element('select_homepage');?></div>
				</div>			
				<?php } ?>
								
				<?php if ($sub_group_info['Config']['type'] == "textarea" && $sub_group_info['Config']['code'] !='all_share' ){ ?>
			    <div class="am-form-group">
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:10px;">
						<?php echo isset($sub_group_info['ConfigI18n'][$backend_locale])?$sub_group_info['ConfigI18n'][$backend_locale]['name']:""?>
					</label>
					<div class="am-u-lg-7 am-u-md-7 am-u-sm-8" >
					<?php 	foreach ($backend_locales as $k => $v) {?>
							<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>"><input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>"> <input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
							<div class="am-u-lg-10 am-u-md-7 am-u-sm-8" style="margin-bottom:10px;">
								<textarea name="data[<?php echo $i; ?>][value]"><?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']; ?></textarea>
							</div>
							<?php if(sizeof($backend_locales)>1){?>
								<label class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:20px;font-weight:bold;"><?php echo $ld[$v['Language']['locale']];?></label>
							<?php }?>
							<?php
								if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) !=""){
									echo $html->link(" ", "javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));
								}
								?>
								<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;">
									<em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em>
								</span>
				<?php $i++;}?>
				</div>
			</div>	
				<?php } ?>	

				<?php if ($sub_group_info['Config']['type'] == "radio") { ?>
			    <div class="am-form-group">
					<?php if($sub_group_info['Config']['code']=='watermark_location'){continue;}	?>
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;">
							<?php echo isset($sub_group_info['ConfigI18n'][$backend_locale])?$sub_group_info['ConfigI18n'][$backend_locale]['name']:""?>
						</label>
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
						<?php 	foreach ($backend_locales as $k => $v) { ?>
							<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
						    <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>">
					    	<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
						<div class="am-u-lg-10 am-u-md-10 am-u-sm-10"> 
							<ul class="am-avg-lg-3 am-avg-md-1 am-avg-sm-1">
							<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['options']) && is_array($sub_group_info['ConfigI18n'][$v['Language']['locale']]['options'])) {
							$options = $sub_group_info['ConfigI18n'][$v['Language']['locale']]['options'];
							foreach ($options as $option) {	$text = explode(":", $option);
							if (@$text[1] != "") {?>
							<li>
							<label style="padding-top:1px;" class="am-radio am-success"  >
								<input type="radio" class="<?php echo $sub_group_info['Config']['code']?><?php echo $v['Language']['locale'];?>"  name="data[<?php echo $i; ?>][value]" data-am-ucheck style="margin-left:0px;" value="<?php echo $text[0]; ?>" <?php if (@$text[0] == $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])echo 'checked'; ?>/>
								<font><?php if (@$text[1]) { echo $text[1];} ?></font>
							</label>&nbsp;&nbsp;&nbsp;&nbsp;
							</li>
							<?php }}} ?>
							</ul>
						</div>			
								<?php if(sizeof($backend_locales)>1){?> 
									<label class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="margin-top:18px;">
										<?php echo $ld[$v['Language']['locale']];?>
									</label>
								<?php }?>
								<?php
								if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) !=""){
									echo $html->link(" ", "javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));
								}
							?><span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;"><em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em></span>
				<?php $i++;}?>	
				</div>	
					</div>			
				<?php } ?>

				<?php if ($sub_group_info['Config']['type'] == "map") { ?>
				<div class="am-form-group">
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:22px">
							<?php echo isset($sub_group_info['ConfigI18n'][$backend_locale])?$sub_group_info['ConfigI18n'][$backend_locale]['name']:"";?>
						</label>
					    <div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
						<?php 	foreach ($backend_locales as $k => $v) { ?>
							<input  name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
						    <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>">
					    	<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
					    	<?php $position=isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])?$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']:'0';?>
					    	<div class="am-u-lg-10 am-u-md-10 am-u-sm-10" style="margin-bottom:10px;">
								<input type="text" id="<?php echo $sub_group_info['Config']['code']?><?php echo $v['Language']['locale'];?>" name="data[<?php echo $i; ?>][value]" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']; ?>" />
							</div>
							<a class="show_map am-u-lg-1 am-u-md-1 am-u-sm-1" style="margin-top:18px"href="javascript:void(0);">
								<?php echo $ld['shop_map']?>
							</a>
				<?php $i++;}?>
						</div>
					</div>
				<?php } ?>
			    
				<?php if ($sub_group_info['Config']['type'] == "image") {?>
				<div class="am-form-group">
						<?php if($sub_group_info['Config']['code']=='watermark_file'){continue;}?>
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"  style="padding-top:13px;">
							<?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name']?>
						</label>
					<div class="am-u-lg-7 am-u-md-7 am-u-sm-8">
					<?php 	foreach ($backend_locales as $k => $v) { ?>
						<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
						<input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>">
						<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
							
						<input name="data[<?php echo $i; ?>][value]" id="upload_img_text_<?php echo $v['Language']['locale'].'_'.$i;?>" type="text" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']; ?>" />
						<input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('upload_img_text_<?php echo $v['Language']['locale'].'_'.$i;?>')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>
						<?php if(sizeof($backend_locales)>1){?>
							<span class="lang" style="margin-top:5px;"><?php echo $ld[$v['Language']['locale']];?></span>
						<?php }?>
						<?php
							if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) !=""){
								echo $html->link(" ","javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));
							}
						?><span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;"><em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em></span>
						<div class="img_select" style="margin:5px;">
						<?php
							echo $html->image((isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']!="")?$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']:$configs['shop_default_img'],array('id'=>'show_upload_img_text_'.$v['Language']['locale'].'_'.$i));
						?>
						</div>
					<?php $i++;}?>
					</div>
				</div>	
				<?php } ?>	

				<?php if ($sub_group_info['Config']['type'] == "checkbox") {?>
			    	<div class="am-form-group">
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3  am-form-label" style="padding-top:12px;">
							<?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name']?>
						</label>
						
						<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
					<?php 	foreach ($backend_locales as $k => $v) { ?>
							<input  name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
							 <input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>"> <input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
							 <div class="am-u-lg-10 am-u-md-10 am-u-sm-10">
							<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['options']) && !empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['options'])) {
									$checkoptions = explode(';', $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']);
									$options = $sub_group_info['ConfigI18n'][$v['Language']['locale']]['options'];
									if(sizeof($options)>1){
										foreach ($options as $option) {
											$text = explode(":", $option);
											if (@$text[1] != "") { ?>
								<label class="am-checkbox am-success " >
									<input type="checkbox" data-am-ucheck name="data[<?php echo $i; ?>][value][]" value="<?php echo $text[0]; ?>" <?php if (in_array($text[0], $checkoptions))echo 'checked'; ?>/>
								</label>
								<?php if (@$text[1]) {
											echo $text[1];
										}}}}else{?>
								<label class="am-checkbox am-success" >		
									<input type="checkbox" data-am-ucheck  name="data[<?php echo $i; ?>][value]" value="1"
									<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])&&$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']==1) echo 'checked' ?>/>
								</label>
							<?php	}} ?>
							</div>
								<?php if(sizeof($backend_locales)>1){?>
									<label class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="margin-top:15px;"><?php echo $ld[$v['Language']['locale']];?></label>
								<?php }?>
								<?php
								if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) != ""){
									echo $html->link(" ", "javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));
								}
							?><span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;"><em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em></span>
				<?php $i++;}?>
				</div>
				</div>
				<?php } ?>
									
				<?php if ($sub_group_info['Config']['type'] == "file") {?>
			    <div class="am-form-group">
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name'];?></label>
						<div class="am-u-lg-6 am-u-md-7 am-u-sm-8">
						<?php foreach ($backend_locales as $k => $v) {?>
							<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
							<input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>">
							<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
							<input type="file" name="data[<?php echo $i; ?>][value]" id="data[<?php echo $i; ?>][value]" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']; ?>" onchange="checkUploadFile(this,'data[<?php echo $i; ?>][value]')"/>
							<?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']];?></span><?php }?>
							<?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) !=""){
									echo $html->link(" ","javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));
								}
							?>
							<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;"><em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em></span>
							<p><?php if(isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])){?>
								<?php if (!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['value'])){?>
									<img src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/favicon.ico'?>" width="20" height="20" />
								<?php }}?>
							</p>
					<?php $i++;}?>
						</div>
					</div>
					<?php } ?>
									
					<?php if($sub_group_info['Config']['type']=="select"){?>
			    	<div class="am-form-group">
					<?php if(($sub_group_info['Config']['code']=='water_text_font'||$sub_group_info['Config']['code']=='water_text_size'))continue;?>
					<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;">
						<?php echo $sub_group_info['ConfigI18n'][$backend_locale]['name']?>
					</label>
					<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
				 <?php foreach($backend_locales as $k=>$v){if(isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']) && !empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])){?>
					<?php if($sub_group_info['Config']['code']=="home_controller" && $v['Language']['locale']=="eng"){ echo "<div></div>";continue;}?>
					 	 	<input name="data[<?php echo $i; ?>][locale]" type="hidden" value="<?php echo $v['Language']['locale']; ?>">
					 	 	<input name="data[<?php echo $i; ?>][config_id]" type="hidden" value="<?php echo $sub_group_info['Config']['id']; ?>">
					 	 	<input name="data[<?php echo $i; ?>][id]" type="hidden" value="<?php if (isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']))echo $sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>">
					 	 <div class="am-u-lg-10 am-u-md-10 am-u-sm-10" style="margin-bottom:10px;">
						 	<select id="data<?php echo $i;?>" name="data[<?php echo $i;?>][value]">
						 		<?php if(isset($sub_group_info['ConfigI18n'][$v['Language']['locale']]['options']) && !empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['options'])){$options = explode(';',$sub_group_info['ConfigI18n'][$v['Language']['locale']]['options']);foreach($options as $option){$text =explode(":",$option);?>
						 			<option value="<?php echo $text[0];?>" <?php if($text[0]==$sub_group_info['ConfigI18n'][$v['Language']['locale']]['value']) echo 'selected';?>><?php if(@$text[1]){ echo $text[1];} ?></option>
					 	 		<?php }} ?>
					 	 	</select>
							<?php if($sub_group_info['Config']['code']=="home_controller" && isset($home_url)){?>
							<input id="home_url" disabled="disabled" name="home_url" type="text" value="<?php echo '/'.$home_url['Route']['controller'].'/'.$home_url['Route']['action'].'/'.($home_url['Route']['model_id']=='home'?'':$home_url['Route']['model_id']);?>"/>
							<?php }?>
							
						</div>
							<?php if(sizeof($backend_locales)>1){?>
								<label class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:20px;"><?php echo $ld[$v['Language']['locale']];?></label>
							<?php }?> 
							<?php if(!empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) && trim($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']) != ""){
							echo $html->link(" ", "javascript:config_help(".(empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']).")",array('escape'=>false,'class'=>'helpbtn'));
							}?>
							<span id="config_help_<?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['id'])?0:$sub_group_info['ConfigI18n'][$v['Language']['locale']]['id']; ?>" style="display:none;"><em><?php echo empty($sub_group_info['ConfigI18n'][$v['Language']['locale']]['description'])?'':$sub_group_info['ConfigI18n'][$v['Language']['locale']]['description']; ?></em></span> 
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
				 <?php $i++;}}?>
				 	</div>
				</div>
				 <?php }?>
							<!-- 循环遍历，判断类型 end -->
							<?php  }?>
						</div>
						<?php } ?>
						<!--子分组循环start -->
					</div>
					</div>
					</div>
					</div>
					</div><!--子标题end-->
				<?php }} ?>
				
				<?php if($svshow->operator_privilege("configvalues_edit")){?>
				<div class="btnouter" style="margin-top:50px;">
					<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
					<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
				</div>
				<?php }?>
				</div>
			</div>	
			<?php echo $form->end(); ?>
 		</div>
	<?php }?>
	</div>
</div>

<script type="text/javascript">
$(function(){
	$("#data2").change();
});
//首页控制器联动js
$("#data2").change(function(){
	var nav=$(this).val();
	$("#NavigationUrl").val("");
	$("#route_controller").val("");	
	if(nav=='pages'){
		$("#NavigationUrl").val("/pages/home");
		$("#route_controller").val("page");
		document.getElementById("Navigation").value='/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category").style.display='none';
		document.getElementById("a_category").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='p_categories'){
		$("#route_controller").val("categories");
		document.getElementById("Navigation").value='/categories/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category").style.display='block';
		document.getElementById("a_category").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='a_categories'){
		$("#route_controller").val("/articles/category");
		document.getElementById("Navigation").value='/categories/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category").style.display='none';
		document.getElementById("a_category").style.display='block';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='brands'){
		$("#route_controller").val("brands");
		document.getElementById("Navigation").value='/brands/view/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category").style.display='none';
		document.getElementById("a_category").style.display='none';
		document.getElementById("brand_div").style.display='block';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='topics'){
		$("#route_controller").val("topics");
		document.getElementById("Navigation").value='/topics/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category").style.display='none';
		document.getElementById("a_category").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='block';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='promotions'){
		$("#route_controller").val("brands");
		document.getElementById("Navigation").value='/brands/view/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category").style.display='none';
		document.getElementById("a_category").style.display='none';
		document.getElementById("brand_div").style.display='block';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='block';
	}	
	if(nav=='products'){
		$("#route_controller").val("products");
		document.getElementById("Navigation").value='/products/';
		document.getElementById("product_div").style.display='block';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category").style.display='none';
		document.getElementById("a_category").style.display='none';
		document.getElementById("brand_div").style.display='none';
	}
	if(nav=='articles'){
		$("#route_controller").val("articles");
		document.getElementById("Navigation").value='/articles/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='block';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category").style.display='none';
		document.getElementById("a_category").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='static_pages'){
		$("#route_controller").val("static_pages");
		document.getElementById("Navigation").value='/static_pages/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='block';
		document.getElementById("p_category").style.display='none';
		document.getElementById("a_category").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='sitemaps'){
		var url="/sitemaps/";
		$("#route_controller").val("sitemaps");
		changeUrl(url);
		document.getElementById("Navigation").value='/sitemaps/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category").style.display='none';
		document.getElementById("a_category").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='contacts'){
		var url="/contacts/";
		$("#route_controller").val("contacts");
		changeUrl(url);
		document.getElementById("Navigation").value='/contacts/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category").style.display='none';
		document.getElementById("a_category").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
	if(nav=='jobs'){
		var url="/jobs/";
		$("#route_controller").val("jobs");
		changeUrl(url);
		document.getElementById("Navigation").value='/jobs/';
		document.getElementById("product_div").style.display='none';
		document.getElementById("article_div").style.display='none';
		document.getElementById("static_page_div").style.display='none';
		document.getElementById("p_category").style.display='none';
		document.getElementById("a_category").style.display='none';
		document.getElementById("brand_div").style.display='none';
		document.getElementById("topic_div").style.display='none';
		document.getElementById("pro_div").style.display='none';
	}
});

function changeNa(id){
	var nav = document.getElementById("Navigation").value;
	var url = nav+id;
//	if($("#data2").val()=='p_categories' || $("#data2").val()=='a_categories'){
//		url='/categories/'+id;
//	}
	
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
	$.ajax({
		url:admin_webroot+"/navigations/search/"+a+'/'+key,
		type:"POST",
		data:{},
		dataType:"html",
		success:function(data){
			if(data.flag==1){
					if(a=='p'){
						$('#product_div').html(data.cat);
//						var node = Y.one('#product_div');
//						node.set('innerHTML', result.cat); 
						if(result.status==1){
						}
					}else if(a=='sp'){
						$('#static_page_div').html(data.cat);
//						var node = Y.one('#static_page_div');
//						node.set('innerHTML', result.cat); 
						if(result.status==1){
						}
					}else{
						$('#article_div').html(data.cat);
//						var node = Y.one('#article_div');
//						node.set('innerHTML', result.cat); 
						if(data.status==1){
						}
					}
				}
				if(data.flag==2){ 
					alert(data.message);
				}
	 }
	});
}
function changeUrl(url){
	$.ajax({
		url:admin_webroot+"/navigations/changeUrl/",
		type:"POST",
		data:{url:url},
		dataType:"json",
		success:function(data){
			if(data.flag==1){
				document.getElementById("NavigationUrl").value=data.url;
			}
			if(data.flag==2){ 
				alert(data.message);
			}
	 }
	});
}
//首页控制器end
$(".show_map").click(function(){
  //alert($(this).parent().find("input[type=text]").val());
  var position=$(this).parent().find("input[type=text]").val();
  var map_locale=$(this).parent().find("input[type=text]").attr("id");
  window.open(admin_webroot+"/maps?position="+position+"&map_locale="+map_locale, 'newwindow', 'height=600, width=1024, top=0, left=0, toolbar=no, menubar=yes, scrollbars=yes,resizable=yes,location=no, status=no');
});
function test_email(){
	var email_addr = document.getElementById('email_addr');
	var smtp_pass = document.getElementById('mail-password'+backend_locale);
	var smtp_host = document.getElementById('mail-smtp'+backend_locale);
	var smtp_user = document.getElementById('mail-account'+backend_locale);
	var smtp_port = document.getElementById('mail-port'+backend_locale);
    var smtp_auth= $('.mail-requires-authorization'+backend_locale+":checked");
    var smtp_ssl = 0;
	var mail_service = 1;
    smtp_ssl_value = smtp_ssl;
    mail_service_value = mail_service;
    
    var sUrl = admin_webroot+"configvalues/test_email/";
    $.ajax({ 
			url:sUrl,
			type:"POST",
			dataType:"json",
			data: {
                    'email_addr':email_addr.value,
                    'smtp_host':smtp_host.value,
                    'smtp_user':smtp_user.value,
                    'smtp_port':smtp_port.value,
                    'smtp_pass':smtp_pass.value,
                    'mail_service':mail_service_value,
                    'smtp_ssl_value':smtp_ssl_value,
                    'smtp_auth':$(smtp_auth).val()
                },
			success: function(data){
                try{
				    if(data==true){
                        alert("<?php echo $ld['congratulations_message_successfully_sent']?> "+document.getElementById('email_addr').value);
                    }else{
                        alert(data);
                    }
    			}catch (e){
    				alert(data);
    			}
			}
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
