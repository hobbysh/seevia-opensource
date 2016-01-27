<style type="text/css">
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
.am-radio input[type="radio"]{margin-left:0px;}
.btnouter{margin:50px;}
 
</style>
<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-g">
	<!--左侧菜单-->
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-detail-menu">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
	    	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
	    	<li><a href="#detail_description"><?php echo $ld['detail_description']?></a></li>
	    	<?php if(!empty($attribute['Attribute'])){?><li><a href="#option_list"><?php echo $ld['option_list']?></a></li><?php }?>
		</ul>
	</div>
	
	<?php echo $form->create('Attributes',array('action'=>'/view/'.(isset($attribute['Attribute']['id'])?$attribute['Attribute']['id']:"0"),'name'=>'AttributesForm'));?>
	<input name="data[Attribute][id]" id="attr_id" type="hidden" value="<?php echo isset($attribute['Attribute']['id'])?$attribute['Attribute']['id']:'0';?>">
	<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
	<input name="data[AttributeI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
	<?php }}?>
	<div class="am-panel-group   am-detail-view" id="accordion"  >
		<!--基本信息-->
		<div id="basic_information" class="am-panel am-panel-default">
			<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<label><?php echo $ld['basic_information']?></label>
					</h4>
		    </div>
			<div class="am-panel-collapse am-collapse am-in">
				<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                    
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $ld['attribute_name']?></label>
						<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
						<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">
							<input type="text" name="data[AttributeI18n][<?php echo $k?>][name]" id="productstypeattr_name_<?php echo $v['Language']['locale'];?>" value="<?php echo isset($attribute['AttributeI18n'][$v['Language']['locale']]['name'])?$attribute['AttributeI18n'][$v['Language']['locale']]['name']:'';?>" />
						</div>
							<?php if(sizeof($backend_locales)>1){?>
								<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-group-label am-text-left" style="font-weight:normal;">
									<?php echo $ld[$v['Language']['locale']];?><em style="color:red;">*</em>
								</label>
							<?php }?>
						<?php }}?>
						</div>
					</div>			
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label" ><?php echo $ld['attribute_code']?></label>
						<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<input type="text" class="am-form-field am-radius"  name="data[Attribute][code]" id="attr_code"  value="<?php echo isset($attribute['Attribute']['code'])?$attribute['Attribute']['code']:'';?>" />
							</div>	
							<label class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:20px;" ><em style="color:red;">*</em></label>	
						</div>
					</div>			
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $ld['attribute_type']?></label>	
						<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">	
								<select name="data[Attribute][type]" data-am-selected>
									<?php foreach($Resource_info["property_type"] as $k=>$v){?>
									<option value="<?php echo $k?>" <?php if(isset($attribute['Attribute']['type'])&&$attribute['Attribute']['type']==$k){echo "selected";}?> ><?php echo $v?></option>
									<?php }?>
								</select>
							</div>
						</div>
					</div>			
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['optional_attribute']?></label>
						<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">			
								<label class="am-radio am-success">
								<input type="radio" value="1" data-am-ucheck name="data[Attribute][attr_type]" <?php echo !isset($attribute['Attribute']['attr_type'])||(isset($attribute['Attribute']['attr_type'])&&$attribute['Attribute']['attr_type']==1)?"checked":""; ?>  />
								<?php echo $ld['yes']?></label>&nbsp;&nbsp;&nbsp;&nbsp;
							<label class="am-radio am-success">
								<input type="radio" value="0" data-am-ucheck name="data[Attribute][attr_type]" <?php echo isset($attribute['Attribute']['attr_type'])&&$attribute['Attribute']['attr_type']==0?"checked":"";?> />
								<?php echo $ld['no']?></label>&nbsp;&nbsp;&nbsp;&nbsp;
							<label class="am-radio am-success">
								<input type="radio" value="2" data-am-ucheck name="data[Attribute][attr_type]" <?php echo isset($attribute['Attribute']['attr_type'])&&$attribute['Attribute']['attr_type']==2?"checked":"";?> />
								<?php echo $ld['custom']?></label>
							</div>
						</div>
					</div>		
					<div class="am-form-group" style="margin-bottom:10px;">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label"><?php echo $ld['attribute_input_type']?></label>
						<div class="am-u-lg-7 am-u-md-9 am-u-sm-9">
							<div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
								<div class="am-form-group">		
								<label class="am-radio am-success" style="padding-top:2px">
									<input type="radio" value="0" data-am-ucheck name="data[Attribute][attr_input_type]" <?php echo !isset($attribute['Attribute']['attr_input_type'])||(isset($attribute['Attribute']['attr_input_type'])&&$attribute['Attribute']['attr_input_type']==0)?"checked":""; ?> >
									<?php echo $ld['text_input']?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label class="am-radio am-success" style="margin-left:14px;padding-top:2px;">
									<input type="radio" value="1" data-am-ucheck name="data[Attribute][attr_input_type]" <?php echo isset($attribute['Attribute']['attr_input_type'])&&$attribute['Attribute']['attr_input_type']==1?"checked":""; ?> >
									<?php echo $ld['select_following_list']?>
								</label>&nbsp;
								</div>
								<div class="am-form-group">	
								<label class="am-radio am-success" style="padding-top:2px">
									<input type="radio" value="2" data-am-ucheck name="data[Attribute][attr_input_type]" <?php echo isset($attribute['Attribute']['attr_input_type'])&&$attribute['Attribute']['attr_input_type']==2?"checked":""; ?> >
									<?php echo $ld['attribute_type_textarea']?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label class="am-radio am-success" style="padding-top:2px">
									<input type="radio" value="3" data-am-ucheck name="data[Attribute][attr_input_type]" <?php echo isset($attribute['Attribute']['attr_input_type'])&&$attribute['Attribute']['attr_input_type']==3?"checked":""; ?> >
									<?php echo $ld['upload_oneself']?>
								</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<label class="am-radio am-success" style="padding-top:2px">
									<input type="radio" value="4" data-am-ucheck name="data[Attribute][attr_input_type]" <?php echo isset($attribute['Attribute']['attr_input_type'])&&$attribute['Attribute']['attr_input_type']==4?"checked":""; ?> >
									<?php echo $ld['date']?>
								</label>&nbsp;&nbsp;
								</div>
							</div>
						</div>
					</div>			
					<div class="am-form-group" >
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label"><?php echo $ld['default_value']?></label>
						<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
						<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">	
								<input type="text" name="data[AttributeI18n][<?php echo $k?>][default_value]" id="productstypeattr_default_value_<?php echo $v['Language']['locale'];?>" value="<?php echo isset($attribute['AttributeI18n'][$v['Language']['locale']]['default_value'])?$attribute['AttributeI18n'][$v['Language']['locale']]['default_value']:'';?>" />
							</div>
							<?php if(sizeof($backend_locales)>1){?>
								<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label am-text-left" style="font-weight:normal;"><?php echo $ld[$v['Language']['locale']]?></label>
							<?php }?>
						<?php }}?>		
						</div>
					</div>
                         
					<div class="am-form-group">
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-view-label" style="margin-top:17px;"><?php echo $ld['valid']?></label>
						<div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
							<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
								<label class="am-radio am-success" >
								<input type="radio" data-am-ucheck name="data[Attribute][status]" value="1" <?php echo !isset($attribute['Attribute']['status'])||(isset($attribute['Attribute']['status'])&&$attribute['Attribute']['status']==1)?"checked":""; ?> />
								<?php echo $ld['yes']?></label>&nbsp;&nbsp;&nbsp;&nbsp;
							<label class="am-radio am-success">
								<input type="radio" data-am-ucheck name="data[Attribute][status]" value="0" <?php echo isset($attribute['Attribute']['status'])&&$attribute['Attribute']['status']==0?"checked":"";?> />
								
								<?php echo $ld['no']?></label>
							</div>
						</div>
					</div>
							 
						<div  class="btnouter">			
				     	<button type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="" onclick="attr_input_checks()"><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
					     </div> 
				</div>		
			</div>
		</div>
		<!--详细描述-->	
		<div id="detail_description" class="am-panel am-panel-default" >
			<div class="am-panel-hd">
			    <h4 class="am-panel-title">
					<label><?php echo $ld['detail_description']?></label>
				</h4>
			</div>			
			<div class="am-panel-collapse am-collapse am-in">
			    <div class="am-panel-bd ">
			       <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-group-label"> </label>
			         	<div class="  am-u-lg-10 am-u-md-9 am-u-sm-9">
				    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
					<?php
						if($configs["show_edit_type"]){?>
						<div class="am-form-group">
						<div ><span class="ckeditorlanguage  "><?php echo $v['Language']['name'];?></span></div>
							<textarea cols="80" id="pta<?php echo $v['Language']['locale'];?>" name="data[AttributeI18n][<?php echo $k?>][description]" rows="10" style="width:auto;height:300px;">
							<?php echo isset($attribute['AttributeI18n'][$v['Language']['locale']]['description'])?$attribute['AttributeI18n'][$v['Language']['locale']]['description']:"";?>
							</textarea>
							<script>
							var editor;
							KindEditor.ready(function(K) {
							editor = K.create('#pta<?php echo $v['Language']['locale'];?>', {width:'80%',
	                        langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
							});
							</script>
						</div>
						<?php }else{?>
							<div class="am-form-group">
								<?php echo $v['Language']['name'];?>
								<textarea cols="80" id="pta<?php echo $v['Language']['locale'];?>" name="data[AttributeI18n][<?php echo $k?>][description]" rows="10"><?php echo isset($attribute['AttributeI18n'][$v['Language']['locale']]['description'])?$attribute['AttributeI18n'][$v['Language']['locale']]['description']:"";?></textarea> 	<?php echo $ckeditor->load("pta".$v['Language']['locale']); ?>
							</div>
					<?php }?>
					<?php }}?>
				 </div><div class="am-cf"></div>
				 	 <div>
									  
					<div class="btnouter" >
						 <button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
					 </div>
											 
					 </div>			
			    </div>
			</div>
		</div>
		<?php if(!empty($attribute['Attribute'])){ ?>
			    <div id="option_list" class="am-panel am-panel-default">
						<div class="am-panel-hd">
						    <h4 class="am-panel-title">
								<label><?php echo $ld['option_list']?></label>
							</h4>
						</div>			
						<div class="am-panel-collapse am-collapse am-in">
							    <div id="attribute_option_list" class="am-panel-bd ">
					                 </div>
				           </div>
			    </div>
    	<?php } ?>	
	</div>
	<?php echo $form->end();?>
</div>
<script type="text/javascript">
function attr_input_checks(){
	var attr_name_obj = document.getElementById("productstypeattr_name_"+backend_locale);
	
	if(attr_name_obj.value==""){
		alert("<?php echo $ld['enter_attribute_name']?>");
		return false;
	}
	var attr_id=document.getElementById("attr_id").value;
	var attr_code=document.getElementById("attr_code").value;
	check_unique(attr_id,attr_code)
}
function check_unique(attr_id,attr_code){
	$.ajax({
		url:"/admin/attributes/check_attr_unique/",
		type:'POST',
		dataType:"json",
		data:"attr_code="+attr_code+"&attr_id="+attr_id,
		success:function(data){
				if(data.code==1){
					document.AttributesForm.submit();
				}else{				
					alert(data.msg);
				}
			}
	});
}	
loadAttrOption();
function loadAttrOption(){
    var attr_id=document.getElementById("attr_id").value;
    if(attr_id!="0"){
    	$.ajax({
    		url:"/admin/attributes/attribute_option/"+attr_id,
    		type:"POST",
    		dataType:"html",
    		data:{ },
    		success:function(data){
                $("#attribute_option_list").html(data);
    		    $("input[type='checkbox']").uCheck();
    		}
    	});
    }
}
</script>
