<?php 
/*****************************************************************************
 * SV-Cart 网站导航
 *===========================================================================
 * 版权所有上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 *---------------------------------------------------------------------------
 *这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *不允许对程序代码以任何形式任何目的的再发布。
 *===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
?>
<?php echo $xml->header(); ?>
<?php echo "<?xml-stylesheet type='text/xsl' href='".$server_host.$webroot."sitemap.xsl' ?>";?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php
if(isset($sitemaps) && sizeof($sitemaps)>0 ){
	foreach($sitemaps as $key=>$v){
		if(($v['Sitemap']['url']=='/topics'&&count($Topic_list)>0&&$v['Sitemap']['type']=="")||($v['Sitemap']['url']=='/promotions'&&count($Promotion_list)>0&&$v['Sitemap']['type']=="")){ ?>
		<url>
			<loc><?php echo Router::url($v['Sitemap']['url'],true); ?></loc>
			<changefreq><?php echo $v['Sitemap']['cycle']?></changefreq>
			<priority><?php echo $v['Sitemap']['priority']; ?></priority>
		</url>
		<?php }
		
		if($v['Sitemap']['type']=="brands"&&isset($brands)&&count($brands)>0){
			foreach($brands as $brand){ ?>
			<!-- 商品品牌 -->
				<url>
					<loc><?php echo Router::url(array('controller'=>$v['Sitemap']['url'],'action'=>'view','id'=>$brand['Brand']['id']),true); ?></loc>
					<lastmod><?php echo $time->toAtom($brand['Brand']['modified']); ?></lastmod>
					<changefreq><?php echo $v['Sitemap']['cycle']?></changefreq>
					<priority><?php echo $v['Sitemap']['priority']?></priority>
				</url>
	<?php 	}
		}
		if($v['Sitemap']['type']=="articles_cat"&&isset($article_cat)&&count($article_cat)>0){
			foreach($article_cat as $article_cat_info){ ?>
				<!-- 文章分类 -->
			 <?php if(isset($languages) && sizeof($languages)>0){?>
			 	 <?php foreach($languages as $a=>$b){?>
				<url>
					<loc><?php echo Router::url(array('controller'=>$svshow->seo_link_path(array('type'=>'AC','name'=>$article_cat_info['CategoryArticleI18n']['name'],'id'=>$article_cat_info['CategoryArticle']['id'])),'action'=>''),true); ?></loc>
					<lastmod><?php echo $time->toAtom($article_cat_info['CategoryArticle']['modified']); ?></lastmod>
					<changefreq><?php echo $v['Sitemap']['cycle']?></changefreq>
					<priority><?php echo $v['Sitemap']['priority']?></priority>
				</url>
				<?php }}?>
	<?php 	}
		}
		if($v['Sitemap']['type']=="product_cat"&&isset($product_cat)&&count($product_cat)>0){
			foreach($product_cat as $product_cat_info){ ?>
			 <!-- 商品分类 -->
				<url>
					 <loc><?php echo Router::url(array('controller'=>$svshow->seo_link_path(array('type'=>'PC','name'=>$product_cat_info['CategoryProductI18n']['name'],'id'=>$product_cat_info['CategoryProduct']['id'])),'action'=>''),true); ?></loc>
					 <lastmod><?php echo $time->toAtom($product_cat_info['CategoryProduct']['modified']); ?></lastmod>
					<changefreq><?php echo $v['Sitemap']['cycle']?></changefreq>
					 <priority><?php echo $v['Sitemap']['priority']?></priority>
				</url>
	<?php 		}
		}
		if($v['Sitemap']['type']=="products"&&isset($products)&&count($products)>0){
					foreach($products as $product){ ?>
					<!-- 商品 -->
						<url>
			<loc><?php echo Router::url(array('controller'=>$svshow->seo_link_path(array('type'=>'P','id'=>$product['Product']['id'],'name'=>$product['ProductI18n']['name'])),'action'=>''),true); ?></loc>
							<lastmod><?php echo $time->toAtom($product['Product']['modified']); ?></lastmod>
					<changefreq><?php echo $v['Sitemap']['cycle']?></changefreq>
							<priority><?php echo $v['Sitemap']['priority']?></priority>
						</url>
	<?php 		}
		}
		if($v['Sitemap']['type']=="articles"&&isset($articles)&&count($articles)>0){
			foreach($articles as $article){ ?>
			 <!-- 文章 -->
			 <?php if(isset($languages) && sizeof($languages)>0){?>
			 	 <?php foreach($languages as $a=>$b){?>
				<url>
					 <loc><?php echo Router::url(array('controller'=>$svshow->seo_link_path(array('type'=>'A','name'=>$article['ArticleI18n']['title'],'sub_name'=>$article['ArticleI18n']['title'],'id'=>$article['Article']['id'])),'action'=>''),true); ?></loc>
					 <lastmod><?php echo $time->toAtom($article['Article']['modified']); ?></lastmod>
					<changefreq><?php echo $v['Sitemap']['cycle']?></changefreq>
					 <priority><?php echo $v['Sitemap']['priority']?></priority>
				</url>
				<?php }?>
			  <?php }?>
	<?php 		}
			}
		elseif($v['Sitemap']['type'] != "articles" && $v['Sitemap']['type'] != "brands"  && $v['Sitemap']['type'] != "product_cat"  && $v['Sitemap']['type'] != "products" && $v['Sitemap']['type'] != "comments" && $v['Sitemap']['type'] != "gallery"){
	?>
	   <?php if(($v['Sitemap']['url']=='/products/advancedsearch/SAD/all'&&count($products)>0)||($v['Sitemap']['url']=='/articles/home'&&count($articles)>0)||$v['Sitemap']['url']=='/'){?>
		<url>
			<loc><?php echo Router::url($v['Sitemap']['url'],true); ?></loc>
			<changefreq><?php echo $v['Sitemap']['cycle']?></changefreq>
			<priority><?php echo $v['Sitemap']['priority']; ?></priority>
		</url>
		<?php }?>
			
			
		<?php if($v['Sitemap']['type']=="rec_products"&&isset($rec_products)&&count($rec_products)>0){
			foreach($rec_products as $rec_product){ ?>
			<!-- 推荐商品 -->
				<url>
				<loc><?php echo Router::url(array('controller'=>$svshow->seo_link_path(array('type'=>'P','id'=>$rec_product['Product']['id'],'name'=>$rec_product['ProductI18n']['name'])),'action'=>''),true); ?></loc>

					<lastmod><?php echo $time->toAtom($rec_product['Product']['modified']); ?></lastmod>
					<changefreq><?php echo $v['Sitemap']['cycle']?></changefreq>
					<priority><?php echo $v['Sitemap']['priority']?></priority>
				</url>
		<?php }}?>
		<?php if($v['Sitemap']['type']=="topics"&&isset($Topic_list)&&count($Topic_list)>0){
			foreach($Topic_list as $topic){ ?>
			<!-- 专题 -->
				<url>
					<loc><?php echo Router::url(array('controller'=>$v['Sitemap']['url'],'action'=>'/'.$topic['Topic']['id']),true); ?></loc>
					<lastmod><?php echo $time->toAtom($topic['Topic']['modified']); ?></lastmod>
					<changefreq><?php echo $v['Sitemap']['cycle']?></changefreq>
					<priority><?php echo $v['Sitemap']['priority']?></priority>
				</url>
		<?php }}?>
		<?php if($v['Sitemap']['type']=="promotions"&&isset($Promotion_list)&&count($Promotion_list)>0){
			foreach($Promotion_list as $promotion){ ?>
			<!-- 促销 -->
				<url>
					<loc><?php echo Router::url(array('controller'=>$v['Sitemap']['url'],'action'=>'/'.$promotion['Promotion']['id']),true); ?></loc>
					<lastmod><?php echo $time->toAtom($promotion['Promotion']['modified']); ?></lastmod>
					<changefreq><?php echo $v['Sitemap']['cycle']?></changefreq>
					<priority><?php echo $v['Sitemap']['priority']?></priority>
				</url>
		<?php }}?>
		<?php if($v['Sitemap']['url']=='/products/advancedsearch/1/0/0/0/0/0/0/0/0'&&isset($hot_search_keywords)&&count($hot_search_keywords)>0){
						foreach($hot_search_keywords as $k=>$keyword){ ?>
			<!--关键字 -->
				<url>
					<loc><?php echo Router::url(array('controller'=>$v['Sitemap']['url'],'action'=>'/'.htmlspecialchars($keyword)),true); ?></loc>
					<changefreq><?php echo $v['Sitemap']['cycle']?></changefreq>
					<priority><?php echo $v['Sitemap']['priority']?></priority>
				</url>

		<?php }}?>

		
<?php 	}}
}
?>
</urlset>
