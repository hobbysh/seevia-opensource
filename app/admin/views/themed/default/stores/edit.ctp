<?php
/*****************************************************************************
 * SV-Cart 店铺管理
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。<?php
/*****************************************************************************
 * SV-Cart 新增实体店
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址：http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发：上海实玮$
 * $Id：add.ctp 2485 2009-06-30 11:33:00Z huangbo $
*****************************************************************************/
?>
<style>
.btnouter{margin:50px;}
.am-form-label{font-weight:bold;}
.am-radio, .am-checkbox{display:inline-block;}
.img_select{max-width:150px;max-height:120px;}
.am-checkbox input[type="checkbox"],.am-radio input[type="radio"]{margin-left:0px;}
.am-form-horizontal .am-form-label, .am-form-horizontal .am-radio, .am-form-horizontal .am-checkbox{padding-top:0px;}
</style>
<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div>
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-detail-menu">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
		   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			<li><a href="#detail_description"><?php echo $ld['detail_description']?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content am-detail-view" id="accordion" >
		<?php echo $form->create('Store',array('action'=>'edit','onsubmit'=>'return stores_check();','class'=>'am-form am-form-horizontal'));?>
			<div id="basic_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
		      			<input type="hidden" id="id" name="data[Store][id]" value="<?php  echo $stores_info['Store']['id'];?>"/>
						<?php if(isset($backend_locales) && sizeof($backend_locales)>0){
						foreach ($backend_locales as $k => $v){?>
							<input id="StoreI18n<?php echo $k;?>Locale" name="data[StoreI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
							<input id="StoreI18n<?php echo $k;?>Id" name="data[StoreI18n][<?php echo $k;?>][id]" type="hidden" value="<?php if(isset($stores_info['StoreI18n'][$v['Language']['locale']])){echo $stores_info['StoreI18n'][$v['Language']['locale']]['id'];}?>">
							<input id="StoreI18n<?php echo $k;?>StoreI18nId" name="data[StoreI18n][<?php echo $k;?>][store_id]" type="hidden" value="<?php echo $stores_info['Store']['id'];?>">
						<?php }}?>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:6px;"><?php echo $ld['operator']?></label>
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
								<?php if(isset($operators) && sizeof($operators)>0){foreach($operators as $ov){?>
									<label class="am-checkbox am-success" style="padding-top:0px;">
										<input type="checkbox" name="operator[]" data-am-ucheck value="<?php echo $ov['Operator']['id']?>" <?php if(in_array($ov['Operator']['id'],$store_operators)) echo 'checked';?> />
										<?php echo $ov['Operator']['name']?>
									</label>&nbsp;&nbsp;
								<?php }}?>
								</br><?php if($svshow->operator_privilege("operators_mgt")){echo $html->link($ld['operators'],"/operators",array("style"=>"text-decoration:underline;color:green;"));}?>
							</div>
						</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['shop_name']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
					    				<input type="text" class="border" id="name<?php echo $v['Language']['locale']?>" name="data[StoreI18n][<?php echo $k;?>][name]" value="<?php echo isset($stores_info['StoreI18n'][$v['Language']['locale']]['name'])?$stores_info['StoreI18n'][$v['Language']['locale']]['name']:''; ?>" />
					    			</div>
					    			<?php if(sizeof($backend_locales)>1){?>
					    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:20px;">
					    					<?php echo $ld[$v['Language']['locale']]?>&nbsp;<em style="color:red;">*</em>
					    				</label>
					    			<?php }?>
				    			<?php }} ?>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;" ><?php echo $ld['url']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
					    				<input type="text" class="border" id="url<?php echo $v['Language']['locale']?>" name="data[StoreI18n][<?php echo $k;?>][url]" value="<?php echo isset($stores_info['StoreI18n'][$v['Language']['locale']]['url'])?$stores_info['StoreI18n'][$v['Language']['locale']]['url']:''; ?>" />
					    			</div>
					    			<?php if(sizeof($backend_locales)>1){?>
					    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:20px">
					    					<?php echo $ld[$v['Language']['locale']]?>&nbsp;
					    				</label>
					    			<?php }?>
				    			<?php }} ?>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['meta_keywords']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
					    				<input type="text" class="border" id="StoreI18n<?php echo $k;?>meta_keywords" name="data[StoreI18n][<?php echo $k;?>][meta_keywords]" value="<?php echo  @$stores_info['StoreI18n'][$v['Language']['locale']]['meta_keywords'];?>" />
										<select style="width:90px;display:none" onchange="add_to_seokeyword(this,'StoreI18n<?php echo $k;?>meta_keywords')">
											<option value="<?php echo $ld['common_keywords']?>"><?php echo $ld['common_keywords']?></option>
											<?php foreach( $seokeyword_data as $sk=>$sv){?>
												<option value='<?php echo $sv["SeoKeyword"]["name"]?>'><?php echo $sv["SeoKeyword"]["name"]?></option>
											<?php }?>
										</select>
					    			</div>
					    			<?php if(sizeof($backend_locales)>1){?>
					    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:20px;">
					    					<?php echo $ld[$v['Language']['locale']]?>&nbsp;
					    				</label>
					    			<?php }?>
				    			<?php }} ?>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:25px;"><?php echo $ld['shop_seo_description']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
					    				<textarea  class="border" name="data[StoreI18n][<?php echo $k;?>][meta_description]"><?php if(isset($stores_info['StoreI18n'][$v['Language']['locale']])){ ?><?php echo  $stores_info['StoreI18n'][$v['Language']['locale']]['meta_description'];?><?php }else{?><?php }?></textarea>
					    			</div>
					    			<?php if(sizeof($backend_locales)>1){?>
					    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:25px;">
					    					<?php echo $ld[$v['Language']['locale']]?>&nbsp;
					    				</label>
					    			<?php }?>
				    			<?php }} ?>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['address']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
					    				<input type="text"  name="data[StoreI18n][<?php echo $k;?>][address]" value="<?php echo isset($stores_info['StoreI18n'][$v['Language']['locale']]['address'])?$stores_info['StoreI18n'][$v['Language']['locale']]['address']:''; ?>" />
					    			</div>
					    			<?php if(sizeof($backend_locales)>1){?>
					    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:22px;">
					    					<?php echo $ld[$v['Language']['locale']]?>&nbsp;
					    				</label>
					    			<?php }?>
				    			<?php }} ?>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['zip_code']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
					    				<input type="text" class="border" id="zipcode<?php echo $v['Language']['locale']?>" name="data[StoreI18n][<?php echo $k;?>][zipcode]" value="<?php echo isset($stores_info['StoreI18n'][$v['Language']['locale']]['zipcode'])?$stores_info['StoreI18n'][$v['Language']['locale']]['zipcode']:''; ?>" />
					    			</div>
					    			<?php if(sizeof($backend_locales)>1){?>
					    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:22px;">
					    					<?php echo $ld[$v['Language']['locale']]?>&nbsp;
					    				</label>
					    			<?php }?>
				    			<?php }} ?>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['shop_traffic']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
					    				<input type="text" name="data[StoreI18n][<?php echo $k;?>][transport]" id="upload_img_text_1<?php echo $k?>"  value="<?php echo isset($stores_info['StoreI18n'][$v['Language']['locale']]['transport'])?$stores_info['StoreI18n'][$v['Language']['locale']]['transport']:''; ?>" />
					    			</div>
					    			<?php if(sizeof($backend_locales)>1){?>
					    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:25px;">
					    					<?php echo $ld[$v['Language']['locale']]?>&nbsp;
					    				</label>
					    			<?php }?>
				    			<?php }} ?>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:22px;"><?php echo $ld['shop_map']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
					    				<input type="text"  name="data[StoreI18n][<?php echo $k;?>][map]"  id="upload_img_text_<?php echo $k?>" value="<?php echo isset($stores_info['StoreI18n'][$v['Language']['locale']]['map'])?$stores_info['StoreI18n'][$v['Language']['locale']]['map']:''; ?>" />
					    			</div>
					    			<?php if(sizeof($backend_locales)>1){?>
					    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:20px;">
					    					<?php echo $ld[$v['Language']['locale']]?>&nbsp;
					    				</label>
					    			<?php }?>
				    			<?php }} ?>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php printf($ld['coordinate'],"X");?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<input type="text" class="border" name="data[Store][X]" value="<?php echo empty($stores_info['Store']['X'])?"":$stores_info['Store']['X']; ?>" />
				    			</div>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php printf($ld['coordinate'],"Y");?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
					    		<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    			<input type="text" class="border" name="data[Store][Y]" value="<?php echo empty($stores_info['Store']['Y'])?"":$stores_info['Store']['Y']; ?>" />
					    		</div>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['picture']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-bottom:10px;">
					    				<input type="text" id="upload_img_text_1<?php echo $v['Language']['locale']?>" name="data[StoreI18n][<?php echo $k;?>][img01]" value="<?php echo @$stores_info['StoreI18n'][$v['Language']['locale']]['img01']?>" />
										<div class="img_select" onclick="select_img('upload_img_text_1<?php echo $v['Language']['locale']?>')" title="<?php echo $ld['choose_picture']?>" style="margin-top:10px;">
											<?php echo $html->image((isset($stores_info['StoreI18n'][$v['Language']['locale']]['img01'])&&$stores_info['StoreI18n'][$v['Language']['locale']]['img01']!="")?$stores_info['StoreI18n'][$v['Language']['locale']]['img01']:$configs['shop_default_img'],array('id'=>'show_upload_img_text_1'.$v['Language']['locale']))?>
										</div>
					    			</div>
					    			<?php if(sizeof($backend_locales)>1){?>
					    				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label am-text-left" style="font-weight:normal;padding-top:22px;">
					    					<?php echo $ld[$v['Language']['locale']]?>&nbsp;
					    				</label>
					    			<?php }?>
				    			<?php }} ?>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['type']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<select name="data[Store][store_type]" data-am-selected>
										<?php foreach( $Resource_info["store_type"] as $kk=>$vv ){ ?>
											<option value="<?php echo $kk;?>" <?php if($stores_info['Store']['store_type']==$kk){echo "selected";}?> >
												<?php echo $vv;?>
											</option>
										<?php }?>
									</select>
				    			</div>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['contacter']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<input type="text"  id="contact_name" class="border" name="data[Store][contact_name]" value="<?php echo $stores_info['Store']['contact_name'];?>" />
				    			</div>
				    			<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left"><em style="color:red;">*</em></label>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['shop_num']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<input type="text"  id="store_sn" class="border" onblur="operator_change()" name="data[Store][store_sn]" value="<?php echo $stores_info['Store']['store_sn']; ?>" />
				    			</div>
				    			<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left"><em style="color:red;">*</em></label>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;">Email</label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<input type="text"  id="contact_email" class="border" name="data[Store][contact_email]" value="<?php echo $stores_info['Store']['contact_email']; ?>" />
				    			</div>
				    			<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-left"><em style="color:red;">*</em></label>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['contacter_phone']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<input type="text"  class="border"  name="data[Store][contact_tele]" value="<?php echo $stores_info['Store']['contact_tele']; ?>" />
				    			</div>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['mobile']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<input type="text"  class="border" name="data[Store][contact_mobile]" value="<?php echo $stores_info['Store']['contact_mobile']; ?>" />
				    			</div>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['fax']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<input type="text"  class="border" name="data[Store][contact_fax]" value="<?php echo $stores_info['Store']['contact_fax']; ?>"/>
				    			</div>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['website']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<input type="text" class="border" name="data[Store][url]" value="<?php echo $stores_info['Store']['url']; ?>" />
				    			</div>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:20px;"><?php echo $ld['remarks_notes']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<textarea class="border" name="data[Store][remark]" ><?php echo $stores_info['Store']['remark']; ?></textarea>
				    			</div>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:15px;"><?php echo $ld['valid']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<label class="am-radio am-success">
				    					<input type="radio" data-am-ucheck value="1" name="data[Store][status]" <?php if($stores_info['Store']['status'] == 1){ echo "checked"; } ?>/><?php echo $ld['yes']?> 
				    				</label>&nbsp;&nbsp;
				    				<label class="am-radio am-success">
				    					<input type="radio"  data-am-ucheck name="data[Store][status]" value="0" <?php if($stores_info['Store']['status'] == 0){ echo "checked"; } ?>/><?php echo $ld['no']?>
				    				</label>
				    			</div>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:28px;"><?php echo $ld['shop_weekday_business_hours']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<textarea class="border" name="data[Store][workday_open_time]" ><?php echo $stores_info['Store']['workday_open_time']; ?></textarea>
				    			</div>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:28px;"><?php echo $ld['shop_weekend_opening_hours']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<textarea class="border" name="data[Store][wenkend_open_time]" ><?php echo $stores_info['Store']['wenkend_open_time']; ?></textarea>
				    			</div>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:15px;"><?php echo $ld['added_time']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<?php echo $stores_info['Store']['created']; ?>
				    			</div>
				    		</div>	
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:15px;"><?php echo $ld['last_modified']?></label>
			    			<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
				    				<?php echo $stores_info['Store']['modified']; ?>
				    			</div>
				    		</div>	
			    		</div>	
					</div>
					<div class="btnouter">
						<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
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
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
		             	<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
			    			<div class="am-u-lg-10 am-u-md-9 am-u-sm-9">
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
						<?php if($configs["show_edit_type"]){?>
		      				<div class="am-form-group">
		      					<div ><span class="ckeditorlanguage  "><?php echo $v['Language']['name'];?></span></div>
	      						<textarea cols="80" id="product_description_id<?php echo $v['Language']['locale'];?>" name="data[StoreI18n][<?php echo $k;?>][description]" rows="10" style="width:auto;height:300px;"><?php if (isset($stores_info['StoreI18n'][$v['Language']['locale']])){?><?php echo  $stores_info['StoreI18n'][$v['Language']['locale']]['description'];}?></textarea>
								<script>
								var editor;
								KindEditor.ready(function(K) {
								editor = K.create('#product_description_id<?php echo $v['Language']['locale'];?>', {width:'80%',
		                        langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
								});
								</script>
		      				</div>
		      			<?php }else{?>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $v['Language']['name'];?></label>
							<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
								<textarea cols="80" id="product_description_id<?php echo $v['Language']['locale'];?>" name="data[StoreI18n][<?php echo $k;?>][description]" rows="10"><?php if (isset($stores_info['StoreI18n'][$v['Language']['locale']])){?><?php echo  $stores_info['StoreI18n'][$v['Language']['locale']]['description'];}?></textarea>
									<?php echo $ckeditor->load("product_description_id".$v['Language']['locale']); ?>
							</div>
						<?php }?>
						<?php }}?>	</div><div class="am-cf"></div>
		      		</div>
		      		<div class="btnouter">
						<input type="submit"  class="am-btn am-btn-success am-btn-sm am-radius" value="<?php echo $ld['d_submit'];?>" />
						<input type="reset"  class="am-btn am-btn-default am-btn-sm am-radius"  value="<?php echo $ld['d_reset']?>" />
					</div>
		      	</div>
		     </div>	
		<?php echo $form->end();?>
	</div>
