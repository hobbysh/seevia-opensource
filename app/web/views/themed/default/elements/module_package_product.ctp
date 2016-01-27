<?php if(isset($sm)&&sizeof($sm)>0){ ?>

<div class="am-g am-g-fixed" style="clear:both;">
  <div class="am-panel am-panel-default" style="margin-top:10px;">
    <div class="am-panel-hd my-head"><?php echo $code_infos[$sk]['name'];?></div>
	<div  class="am-panel-bd">
	  <ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-5 am-gallery-overlay" data-am-gallery="{ }">
	  <?php $flagnum2=0;foreach($sm as $k=>$v){ ?>
		<li>
		  <div class="am-gallery-item">
			<?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>($v['Product']['img_detail']!=''?$v['Product']['img_detail']:$configs['products_default_image']),'name'=>$v['PackageProduct']['package_product_name'],'sub_name'=>$v['PackageProduct']['package_product_name'],'target'=>'_blank'));?>
			<h3 class="am-gallery-title">
			  <div class="am-u-lg-7 am-u-md-7 am-u-sm-7" style="padding:0;overflow:hidden;text-overflow:ellipsis;">
			  <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['PackageProduct']['package_product_name'],'sub_name'=>$v['PackageProduct']['package_product_name'],'target'=>'_blank'));?>
			  </div>
			  <div class="am-u-lg-5 am-u-md-4 am-u-sm-5" style="color:#fff;padding:0;text-align:right;"><?php  echo $svshow->price_format($v['Product']['shop_price'],$configs['price_format']); ?>(数量:<?php echo $v['PackageProduct']['package_product_qty'] ?>)</div>
			</h3>
		  </div>
		</li>
	  <?php } ?>
	  </ul>
	</div>
  </div>
</div>
<?php } ?>