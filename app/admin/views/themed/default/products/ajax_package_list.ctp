<?php
ob_start();
?>
    <table style="width:95%;" class="tablelist">
        <thead>
        <tr style="background:#E9F8D9;">
            <th id="taozhuang"><?php echo $ld['product'].$ld['picture'];?></th>
            <th class="thname thbang"><?php echo $ld['code']?></th>
            <th class="thname thbang"><?php echo $ld['name']?></th>
            <th class="thsort"><?php echo $ld['price']?></th>
            <th class="thsort"><?php echo $ld['quantity']?></th>
            <th class="thsort"><?php echo $ld['app_qty']?></th>
            <th class="thsort"><?php echo $ld['sort']?></th>
            <th><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($package_products)&&!empty($package_products)){foreach($package_products as $pk=>$pv){?>
            <tr >
                <td><a target="_blank" href="/admin/products/view/<?php echo $pv['Product']['id']?>"><img style="margin:5px 0 5px 10px;" width='45px' height='45px' src="<?php echo $pv['Product']['img_thumb']?>"/></a></td>
                <td><?php echo $pv['Product']['code']?>
                    <input type="hidden" name="data[PackageProduct][<?php echo $pk;?>][package_product_id]" value="<?php echo $pv['Product']['id'];?>">
                    <input type="hidden" name="data[PackageProduct][<?php echo $pk;?>][package_product_code]" value="<?php echo $pv['Product']['code'];?>">
                </td>
                <td><?php if($svshow->operator_privilege('products_edit')){?><span onclick="update_packageproduct_name(this)"><?php } echo $pv['ProductI18n']['name'];?></span>
                    <input type="hidden" name="data[PackageProduct][<?php echo $pk;?>][package_product_name]" value="<?php echo $pv['ProductI18n']['name'];?>">
                </td>
                <td class="thsort"><?php echo $pv['Product']['shop_price']?></td>
                <td class="thsort"><?php echo $pv['Product']['quantity']?></td>
                <td class="thsort">
                    <?php if($svshow->operator_privilege('products_edit')){?><span onclick="update_package_qty(this)"><?php } //echo $pv['Product']['qty'];?>1</span>
                    <input type="hidden" name="data[PackageProduct][<?php echo $pk;?>][package_product_qty]" value="1">
                </td>
                <td class="thsort"><?php if(count($package_products)==1){echo "-";}elseif($pk==0){?>
                        <a class="up" onclick="up(this)" style="color:#cc0000;display:none;">&#9650;</a>&nbsp;<a class="down" onclick="down(this)">&#9660;</a>
                    <?php }elseif($pk==(count($package_products)-1)){?>
                        <a class="up" onclick="up(this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a style="display:none;" class="down" onclick="down(this)">&#9660;</a>
                    <?php }else{?>
                        <a class="up" onclick="up(this)" style="color:#cc0000;">&#9650;</a>&nbsp;<a class="down" onclick="down(this)">&#9660;</a>
                    <?php }?>
                    <input class="pkg_orderby" type="hidden" name="data[PackageProduct][<?php echo $pk;?>][orderby]" value="<?php echo $pk;?>">
                </td>
                <td><a href="javascript:void(0);" id="<?php echo $pv['Product']['id']?>" onclick="del_package(this)" class="del_package_product am-btn am-btn-danger am-btn-xs am-radius"><?php echo $ld["delete"]?></a></td>
            </tr>
        <?php }}else{?>
            <tr><td></td><td colspan="5" align="center"><?php echo $ld['not_add_package']?></td><td></td></tr>
        <?php }?>
        </tbody>
    </table>
    <style>
        #package_table .tablelist tr .thsort {
            text-align: center;width:50px;padding:0px 8px;
        }
    </style>
<?php
$out1 = ob_get_contents();
ob_end_clean();
$result=array("content"=>$out1);
die(json_encode($result));
?>