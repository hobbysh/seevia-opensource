<?php if($code_infos[$sk]['type']=="module_home_recommend_article"){ $article_recommend = $sm;?>
<div class="am-u-lg-6 am-u-md-6 am-rencommed  doc-example">
  <div class="am-list-news am-list-news-default am-no-layout"style="margin-top:0;">
	<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
	  <h2 class="am-titlebar-title"><?php echo $code_infos[$sk]['name'];?></h2>
	  <nav class="am-titlebar-nav">
	    <a href="/articles/category/35"><?php echo $ld['more'];?> &raquo;</a>
	  </nav>
	</div>
	<!-- 推荐文章开始 -->
	<div class="am-list-news-bd">
  	  <ul class="am-list">
	  <?php if(isset($article_recommend)){foreach($article_recommend as $k=>$v){?>
		<li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-left" >
		  <div class="am-col am-u-sm-4 am-list-thumb">
			<?php echo $svshow->seo_link(array('type'=>'A','id'=>$v['Article']['id'],'img'=>(empty($v['ArticleI18n']['img01'])?$configs['shop_default_img']:$v['ArticleI18n']['img01']),'name'=>$v['ArticleI18n']['title'],'sub_name'=>$v['ArticleI18n']['subtitle']));?>
		  </div>
		  <div class="am-col am-u-sm-8 am-list-main">
			<h3 class="am-list-item-hd" style="padding-left:0.7rem">
			<a href="<?php echo $svshow->seo_link_url(array('type'=>'A','name'=>$v['ArticleI18n']['title'],'id'=>$v['Article']['id']));?>" >
		      <?php echo $v['ArticleI18n']['title']?>
			</a>
			</h3>
			<div class="am-list-item-text" style="padding-left:0.7rem">
			  <?php echo $v['ArticleI18n']['meta_description'];?>
			</div>
		  </div>
		</li>
	  <?php }?>
  	  </ul>
	</div>
  <?php }?>
  <!-- 推荐文章结束 -->	
  </div>	
</div>
<?php }?>