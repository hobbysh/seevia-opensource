<?php if($code_infos[$sk]['type']=="module_topic_product"&&isset($sm['TopicProduct'])&&sizeof($sm['TopicProduct'])>0){ ?>
<!--myproduct-->
<div class="am-u-md-6">
  <h2 class="topic_title_style"><?php echo $code_infos[$sk]['name'];?></h2>
  <!-- 专题关联商品开始 -->
  <ul class="am-list am-avg-sm-1 am-topic-list">
	<?php foreach($sm['TopicProduct'] as $p){?>
	<li>
	  <div class="am-u-lg-5 am-u-md-4 am-u-sm-3"><?php echo $svshow->seo_link(array('type'=>'P','id'=>$p['Product']['id'],'img'=>($p['Product']['img_detail']!=''?$p['Product']['img_detail']:$configs['products_default_image']),'name'=>$p['ProductI18n']['name'],'sub_name'=>$p['ProductI18n']['name']));?></div>
	  <div class="am-u-lg-7 am-u-md-6 am-u-sm-9"><?php echo $svshow->seo_link(array('type'=>'P','id'=>$p['Product']['id'],'name'=>$p['ProductI18n']['name'],'sub_name'=>$svshow->cut_str($p['ProductI18n']['name'],13)));?></div>
	  <div class="am-u-lg-7 am-u-md-6 am-u-sm-9">￥<?php echo $svshow->seo_link(array('type'=>'P','id'=>$p['Product']['id'],'name'=>$p['Product']['shop_price'],'sub_name'=>$p['Product']['shop_price']));?></div>
	</li>
	<?php } ?>
  </ul>
  <!-- 专题关联商品结束 -->
</div>
<?php } ?>