<?php
/*****************************************************************************
 * SV-Cart 公共类型树
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
?>
<style type="text/css">
.product_type_tree div[class*="am-u-"]:last-child{float:left;}
.am-form selec{
    font-size: 1.2rem;
}
</style>
<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1 product_type_tree">
   <li>
        <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text" style=""><?php echo $ld['select_attribute'];?></label>
        <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
            <?php if(!empty($product_type_tree)){?>
                <select name="product_type_id" id="product_type_id" onchange="getAttr()">
                    <option value="0" selected><?php echo $ld['all_data']?></option>
                    <?php if(isset($product_type_tree) && sizeof($product_type_tree)>0){foreach($product_type_tree as $k=>$v){?>
                        <option value="<?php echo $v['ProductType']['id']?>" <?php if($product_type_id == $v['ProductType']['id']){?>selected<?php }?>><?php echo $v['ProductTypeI18n']['name']?></option>
                    <?php }}?>
                </select>
            <?php }?>
        </div>
   </li>
   <li>
        <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
        <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
            <select name="attr_cate_id" id='attr_cate_id' onchange="formsubmit()" <?php if(empty($attr_cate)){echo 'style="display:none;"';}else{echo '';}?>>
                <option value="" selected>关联属性分类</option>
                <?php if(!empty($attr_cate)){foreach($attr_cate as $atk=>$atv){?>
                    <option value="<?php echo $atv['CategoryProduct']['id'];?>" <?php if(isset($attr_cate_sel)&& $attr_cate_sel==$atv['CategoryProduct']['id']){ echo "selected";}?>><?php echo $atv['CategoryProductI18n']['name'];?></option>
                <?php }}?>
            </select>
        </div>
    </li>
    <li>
        <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
        <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
            <select name="product_attr_id" id="product_attr_id" onchange="getDropdownInfo();" style="display:none;">
                <option value="0"><?php echo $ld['select_attribute']?></option>
            </select>
        </div>
    </li>
    <li>
        <label class="am-u-lg-3  am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
        <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
            <select name="product_dropdown_id" id="product_dropdown_id"  style="display:none;">
                <option value="0"><?php echo $ld['select_attribute_value']?></option>
            </select>
        </div>
    </li>
</ul>