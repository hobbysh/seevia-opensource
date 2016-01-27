<?php echo $htmlSeevia->js(array("region")); ?>
<div class="am-cf am-user">
	<h3><?php echo $ld['edit_shipping_address'] ?></h3>
</div>
<div class="am-panel am-panel-default">
  <div class="am-panel-bd">
	<?php echo $form->create('/addresses',array('action'=>'show_edit/'.$address['UserAddress']['id'],'id'=>'add_address_form','name'=>'edit_address_act_update','class'=>'am-form am-form-horizontal','type'=>'POST','onsubmit'=>'return(check_form(this));'));?>
	<input type="hidden" name="data[address][user_id]" value="<?php echo $_SESSION['User']['User']['id'];?>"/>
	<input type="hidden" name="data[address][id]" value="<?php echo isset($address['UserAddress']['id'])?$address['UserAddress']['id']:'';?>"/>
	<div class="am-form-detail">
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['consignee'] ?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
		  <input type="text" name="data[address][consignee]"  value="<?php echo isset($address['UserAddress']['consignee'])?$address['UserAddress']['consignee']:'';?>"/>
		</div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld["region"]?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
		  <input type='hidden' id='local' value="<?php echo LOCALE;?>">
		  <span id="regionsupdate">
			<select style="width: 113px;"  gtbfieldid="1" name="region" id="region" onchange="reload_two_regions()" chkRules="region:<?php echo $ld['j_please_select']?>;">
			  <option><?php echo $ld['state_province']?></option>
			  <option>...</option>
			</select>
			<select style="width: 113px;" gtbfieldid="2" onchange="reload_two_regions()">
			  <option><?php echo $ld['city']?></option>
			  <option>...</option>
			</select>
			<select style="width: 113px;"  gtbfieldid="3" onchange="reload_two_regions()">
			  <option><?php echo $ld['counties']?></option>
			  <option>...</option>
			</select>
		  </span><!--<em><font color="red">*</font><font></font></em>-->
		</div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['address']?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
		  <input type="text" name="data[address][address]" chkRules="nnull:<?php echo $ld['address_empty']?>;" value="<?php echo isset($address['UserAddress']['address'])?$address['UserAddress']['address']:'';?>"/>
		  <em><font color="red">*</font><font><?php echo $ld['view_etailed_distribution_range']?></font></em>
		</div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['address_to']?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-9"><input type="text" name="data[address][sign_building]"  value="<?php echo isset($address['UserAddress']['sign_building'])?$address['UserAddress']['sign_building']:'';?>"/></div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['zip']?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
		  <input type="text" name="data[address][zipcode]" maxlength="6" chkRules="zip_code:<?php echo $ld['zipcode_incorrectly']?>"  value="<?php echo isset($address['UserAddress']['zipcode'])?$address['UserAddress']['zipcode']:'';?>"/>
		  <em><font color="red"></font><font></font></em>
		</div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['telephone']?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
		  <input type="text"  name="data[address][telephone]"  id="telephones" placeholder="021-56661111" chkRules="must_one:<?php echo $ld['mobile_and_fixed_telephone_at_least_one_required']?>:mobiles;tel:<?php echo $ld['telephone_incorrectly_completed']?>"  value="<?php echo isset($address['UserAddress']['telephone'])?$address['UserAddress']['telephone']:'';?>"/>
		  <em><font color="red">*</font><font><?php echo $ld['please_fill_in_the_code']?></font></em>
		</div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['mobile']?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
		  <input type="text" name="data[address][mobile]" id="mobiles"  chkRules="must_one:<?php echo $ld['mobile_and_fixed_telephone_at_least_one_required']?>:telephones;mobile:<?php echo $ld['phone_incorrectly_completed']?>"  value="<?php echo isset($address['UserAddress']['mobile'])?$address['UserAddress']['mobile']:'';?>"/>
		  <em><font color="red">*</font><font><?php echo $ld['mobile_and_fixed_telephone_at_least_one_required']?></font></em>
		</div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
		  <button type="submit" class="am-btn am-btn-primary"><?php echo $ld['save']?></button>
		</div>
	  </div>
	</div>
	<?php echo $form->end();?>
  </div>
</div>

<script type="text/javascript">
		var regions_add =  '';

	<?php if(!empty($address['UserAddress']['country'])){?>
		var regions_add =  '<?php echo $address['UserAddress']['country']." ".$address['UserAddress']['province']." ".$address['UserAddress']['city'];?>';
	<?php }else {?>
		var regions_add =  '';
	<?php }?>
	show_two_regions(regions_add);

	$(document).ready(function(){
		auto_check_form("add_address_form",false);
	});
	</script>