</div>
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
function stores_check(){

		if(document.getElementById('contact_name').value==''){
			alert("<?php echo $ld['store_contacter_empty']?>");
			return false;
		}
		if(document.getElementById('store_sn').value==''){
			alert("<?php echo $ld['store_shop_num_empty']?>");
			return false;
		}
		if(document.getElementById('contact_email').value==''){
			alert("<?php echo $ld['please_fill_user_email']?>");
			return false;
		}
		 var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
		 var email=document.getElementById('contact_email').value;
		 //alert(email);
		if(!myreg.test(email)){
 	 		alert("<?php echo $ld['enter_valid_email']?>");
 			return false;
 		}

		return true;
	}
function operator_change(){
	var store_sn = document.getElementById("store_sn").value;
	if(store_sn!=""){
       var store_sn=document.getElementById('store_sn');
       var id=document.getElementById('id').value;
       if(id==''){
       	   var id=0;
       }
		$.ajax({
			url:admin_webroot+"stores/act_view/"+id,
			type:"POST",
			data:{store_sn:store_sn.value},
			dataType:"json",
			success:function(data){
				if(data.code==1){

                 }else{
                      alert("<?php echo $ld['shop_id_already_exists']?>");
                 }
			}
		});
			
    /*       YUI().use("io",function(Y) {
           var store_sn=document.getElementById('store_sn');
           var id=document.getElementById('id').value;
           if(id==''){
           	   var id=0;
           }
           var sUrl = admin_webroot+"stores/act_view/"+id;
           var cfg = {
           method: "POST",
           data: "store_sn="+store_sn.value
           };
           var request = Y.io(sUrl, cfg);

           var handleSuccess = function(ioId, o){
                try{
                     eval('result='+o.responseText);
                     if(result.code==1){

                     }else{
                          alert("<?php echo $ld['shop_id_already_exists']?>");
                     }
                }catch(e){
                     alert("<?php echo $ld['object_transform_failed']?>");
                     alert(o.responseText);
                }
           }
           var handleFailure = function(ioId, o){
                alert("<?php echo $ld['asynchronous_request_failed']?>");
           }
           Y.on('io:success', handleSuccess);
           Y.on('io:failure', handleFailure);
      });*/
	}
          //return false;
 }
</script>

