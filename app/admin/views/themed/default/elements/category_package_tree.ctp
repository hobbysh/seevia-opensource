<?php
/*****************************************************************************
 * SV-Cart 公共分类树
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
<select name="category_package_id" id="category_package_id" style="width:150px">
	<option value=""><?php echo $ld['select_categories']?></option>
	<option value="-1" <?php if(isset($category_id) && $category_id ==-1)echo 'selected';?>><?php echo $ld['unknown_classification']?></option>
<!--	<option value="0" <?php if($category_id == '0'){?>selected<?php }?> ><?php echo $ld['not_in_category']?></option>	-->
	<?php if(isset($category_tree) && sizeof($category_tree)>0){ $category_id = isset($category_id)?$category_id:'';foreach($category_tree as $first_k=>$first_v){?>
		<option value="<?php echo $first_v['CategoryProduct']['id'];?>" <?php if($category_id == $first_v['CategoryProduct']['id']){?>selected<?php }?> ><?php echo $first_v['CategoryProductI18n']['name'];?></option>
	<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?><?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
		<option value="<?php echo $second_v['CategoryProduct']['id'];?>" <?php if($category_id == $second_v['CategoryProduct']['id']){?>selected<?php }?> >&nbsp;&nbsp;<?php echo $second_v['CategoryProductI18n']['name'];?></option>
	<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?><?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
		<option value="<?php echo $third_v['CategoryProduct']['id'];?>" <?php if($category_id == $third_v['CategoryProduct']['id']){?>selected<?php }?> >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryProductI18n']['name'];?></option>

	<?php }}}}}}?>
</select>

