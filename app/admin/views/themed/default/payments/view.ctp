<style>
    .am-radio, .am-checkbox{display:inline;}
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    label{font-weight:normal;}
    .am-form-horizontal .am-radio{padding-top:0;}
    .am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}
</style>
<script src="/plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<?php echo $form->create('Payment',array('action'=>'view/'.(isset($payment['Payment']['id'])?$payment['Payment']['id']:0),'onsubmit'=>'return pay_input_checks()'));?> <input id="payment_id" type="hidden" name="data[Payment][id]" value="<?php echo isset($payment['Payment']['id'])?$payment['Payment']['id']:0; ?>"/>
<?php if(isset($backend_locales) && sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
    <input id="PaymentI18n<?php echo $k;?>Locale" name="data[PaymentI18n][<?php echo $k;?>][locale]" type="hidden" value="<?php echo $v['Language']['locale'];?>">
    <?php if(isset($payment['PaymentI18n'][$v['Language']['locale']])){?>
        <input id="PaymentI18n<?php echo $k;?>Id" name="data[PaymentI18n][<?php echo $k;?>][id]" type="hidden" value="<?php echo $payment['PaymentI18n'][$v['Language']['locale']]['id'];?>">
    <?php }?>
    <input id="PaymentI18n<?php echo $k;?>PaymentId" name="data[PaymentI18n][<?php echo $k;?>][payment_id]" type="hidden" value="<?php echo isset($payment['Payment']['id'])?$payment['Payment']['id']:0; ?>">
<?php }}?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-detail-menu" style="margin:10px 0 0 0">
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#basic_info"><?php echo $ld['basic_information']?></a></li>
        <li><a href="#detail"><?php echo $ld['detail_description']?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9  am-detail-view" id="accordion"  >
    <div id="basic_info" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['basic_information']?>
            </h4>
        </div>
        <div id="basic_information" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tr>
                        <th style="width:20%;padding-top:12px"><?php echo $ld['payment_superiors']?></th>
                        <td><select name="data[Payment][parent_id]" data-am-selected="{noSelectedText:''}">
                                <option value='0'><?php echo $ld['top_payment'] ?></option>
                                <?php foreach($parent_payment_list as $k=>$v){ ?>
                                    <option value="<?php echo $v['Payment']['id'] ?>" <?php echo isset($payment['Payment']['parent_id'])&&$payment['Payment']['parent_id']==$v['Payment']['id']?'selected':''; ?>><?php echo $v['Payment']['code'].'-'.$v['PaymentI18n']['name'] ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <th  style="padding-top:15px"><?php echo $ld['code']?></th>
                        <td><input style="width:200px;float:left;" type="text" id="payment_code" name="data[Payment][code]" value="<?php echo isset($payment['Payment']['code'])?$payment['Payment']['code']:''; ?>" onchange="checkpaymentcode(this,1)" /><em style="color:red;">*</em></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px" rowspan="<?php echo count($backend_locales)+1;?>"><?php echo $ld['payment_name']?></th>
                    </tr>
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                        <td><input style="width:200px;float:left;" type="text" id="name<?php echo $v['Language']['locale']?>" name="data[PaymentI18n][<?php echo $k?>][name]" value="<?php echo isset($payment['PaymentI18n'][$v['Language']['locale']])?$payment['PaymentI18n'][$v['Language']['locale']]['name']:'' ?>"><?php if(sizeof($backend_locales)>1){?><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em style="color:red;">*</em></td>
                    </tr>
                    <?php }}?>
                    <tr>
                        <th style="padding-top:10px"><?php echo $ld['payment_status']?></th>
                        <td><label class="am-radio am-success" style="padding-top:2px;"><input type="radio" name="data[Payment][status]" value="1" data-am-ucheck <?php echo isset($payment['Payment']['status'])&&$payment['Payment']['status']=='1'?" checked='checked'":"" ?>/><?php echo $ld['yes']?></label>
                            <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[Payment][status]" value="0" data-am-ucheck <?php echo (isset($payment['Payment']['status'])&&$payment['Payment']['status']=='0')||!isset($payment['Payment'])?" checked='checked'":"" ?> /><?php echo $ld['no']?></label></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px"><?php echo $ld['payment_fee']?></th>
                        <td><input type="text" style="width:200px;" name="data[Payment][fee]" value="<?php echo isset($payment['Payment']['fee'])?$payment['Payment']['fee']:0; ?>" id='fee'/></td>
                    </tr>
                    <tr>
                        <th style="padding-top:15px">Logo</th>
    					<td><input style="width:200px;float:left;margin-right:5px;" id="payment_logo" type="text" name="data[Payment][logo]" value="<?php echo isset($payment['Payment']['logo'])?$payment['Payment']['logo']:'';?>" /><input type="button"  class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('payment_logo')" value="<?php echo $ld['choose_picture']?>" />&nbsp;
                            <div class="img_select" style="margin:5px;">
    							<?php echo $html->image((isset($payment['Payment']['logo'])&&$payment['Payment']['logo']!="")?$payment['Payment']['logo']:$configs['shop_default_img'],array('id'=>'show_payment_logo')); ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th  style="padding-top:10px"><?php echo $ld['order_available']?></th>
                        <td ><label class="am-radio am-success" style="padding-top:2px;"><input type="radio" name="data[Payment][order_use_flag]" value="1" data-am-ucheck <?php echo isset($payment['Payment']['order_use_flag'])&&$payment['Payment']['order_use_flag']=='1'?" checked='checked'":"" ?>/><?php echo $ld['yes']?></label>
                            <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[Payment][order_use_flag]" value="0" data-am-ucheck <?php echo (isset($payment['Payment']['order_use_flag'])&&$payment['Payment']['order_use_flag']=='0')||!isset($payment['Payment'])?" checked='checked'":"" ?> /><?php echo $ld['no']?></label></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['recharge_available']?></th>
                        <td><label class="am-radio am-success " style="padding-top:2px;"><input type="radio" name="data[Payment][supply_use_flag]" value="1" data-am-ucheck <?php echo isset($payment['Payment']['supply_use_flag'])&&$payment['Payment']['supply_use_flag']=='1'?" checked='checked'":"" ?> /><?php echo $ld['yes']?></label>
                            <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[Payment][supply_use_flag]" value="0" data-am-ucheck <?php echo (isset($payment['Payment']['supply_use_flag'])&&$payment['Payment']['supply_use_flag']=='0')||!isset($payment['Payment'])?" checked='checked'":"" ?> /><?php echo $ld['no']?></label></td>
                    </tr>
                    <tr>
                        <th><?php echo $ld['support_cod']?></th>
                        <td><label class="am-radio am-success" style="padding-top:2px;"><input type="radio" name="data[Payment][is_cod]" value="1" data-am-ucheck <?php echo isset($payment['Payment']['is_cod'])&&$payment['Payment']['is_cod']=='1'?" checked='checked'":"" ?> /><?php echo $ld['yes']?></label>
                            <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[Payment][is_cod]" value="0" data-am-ucheck <?php echo (isset($payment['Payment']['is_cod'])&&$payment['Payment']['is_cod']=='0')||!isset($payment['Payment'])?" checked='checked'":"" ?> /><?php echo $ld['no']?></label></td>
                    </tr>
                    <tr>
                        <th><?php echo isset($ld['support_online_payment'])?$ld['support_online_payment']:$ld['support_online'] ?></th>
                        <td><label class="am-radio am-success" style="padding-top:2px;"><input type="radio" name="data[Payment][is_online]" value="1" data-am-ucheck <?php echo isset($payment['Payment']['is_online'])&&$payment['Payment']['is_online']=='1'?" checked='checked'":"" ?> /><?php echo $ld['yes']?></label>
                            <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success" style="padding-top:2px;"><input type="radio" name="data[Payment][is_online]" value="0" data-am-ucheck <?php echo (isset($payment['Payment']['is_online'])&&$payment['Payment']['is_online']=='0')||!isset($payment['Payment'])?" checked='checked'":"" ?> /><?php echo $ld['no']?></label></td>
                    </tr>
                    <?php if(isset($payment['Payment']['is_getinshop'])){?>
                        <tr>
                            <th><?php echo isset($ld['support_getinshop'])?$ld['support_getinshop']:$ld['support_ store'] ?></th>
                            <td><label class="am-radio am-success"style="padding-top:2px;"><input type="radio" name="data[Payment][is_getinshop]" value="1" data-am-ucheck <?php if( $payment['Payment']['is_getinshop'] == 1 ){ echo "checked"; } ?> /><?php echo $ld['yes']?></label>
                                <label style="margin-left:10px;padding-top:2px" class="am-radio am-success"><input type="radio" name="data[Payment][is_getinshop]" value="0" data-am-ucheck <?php if( $payment['Payment']['is_getinshop'] == 0 ){ echo "checked"; }?> /><?php echo $ld['no']?></label></td>
                        </tr>
                    <?php }?>
                    <?php if(isset($config) && count($config)>0){
                        foreach($config as $k=>$v){?>
                            <?php if($v['type']=="text"){?>
                                <tr>
                                    <th><?php echo $v['name']?></th>
                                    <td><input type="text" class="text_inputs" name="config[<?php echo $k?>]" value="<?php echo (isset($config_value[$k]))?$config_value[$k]:"";?>" /></td>
                                </tr>
                            <?php }elseif($v['type'] =="radio"){ ?>
                                <tr>
                                    <th><?php echo $v['name']?></th>
                                    <td><label><input type="radio" name="config[<?php echo $k?>]" value="1" <?php if(isset($config_value[$k]) && $config_value[$k] == 1) echo "checked";?> /><?php echo $ld['yes']?></label>
                                        <label><input style="margin-left:5px;" type="radio" name="config[<?php echo $k?>]" value="0" <?php if(isset($config_value[$k]) && $config_value[$k] == 0) echo "checked";?> /><?php echo $ld['no']?></label></td>
                                </tr>
                            <?php }elseif($v['type'] =="select"){ ?>
                                <tr>
                                    <th><?php echo $v['name']?></th>
                                    <td><label><select name="config[<?php echo $k?>]" ><?php pr($config_value[$k]);?>
                                                <?php foreach($v['value'] as $kk=>$vv){?>
                                                    <option value='<?php echo $kk;?>' <?php if(isset($config_value[$k]) && $config_value[$k] == $kk) echo "selected";?>><?php echo $vv;?></option>
                                                <?php }?>
                                            </select>
                                        </label>
                                    </td>
                                </tr>
                            <?php }elseif($v['type'] =="textarea"){?>
                                <tr>
                                    <th><?php echo $v['name']?></th>
                                    <td>
                                        <textarea name="config[<?php echo $k?>]"><?php if($k == 'bank') {?><?php echo (isset($config_value[$k]['bb']))?$config_value[$k]['bb']:"";?><?php }else{?><?php echo (isset($config_value[$k]))?$config_value[$k]:"";?><?php } ?></textarea>
                                        <?php if($k == 'bank') {?><?php echo $ld['distinguish_banks']?><?php }?>
                                    </td>
                                </tr>
                            <?php }?>
                        <?php }?>
                    <?php }?>
                     <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                        <?php  if($configs["show_edit_type"]){?>
                   <tr>
                       <th ><?php echo $ld['detail_description']?></th>
                       <td> <span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span>
                                    <textarea cols="80" id="11elm<?php echo $v['Language']['locale'];?>" name="data[PaymentI18n][<?php echo $k;?>][description]" rows="10" style="height:300px;"><?php echo isset($payment['PaymentI18n'][$v["Language"]["locale"]]['description'])?$payment['PaymentI18n'][$v["Language"]["locale"]]['description']:"";?></textarea>
                                    <script>
                                        var editor;
                                        KindEditor.ready(function(K) { 
                                            editor = K.create('#11elm<?php echo $v['Language']['locale'];?>', {width:'93%',
                                                  langType : '<?php echo $v['Language']['google_translate_code']?>',cssPath : '/css/index.css',filterMode : false});
                                        });
                                    </script> </td>	   
                  </tr>  <?php }else{?>
                           <tr>
                                <td><span class="ckeditorlanguage"><?php echo $v['Language']['name'];?></span>
                                    <textarea cols="80" id="11elm<?php echo $v['Language']['locale'];?>" name="data[PaymentI18n][<?php echo $k;?>][description]" rows="10"><?php if(isset($payment['PaymentI18n'][$v["Language"]["locale"]]['description'])&&$payment['PaymentI18n'][$v["Language"]["locale"]]['description']!="")echo $payment['PaymentI18n'][$v["Language"]["locale"]]['description']?></textarea>
                                    <?php echo $ckeditor->load("11elm".$v['Language']['locale']); ?></td>
                            </tr>
                        <?php }?>
                    <?php }}?>
                    	
                    	
                </table>
                <div class="btnouter">
                    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit']?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
                </div>
            </div>
        </div>
    </div>
     
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    var payment_code_flag=true;
    function checkpaymentcode(input,action_flag){
        var code=input.value;
        var payment_id=document.getElementById("payment_id").value;
        if(code==""){
            payment_code_flag=false;
            return false;
        }
        var sUrl = admin_webroot+"payments/checkpaymentcode/";//访问的URL地址
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            data: {id: payment_id, code: code},
            success: function (result) {
                if(result.code==1){
                    if(action_flag==1){
                        alert(result.msg);
                    }
                    payment_code_flag=false;
                    return false;
                }
                payment_code_flag=true;
            }
        });
    }
    checkpaymentcode(document.getElementById("payment_code"),0)

    function pay_input_checks(){
        var payment_code=document.getElementById("payment_code").value;
        var pay_name_obj = document.getElementById("name"+backend_locale);
        var fee= document.getElementById("fee");

        if(payment_code==""){
            alert("<?php printf($ld['name_not_be_empty'],$ld['code']); ?>");
            return false;
        }else if(payment_code_flag==false){
            alert("<?php $ld['code_already_exists']; ?>");
            return false;
        }
        if(pay_name_obj.value==""){
            alert("<?php echo $ld['enter_payment_name']?>");
            return false;
        }
        if(! isNumber(fee.value)) {
            alert("<?php echo $ld['error_fee_format']?>");
            return false;
        }
        return true;
    }

    function isNumber(String){
        var Letters = "1234567890%"; //可以自己增加可输入值
        var i;
        var c;
        if(String.charAt( 0 )=='%')
            return false;
        for( i = 0; i < String.length; i ++ )
        {
            c = String.charAt( i );
            if (Letters.indexOf( c ) < 0)
                return false;
        }
        return true;
    }
</script>