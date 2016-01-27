<?php echo $htmlSeevia->js(array("region")); ?>
<div class="am-cf am-user">
	<h3><?php echo $ld['account_address_book'] ?></h3>
</div>
<div class="am-panel am-panel-default">
  <?php $i=1;if(isset($this->data['user_address']) && !empty($this->data['user_address'])) foreach($this->data['user_address'] as $k=>$v){ //pr($v['UserAddress']);?>
  <div class="am-panel-bd">
	<table cellpadding="0" cellspacing="0" style="margin-left:4.5rem">
		<tr>
			<td width="150"><?php echo $ld['consignee']?>: <?php echo $v["UserAddress"]['consignee'];?><br />
				<?php echo $ld['zip']?>: <?php echo $v["UserAddress"]['zipcode'];?></td>
			<td><?php echo $ld['delivery_address']?>: <?php echo $v['UserAddress']['country']." ".$v["UserAddress"]['province']." ".$v["UserAddress"]['city']." ".$v["UserAddress"]['district']." ".$v["UserAddress"]['address'];?>
			<?php if(isset($v['UserAddress']['sign_building'])&&!empty($v['UserAddress']['sign_building'])){?><br />
			<?php echo $ld['address_to']?>: <?php echo $v['UserAddress']['sign_building'];?>
			<?php }?>
			<?php if(!empty($v["UserAddress"]['mobile'])){ //pr($v["UserAddress"]);?><br />
				<?php echo !empty($v["UserAddress"]['mobile'])? $ld['mobile'].": ".$v["UserAddress"]['mobile']: $ld['telephone'].":".$v["UserAddress"]['telephone'];?>
			<?php } if(!empty($v["UserAddress"]['telephone'])){ //pr($v["UserAddress"]);?>
			<br /><?php echo !empty($v["UserAddress"]['telephone'])? $ld['telephone'].": ".$v["UserAddress"]['telephone']: $ld['telephone'].":".$v["UserAddress"]['telephone'];?>
			<?php }?>
			</td>
		</tr>
		<tr>
			<td >
			  <?php if(isset($userinfo)){ if($v['UserAddress']['id']!=$userinfo['User']['address_id']){?>
				<button type="button" class="am-btn am-btn-primary" style="margin-top:5px" onclick="window.location.href='<?php echo $html->url('/addresses/defaultaddress/'.$v['UserAddress']['id']); ?>'">
				  <?php echo $ld['set_as_default'];?>
				</button>
			  <?php }else{echo $ld['default_shipping_address'];}}?>
			</td>
			<td align="left" >
				<button type="button" class="am-btn am-btn-default" style="margin-top:5px" onclick="window.location.href='<?php echo $html->url('/addresses/show_edit/'.$v['UserAddress']['id']);?>'"><?php echo $ld['modify']?></button>
				<button type="button" class="am-btn am-btn-default am-address-del" style="margin-top:5px" onclick="window.location.href='<?php echo $html->url('/addresses/user_deladdress/'.$v['UserAddress']['id']);?>'"><?php echo $ld['delete']?></button>
			</td>
		</tr>
	</table>
  </div>
  <?php $i++;}?>
</div>
<div class="am-cf am-user" style="width:95%;height:40px;margin:0 auto; background:#F5F5F5;font-size:15px;padding:6px;color:#0e90d2;">
	<h4><?php echo $ld['edit_shipping_address'] ?></h4>
</div>
<div class="am-panel am-panel-default">
  <div class="am-panel-bd">
	<?php if(!(isset($configs['vip-address-num'])&& $configs['vip-address-num']!=""&& $num>=$configs['vip-address-num'])){?>
	<?php echo $form->create('/addresses',array('action'=>'show_edit','id'=>'address_form','name'=>'edit_address_act_update','type'=>'POST','onsubmit'=>'return(check_form(this));','class'=>'am-form am-form-horizontal'));?>
	<input type="hidden" name="data[address][user_id]" value="<?php echo $_SESSION['User']['User']['id'];?>"/>
	<input type="hidden" id="isNo" value="0"/>
	<div class="am-form-detail am-address">
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['consignee'] ?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
		  <input type="text" name="data[address][consignee]" chkRules="nnull:<?php echo $ld['consignee_not_empty']?>;"/>
		  <em><font color="red">*</font><font></font></em>
		</div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld["region"]?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
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
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['address']?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
		  <input class="" type="text" name="data[address][address]" chkRules="nnull:<?php echo $ld['address_empty']?>;" value="<?php echo isset($address['UserAddress']['address'])?$address['UserAddress']['address']:'';?>"/>
		  <em><font color="red">*</font><font><?php echo $ld['view_etailed_distribution_range']?></font></em>
		</div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['address_to']?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-8"><input class="" type="text" name="data[address][sign_building]"  value="<?php echo isset($address['UserAddress']['sign_building'])?$address['UserAddress']['sign_building']:'';?>"/></div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['zip']?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
		  <input type="text" name="data[address][zipcode]" maxlength='6' chkRules="zip_code:<?php echo $ld['zipcode_incorrectly']?>"  value="<?php echo isset($address['UserAddress']['zipcode'])?$address['UserAddress']['zipcode']:'';?>"/>
		  <em><font color="red"></font><font></font></em>
		</div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['telephone']?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
		  <input type="text"  name="data[address][telephone]"  id="telephones" placeholder="000-00000000" chkRules="must_one:<?php echo $ld['mobile_and_fixed_telephone_at_least_one_required']?>:mobiles;tel:<?php echo $ld['telephone_incorrectly_completed']?>"  value="<?php echo isset($address['UserAddress']['telephone'])?$address['UserAddress']['telephone']:'';?>"/>
		  <em><font color="red">*</font><font><?php echo $ld['please_fill_in_the_code']?></font></em>
		</div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['mobile']?></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
		  <input type="text" name="data[address][mobile]" id="mobiles"  chkRules="must_one:<?php echo $ld['mobile_and_fixed_telephone_at_least_one_required']?>:telephones;mobile:<?php echo $ld['phone_incorrectly_completed']?>"  value="<?php echo isset($address['UserAddress']['mobile'])?$address['UserAddress']['mobile']:'';?>"/>
		  <em><font color="red">*</font><font><?php echo $ld['mobile_and_fixed_telephone_at_least_one_required']?></font></em>
		</div>
	  </div>
	  <div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"></label>
		<div class="am-u-lg-8 am-u-md-9 am-u-sm-8">
		  <button type="submit" class="am-btn am-btn-primary"><?php echo $ld['user_save']?></button>
		</div>
	  </div>
	</div>
	<?php echo $form->end();?>
	<?php }else{?>
	<span><?php echo $ld['maximum_number_of_address_book']; ?></span>
	<?php }?>

  </div>
</div>
<script type="text/javascript">
var regions_add =  '';
	show_two_regions(regions_add);
	$(document).ready(function(){
		auto_check_form("address_form",false);
	});
</script>

