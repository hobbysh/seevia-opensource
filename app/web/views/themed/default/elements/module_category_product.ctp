
<?php if($code_infos[$sk]['type']=="module_category_product"){ ?>
		
	<?php if($sm['cate_grade']=='top'){//最顶层分类?>
	<!-- 所有推荐，最新，促销商品 -->
	<div class="am-u-md-9">
	  <?php  echo $this->element('category_products_tab',array('products_tab'=>$sm['product'],'sub_categories_product'=>$sm['sub_categories_product'],'brand'=>isset($sm['brand'])?$sm['brand']:array()));?>
	</div>
	<!-- 所有推荐，最新，促销商品 end -->
	<?php }else if($sm['cate_grade']=='middle'){//中间层分类?>
	<!-- 所有推荐，最新，促销商品 -->
	<?php echo $this->element('products_tab2',array('products_tab'=>$sm['product']));?>
	<!-- 所有推荐，最新，促销商品 end -->
	<?php }else if($sm['cate_grade']=='bottom'){//最底层分类?>
	<!-- 分类商品列表 -->
	<?php echo $this->element('products',array('products'=>$sm['bottom'],"pages_list"=>$sm['paging']));?>
	<!-- 分类商品列表 end -->
	<?php }?>
<?php }?>

<?php if(isset($category['CategoryProduct'])){ ?>
<script type="text/javascript">
var wechat_shareTitle="<?php echo $category['CategoryProductI18n']['name'] ?>";
var wechat_descContent="<?php echo $svshow->emptyreplace($category['CategoryProductI18n']['detail'])==''?$category['CategoryProductI18n']['meta_description']:$svshow->emptyreplace($category['CategoryProductI18n']['detail']); ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php if($svshow->imgfilehave($server_host.(str_replace($server_host,'',$category['CategoryProduct']['img01'])))){ ?>
var wechat_imgUrl="<?php echo $server_host.(str_replace($server_host,'',$category['CategoryProduct']['img01'])); ?>";
<?php } ?>
</script>
<?php } ?>