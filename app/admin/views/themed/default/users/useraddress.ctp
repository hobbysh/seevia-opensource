<?php if($type=="list"){ ?>
<div id="user_addr_ajaxdata">
	<p style="text-align:right;padding-right:1.6rem;">
	  <a class="am-btn am-btn-warning am-radius am-btn-sm" href="javascript:void(0);" onclick="editaddr(0)"><?php echo $ld['add']?></a>
	</p>
	<table class="am-table  table-main">
		<thead>
			<tr>
				<th width="10%"><?php echo $ld['consignee']?></th>
				<th width="30%"><?php echo $ld['address']?></th>
				<th width="10%"><?php echo $ld['contact']?></th>
				<th width="30%"><?php echo $ld['remarks_notes']?></th>
				<th width="20%"><?php echo $ld['operate']?></th>
			</tr>
		</thead>
		<tbody>
		<?php if(isset($user_address)&&sizeof($user_address)>0){ foreach($user_address as $v){ ?>
			<tr>
				<td><?php echo $v['UserAddress']['consignee']; ?></td>
				<td><?php 
						$countryinfo=isset($regions_list[$v['UserAddress']['country']])?$regions_list[$v['UserAddress']['country']]:'';
						$provinceinfo=isset($regions_list[$v['UserAddress']['province']])?$regions_list[$v['UserAddress']['province']]:'';
						$cityinfo=isset($regions_list[$v['UserAddress']['city']])?$regions_list[$v['UserAddress']['city']]:'';
						$districtindo=isset($regions_list[$v['UserAddress']['district']])?$regions_list[$v['UserAddress']['district']]:'';
						echo $countryinfo.$provinceinfo.$cityinfo.$districtindo;
					?><br ><?php echo $v['UserAddress']['address']; ?><br ><?php echo $v['UserAddress']['zipcode']; ?></td>
				<td><?php echo $v['UserAddress']['mobile']; ?><br /><?php echo $v['UserAddress']['telephone']; ?><br /><?php echo $v['UserAddress']['email']; ?></td>
				<td><?php echo $ld['best_delivery_time'] ?>:<br /><?php echo $v['UserAddress']['best_time']; ?><br /><?php echo $ld['address_to'] ?>:<br /><?php echo $v['UserAddress']['sign_building']; ?></td>
				<td><a href="javascript:void(0);" class="am-btn am-btn-default am-radius am-btn-sm am-text-secondary" onclick="editaddr(<?php echo $v['UserAddress']['id'] ?>)"><?php echo $ld['edit'] ?></a>&nbsp;<a href="javascript:void(0);" class="am-btn am-btn-default am-radius am-btn-sm am-text-secondary" onclick="deladdr(<?php echo $v['UserAddress']['id'] ?>)"><?php echo $ld['remove'] ?></a></td>
			</tr>
		<?php }} ?>
		</tbody>
	</table>

</div>
<style type="text/css">
#user_addr_ajaxdata td{vertical-align: middle;}
</style>
<script type="text/javascript">
function loaduseraddr(user_id){
	$.ajax({ url: "/admin/users/useraddress/"+user_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#user_addr_ajaxdata").parent().html(data);
  		}
  	});
}

function editaddr(Id){
	$("#user_address_btn").click();
	var user_id=document.getElementsByName("data[User][id]")?document.getElementsByName("data[User][id]")[0].value:0;
	$.ajax({ url: "/admin/users/useraddress/"+user_id+"/edit",
		type:"POST",
		data:{'Id':Id},
		dataType:"html",
		success: function(data){
			//$("#user_addr_ajaxdata").parent().find('#addredittables').remove();
			//$("#user_addr_ajaxdata").parent().append(data);
			$("#user_address_popup .am-modal-bd").find('#addredittables').remove();
			$("#user_address_popup .am-modal-bd").html(data);
  		}
  	});
}

