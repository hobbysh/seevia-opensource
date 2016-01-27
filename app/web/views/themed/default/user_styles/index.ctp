<div class="am-cf am-user">
	<h3><?php echo $ld['user_template'] ?></h3>
</div>
<div class="user_style" id="user_style">
            <?php 
    			if(isset($user_style_list)&&sizeof($user_style_list)>0){ 
    				foreach($user_style_list as $k=>$v){
    		?>
            <h3><?php echo isset($product_type_data[$k])?$product_type_data[$k]:''; ?></h3>
     <table cellpadding="0" cellspacing="0" class="am-table am-table-striped am-table-hover table-main user_style_list">
        <thead>
            <tr>
                <th width="8%">版型</th>
                <th colspan="2" width="35%">商品</th>
                <th width="12%" class="am-hide-sm-only">下单时间</th>
                <th class="am-hide-sm-only">模板名称</th>
    			<th class="am-hide-sm-only" style="text-align:center;" width="6%">默认</th>
    			<th colspan='2'>备注</th>
    		</tr>
        </thead>
        <tbody>
                <?php  foreach($v as $kkk=>$vvv){ ?>
            <tr>
                <td><?php echo isset($product_style_data[$vvv['UserStyle']['style_id']])?$product_style_data[$vvv['UserStyle']['style_id']]:''; ?></td>
                <td width="8%"><a href="<?php echo $html->url('/products/'.$order_pro_data[$vvv['UserStyle']['id']]['Product']['id']); ?>"><img class="pro_img" src="<?php echo $order_pro_data[$vvv['UserStyle']['id']]['Product']['img_thumb']; ?>"></a></td>
                <td><?php echo $order_pro_data[$vvv['UserStyle']['id']]['ProductI18n']['name']; ?></td>
                <td  class="am-hide-sm-only"><?php echo date("Y-m-d",strtotime($order_pro_data[$vvv['UserStyle']['id']]['Order']['created'])); ?></td>
                <td class="am-hide-sm-only"><?php echo $vvv['UserStyle']['user_style_name']; ?></td>
                <td class="am-hide-sm-only" align="center"><span onclick="set_style_default(this,'<?php echo $vvv['UserStyle']['id']; ?>','<?php echo $vvv['UserStyle']['attribute_code']; ?>')" class="default_style default_style_<?php echo $vvv['UserStyle']['attribute_code']; ?> <?php echo $vvv['UserStyle']['default_status']?'am-icon-check am-yes':'am-icon-close am-no'; ?>">&nbsp;</span></td>
                <td width="15%"><span id="remark_data_<?php echo $vvv['UserStyle']['id'] ?>" class="remark_data"><?php echo $vvv['UserStyle']['remark']; ?></span></td>
                <td width="3%" class="user_style_action"><a href="javascript:void(0);" class="am-icon-edit" onclick="update_remark(this,'<?php echo $vvv['UserStyle']['id'] ?>')"></a></td>
    		</tr>
               <?php } ?>
           <tbody>
    </table>
            <?php }}else{?>
                <div><?php echo $ld['no_record'];?></div>
            <?php } ?>

</div>
<button id="popup_remark" class="am-btn" style="display:none;" data-am-modal="{target: '#user_style_remark',closeViaDimmer: 0,width: 340, height: 300}">Popup</button>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="user_style_remark">
	<div class="am-modal-dialog">
		<div class="am-modal-hd" style=" z-index: 11;">
	      <h4 class="am-popup-title">备注</h4>
	      <span data-am-modal-close class="am-close">&times;</span>
	    </div>
	    <div class="am-modal-bd" style="padding:0;">
            <form action="<?php echo $html->url('/user_styles/update_remark'); ?>" id="UserStyle_remark">
	    	<input type="hidden" name="data[UserStyle][id]" value="0">
	    	<div class="am-g am-form am-form-horizontal">
                <div class="am-form-group">
                      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">备注</label>
                      <div class="am-u-lg-8 am-u-md-6 am-u-sm-8"><input type="text" name="data[UserStyle][remark]" value="" ></div>
            	</div>
                <div class="am-form-group">
                      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
                      <div class="am-u-lg-8 am-u-md-6 am-u-sm-8 am-text-left"><a class="am-btn am-btn-primary am-btn-sm" href="javascript:void(0);" onclick="save_remark()"><?php echo $ld['save'];?></a></div>
            	</div>
	    	</div>
	        </form>
	    </div>
	</div>
</div>

<script type="text/javascript">
function set_style_default(obj,Id,attribute_code){
    var ClassName=$(obj).attr('class');
	var default_status = (ClassName.match(/yes/i)) ? 0 : 1;
    $.ajax({ url:"<?php echo $html->url('/user_styles/set_style_default'); ?>",
        	type:"POST",
        	dataType:"json",
        	data: {id:Id,default_status:default_status},
        	success: function(data){
                if(data.flag==1){
                    $(obj).parent().parent().parent().parent().find(".default_style_"+attribute_code).removeClass("am-icon-check am-yes");
                    $(obj).parent().parent().parent().parent().find(".default_style_"+attribute_code).addClass("am-icon-close am-no");
                    if(default_status==1){
                        $(obj).removeClass("am-icon-close am-no");
                        $(obj).addClass("am-icon-check am-yes");
                    }
                }
        	}
        });
}


function save_remark(){
    var userstyle_id=$("#user_style_remark input[name='data[UserStyle][id]']").val();
    var remark=$("#user_style_remark input[name='data[UserStyle][remark]']").val();
    if(remark.length>0){
        $.ajax({ url:"<?php echo $html->url('/user_styles/update_remark'); ?>",
                	type:"POST",
                	dataType:"json",
                	data: $("#UserStyle_remark").serialize(),
                	success: function(data){
                        if(data.flag==1){
                            $("#remark_data_"+userstyle_id).html(remark);
                            
                            $('#user_style_remark').modal('close');
                            $("#user_style_remark input[name='data[UserStyle][id]']").val(0);
                            $("#user_style_remark input[name='data[UserStyle][remark]']").val('');
                        }
                	}
                });
    }else{
        alert('请填写备注信息');
    }
}
</script>