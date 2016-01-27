<div class="am-u-md-12">
  <h2 class="topic_info_title"><?php// echo $code_infos[$sk]['name'];?></h2> 
  <?php if($code_infos[$sk]['type']=="module_topic_info"){ ?>
	<?php if(!empty($sm['Topic'])){ ?>
	<div id="top">
	  <div class="am-u-lg-5 am-u-md-4 am-u-sm-5">
		<img src="<?php echo $sm['TopicI18n']['img01']; ?>"  class="am-img-responsive" />
	  </div>
	  <div class="am-u-lg-7 am-u-md-6 am-u-sm-6"><?php echo $sm['TopicI18n']['title'] ?></div>		
	  <div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
	         <time>
	  	  <?php echo $ld["time"]; ?>:<?php echo date("Y/m/d",strtotime($sm["Topic"]["start_time"])); ?>-<?php echo date("Y/m/d",strtotime($sm["Topic"]["end_time"])); ?>
		</time>
	  </div>
	</div>
	<!-- 专题详情内容 -->
	<div class="topiccontent" style="clear:both">
	  <h2 class="topic_detail"></h2>
	  <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 auto_zoom">
		<?php echo $sm["TopicI18n"]["intro"]; ?>
	  </div>
	</div>
	<!-- 专题详情内容 -->
  <?php }}?>
</div>
<script type="text/javascript">
var wechat_shareTitle="<?php echo $sm['TopicI18n']['title'] ?>";
var wechat_descContent="<?php echo $svshow->emptyreplace($sm['TopicI18n']['intro']); ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php if($svshow->imgfilehave($server_host.(str_replace($server_host,'',$category['TopicI18n']['img01'])))){ ?>
var wechat_imgUrl="<?php echo $server_host.(str_replace($server_host,'',$category['TopicI18n']['img01'])); ?>";
<?php } ?>
</script>