function deladdr(Id){
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		var user_id=document.getElementsByName("data[User][id]")?document.getElementsByName("data[User][id]")[0].value:0;
		$.ajax({ url: "/admin/users/useraddress/"+user_id+"/del",
			type:"POST",
			data:{'Id':Id},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loaduseraddr(user_id);
				}else{
					alert("<?php echo $ld['delete_failure'] ?>");
				}
			}
		});
	}
}
</script>
<?php }else{ ?>

  <div class="am-tab-panel am-fade am-active am-in am-form-detail am-form am-form-horizontal" id="addredittables">
	<div class="am-form-group">
      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['consignee']?></label>
      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-form-icon am-form-feedback">
        <input type="hidden" value="<?php echo isset($user_addressInfo['UserAddress']['id'])?$user_addressInfo['UserAddress']['id']:'0'; ?>" name="data[UserAddress][id]" >
        <input type="hidden" value="<?php echo isset($user_addressInfo['UserAddress']['user_id'])?$user_addressInfo['UserAddress']['user_id']:'0'; ?>" name="data[UserAddress][user_id]" >
        <input type="text" value="<?php echo isset($user_addressInfo['UserAddress']['consignee'])?$user_addressInfo['UserAddress']['consignee']:''; ?>" id="addr_consignee" name="data[UserAddress][consignee]" class="am-form-field" onkeydown="if(event.keyCode==13){return false;}" />
      	<span></span>
      </div>
    </div>
	<div class="am-form-group">
      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['region']?></label>
      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input type="hidden" id="region_hidden_id0" value="<?php echo isset($user_addressInfo['UserAddress']['regions'])?$user_addressInfo['UserAddress']['regions']:''; ?>" name="data[UserAddress][regions]" >
        <span id="regionsupdate0" style="float:none;">
		<select style="width: auto;" gtbfieldid="1" name="region" id="region" onchange="reload_two_regions()">
			<option><?php echo $ld['please_select']?></option>
			<option>...</option>
		</select>
		<select style="width: auto;" gtbfieldid="2" onchange="reload_two_regions()">
			<option><?php echo $ld['please_select']?></option>
			<option>...</option>
		</select>
		<select style="width: auto;" gtbfieldid="3" onchange="reload_two_regions()">
			<option><?php echo $ld['please_select']?></option>
			<option>...</option>
		</select>
		</span>
		<script type="text/javascript">
			var regions_add = "<?php echo isset($user_addressInfo['UserAddress']['regions'])?$user_addressInfo['UserAddress']['regions']:''; ?>";
			show_two_regions(regions_add,0,0);
		</script>
	  </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['address']?></label>
      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-form-icon am-form-feedback">
      	<input type="text" value="<?php echo isset($user_addressInfo['UserAddress']['address'])?$user_addressInfo['UserAddress']['address']:''; ?>" name="data[UserAddress][address]" id="addr_address" class="am-form-field" onkeydown="if(event.keyCode==13){return false;}" ><span></span>
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['zip_code']?></label>
      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input type="text" value="<?php echo isset($user_addressInfo['UserAddress']['zipcode'])?$user_addressInfo['UserAddress']['zipcode']:''; ?>" name="data[UserAddress][zipcode]" onkeydown="if(event.keyCode==13){return false;}"></div>
    </div>
    <div class="am-form-group">
      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['phone']?></label>
      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-form-icon am-form-feedback">
        <input type="text" value="<?php echo isset($user_addressInfo['UserAddress']['telephone'])?$user_addressInfo['UserAddress']['telephone']:''; ?>" name="data[UserAddress][telephone]" id="addr_phone" class="am-form-field" onkeydown="if(event.keyCode==13){return false;}" ><span></span>
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['mobile']?></label>
      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 am-form-icon am-form-feedback">
      	<input type="text" value="<?php echo isset($user_addressInfo['UserAddress']['mobile'])?$user_addressInfo['UserAddress']['mobile']:''; ?>" name="data[UserAddress][mobile]" id="addr_mobile" class="am-form-field" onkeydown="if(event.keyCode==13){return false;}" ><span></span>
      </div>
    </div>
    <div class="am-form-group">
      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['email']?></label>
      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input type="text" value="<?php echo isset($user_addressInfo['UserAddress']['email'])?$user_addressInfo['UserAddress']['email']:''; ?>" name="data[UserAddress][email]" onkeydown="if(event.keyCode==13){return false;}" ></div>
    </div>
    <div class="am-form-group">
      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['best_delivery_time']?></label>
      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input type="text" value="<?php echo isset($user_addressInfo['UserAddress']['best_time'])?$user_addressInfo['UserAddress']['best_time']:''; ?>" name="data[UserAddress][best_time]" onkeydown="if(event.keyCode==13){return false;}" ></div>
    </div>
    <div class="am-form-group">
      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['address_to']?></label>
      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input type="text" value="<?php echo isset($user_addressInfo['UserAddress']['sign_building'])?$user_addressInfo['UserAddress']['sign_building']:''; ?>" name="data[UserAddress][sign_building]" onkeydown="if(event.keyCode==13){return false;}"></div>
    </div>
	<div class="am-form-group">
		<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><a class="am-btn am-btn-success am-radius am-btn-sm" href="javascript:void(0);" onclick="saveaddr()"><?php echo $ld['save']; ?></a><a class="am-btn am-btn-success am-radius am-btn-sm" href="javascript:void(0);" onclick="clearaddr()"><?php echo $ld['cancel']; ?></a></div>
	</div>
  </div>


<style type="text/css">
#addredittables .am-form-group select{float:left;width:32%;}
#addredittables .am-form-group:last-child div a:first-child{margin-right:1.2rem;}
</style>
<script type="text/javascript">
$("#addr_consignee").blur(function(){
	if($(this).val()==""){
		$(this).parent().addClass("am-form-warning");
		$(this).parent().removeClass("am-form-success");
		$(this).parent().find("span").addClass("am-icon-warning");
		$(this).parent().find("span").removeClass("am-icon-success");
	}else{
		$(this).parent().removeClass("am-form-warning");
		$(this).parent().addClass("am-form-success");
		$(this).parent().find("span").removeClass("am-icon-warning");
		$(this).parent().find("span").addClass("am-icon-check");
	}
});
$("#addr_address").blur(function(){
	if($(this).val()==""){
		$(this).parent().addClass("am-form-warning");
		$(this).parent().removeClass("am-form-success");
		$(this).parent().find("span").addClass("am-icon-warning");
		$(this).parent().find("span").removeClass("am-icon-success");
	}else{
		$(this).parent().removeClass("am-form-warning");
		$(this).parent().addClass("am-form-success");
		$(this).parent().find("span").removeClass("am-icon-warning");
		$(this).parent().find("span").addClass("am-icon-check");
	}
});
$("#addr_mobile").blur(function(){
	if($("#addr_phone").val()==""){
		if($(this).val()==""){
			$(this).parent().addClass("am-form-warning");
			$(this).parent().removeClass("am-form-success");
			$(this).parent().find("span").addClass("am-icon-warning");
			$(this).parent().find("span").removeClass("am-icon-success");
		}else{
			$(this).parent().removeClass("am-form-warning");
			$(this).parent().addClass("am-form-success");
			$(this).parent().find("span").removeClass("am-icon-warning");
			$(this).parent().find("span").addClass("am-icon-check");
			$("#addr_phone").parent().removeClass("am-form-warning");
			$("#addr_phone").parent().find("span").removeClass("am-icon-warning");
			$("#addr_phone").parent().removeClass("am-form-success");
			$("#addr_phone").parent().find("span").removeClass("am-icon-check");
		}
	}else{
		$("#addr_phone").parent().removeClass("am-form-warning");
		$("#addr_phone").parent().find("span").removeClass("am-icon-warning");
		$("#addr_phone").parent().removeClass("am-form-success");
		$("#addr_phone").parent().find("span").removeClass("am-icon-check");
		$("#addr_mobile").parent().removeClass("am-form-warning");
		$("#addr_mobile").parent().find("span").removeClass("am-icon-warning");
		$("#addr_mobile").parent().removeClass("am-form-success");
		$("#addr_mobile").parent().find("span").removeClass("am-icon-check");
	}
});
$("#addr_phone").blur(function(){
	if($("#addr_mobile").val()==""){
		if($(this).val()==""){
			$(this).parent().addClass("am-form-warning");
			$(this).parent().removeClass("am-form-success");
			$(this).parent().find("span").addClass("am-icon-warning");
			$(this).parent().find("span").removeClass("am-icon-success");
		}else{
			$(this).parent().removeClass("am-form-warning");
			$(this).parent().addClass("am-form-success");
			$(this).parent().find("span").removeClass("am-icon-warning");
			$(this).parent().find("span").addClass("am-icon-check");
			$("#addr_mobile").parent().removeClass("am-form-warning");
			$("#addr_mobile").parent().find("span").removeClass("am-icon-warning");
			$("#addr_mobile").parent().removeClass("am-form-success");
			$("#addr_mobile").parent().find("span").removeClass("am-icon-check");
		}
	}else{
		$("#addr_phone").parent().removeClass("am-form-warning");
		$("#addr_phone").parent().find("span").removeClass("am-icon-warning");
		$("#addr_phone").parent().removeClass("am-form-success");
		$("#addr_phone").parent().find("span").removeClass("am-icon-check");
		$("#addr_mobile").parent().removeClass("am-form-warning");
		$("#addr_mobile").parent().find("span").removeClass("am-icon-warning");
		$("#addr_mobile").parent().removeClass("am-form-success");
		$("#addr_mobile").parent().find("span").removeClass("am-icon-check");
	}
});
function saveaddr(){
	var user_id=document.getElementsByName("data[User][id]")?document.getElementsByName("data[User][id]")[0].value:0;
	var addr_id=document.getElementsByName("data[UserAddress][id]")[0].value;
	var consignee=document.getElementsByName("data[UserAddress][consignee]")[0].value;
	var regions=document.getElementsByName("data[UserAddress][regions]")[0].value;
	var country_select=document.getElementById("AddressRegionUpdate00");
	var province_select=document.getElementById("AddressRegionUpdate01");
	var city_select=document.getElementById("AddressRegionUpdate02");
	var zip_code=document.getElementsByName("data[UserAddress][zipcode]")[0].value;
	var address=document.getElementsByName("data[UserAddress][address]")[0].value;
	var telephone=document.getElementsByName("data[UserAddress][telephone]")[0].value;
	var mobile=document.getElementsByName("data[UserAddress][mobile]")[0].value;
	var email=document.getElementsByName("data[UserAddress][email]")[0].value;
	var best_time=document.getElementsByName("data[UserAddress][best_time]")[0].value;
	var sign_building=document.getElementsByName("data[UserAddress][sign_building]")[0].value;
	//alert(province_select.selectedIndex);

	if(user_id==""||user_id==0){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['vip']); ?>");
		return;
	}else if(consignee==""){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['consignee']); ?>");
		return false;
	}else if(country_select.selectedIndex == 0){
		alert(j_please_country);return false;
	}else if(province_select.selectedIndex==0){
		alert(j_please_province);return false;
	}else if(city_select.selectedIndex==0){
		alert(j_please_city);return false;
	}else if(address==""){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['address']); ?>");
		return false;
	}else if(telephone==""&&mobile==""){
		alert("<?php echo sprintf($ld['name_not_be_empty'],$ld['contact']); ?>");
		return false;
	}else{
		$.ajax({ url: "/admin/users/useraddress/0/save",
			type:"POST",
			data:{
					'data[UserAddress][id]':addr_id,
					'data[UserAddress][user_id]':user_id,
					'data[UserAddress][consignee]':consignee,
					'data[UserAddress][regions]':regions,
					'data[UserAddress][zipcode]':zip_code,
					'data[UserAddress][address]':address,
					'data[UserAddress][telephone]':telephone,
					'data[UserAddress][mobile]':mobile,
					'data[UserAddress][email]':email,
					'data[UserAddress][best_time]':best_time,
					'data[UserAddress][sign_building]':sign_building
				},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loaduseraddr(user_id);
					$("#user_address_popup").modal('close');
				}else{
					alert("<?php echo $ld['operation_success'] ?>");
				}
	  		}
	  	});
	  	return false;
  	}
}
function clearaddr(){
	$("#user_addr_ajaxdata").parent().find('#addredittables').remove();
	$("#user_address_popup").modal('close');
}
</script>
<?php } ?>