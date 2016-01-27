<!--myproduct-->
<div class="am-g am-g-fixed">
  <div class="am-panel am-panel-default" style="margin-top:10px;">
    <div class="am-panel-hd my-head"><?php echo $code_infos[$sk]['name'];?></div>
	<div  class="am-panel-bd">
	  <!-- 分类商品开始 -->
	<div class="">
	<ul class="am-avg-sm-3 am-avg-md-6 am-avg-lg-6 am-slides" >
	<?php foreach($sm as $k=>$p){?>
	  <li style="height:auto;padding:5px;">
		  <?php echo $svshow->seo_link(array('type'=>'P','id'=>$p['Product']['id'],'img'=>($p['Product']['img_detail']!=''?$p['Product']['img_detail']:$configs['products_default_image']),'name'=>$p['ProductI18n']['name'],'class'=>"am-resp",'sub_name'=>$p['ProductI18n']['name']));?>
	  </li>
	<?php }?>
	</ul>
	</div>
	  <!-- 分类商品结束 -->
	</div>
  </div>
</div>
<div class="clear"></div>						

<script>
	$(".am-resp img").addClass("am-img-responsive");
</script>