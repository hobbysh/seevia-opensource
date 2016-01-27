<style type="text/css">
.productsAttrTable{margin-bottom:0px;}
.productsAttrTable table{margin-bottom:0px;}
#productsAttrdiv td input[type="text"] {width: 130px;}
#productsAttrdiv td .input_text {width: 130px;}
#productsAttrdiv td .img_select {width: 130px;}
#productsAttrdiv td .img_select img { max-height: 120px;position: relative; display:block; }
#productsAttrdiv td .img_select span { width:130px; }
</style>
<?php
if(!empty($attr_data)){
$j=0;
foreach($attr_data as $key => $val){
if($val['Attribute']['type']=="customize"){continue;}
$attr_name=$val['AttributeI18n']['name'];
?>
<table id="productsAttrTable" class="am-table productsAttrTable" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width:150px;padding-left:22px;vertical-align:middle;border-top:0;padding-top:0px;padding-bottom:0px;"><?php echo $attr_name; ?></td>
        <td style="border-top:0px;padding:0px;">
            <?php $i=0;$upload=true;
            if(!empty($val['ProductAttribute'])){foreach($val['ProductAttribute'] as $kk=>$vv){
            if($i==0||$i==$lan_count){
            $i=0;
            $table_name='attr_table_'.$val['Attribute']['id'].'_'.++$j;
            ?>
            <table id="<?php echo $table_name ?>" name="<?php echo $table_name ?>" class="am-table"  cellpadding="0" cellspacing="0">
                <tbody>
                <?php
                }
                foreach($front_locales as $k=>$v){
                    $k_locale=$vv['locale'];
                    if($vv['locale']==$v['Language']['locale']){
                        $val['ProductAttribute']['attribute_value'] = empty($vv['attribute_value'])?$val['Attribute']['locale'][$v['Language']['locale']]['default_value']:$vv['attribute_value'];
                        $val['ProductAttribute']['attribute_image_path']=empty($vv['attribute_image_path'])?'':$vv['attribute_image_path'];
                        $val['ProductAttribute']['attribute_back_image_path']=empty($vv['attribute_back_image_path'])?'':$vv['attribute_back_image_path'];
                        $val['ProductAttribute']['attribute_related_image_path']=empty($vv['attribute_related_image_path'])?'':$vv['attribute_related_image_path'];
                        $val['ProductAttribute']['attribute_related_back_image_path']=empty($vv['attribute_related_back_image_path'])?'':$vv['attribute_related_back_image_path'];
                        $val['ProductAttribute']['attribute_price'] = $vv['attribute_price'];
                        $val['ProductAttribute']['orderby'] = $vv['orderby'];
                        $upload=false;
                        ?>
                        <tr>
                            <td colspan="4">
                                <input type='hidden' name="attr_id_list[<?php echo $vv['id']; ?>][<?php echo $k_locale; ?>]" value="<?php echo $val['Attribute']['id']; ?>" /><input type='hidden' name="attr_locale_list[<?php echo $vv['id']; ?>][<?php echo $k_locale; ?>]" value="<?php echo $v['Language']['locale']; ?>" />
                                <?php if($val['Attribute']['attr_input_type']=="0"||$val['Attribute']['attr_input_type']=="4"){ ?>
                                    <input type='text' style="float:left;margin-right:10px;" class="input_text" name="attr_value_list[<?php echo $vv['id']; ?>][<?php echo $k_locale; ?>]" value="<?php echo $val['ProductAttribute']['attribute_value']; ?>" />
                                <?php }elseif($val['Attribute']['attr_input_type']=="2"){ ?>
                                    <textarea style="float:left;margin-right:10px;" name="attr_value_list[<?php echo $vv['id']; ?>][<?php echo $k_locale; ?>]" ><?php echo $val['ProductAttribute']['attribute_value'];?></textarea>;
                                <?php }elseif($val['Attribute']['attr_input_type']=="3"){
                                    $upload=false;
                                    $value=isset($val['ProductAttribute']['attribute_value'])&&!empty($val['ProductAttribute']['attribute_value'])?$val['ProductAttribute']['attribute_value']:'jpg,png,gif:500';
                                    $value=explode(':',$value);
                                    $value[0]=isset($value[0])&&!empty($value[0])?$value[0]:"jpg,png,gif";
                                    $value[1]=isset($value[1])&&!empty($value[1])?$value[1]:"500";
                                    echo $ld['prod_type_format'];
                                    ?>
                                    <input style="float:left;margin-right:10px;" name="attr_value_list[<?php echo $vv['id']; ?>][<?php echo $k_locale; ?>]" type="text" class="input_text" value="<?php echo $value[0]; ?>"/><?php echo $ld['prod_type_format_require'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$ld['prod_type_format_size']; ?><input name="attr_value_upload_size[<?php echo $vv['id']; ?>][<?php echo $k_locale; ?>]" type="text" class="input_text" value="<?php echo $value[1]; ?>"/>(****KB)
                                <?php }else{  $attr_values=$val['AttributeOption']; ?>
                                    <select style="width:100px;float:left;margin-right:10px;" name="attr_value_list[<?php echo $vv['id']; ?>][<?php echo $k_locale; ?>]" >
                                        <option value=''><?php echo $ld['please_select'] ?></option>
                                        <?php foreach($attr_values as $opt){ ?>
                                            <option value="<?php echo $opt['option_value'] ?>" <?php echo $val['ProductAttribute']['attribute_value']==$opt['option_value']?"selected='selected'":''; ?>><?php echo $opt['option_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } ?>
                                <div style="float:left;margin-right:10px;padding-top:8px;"><?php if($val['Attribute']['type']=="buy")echo $ld['attributes_price'];?></div><input style="float:left;width:80px;margin-right:10px;" type="text" name="attr_price_list[<?php echo $vv['id']?>][<?php echo $k_locale?>]" value="<?php echo $val['ProductAttribute']['attribute_price']?>" size="10" maxlength="10" /><input type="hidden" name="attr_price_list[<?php echo $vv['id']?>][<?php echo $k_locale?>]" value="0" />
                                 <div  style="vertical-align:middle;float:left;margin-right:10px;padding-top:8px;"><?php echo $ld['sort'];?></div>
                                <input type='text' size='4' style="width:80px;" name='attr_orderby_list[<?php echo $vv['id'];?>][<?php echo $k_locale;?>]' value='<?php echo $val['ProductAttribute']['orderby'];?>' />

                                </td>
                               
                        </tr>
                    <?php
                    }$i++;
                    break;}
                if($i==$lan_count){
                    echo  '</tbody></table>';
                }
                ?>
                <?php }} ?>
                <?php
                if($upload){
                $table_name='attr_table_'.$val['Attribute']['id'].'_'.++$j;
                ?>
                <table id="<?php echo $table_name; ?>" name="<?php echo $table_name; ?>" style="margin-bottom: 5px;" class="am-table">
                    <tbody>
                    <?php
                    foreach($front_locales as $k=>$v){
                        $value = @$val['Attribute']['locale'][$v['Language']['locale']]['default_value'];
                        $k_locale=$v['Language']['locale'];
                        $price ="";
                        ?>
                        <tr>
                            <td colspan="4" style="border-top:0"><input type='hidden' name='clone_attr_id_list[<?php echo $key; ?>][<?php echo $k_locale; ?>]' value="<?php echo $val['Attribute']['id']; ?>" /><input  type='hidden' name='clone_attr_locale_list[<?php echo $key; ?>][<?php echo $k_locale; ?>]' value="<?php echo $v['Language']['locale']; ?>" />
                                <?php if($val['Attribute']['attr_input_type']=="0"||$val['Attribute']['attr_input_type']=="4"){ ?>
                                    <input style="float:left;margin-right:10px;" name="clone_attr_value_list[<?php echo $key; ?>][<?php echo $k_locale; ?>]" type="text" class="input_text" value="<?php echo $value; ?>"  />
                                <?php }elseif($val['Attribute']['attr_input_type']=="2"){ ?>
                                    <textarea style="float:left;margin-right:10px;" name="clone_attr_value_list[<?php echo $key; ?>][<?php echo $k_locale; ?>]" ><?php echo $value; ?></textarea>
                                <?php }elseif($val['Attribute']['attr_input_type']=="3"){ echo $ld['prod_type_format']; ?>
                                    <input style="float:left;margin-right:10px;" name="clone_attr_value_list[<?php echo $key; ?>][<?php echo $k_locale; ?>]" type="text" class="input_text" value="jpg,png,gif"/><?php echo $ld['prod_type_format_require'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$ld['prod_type_format_size']; ?><input name="clone_attr_value_upload_size[<?php echo $key; ?>][<?php echo $k_locale; ?>]" type="text" class="input_text" value="500"/>(****KB)
                                <?php }else{  $attr_values=$val['AttributeOption']; ?>
                                    <select style="width:100px;float:left;margin-right:10px;" name="clone_attr_value_list[<?php echo $key; ?>][<?php echo $k_locale; ?>]" >
                                        <option value=''><?php echo $ld['please_select'] ?></option>
                                        <?php foreach($attr_values as $opt){ ?>
                                            <option value="<?php echo $opt['option_value'] ?>"><?php echo $opt['option_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } ?>
                                <?php if($val['Attribute']['type']=="buy"){?><div style="float:left;margin:6px 10px 0px 10px;padding-top:3px;"><?php echo $ld['attributes_price'];?></div>
                                    <input style="float:left;margin-right:10px;" type="text" name="clone_attr_price_list[<?php echo $key; ?>][<?php echo $k_locale; ?>]" value="<?php echo $price; ?>" size="10" maxlength="10" />
                                <?php }else{?>
                                    <input type="hidden" name="attr_price_list[<?php echo $key; ?>][<?php echo $k_locale; ?>]" value="0" />
                                <?php } ?>
                                <div style="float:left;margin:6px 10px 0px 10px;padding-top:3px;"><?php echo $ld['sort'];?></div>
								<input type="text" value="" name="clone_attr_orderby_list[<?php echo $key; ?>][<?php echo $k_locale; ?>]" size="4" style="float:left;width:80px;">
                                </td>
                        </tr>
                        <?php break;} ?>
                    </tbody>
                </table>
                <?php
                }
                ?>
                </td>
                </tr>
            </table>
            <?php }} ?>
            <script type="text/javascript">
                $(function(){
                    $("#productsAttrTable .img_select").each(function(){
                        var imgobj=$(this).find("img");
                        var imgsrc=imgobj.attr("src");
                        if(imgsrc.indexOf('default_no_photo.png')<0){
                            $(this).addClass('img_exist');
                        }
                    })
                })
            </script>
        </td>
    </tr>
</table>