<?php $flagnum2=0;//pr($products_tab);?>		
<?php if(!empty($products_tab)){?>
  <?php foreach($products_tab as $k=>$v){?>
    <?php if(!empty($v)){?>
	<div class="am-u-md-9 am-fr">
	  <h2><?php echo $ld[$k];?></h2>
	  <ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-3 am-gallery-overlay" data-am-gallery="{ }">
		<?php $flagnum=0;foreach($v as $vv){?>
		<li>
		  <div class="am-gallery-item">
		    <?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
			<span class="like_icon am-gallery-like" style="">
			  <img id="<?php echo $vv['Product']['id'];?>" src="/theme/default/img/like_icon.png" />
			  <span style="" id="<?php echo 'like_num'.$vv['Product']['id'];?>" class="like_num">
			    <?php if(isset($vv['Product']['like_num'])){echo $vv['Product']['like_num'];}else{echo '0';}?>
			  </span>
			</span>
			<?php } ?>
			<?php echo $svshow->seo_link(array('type'=>'P','id'=>$vv['Product']['id'],'img'=>($vv['Product']['img_detail']!=''?$vv['Product']['img_detail']:$configs['products_default_image']),'name'=>$vv['Product']['name'],'sub_name'=>$vv['Product']['name']));?>
			<h3 class="am-gallery-title">
	          <?php echo $svshow->seo_link(array('type'=>'P','id'=>$vv['Product']['id'],'name'=>$vv['Product']['name'],'sub_name'=>$vv['Product']['name']));?>
	      	</h3>
	      </div>
	      <div class="am-g pro_price pro_unit">
              	<?php if(isset($configs['show_product_price_onlist'])&&$configs['show_product_price_onlist']=='1'){ if(isset($vv['price_range'])){echo $svshow->price_format($vv['price_range']['min_price'],$configs['price_format'])." -".$svshow->price_format($p['price_range']['max_price'],$configs['price_format']);}else{echo $svshow->price_format($vv['Product']['shop_price'],$configs['price_format']);}} ?>
              	<?php if(isset($configs['show_product_unit'])&&$configs['show_product_unit']=='1'&&!empty($vv['Product']['unit'])){ echo "/".$vv['Product']['unit'];} ?>
    		</div>
		  <?php $flagnum2++;$flagnum++; if($flagnum==11){break;}?>
		</li>
		<?php }?>
	  </ul>
	</div>
	<?php }?>
  <?php }?>
<?php }else{?>
<h2 class='detail-h2'>
  <?php echo $ld['no_related_products']?>
</h2>
<?php }?>
