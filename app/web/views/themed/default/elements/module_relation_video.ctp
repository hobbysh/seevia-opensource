<?php //pr($sm);?>
<div id="<?php echo $sk?>" class=" <?php echo 'div_'.$sk; ?> am-u-lg-4 am-u-md-4 am-u-sm-12">		
  <?php if($code_infos[$sk]['type']=="module_relation_video"){?>
  <div id="mainBox" >
  	<div  data-am-widget="list_news" class="am-list-news am-list-news-default" style="margin:0;">
	  <div class="am-titlebar am-titlebar-default am-no-layout" data-am-widget="titlebar"  style="margin:0;border:none;">
		<h2 class="am-titlebar-title">
		  <?php echo isset($video_type['CategoryArticleI18n']['name'])?$video_type['CategoryArticleI18n']['name']:"相关视频";?>
		</h2>
	  </div>
<!--	  <div class="am-list-news-hd am-cf">
		<h2><?php echo isset($video_type['CategoryArticleI18n']['name'])?$video_type['CategoryArticleI18n']['name']:"相关视频";?></h2>
	  </div>-->
      <div id="wrapper" class="am-list-news-bd"  style="max-height:423px;overflow:hidden;">
        <ul class="am-list">
		<?php $titel_i=0;if(!empty($sm)){foreach( $sm as $k=>$v){?>
		  <li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-left">
			<div class="am-u-sm-4 am-list-thumb">
			  <?php echo $svshow->seo_link(array('type'=>'AV','id'=>$v['Article']['id'],'img'=>$v['ArticleI18n']['img01'],'name'=>$v['ArticleI18n']['title'],'sub_name'=>$v['ArticleI18n']['title']));?>
			</div>
			<div class="am-u-sm-8 am-list-main">
			  <h3 class="am-list-item-hd" title="<?php echo $v['ArticleI18n']['title']?>">
				<?php echo $v['ArticleI18n']['title']?>
			  </h3>
			  <div class="am-list-item-text">
				<?php echo $svshow->seo_link(array('type'=>'AV','id'=>$v['Article']['id'],'name'=>$v['ArticleI18n']['subtitle'],'sub_name'=>$v['ArticleI18n']['subtitle']));?>
			  </div>
			</div>
		  </li>
		<?php }}?>
		</ul>
	  </div>
	</div>
  </div>
  <?php }?>
</div>

<style>
.am-list-item-hd{white-space:nowrap;overflow: hidden;text-overflow:ellipsis;}
</style>
<script type="text/javascript">
var wrapper = document.getElementById('wrapper');
var IScroll = $.AMUI.IScroll;
var myScroll = new IScroll(wrapper);


</script>