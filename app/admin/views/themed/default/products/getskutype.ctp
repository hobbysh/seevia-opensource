<?php ob_start();?>
<?php if(isset($skutype_list)&&sizeof($skutype_list)>0){ ?>
    <style>
        .skutype{margin:5px 0px;}
        .skutype .title{padding:3px 0px;font-size:14px;}
        .skutype span{margin-right:12px;}
        .skutype span.title_name{margin-right:0px;}
    </style>
    <?php if(!empty($sku_attr_codelist)&&sizeof($sku_attr_codelist)>0){ ?>
        <div id="skutype_list" class="tablelist am-u-md-12 am-u-sm-12">
            <input type="hidden" id="sku_pro_type_id" value="<?php echo $sku_pro_type_id; ?>" />
            <input type="hidden" id="sku_pro_type_attr_length" value="<?php echo sizeof($sku_attr_codelist); ?>" />
            <table id="skutype_info_list" class="am-table  table-main">
                <thead>
                <tr>
                    <th><?php echo $ld['name']; ?><br />
                        <?php echo $ld['code']; ?>
                    </th>
                    
                    <th><?php echo $ld['quantity'];?></th>
                    
                    <?php foreach($sku_attr_codelist as $k=>$v){ ?>
                        <th class="attr_th <?php echo $k; ?>"><?php echo $v; ?></th>
                    <?php } ?>
                    <th style="width:150px;min-width:150px;"><?php echo $ld['price'];?></th>
                    <th style="width:150px;min-width:150px;"><?php echo $ld['operate'];?></th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($sku_pro_data)&&sizeof($sku_pro_data)>0){foreach($sku_pro_data as $v){ ?>
                    <tr id="sku_code_<?php echo $v['code'] ?>">
                        <td><?php echo $v['name'] ?><br />
                            <?php echo $v['code'] ?>
                        </td>
                        
                        <td><?php echo $v['quantity'] ?><input type="hidden" name="data[SkuProduct][quantity][]" value="<?php echo $v['quantity']; ?>" /></td>
                        
                        <?php foreach($sku_attr_codelist as $kk=>$vv){ ?>
                            <td><?php echo $v['AttrInfo'][$kk]; ?><input type='hidden' class="data_<?php echo $kk; ?>" value="<?php echo $v['AttrInfo'][$kk]; ?>"></td>
                        <?php } ?>
                        <td><input type="hidden" value="<?php echo $v['code']; ?>" name="data[SkuProduct][sku_product_code][]"  /><input type='text' value="<?php echo $v['price']; ?>" name="data[SkuProduct][price][]"  /></td>
                        <td><a href='javascript:void(0);' class='remove_sku_attr' onclick="remove_sku_pro('<?php echo $v['code'] ?>')"><?php echo $ld['remove']; ?></a></td>
                    </tr>
                <?php }} ?>
                </tbody>
            </table>
        </div>
        <div id="productSku_list_table">
            <div><?php echo $ld['product'].$ld['name']; ?>:</div><input type="text" style="float:left;margin-right:5px;width:200px;" id="sku_search_pro_name" value="" /><input style="float:left;" type="button" class="am-btn am-btn-success am-btn-sm" onclick="sku_search_pro()" value="<?php echo $ld['search_products']; ?>">
            <table class="am-table  table-main" id="sku_search_pro">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th><?php echo $ld['name']; ?></th>
                    <th><?php echo $ld['code']; ?></th>
                    <th><?php echo $ld['price'];?></th>
                    <th><?php echo $ld['quantity'];?></th>
                    <?php foreach($sku_attr_codelist as $vv){ ?>
                        <th><?php echo $vv; ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    <?php } ?>
    <script type="text/javascript">
        function sku_search_pro(){
            var sku_pro_type_id=document.getElementById("sku_pro_type_id").value;
            var pro_name=document.getElementById("sku_search_pro_name").value;
            if(pro_name.length>0){
                $.ajax({ url: "/admin/products/sku_search_pro",
                    type:"POST",
                    data:{'sku_search_pro_name': pro_name,'pro_type':sku_pro_type_id},
                    success: function(data){
                        var result=eval('result='+data);
                        $("#sku_search_pro tbody").html(result.pro_data_html);
                        check_sku_data(true);
                    }
                });
            }
        }
    </script>
<?php } ?>
<?php
$out1 = ob_get_contents();ob_end_clean();
$result=array("flag"=>'1',"sku_html"=>$out1,'sku_attr_code'=>$sku_attr_code);
echo json_encode($result);
?>