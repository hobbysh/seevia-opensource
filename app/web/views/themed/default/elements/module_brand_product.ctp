<div class="am-u-md-8">
  <h2><?php echo $code_infos[$sk]['name'];?></h2>
  <div>
	<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-3 am-gallery-overlay" data-am-gallery="{ }">
	<?php $flagnum=0; $i=0;foreach($sm as $k=>$v){ ?>
	  <li>
		<div class="am-gallery-item">
		  <?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
		  <span class="like_icon am-gallery-like" style="">
		    <img id="<?php echo $v['Product']['id'];?>" style="width:15px;height:15px;" src="/theme/default/img/like_icon.png" />
		    <span style="" id="<?php echo 'like_num'.$v['Product']['id'];?>" class="like_num">
			  <?php if(isset($v['Product']['like_stat'])){echo $v['Product']['like_stat'];}else{echo '0';}?>
			</span>
		  </span>
		  <?php } ?>
		  <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>($v['Product']['img_detail']!=''?$v['Product']['img_detail']:$configs['products_default_image']),'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name']));?>
		  <h3 class="am-gallery-title">
	        	<?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name']));?>
	      </h3>
	    </div>
		<div class="am-g pro_price pro_unit">
			<?php if(isset($configs['show_product_price_onlist'])&&$configs['show_product_price_onlist']=='1'){ if(isset($v['price_range'])){echo $svshow->price_format($v['price_range']['min_price'],$configs['price_format'])." -".$svshow->price_format($v['price_range']['max_price'],$configs['price_format']);}else{echo $svshow->price_format($v['Product']['shop_price'],$configs['price_format']);}} ?>
			<?php if(isset($configs['show_product_unit'])&&$configs['show_product_unit']=='1'&&!empty($v['Product']['unit'])){ echo "/".$v['Product']['unit'];} ?>
		</div>
		<?php $i++; $flagnum++; if($i==12){break;}?>
	  </li>
	<?php }?>
	</ul>
  </div>
</div>
