<style type="text/css">
.customize_attrInfo .am-form select{padding:.5rem;}
.customize_attrInfo .am-form tr td{vertical-align: inherit;}
.am-gallery-default .am-gallery-title.am-attr-selected{margin-top:-22px;}
.am-gallery-default .am-gallery-title{margin-top:0px;}
.attr_img_sel img{border:2px solid #fff}
.attr_img_sel.am-seevia-selected img{border:2px solid #65c5b3}
</style>
<?php //pr($customize_attr);?>
<div class="customize_attrInfo" id="customize_attrInfo<?php echo $cart_pro_code; ?>">
  <table class="am-table am-form">
	<tr>
	  <td width="25%" style="white-space:nowrap">定制属性</td>
	  <td width="20%" style="white-space:nowrap">默认值</td>
	  <td width="35%" style="white-space:nowrap">修改选项</td>
	  <td width="20%" style="white-space:nowrap">修改值</td>
	</tr>
	<?php if(isset($customize_attr)&&sizeof($customize_attr)>0){foreach($customize_attr as $k=>$v){ $customize_attr_input=false;
	if($v['is_customize']==1){continue;}
	if(isset($v['select_value'])&&!empty($v['select_value'])){$customize_attr_input=true;}?>
	<?php if(isset($v['select_img'])&&!empty($v['select_img'])){ ?>
	<tr>
	  <td><?php echo $v['attr_name'] ?></td>
	  <td colspan="3">
	  <?php if(sizeof($v['select_img'])>1){ ?>
		<ul class="am-gallery am-avg-sm-3 am-avg-md-4 am-avg-lg-5 am-gallery-default" >
		<?php foreach($v['select_img'] as $ik=>$iv){?>
		  <li>
		    <div class="am-gallery-item attr_img_sel">
		      <img src="<?php echo $iv?>" />
		      <!--<span class="am-icon-check am-text-success" style="position:relative;bottom:22px;display:none;"></span>-->
		      <?php if(isset($v['select_price'])){?>
		      <input type="hidden" class="attr_price" value="<?php echo $v['select_price'][$ik] ?>" />
		      <?php }?>
		    </div>
		    <h3 class="am-gallery-title"><?php echo $ik?></h3>
		  </li>
		<?php }?>
		</ul>
		<input type="hidden" name="CartProductValue[<?php echo $cart_pro_code; ?>][<?php echo $v['attr_id'] ?>]" value="<?php echo $v['default_value'] ?>" />
		<input type="hidden" name="AccessoryPrice[<?php echo $cart_pro_code; ?>][<?php echo $v['attr_id'] ?>]" value="" />
	  <?php }?>
	  </td>
	</tr>
	<?php }else{?>
	<tr>
	  <td><?php echo $v['attr_name'] ?></td>
	  <td><?php echo $v['default_value'] ?></td>
	  <td><?php if(isset($v['select_value'])&&!empty($v['select_value'])&&$v['is_customize']==1){$customize_attr_input=true;
			if(sizeof($v['select_value'])>1){
		  ?>
	    <select class="customize_attr_value_sel">
		<?php foreach($v['select_value'] as $slv){if(trim($slv)=="")continue;
		  $attr_type_txt=$slv;
		  if($slv>0){$attr_type_txt='增加'.$slv;}else if($slv<0){$attr_type_txt='减少'.($slv*-1);}else{$attr_type_txt='无改动';}
		  $sel_txt="";
		  if($slv==0){$sel_txt=" selected='selected'";}
		?>
		  <option value="<?php echo trim($slv); ?>" <?php echo $sel_txt; ?>><?php echo $attr_type_txt; ?></option>
		<?php } ?>
	    </select>
	    <?php }}else if(isset($v['select_value'])&&!empty($v['select_value'])&&$v['is_customize']==0){$customize_attr_input=true; 
		  if(sizeof($v['select_value'])>1){
		  ?>
	    <select class="attr_value_sel">
		<?php foreach($v['select_value'] as $slk=>$slv){if(trim($slv)=="")continue;
		  $sel_txt="";
		  if($slv==$v['default_value']){$sel_txt=" selected='selected'";}
		?>
		  <option value="<?php echo trim($slv); ?>" <?php echo $sel_txt; ?>><?php echo $slk; ?></option>
		<?php } ?>
	    </select>
	  <?php }} ?>
	  </td>
	  <td>
		<?php if($customize_attr_input){ ?>
		<span style="padding-left:1.5rem"></span><input type="hidden" name="CartProductValue[<?php echo $cart_pro_code; ?>][<?php echo $v['attr_id'] ?>]" value="<?php echo $v['default_value'] ?>" />
		<?php }else{ ?>
		<input type="text" name="CartProductValue[<?php echo $cart_pro_code; ?>][<?php echo $v['attr_id'] ?>]" value="<?php echo $v['default_value'] ?>" />
		<?php } ?>
	  </td>
	</tr>
	<?php }?>
	<?php }} ?>
	<tr>
	  <td><?php echo $ld['remark'] ?></td>
	  <td colspan="3"><textarea name="CartProductNote[<?php echo $cart_pro_code; ?>]" style="width:100%;resize:none;" rows="3"></textarea></td>
	</tr>
  </table>
</div>
<script type="text/javascript">
//定制属性修改
$(".customize_attr_value_sel").change(function(){
	var TR=$(this).parent().parent();
	var change_value=Number($(this).val());
	TR.find("td:eq(1)").html();
	var hidinput=Number(TR.find("td:eq(1)").html());
	TR.find("td:eq(3) span").html(hidinput+change_value);
	TR.find("td:eq(3) input").val(hidinput+change_value);
});
//普通属性修改
$(".attr_value_sel").change(function(){
	var change_value=$(this).val();
	var TR=$(this).parent().parent();
	var hidinput=Number(TR.find("td:eq(1)").html());
	if(change_value!=""){
		TR.find("td:eq(3) span").html(change_value);
		TR.find("td:eq(3) input").val(change_value);
	}else{
		TR.find("td:eq(3) span").html(hidinput);
		TR.find("td:eq(3) input").val(hidinput);
	}
});
//图片属性修改
$(".attr_img_sel").click(function(){
	$(this).parent().parent().find(".attr_img_sel").removeClass("am-seevia-selected");
	$(this).addClass("am-seevia-selected");
	var change_value=$(this).parent().find(".am-gallery-title").html();
	var TR=$(this).parent().parent().parent();
	var hidinput=TR.find("input[type=hidden][name*='CartProductValue']").val();
	var hidprice=TR.find("input[type=hidden][name*='AccessoryPrice']");
	//alert(change_value);
	if(change_value!=""){
		TR.find("input[type=hidden][name*='CartProductValue']").val(change_value);
		//$(this).parent().parent().find(".am-icon-check").hide();
		//$(this).parent().parent().find(".am-gallery-title").removeClass("am-attr-selected");
		//$(this).parent().find(".am-gallery-title").addClass("am-attr-selected");
		//$(this).find(".am-icon-check").show();
		var basic_price=$("#pro_price").val();
		$("#basic_price").html("<div class='am-fl'>基础价格</div><div class='am-fr'>"+Number(basic_price)+"</div>");
		//获取原有定制属性价格
		var accessory_price=$("#accessory_price").html();
		var attr_price=$(this).find(".attr_price").val();
		hidprice.val(attr_price);
		if(accessory_price==""){
			$("#accessory_price").html("<div class='am-fl'>配件价格</div><div class='am-fr'>"+hidprice.val()+"</div>");
			$("#total_prices").html("<div class='am-fl'>总价</div><div class='am-fr'>"+(Number(basic_price)+Number(attr_price))+"</div>");
		}else{
			if($("input[type=hidden][name*='AccessoryPrice']").length>0){
				var tmp_price=0;
				$("input[type=hidden][name*='AccessoryPrice']").each(function(i,item){
					tmp_price+=Number($(this).val());
				});
				$("#accessory_price").html("<div class='am-fl'>配件价格</div><div class='am-fr'>"+tmp_price+"</div>");
			}
			$("#total_prices").html("<div class='am-fl'>总价</div><div class='am-fr'>"+(Number(basic_price)+Number(tmp_price))+"</div>");
		}
		$("#attr_price_list").show();
		
	}else{
		TR.find("input[type=hidden]").val(hidinput);
	}
});
</script>