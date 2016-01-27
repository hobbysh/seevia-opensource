<div style="clear:both;"></div>
<div class="am-g am-g-fixed">
  <div class="am-panel am-panel-default" style="margin-top:10px;">
    <div class="am-panel-hd my-head"><?php echo $code_infos[$sk]['name'];?></div>
	<div  class="am-panel-bd">
	  <!-- 分类商品开始 -->
		<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-4 am-gallery-overlay" data-am-gallery="{ }">
		<?php foreach($sm as $vv){?>
		  <li>
		    <div class="am-gallery-item">
		      <?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
			  <span class="like_icon am-gallery-like" style="">
			    <img id="<?php echo $vv['Product']['id'];?>" style="width:15px;height:15px;" src="/theme/default/img/like_icon.png" />
			    <span style="" id="<?php echo 'like_num'.$vv['Product']['id'];?>" class="like_num">
				  <?php echo $vv['Product']['like_stat'];?>
				</span>
			  </span>
			  <?php } ?>
			  <?php echo $svshow->seo_link(array('type'=>'P','id'=>$vv['Product']['id'],'img'=>($vv['Product']['img_detail']!=''?$vv['Product']['img_detail']:$configs['products_default_image']),'name'=>$vv['ProductI18n']['name'],'sub_name'=>$vv['ProductI18n']['name']));?>
			  <h3 class="am-gallery-title">
				<?php echo $svshow->seo_link(array('type'=>'P','id'=>$vv['Product']['id'],'name'=>$vv['ProductI18n']['name'],'sub_name'=>$vv['ProductI18n']['name']));?>
			  </h3>
			</div>
			<div class="am-g pro_price pro_unit">
                      	<?php if(isset($configs['show_product_price_onlist'])&&$configs['show_product_price_onlist']=='1'){ if(isset($vv['price_range'])){echo $svshow->price_format($vv['price_range']['min_price'],$configs['price_format'])." -".$svshow->price_format($vv['price_range']['max_price'],$configs['price_format']);}else{echo $svshow->price_format($vv['Product']['shop_price'],$configs['price_format']);}} ?>
                      	<?php if(isset($configs['show_product_unit'])&&$configs['show_product_unit']=='1'&&!empty($vv['Product']['unit'])){ echo "/".$vv['Product']['unit'];} ?>
            		</div>
		  </li>
		<?php }?>
		</ul>
	  <!-- 分类商品结束 -->
	</div>
  </div>
</div>
<div class="clear"></div>