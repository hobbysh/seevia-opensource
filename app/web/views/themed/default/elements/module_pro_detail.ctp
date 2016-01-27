<div  class="am-g am-g-fixed" style="clear:both;padding-top:10px;">
  <div class="am-panel am-panel-default">
               <section data-am-widget="accordion" class="am-accordion am-accordion-gapped" data-am-accordion='{  }' style="margin:0;">
                  <dl class="am-accordion-item" style="border:1px solid #dedede;">
                    <dt class="am-accordion-title" style="padding:0px 0px;background:#F5F5F5">	
			<div class="am-panel-hd my-head "><?php echo $code_infos[$sk]['name'];?></div></dt>
    <!--商品详情-->
    <dd class="am-accordion-bd am-collapse am-in">
<div class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-overlay am-no-layout" data-am-gallery="{ }" data-am-widget="gallery">
	<div class="am-panel-bd am-gallery-item product_description auto_zoom" style="padding:5px 15px;">
		<?php if(isset($sm['product_category_data'])){foreach($sm['product_category_data'] as $v){?>
		<?php echo isset($v['CategoryProductI18n']['top_detail'])?$v['CategoryProductI18n']['top_detail']:'';?>
		<?php }}?>
		<?php echo isset($sm['product_detail']['ProductI18n']['description'])?$sm['product_detail']['ProductI18n']['description']:""; ?>
		<?php if(isset($sm['product_category_data'])){foreach($sm['product_category_data'] as $v){?>
		<?php echo isset($v['CategoryProductI18n']['foot_detail'])?$v['CategoryProductI18n']['foot_detail']:'';?>
		<?php }}?>
	</div>
</div>
	 </dd>
    </dl>
  </section>
  </div>
</div>
<script type="text/javascript">
var wechat_descContent="<?php echo $svshow->emptyreplace($sm['product_detail']['ProductI18n']['description']); ?>";
</script>
<style type="text/css">
.am-slider .am-control-nav, .am-slider-carousel ul.am-direction-nav {display: none;}
</style>