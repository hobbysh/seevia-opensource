<!--myproduct-->
<!-- 相册开始 -->
<div class="am-g am-g-fixed am-hide" style="clear:both;">
  <div class="am-u-lg-5 am-u-md-5 am-u-sm-11 am-fl" style="margin-right:4rem;padding:0 8px;">
	<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2
	  am-avg-md-2 am-avg-lg-4 am-gallery-default" data-am-gallery="{ pureview: true }">
	<?php if($code_infos[$sk]['type']=="module_pro_photo"){$i=0;foreach($sm as $p){?>
	  <li>
	    <div class="am-gallery-item">
	      <a href="<?php echo  $p['ProductGallery']['img_big'];?>" class="">
	        <img src="<?php echo $p['ProductGallery']['img_big']!=''?$p['ProductGallery']['img_big']:$configs['products_default_image'];?>"  />
	      </a>
	    </div>
	  </li>
	<?php }}?>
	</ul>
  </div>
</div>
<!-- 相册结束 -->   								