<style type="text/css">
 .am-radio, .am-checkbox{margin-top:0px;margin-bottom:0px;display: inline-block;vertical-align: text-top;}
 .am-checkbox, .am-radio{margin-bottom:0px;}
 .am-radio input[type="radio"]{margin-left:0px;}
 .am-form-horizontal .am-radio{padding-top:0px;}
 .am-yes{color:#5eb95e;}
 .am-no{color:#dd514c;}
</style>
<div class="am-g">
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-detail-menu">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
			<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			<?php if(isset($product_style['ProductStyle']['id'])){?>
			<li><a href="#edition_specification"><?php echo $ld['edition_specification']?></a></li>
			<?php }?>	
		</ul>
	</div>
	<?php echo $form->create('ProductStyle',array('action'=>'view/'.(isset($product_style)?$product_style['ProductStyle']['id']:""),'name'=>'ProductStyleForm','onsubmit'=>'','class'=>'am-form-horizontal'));?>
		<input type="hidden" id="data[ProductStyle][id]"  name="data[ProductStyle][id]" value="<?php echo isset($product_style['ProductStyle']['id'])?$product_style['ProductStyle']['id']:0 ?>" />

		<div class="am-panel-group am-u-lg-10 am-u-md-9 am-u-sm-8 am-fr">
		<!--基本信息-->
			   <div id="basic_information" class="am-panel am-panel-default ">
				<div class="am-panel-hd">
						<h4 class="am-panel-title">
							<label><?php echo $ld['basic_information']?></label>
						</h4>
			    </div>
				<div class="am-in">
					<div class="am-panel-bd am-form">
						      <div class="am-g">
							<label class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-form-label-text"><?php echo $ld['name']?></label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
								<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
									<input type="hidden"  name="data[ProductStyleI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale'] ?>"><input type="text" id="product_style_name<?php echo $v['Language']['locale'];?>" name="data[ProductStyleI18n][<?php echo $k;?>][style_name]" value="<?php echo isset($product_style['ProductStyleI18n'][$v['Language']['locale']]['style_name'])?$product_style['ProductStyleI18n'][$v['Language']['locale']]['style_name']:'';?>" />
								</div>
									<?php if(sizeof($backend_locales)>1){?>
										<label class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-form-label  am-text-left" style="font-weight:normal;">
											<?php echo $ld[$v['Language']['locale']];?><em style="color:red;">*</em>
										</label>
									<?php }?>
								<?php }}?>
							</div>			
						</div>

						<div class="am-g">
							<label class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-form-label-text"><?php echo $ld['sort']?></label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<input class="input_sort" type="text" value="<?php echo isset($product_style['ProductStyle']['orderby'])?$product_style['ProductStyle']['orderby']:'50';?>" name="data[ProductStyle][orderby]">
								</div>
							</div>			
						</div>
						<div class="am-g"  style="margin-top:10px;">
							<label class="am-u-lg-1 am-u-md-2 am-u-sm-2" style="margin-left:10px;margin-top:2px;"><?php echo $ld['status']?></label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
								<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<label class="am-radio am-success">
										<input type="radio" name="data[ProductStyle][status]" data-am-ucheck value="1" <?php if((isset($product_style)&&$product_style['ProductStyle']['status']==1)||!isset($product_style)){?> checked<?php }?>>
										<?php echo $ld['yes']?>
									</label>&nbsp;&nbsp;
									<label class="am-radio am-success">
										<input type="radio" name="data[ProductStyle][status]" data-am-ucheck value="0" <?php if(isset($product_style)&&$product_style['ProductStyle']['status']==0){?> checked<?php }?>>
										<?php echo $ld['no']?>
									</label>
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
			<!--版型规格-->
			<?php if(isset($product_style['ProductStyle']['id'])){?>
				<div id="edition_specification" class="am-panel am-panel-default">
					<div class="am-panel-hd">
					    <h4 class="am-panel-title">
							<label><?php echo $ld['edition_specification']?></label>
						</h4>
					</div>			
					<div class="am-panel-collapse am-collapse am-in">
						<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
							<div>
								<?php if($svshow->operator_privilege("product_style_add")){?>
									<div class="am-text-right" style="margin-right:10px;margin-bottom:10px;">
										<a class="am-btn am-btn-warning am-btn-sm am-radius addbutton" href="<?php echo $html->url('/style_type_groups/view/?style_id='.$product_style['ProductStyle']['id']); ?>">
											<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
										</a> 
									</div>
								<?php }?>
								<div class="am-panel-group am-panel-tree">
									<div class="am-panel am-panel-default am-panel-header">
										<div class="am-panel-hd">
											<div class="am-panel-title">
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['number'] ?></div>
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">分组</div>
												<div class="am-u-lg-2 am-u-md-1 am-u-sm-2">规格</div>
												<div class="am-u-lg-2 am-u-md-1 am-u-sm-2"><?php echo $ld['sort']?></div>
												<div class="am-u-lg-2 am-u-md-1 am-u-sm-2"><?php echo $ld['status']?></div>
												<div class="am-u-lg-2 am-u-md-5 am-u-sm-2"><?php echo $ld['operate']?></div>
												<div style="clear:both;"></div>
											</div>
										</div>
									</div>
									<?php if(isset($style_type_group) && sizeof($style_type_group)>0){foreach($style_type_group as $sk=>$sv){ ?>			
										<div>	
											<div class="am-panel am-panel-default am-panel-body">
												<div class="am-panel-bd">
													<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $sv['StyleTypeGroup']['id']; ?>&nbsp;</div>
													<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
														<?php echo isset($sv['StyleTypeGroup']['group_code'])?$sv['StyleTypeGroup']['group_code']:'';?>&nbsp;
													</div>
													<div class="am-u-lg-2 am-u-md-1 am-u-sm-2"><?php echo $sv['StyleTypeGroup']['group_name'];?>&nbsp;</div>
													<div class="am-u-lg-2 am-u-md-1 am-u-sm-2"><?php echo $sv['StyleTypeGroup']['orderby'] ?>&nbsp;</div>
													<div class="am-u-lg-2 am-u-md-1 am-u-sm-2">
														<?php if($sv['StyleTypeGroup']['status']) {?>
															<span class="am-icon-check am-yes">&nbsp;</span>
														<?php }else{?>
															<span class="am-icon-close am-no">&nbsp;</span>
														<?php }?>&nbsp;
													</div>
													<div class="am-u-lg-2 am-u-md-5 am-u-sm-2">
														<?php if($svshow->operator_privilege("category_types_add")){
															echo $html->link($ld['edit'],"/style_type_groups/view/".$sv['StyleTypeGroup']['id'],array('class'=>'am-btn am-radius am-btn-default am-btn-sm')).'&nbsp;';}
														if($svshow->operator_privilege("category_types_add")){echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-radius am-btn-default am-text-danger am-btn-sm","onclick"=>"list_delete_submit('{$admin_webroot}style_type_groups/remove/{$sv['StyleTypeGroup']['id']}')"));}?>
													</div>
													<div style="clear:both;"></div>
												</div>
											</div>
										</div>
									<?php }}?>			
								</div>
							</div>
						</div>
					</div>
				</div>
			
			<?php }?>
		</div>
	<?php echo $form->end();?>
</div>