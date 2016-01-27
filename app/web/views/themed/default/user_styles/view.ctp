<div class="am-cf am-user">
	<h3><?php echo isset($ur_heres[sizeof($ur_heres)-1])?$ur_heres[sizeof($ur_heres)-1]['name']:$ld['user_template']; ?></h3>
</div>
<div class="user_style" id="user_style">
    <?php echo $form->create('/user_styles',array('action'=>'view/'.(isset($user_style_data['UserStyle']['id'])?$user_style_data['UserStyle']['id']:0),'id'=>'user_style_form','name'=>'user_style','type'=>'POST','class'=>'am-form am-form-horizontal','onsubmit'=>'return(check_form(this));'));?>
        <input type="hidden" name="data[UserStyle][id]" id="user_style_id" value="<?php echo isset($user_style_data['UserStyle']['id'])?$user_style_data['UserStyle']['id']:0; ?>">
        <input type="hidden" name="data[UserStyle][user_id]" value="<?php echo isset($user_list['User']['id'])?$user_list['User']['id']:'0'; ?>">
        <div class="am-form-detail">
            <div class="am-form-group">
                <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">版型:</label>
                <div class="am-u-lg-8 am-u-md-9 am-u-sm-9"><select name="data[UserStyle][style_id]" id="product_style_id" onchange="get_StyleTypeGroup()">
                        <option value='0'><?php echo $ld['please_select'] ?></option>
                    <?php if(isset($ProductStyle_list)){foreach($ProductStyle_list as $v){ ?>
                        <option <?php echo isset($user_style_data['UserStyle']['style_id'])&&$user_style_data['UserStyle']['style_id']==$v['ProductStyle']['id']?"selected":"" ?> value="<?php echo $v['ProductStyle']['id'] ?>"><?php echo $v['ProductStyleI18n']['style_name'] ?></option>
                    <?php }} ?>
                </select><em>*</em></div>
            </div>
            <div class="am-form-group">
                <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">类型:</label>
                <div class="am-u-lg-8 am-u-md-9 am-u-sm-9"><select name="data[UserStyle][type_id]" id="product_type_id" onchange="get_StyleTypeGroup()">
                        <option value='0'><?php echo $ld['please_select'] ?></option>
                    <?php if(isset($ProductType_list)){foreach($ProductType_list as $v){ ?>
                        <option <?php echo isset($user_style_data['UserStyle']['type_id'])&&$user_style_data['UserStyle']['type_id']==$v['ProductType']['id']?"selected":"" ?> value="<?php echo $v['ProductType']['id'] ?>"><?php echo $v['ProductTypeI18n']['name'] ?></option>
                    <?php }} ?>
                </select><em>*</em></div>
            </div>
            <div class="am-form-group">
                <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">规格:</label>
                <div class="am-u-lg-8 am-u-md-9 am-u-sm-9"><select name="data[UserStyle][attribute_code]" id="attribute_code" onchange="user_style_attr_value()">
                        <option value='0'><?php echo $ld['please_select']; ?></option>
                    <?php if(isset($StyleTypeGroup_list)){foreach($StyleTypeGroup_list as $v){ ?>
                        <option <?php echo isset($user_style_data['UserStyle']['attribute_code'])&&$user_style_data['UserStyle']['attribute_code']==$v['StyleTypeGroup']['group_name']?"selected":"" ?> value="<?php echo $v['StyleTypeGroup']['group_name'] ?>"><?php echo $v['StyleTypeGroup']['group_name'] ?></option>
                    <?php }} ?>
                </select><em>*</em></div>
            </div>
            <div class="am-form-group">
                <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"></label>
                <div class="am-u-lg-8 am-u-md-9 am-u-sm-9" id="attr_info"></div>
            </div>
            <div class="am-form-group">
                <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">模板名称:</label>
                <div class="am-u-lg-8 am-u-md-9 am-u-sm-9"><input type="text" name="data[UserStyle][user_style_name]" value="<?php echo isset($user_style_data['UserStyle']['user_style_name'])?$user_style_data['UserStyle']['user_style_name']:''; ?>"><em>*</em></div>
            </div>
            <div class="am-form-group">
                <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">设为默认:</label>
                <div class="am-u-lg-8 am-u-md-9 am-u-sm-9">
                    <label class="am-radio am-success"><input class="am-ucheck-radio" type="radio" data-am-ucheck name="data[UserStyle][default_status]" <?php echo isset($user_style_data['UserStyle']['default_status'])&&$user_style_data['UserStyle']['default_status']=='1'?"checked":"" ?> value="1"><?php echo $ld['yes'] ?></label><label class="am-radio am-success"><input class="am-ucheck-radio" type="radio" data-am-ucheck name="data[UserStyle][default_status]" <?php echo (isset($user_style_data['UserStyle']['default_status'])&&$user_style_data['UserStyle']['default_status']=='0')||!isset($user_style_data['UserStyle'])?"checked":"" ?> value="0"><?php echo $ld['no'] ?></label></div>
            </div>
            <div class="am-form-group">
                <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
                <div class="am-u-lg-8 am-u-md-9 am-u-sm-9"><input type="submit" value="<?php echo $ld['save'] ?>" class="am-btn am-btn-success"></div>
            </div>
        </div>
    <?php echo $form->end();?>
</div>

<script type="text/javascript">
function get_StyleTypeGroup(){
    var product_style_id=document.getElementById('product_style_id').value;
    var product_type_id=document.getElementById('product_type_id').value;
    var attribute_code=document.getElementById('attribute_code');
    $("#attribute_code").find("option").remove();
    $("<option></option>").val("0").text("<?php echo $ld['please_select'] ?>").appendTo($("#attribute_code"));
    if(product_style_id!="0"&&product_type_id!="0"){
        $.ajax({ url:"<?php echo $html->url('/user_styles/get_StyleTypeGroup'); ?>",
                	type:"POST",
                	dataType:"json",
                	data: {product_style_id:product_style_id,product_type_id:product_type_id},
                	success: function(data){
                        if(data.flag==1){
                            $.each(data.Group_data, function (i, item){
                                $("<option></option>").val(item).text(item).appendTo($("#attribute_code"));
                            });
                        }
                	}
                });
    }
}
user_style_attr_value();
function user_style_attr_value(){
    var user_style_id=document.getElementById('user_style_id').value;
    var product_style_id=document.getElementById('product_style_id').value;
    var product_type_id=document.getElementById('product_type_id').value;
    var group_name=document.getElementById('attribute_code').value;
    if(product_style_id!="0"&&product_type_id!="0"&&group_name!="0"){
        $.ajax({ url:"<?php echo $html->url('/user_styles/user_style_attr_value'); ?>",
                	type:"POST",
                	dataType:"html",
                	data: {product_style_id:product_style_id,product_type_id:product_type_id,user_style_id:user_style_id,group_name:group_name},
                	success: function(data){
                        $("#attr_info").html(data);
                	}
                });
    }else{
        $("#attr_info").html("");
    }
}
</script>