<?php if($code_infos[$sk]['type']=="module_home_article"){//pr($sm); ?>
<div class="am-u-lg-6 am-u-md-6 doc-example" >
  <div class="am-list-news am-list-news-default am-no-layout " style="margin-top:0px;">
	<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default" >
	  <h2 class="am-titlebar-title"><?php echo $code_infos[$sk]['name'];?></h2>
	  <nav class="am-titlebar-nav">
	    <a href="/articles/category/1" class=""><?php echo $ld['more'];?> &raquo;</a>
	  </nav>
	</div>
	<div class="am-list-news-bd" >
	  <ul class="am-list">
	  <?php foreach($sm as $k=>$a){?>
	    <li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-left">
	      <div class="am-col am-u-sm-4 am-list-thumb articleimg">
		    <?php echo $svshow->seo_link(array('type'=>'A','id'=>$a['Article']['id'],'img'=>(empty($a['ArticleI18n']['img01'])?$configs['shop_default_img']:$a['ArticleI18n']['img01']),'name'=>$a['ArticleI18n']['title']));?>
		  </div>

		  <div class="am-col am-u-sm-8 am-list-main" >
		    <h3 class="am-list-item-hd " style="padding-left:0.7rem" ><?php echo $svshow->seo_link(array('type'=>'A', 'name'=>$a['ArticleI18n']['title'], 'sub_name'=>$a['ArticleI18n']['title'], 'id'=>$a['Article']['id']));?></h3>
		    <div class="am-list-item-text" style="padding-left:0.7rem">
			  <?php echo $a['ArticleI18n']['meta_description'];?>
		    </div>
		  </div>
		  <div class="am-cf"></div>
	    </li>
	  <?php }?>
	  </ul>
	</div>
  </div>
</div>
<?php }?>

