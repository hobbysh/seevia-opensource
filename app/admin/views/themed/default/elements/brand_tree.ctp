<?php
/*****************************************************************************
 * SV-Cart 公共品牌树
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
<?php if(!empty($brand_tree)){?>
	<select name="brand_id" id="brand_id" data-am-selected="{maxHeight:400,noSelectedText:'<?php echo $ld['all'] ?>'}">
		<option value="0"><?php echo $ld['all_data']?></option>
		<option value="-1" <?php if(isset($brand_id) && $brand_id ==-1)echo 'selected';?>><?php echo $ld['unknown_brand']?></option>
		<?php if(isset($brand_tree) && sizeof($brand_tree)>0){$brand_id = isset($brand_id)?$brand_id:'';?><?php foreach($brand_tree as $k=>$v){?>
		<option value="<?php echo $v['Brand']['id']?>" <?php if($brand_id == $v['Brand']['id']){?>selected<?php }?>><?php echo $v['BrandI18n']['name']?></option>
		<?php }}?>
	</select>
<?php }?>
