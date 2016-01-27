<?php 
/*****************************************************************************
 * SV-Cart 文章公共分类树
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
<select name="article_category_id" id="article_category_id" data-am-selected>
	<option value="0"><?php echo $ld['article_categories']?></option>
	<?php if(isset($category_tree_articles) && sizeof($category_tree_articles)>0){?><?php foreach($category_tree_articles as $first_k=>$first_v){?>
	<option value="<?php echo $first_v['CategoryArticle']['id'];?>" <?php if($first_v['CategoryArticle']['id']==$article_category_id){ echo "selected";}?> ><?php echo $first_v['CategoryArticleI18n']['name'];?></option>
	<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?><?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
	<option value="<?php echo $second_v['CategoryArticle']['id'];?>" <?php if($second_v['CategoryArticle']['id']==$article_category_id){ echo "selected";}?> >&nbsp;&nbsp;<?php echo $second_v['CategoryArticleI18n']['name'];?></option>
	<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?><?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
	<option value="<?php echo $third_v['CategoryArticle']['id'];?>" <?php if($third_v['CategoryArticle']['id']==$article_category_id){ echo "selected";}?> >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryArticleI18n']['name'];?></option>
	<?php }}}}}}?>
</select>
