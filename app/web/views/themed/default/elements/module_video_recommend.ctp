<!-- \\192.168.10.211\core\co\views\themes\default\elements\module_article_category.ctp -->

<?php //pr($sm);?>
<?php if($code_infos[$sk]['type']=="module_video_recommend"){ $article_recommend = $sm;?>
<div class="am-u-lg-4 am-u-md-4 am-u-sm-12">
  <div class="am-list-news am-list-news-default am-no-layout" style="margin:10px auto;">
	<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" style="border:0;margin:0;">
	  <h2 class="am-titlebar-title"><?php echo $code_infos[$sk]['name'];?></h2>
	</div>
	<div class="am-list-news-bd">
	  <ul class="am-list">
	  <?php if(isset($article_recommend)){foreach($article_recommend as $k=>$v){?>
	    <li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-left">
	      <div class="am-col am-u-sm-4 am-list-thumb">
		    <?php echo $svshow->seo_link(array('type'=>'AV','id'=>$v['Article']['id'],'img'=>(empty($v['ArticleI18n']['img01'])?$configs['shop_default_img']:$v['ArticleI18n']['img01']),'name'=>$v['ArticleI18n']['title'],'sub_name'=>$v['ArticleI18n']['subtitle']));?>
		  </div>
		  <div class="am-col am-u-sm-8 am-list-main">
		    <h3 class="am-list-item-hd "><?php echo $svshow->seo_link(array('type'=>'AV', 'name'=>$v['ArticleI18n']['title'], 'sub_name'=>$v['ArticleI18n']['title'], 'id'=>$v['Article']['id']));?></h3>
		    <div class="am-list-item-text">
			  <?php echo $svshow->seo_link(array('type'=>'AV','name'=>$v['ArticleI18n']['subtitle'],'id'=>$v['Article']['id']));?>
		    </div>
		  </div>
	    </li>
	  <?php }}?>
	  </ul>
	</div>
  </div>
</div>
<?php }?>