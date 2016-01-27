<!-- \\192.168.10.211\core\co\views\themes\default\elements\module_article_category.ctp -->

<?php //pr($sm);?>
<div id="article_recommend" class="am-u-md-3 am-rencommed am-fr">
  <h2><?php echo $code_infos[$sk]['name'];?></h2>
  <!-- 推荐文章开始 -->					
  <?php if($code_infos[$sk]['type']=="module_article_recommend"){ $article_recommend = $sm;?>
  <ul class="am-list am-avg-sm-1">
	<?php if(isset($article_recommend)){foreach($article_recommend as $k=>$v){?>
	<li class="am-padding-vertical-xs <?php echo (isset($zhou_cats) && $zhou_cats[0]==$v['Article']['id'])?'current':''?>">
	  <div class="am-u-lg-5 am-u-md-4 am-u-sm-3">
		<?php echo $svshow->seo_link(array('type'=>'A','id'=>$v['Article']['id'],'img'=>(empty($v['ArticleI18n']['img01'])?$configs['shop_default_img']:$v['ArticleI18n']['img01']),'name'=>$v['ArticleI18n']['title'],'sub_name'=>$v['ArticleI18n']['subtitle']));?>
	  </div>
	  <div class="am-u-lg-7 am-u-md-6 am-u-sm-9">
		<a href="<?php echo $svshow->seo_link_url(array('type'=>'A','name'=>$v['ArticleI18n']['title'],'id'=>$v['Article']['id']));?>" title="<?php echo $v['ArticleI18n']['title']; ?>">
	      <?php echo substr($v['ArticleI18n']['title'],0,42);?>
		</a>
	  </div>
	</li>
	<?php }?>
  </ul>
  <?php }}?>
  <!-- 推荐文章结束 -->
</div>
