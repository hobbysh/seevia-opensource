<?php if($code_infos[$sk]['type']=="module_topic_article"&&isset($sm)&&$sm!=1){ ?>
<!--mytopic_article-->
<div class="am-u-md-6">
  <h2 class="topic_title_style"><?php echo $code_infos[$sk]['name'];?></h2>
  <!-- 专题关联文章开始 -->
  <ul class="am-list am-avg-sm-1 am-topic-list">
	<?php foreach($sm as $a){ ?>
	<li>
      <div class="am-u-lg-12 am-u-md-12 am-u-sm-12">
		<a href="<?php echo $html->url('/articles/'.$a['Article']['id']); ?>">
		  <?php echo $svshow->cut_str($a['ArticleI18n']['title'],20); ?>
		</a>
	  </div>
	  <!--<div class="created"><?php //echo date("Y年m月d日",strtotime($a['Article']['created'])); ?><?php //echo $a['Article']['created']; ?></div>-->
	</li>
	<?php } ?>
  </ul>
  <!-- 专题关联文章结束 -->
</div>
<?php } ?>
