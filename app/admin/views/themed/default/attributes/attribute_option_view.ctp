<style>
.am-form-horizontal .am-form-label{padding-top:6px;}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:0px;}
.am-form-label{font-weight:bold;}
.btnouter{margin:50px;}
.am-radio input[type="radio"]{margin-left:0px;}
.img_select{max-width:150px;max-height:120px;}
.am-form-group{margin-top:10px;}
</style>
<div class="am-g">
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
	    	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
		</ul>
	</div>
	<?php echo $form->create('Attributes',array('action'=>"/attribute_option_view/{$attribute_id}/".(isset($attribute_option_data['AttributeOption']['id'])?$attribute_option_data['AttributeOption']['id']:"0"),'onSubmit'=>'return check_attr_option();','name'=>'AttributeOptionForm','class'=>'am-form am-form-horizontal'));?>
				
	<input name="data[AttributeOption][id]" id="AttributeOption_id" type="hidden" value="<?php echo isset($attribute_option_data['AttributeOption']['id'])?$attribute_option_data['AttributeOption']['id']:'0';?>">
	<input name="data[AttributeOption][attribute_id]" type="hidden" value="<?php echo $attribute_id; ?>">
	<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
		<div id="basic_information" class="am-panel am-panel-default">
			<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<label><?php echo $ld['basic_information']?></label>
					</h4>
		    </div>
			<div class="am-panel-collapse am-collapse am-in">
				<div class="am-panel-bd">
					<div class="am-form-group">	
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:15px;"><?php echo $ld['language']?></label>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<select name="data[AttributeOption][locale]" data-am-selected >
	                            <?php foreach($option_language as $k=>$v){ ?>
	                            <option value="<?php echo $k; ?>" <?php echo isset($attribute_option_data['AttributeOption']['locale'])&&$attribute_option_data['AttributeOption']['locale']==$k?"selected":''; ?>><?php echo $v; ?></option>
	                            <?php } ?>
	                        </select>
						</div>
					</div>
					<div class="am-form-group">	
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:10px;"><?php echo $ld['option_name']?></label>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<input type="text" id="attr_option_name" class="am-form-field am-radius"   name="data[AttributeOption][option_name]" value="<?php echo isset($attribute_option_data['AttributeOption']['option_name'])?$attribute_option_data['AttributeOption']['option_name']:''; ?>" >
						</div>
						<label class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-top:10px;"><em style="color:red;">*</em></label>
					</div>
					<div class="am-form-group">	
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:10px;"><?php echo $ld['option_value']?></label>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<input type="text" id="attr_option_value" class="am-form-field am-radius"   name="data[AttributeOption][option_value]" value="<?php echo isset($attribute_option_data['AttributeOption']['option_value'])?$attribute_option_data['AttributeOption']['option_value']:''; ?>" >
						</div>
					</div>
					<?php if($attribute['Attribute']['type']=='customize'){ ?>
                    <div class="am-form-group">	
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" ><?php echo $ld['price']?></label>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<input type="text" name="data[AttributeOption][price]" class="am-form-field am-radius"   value="<?php echo isset($attribute_option_data['AttributeOption']['price'])?$attribute_option_data['AttributeOption']['price']:'0.00'; ?>" >
						</div>
					</div>
                    <?php } ?>
					<div class="am-form-group">	
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['thumbnail']?>1</label>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<input id="attribute_option_image1" type="text" name="data[AttributeOption][attribute_option_image1]" value="<?php echo isset($attribute_option_data['AttributeOption']['attribute_option_image1'])?$attribute_option_data['AttributeOption']['attribute_option_image1']:'';?>" />
							
							<input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('attribute_option_image1')" value="<?php echo $ld['choose_picture']?>" style="margin-top:5px;"/>&nbsp;
	                        
	                        <div class="img_select" style="margin:5px;">
								<?php echo $html->image((isset($attribute_option_data['AttributeOption']['attribute_option_image1'])&&$attribute_option_data['AttributeOption']['attribute_option_image1']!="")?$attribute_option_data['AttributeOption']['attribute_option_image1']:$configs['shop_default_img'],array('id'=>'show_attribute_option_image1'))?>
							</div>
						</div>
					</div>
					<div class="am-form-group">	
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['thumbnail']?>2</label>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<input id="attribute_option_image2" type="text" name="data[AttributeOption][attribute_option_image2]" value="<?php echo isset($attribute_option_data['AttributeOption']['attribute_option_image2'])?$attribute_option_data['AttributeOption']['attribute_option_image2']:'';?>" />
							
							<input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('attribute_option_image2')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>&nbsp;
	                        
	                        <div class="img_select" style="margin:5px;">
								<?php echo $html->image((isset($attribute_option_data['AttributeOption']['attribute_option_image2'])&&$attribute_option_data['AttributeOption']['attribute_option_image2']!="")?$attribute_option_data['AttributeOption']['attribute_option_image2']:$configs['shop_default_img'],array('id'=>'show_attribute_option_image2'))?>
							</div>
						</div>
					</div>
					<div class="am-form-group">	
						<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:4px;"><?php echo $ld['status']?></label>
						<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
							<label class="am-radio am-success">
								<input type="radio" data-am-ucheck value="1" name="data[AttributeOption][status]" <?php echo !isset($attribute_option_data['AttributeOption']['status'])||(isset($attribute_option_data['AttributeOption']['status'])&&$attribute_option_data['AttributeOption']['status']==1)?"checked":""; ?> />
								<?php echo $ld['yes']?>
							</label>&nbsp;&nbsp;
							<label class="am-radio am-success">
								<input type="radio" data-am-ucheck value="0" name="data[AttributeOption][status]" <?php echo isset($attribute_option_data['AttributeOption']['status'])&&$attribute_option_data['AttributeOption']['status']==0?"checked":"";?> />
								<?php echo $ld['no']?>
							</label>
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
				
	<?php echo $form->end();?>
</div>
<script type="text/javascript">
function check_attr_option(){
    var attr_option_name=document.getElementById("attr_option_name").value;
    var attr_option_value=document.getElementById("attr_option_value").value;
    if(attr_option_name==""){
        alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['option_name']) ?>");
        return false;
    }
    if(attr_option_value==""){
        alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['option_value']) ?>");
        return false;
    }
    return true;
}
</script>