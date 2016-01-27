<?php 
/*****************************************************************************
 * SV-Cart 编辑实商店设置
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
?>
<style>
	.am-radio input[type="radio"]{margin-left:0px;}
	.am-radio, .am-checkbox{display:inline-block;}
</style>
<div class="am-g">
	<!--左边菜单-->
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 ">
		<ul class="am-list admin-sidebar-list">
	    	<li><a data-am-collapse="{parent: '#accordion'}" href="#shop_configs">
				<?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['edit'].' '.$ld['shop_configs']:$ld['edit'].$ld['shop_configs'];?>
				</a>
			</li>
		</ul>
	</div>
	<?php echo $form->create('Config',array('action'=>'/view/','onsubmit'=>'return userconfigs_check();'));?>
		<div class="am-panel-group admin-content" id="accordion">
			<div class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#shop_configs'}">
						<label>
							<?php echo isset($backend_locale)&&$backend_locale=='eng'?$ld['edit'].' '.$ld['shop_configs']:$ld['edit'].$ld['shop_configs'];?>
						</label>
					</h4>
		    	</div>
		    	<div id="shop_configs" class="am-panel-collapse am-collapse am-in">
		    		<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					<input name="data[ConfigI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
					<?php if(isset($configs_info['Config']['code'])&&$configs_info['Config']['code']!="google-js"){?>
					<input name="data[ConfigI18n][<?php echo $k;?>][value]" type="hidden" value="<?php if(isset($configs_info['ConfigI18n'][$k]['value'])){ echo $configs_info['ConfigI18n'][$k]['value']; }?>">
					<?php }else{?>
					<textarea style="display:none" id="configs_hid_value_<?php echo $v['Language']['locale'];?>" name="data[ConfigI18n][<?php echo $k;?>][value]" ><?php if(isset($configs_info['ConfigI18n'][$k]['value'])){ echo $configs_info['ConfigI18n'][$k]['value']; }?></textarea>
					<?php }}}?>
	  				<input type="hidden" name="data[Config][id]" value="<?php if(isset($configs_info) &&!empty($configs_info)){ echo $configs_info['Config']['id'];}else{ echo '';}?>" />
	  				<input type="hidden" name="data[Config][store_id]" value="<?php if(isset($configs_info)&&!empty($configs_info)){echo $configs_info['Config']['store_id'];}else{ echo '0';}?>" />
					<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['group'];?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach($backend_locales as $k => $v){?>	
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
									<input type="text" id="config_group" name="data[Config][group_code]" value="<?php if(isset($configs_info)&&!empty($configs_info)){echo $configs_info['Config']['group_code'];}?>" />
								</div>
								<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><em>*</em></div>
	                            <?php }}?>
	                        </div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['subparameter'];?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input type="text" id="config_group" name="data[Config][subgroup_code]" value="<?php if(isset($configs_info)&&!empty($configs_info)){echo $configs_info['Config']['subgroup_code'];}?>" />
								</div>	
								<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><em>*</em></div>	
							</div>
						</div>		
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['code'];?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input type="text" id="config_code" name="data[Config][code]"  value="<?php if(isset($configs_info)&&!empty($configs_info)){echo $configs_info['Config']['code'];}?>"/>
								</div>	
								<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><em>*</em></div>	
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">
								<?php echo isset($backend_locale)&&$backend_locale=='eng'?'HTML '.$ld['type']:'HTML'.$ld['type'];?>:
							</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
									<select name="data[Config][type]" data-am-selected  onchange="selectClicked(this.value)" id="ConfigType">
										<option value="text" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="text"){echo "selected";} ?> >text</option>
										<option value="radio" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="radio"){echo "selected";} ?> >radio</option>
										<option value="select" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="select"){echo "selected";} ?> >select</option>
										<option value="checkbox" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="checkbox"){echo "selected";} ?> >checkbox</option>
										<option value="textarea" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="textarea"){echo "selected";} ?> >textarea</option>
										<option value="image" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="image"){echo "selected";} ?> >image</option>
										<option value="hidden" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="hidden"){echo "selected";} ?> >hidden</option>
										<option value="map" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="map"){echo "selected";} ?> >map</option>
										<option value="send_email_test" <?php if(!empty($configs_info)&&$configs_info['Config']['type']=="send_email_test"){echo "selected";} ?> >send email test</option>
									</select>	
								</div>
							</div>	
						</div>			
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['versions']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input type="text" id="config_section" name="data[Config][section]"  value="<?php if(isset($configs_info)&&!empty($configs_info)){echo $configs_info['Config']['section'];}?>" />
								</div>		
							</div>
						</div>								
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['name'];?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
									<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
										<input id="configs_name_<?php echo $v['Language']['locale'];?>" name="data[ConfigI18n][<?php echo $k;?>][name]" type="text" value="<?php echo isset($configs_info['ConfigI18n'][$v['Language']['locale']])?$configs_info['ConfigI18n'][$v['Language']['locale']]['name']:'';?>">
									</div>
									<?php if(sizeof($backend_locales)>1){?>
										<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left"><?php echo $ld[$v['Language']['locale']]?>&nbsp;<em>*</em></div>
									<?php }?>
								<?php }}?>	
							</div>
						</div>		
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['default_value']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
									<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
										<textarea id="configs_value_<?php echo $v['Language']['locale'];?>" name="data[ConfigI18n][<?php echo $k;?>][default_value]" >
										<?php if(isset($configs_info['ConfigI18n'][$k]['default_value'])){ echo $configs_info['ConfigI18n'][$k]['default_value']; }?>
										</textarea>
									</div>
									<?php if(sizeof($backend_locales)>1){?>
										<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld[$v['Language']['locale']]?></div>
									<?php }?>
								<?php }}?>		
							</div>
						</div>	
						<div class="am-form-group option_textarea" style="<?php if(isset($configs_info['Config'])&&($configs_info['Config']['type'] == 'radio' || $configs_info['Config']['type'] == 'select'|| $configs_info['Config']['type'] == 'checkbox')){echo '';}else{echo 'display:none;';} ?>">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['option_list']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
									<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">	
										<dl><dd>
										<textarea id="option_textarea"  name="data[ConfigI18n][<?php echo $k;?>][options]" id="ConfigI18n<?php echo $k;?>Options"><?php if(isset($configs_info['ConfigI18n'][$v['Language']['locale']]['options'])){ echo $configs_info['ConfigI18n'][$k]['options']; }?>
										</textarea></dd></dl>
									</div>
									<?php if(sizeof($backend_locales)>1){?>
										<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld[$v['Language']['locale']]?></div>
									<?php }?>		
								<?php }}?>	
							</div>
						</div>
									
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['description']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
									<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">	
										<input type="text" id="config_section" name="data[Config][section]"  value="<?php if(isset($configs_info)&&!empty($configs_info)){echo $configs_info['Config']['section'];}?>" />
									</div>	
								<?php }}?>	
							</div>	
						</div>
							
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:0;"><?php echo $ld['readonly']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
									<label class="am-radio am-success" style="padding-top: 0;"><input type="radio" name="data[Config][readonly]" data-am-ucheck value="1" <?php if( !empty($configs_info) && $configs_info['Config']['readonly'] == 1 ){ echo "checked"; } ?> /><?php echo $ld['yes']?></label>&nbsp;&nbsp;
									<label class="am-radio am-success" style="padding-top: 0;"><input type="radio" name="data[Config][readonly]" data-am-ucheck value="0" <?php if( !empty($configs_info) && $configs_info['Config']['readonly'] == 0 ){ echo "checked"; } ?> /><?php echo $ld['no']?></label>	
								</div>	
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:0;"><?php echo $ld['valid']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
									<label class="am-radio am-success" style="padding-top: 0;">
										<input type="radio" name="data[Config][status]" data-am-ucheck value="1" <?php if(isset($configs_info['Config']['status']) && $configs_info['Config']['status'] == 1){ echo "checked";} ?> /><?php echo $ld['yes']?>
									</label>&nbsp;&nbsp;
									<label class="am-radio am-success" style="padding-top:0px;">
										<input type="radio" name="data[Config][status]" data-am-ucheck value="0" <?php if(isset($configs_info['Config']['status']) && $configs_info['Config']['status'] != 1){ echo "checked";} ?> /><?php echo $ld['no']?>
									</label>
								</div>	
							</div>
						</div>				
						<div class="am-form-group">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['sort']?>:</label>
							<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
									<input type="text" id="config_section" name="data[Config][orderby]" onkeyup="check_input_num(this)"  value="<?php if(isset($configs_info)&&!empty($configs_info)){echo $configs_info['Config']['orderby'];}?>" /><?php echo $ld['role_sort_default_num']?>
								</div>	
							</div>
						</div>
						<div class="btnouter">
							<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value="">
								<?php echo $ld['d_submit'];?>
							</button>
							<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" >
								<?php echo $ld['d_reset']?>
							</button>
						</div>			
					</div>
				</div>
			</div>
		</div>
	<?php echo $form->end();?>
</div>	
<script type="text/javascript">

function selectClicked(htmlType){
    send_style=document.getElementById('option_textarea');
    if(htmlType == 'text'|| htmlType == 'textarea' || htmlType == 'checkbox'|| htmlType == 'image'){
		$(".option_textarea").hide();
    }
    else if(htmlType == 'radio'|| htmlType == 'select'){
		$(".option_textarea").show();
    }

}
</script>