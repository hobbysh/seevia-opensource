<!--一级分类商品列表页面-->
<?php if(isset($configs['product-list-Show-Type']) && $configs['product-list-Show-Type']==3){?>
	<?php if(isset($brand)&&sizeof($brand)>0){ ?>
		<div class="am-margin-bottom10" style="">
			<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
			  <h2 class="am-titlebar-title"><span class="am-padding-left7"><?php echo $ld["brand"];?></span><span class="pro_more"></span></h2>
		    </div>
		    <div class="am-g" style="margin:0;">
				<ul data-am-widget="gallery" class="am-gallery am-avg-sm-3 am-avg-md-3 am-avg-lg-3 am-gallery-overlay" data-am-gallery="{ }">
			<?php foreach($brand as $v){ ?>
					<li>
				        <div class="am-gallery-item">
				          <a title="<?php echo $v['BrandI18n']['name'] ?>" href="<?php echo $html->url('/brands/'.$v['Brand']['id']); ?>"><img alt="" src="<?php echo $v['BrandI18n']['img01'] ?>" /></a>
				      	  <h3 class="am-gallery-title">
				            <a title="<?php echo $v['BrandI18n']['name'] ?>" href="<?php echo $html->url('/brands/'.$v['Brand']['id']); ?>"><?php echo $v['BrandI18n']['name'] ?></a>
						  </h3>
				        </div>
				    </li>
			<?php } ?>
				</ul>
			</div>
		</div>
		<?php } ?>
  <?php if(!empty($products_tab)||!empty($sub_categories_product)){ ?>
    <?php $first=true;	$flagnum2=0;foreach($products_tab as $k=>$v){ ?>
	<div class="am-margin-bottom10">
	    <div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
		  <h2 class="am-titlebar-title"><span class="am-padding-left7"><?php echo $ld["".$k.""];?></span><span class="pro_more"><?php if(isset($tab_id)){ echo $svshow->seo_link(array('type'=>'PC','name'=>$tab_name,'sub_name'=>$ld['more'],'id'=>$tab_id));}?></span></h2>
	    </div>
		  <?php if(!empty($v)){?>
		  <div class="am-g" style="margin:0;">
			<ul data-am-widget="gallery" class="am-gallery am-avg-sm-3 am-avg-md-3 am-avg-lg-3 am-gallery-overlay" data-am-gallery="{ }">
			<?php  $i=0;	$flagnum=0;foreach($v as $p){ 
			  if(isset($showNum)&&$i==$showNum){ 
		     	break;
		      }?>
			  <li>
		        <div class="am-gallery-item">
		          <?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
				  <span class="like_icon am-gallery-like" style="">
				    <img id="<?php echo $p['Product']['id'];?>"  src="/theme/default/img/like_icon.png" />
				    <span style="" id="<?php echo 'like_num'.$p['Product']['id'];?>" class="like_num">
					  <?php if(isset($p['Product']['like_num'])){echo $p['Product']['like_num'];}else{echo '0';}?>
					</span>
				  </span>
				  <?php } ?>
		          <?php echo $svshow->seo_link(array('type'=>'P','id'=>$p['Product']['id'],'img'=>($p['Product']['img_detail']!=''?$p['Product']['img_detail']:$configs['products_default_image']),'name'=>$p['Product']['name'],'sub_name'=>$p['Product']['name']));?>
		      	  <h3 class="am-gallery-title">
		            <?php echo $svshow->seo_link(array('type'=>'P','id'=>$p['Product']['id'],'name'=>$p['Product']['name'],'sub_name'=>$p['Product']['name']));?>
				  </h3>
		        </div>
			 <div class="am-g pro_price pro_unit">
	              	<?php if(isset($configs['show_product_price_onlist'])&&$configs['show_product_price_onlist']=='1'){ if(isset($p['price_range'])){echo $svshow->price_format($p['price_range']['min_price'],$configs['price_format'])." -".$svshow->price_format($p['price_range']['max_price'],$configs['price_format']);}else{echo $svshow->price_format($p['Product']['shop_price'],$configs['price_format']);}} ?>
	              	<?php if(isset($configs['show_product_unit'])&&$configs['show_product_unit']=='1'&&!empty($p['Product']['unit'])){ echo "/".$p['Product']['unit'];} ?>
	    		 </div>
		      </li>
			<?php $i++;if($i==12){break;}}?>
			</ul>
			<?php  $flagnum2++;$flagnum++; }?>
		  </div>
	</div>
    <?php $first=false;$temp_num2=$flagnum2;}?>
  <?php }?>
	<div class="clear"></div>
	<!--子分类商品显示-->
	<?php if(isset($sub_categories_product)&&sizeof($sub_categories_product)>0){?>
	  <?php $sp_first=true;$sp_flagnum2=isset($temp_num2)?$temp_num2:0;foreach($sub_categories_product as $sk=>$sv){ ?>
	<div class="am-margin-bottom10">
	  <div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
		<h2 class="am-titlebar-title"><span class="am-padding-left7"><?php echo $sk;?></span></h2>
	  </div>
		  <?php if(!empty($sv)){?>
			<div class="am-g" style="margin:0;">
			  <ul data-am-widget="gallery" class="am-gallery am-avg-sm-3 am-avg-md-3 am-avg-lg-3 am-gallery-overlay" data-am-gallery="{ }">
			  <?php $si=0;$sp_flagnum=0;foreach($sv as $s_v){ ?>
				<li>
			  	  <div class="am-gallery-item">
			  	    <?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
					<span class="like_icon am-gallery-like" style="">
				      <img id="<?php echo $s_v['Product']['id'];?>"  src="/theme/default/img/like_icon.png" />
				      <span style="" id="<?php echo 'like_num'.$s_v['Product']['id'];?>" class="like_num">
					    <?php if(isset($s_v['Product']['like_num'])){echo $s_v['Product']['like_num'];}else{echo '0';}?>
					  </span>
				    </span>
				    <?php } ?>
			        <?php echo $svshow->seo_link(array('type'=>'P','id'=>$s_v['Product']['id'],'img'=>($s_v['Product']['img_detail']!=''?$s_v['Product']['img_detail']:$configs['products_default_image']),'name'=>$s_v['ProductI18n']['name'],'sub_name'=>$s_v['ProductI18n']['name']));?>
			        <h3 class="am-gallery-title">
			          <?php echo $svshow->seo_link(array('type'=>'P','id'=>$s_v['Product']['id'],'name'=>$s_v['ProductI18n']['name'],'sub_name'=>$s_v['ProductI18n']['name']));?>
			        </h3>
		      	  </div>
		      	  <div class="am-g pro_price pro_unit">
		              	<?php if(isset($configs['show_product_price_onlist'])&&$configs['show_product_price_onlist']=='1'){ if(isset($s_v['price_range'])){echo $svshow->price_format($s_v['price_range']['min_price'],$configs['price_format'])." -".$svshow->price_format($s_v['price_range']['max_price'],$configs['price_format']);}else{echo $svshow->price_format($s_v['Product']['shop_price'],$configs['price_format']);}} ?>
		              	<?php if(isset($configs['show_product_unit'])&&$configs['show_product_unit']=='1'&&!empty($s_v['Product']['unit'])){ echo "/".$s_v['Product']['unit'];} ?>
		    		 </div>
				</li>
			  <?php $si++; $sp_flagnum++;$sp_flagnum2++; if($si==12){break;}}?>
			  </ul>
			</div>
		  <?php $sp_first=false;}?>
	  </div>
	  <?php }?>
	<?php }?>
<?php }else if($configs['product-list-Show-Type']==1){?>
	<?php if(isset($brand)&&sizeof($brand)>0){ ?>
		<div class="am-margin-bottom10">
			<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
			  <h2 class="am-titlebar-title"><span class="am-padding-left7"><?php echo $ld["brand"];?></span><span class="pro_more"></span></h2>
		    </div>
		    <div class="am-g am-list-news-bd" style="margin:0;">
			<ul class="am-list am-avg-sm-1 am-avg-md-1 am-avg-lg-1" >
			<?php foreach($brand as $v){ ?>
					<li class="am-list-item-dated">
				        <a title="<?php echo $v['BrandI18n']['name'] ?>" href="<?php echo $html->url('/brands/'.$v['Brand']['id']); ?>"><?php echo $v['BrandI18n']['name'] ?></a>
				    </li>
			<?php } ?>
				</ul>
			</div>
		</div>
		<?php } ?>
  <?php if(!empty($products_tab)||!empty($sub_categories_product)){ ?>
    <?php $first=true;	$flagnum2=0;foreach($products_tab as $k=>$v){ ?>
	<div class="am-margin-bottom10">
	    <div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
		  <h2 class="am-titlebar-title"><span class="am-padding-left7"><?php echo $ld["".$k.""];?></span><span class="pro_more"><?php if(isset($tab_id)){ echo $svshow->seo_link(array('type'=>'PC','name'=>$tab_name,'sub_name'=>$ld['more'],'id'=>$tab_id));}?></span></h2>
	    </div>
		  <?php if(!empty($v)){?>
		  <div class="am-g am-list-news-bd" style="margin:0;">
			<ul class="am-list am-avg-sm-1 am-avg-md-1 am-avg-lg-1" >
			<?php  $i=0;	$flagnum=0;foreach($v as $p){ 
			  if(isset($showNum)&&$i==$showNum){ 
		     	break;
		      }?>
			  <li class="am-list-item-dated">
		        <?php echo $svshow->seo_link(array('type'=>'P','id'=>$p['Product']['id'],'name'=>$p['Product']['name'],'sub_name'=>$p['Product']['name']));?>
				<span class="am-list-date"><?php if(isset($p['Product']['shop_price'])){echo $p['Product']['shop_price'];}?></span>
		      </li>
			<?php $i++;if($i==12){break;}}?>
			</ul>
			<?php  $flagnum2++;$flagnum++; }?>
		  </div>
	</div>
    <?php $first=false;$temp_num2=$flagnum2;}?>
  <?php }?>
  <!--子分类商品显示-->
  <?php if(isset($sub_categories_product)&&sizeof($sub_categories_product)>0){?>
	<?php $sp_first=true;$sp_flagnum2=isset($temp_num2)?$temp_num2:0;foreach($sub_categories_product as $sk=>$sv){ ?>
	<div class="am-margin-bottom10">
	  <div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
		<h2 class="am-titlebar-title"><span class="am-padding-left7"><?php echo $sk;?></span></h2>
	  </div>
		  <?php if(!empty($sv)){?>
			<div class="am-g am-list-news-bd" style="margin:0;">
			  <ul class="am-list am-avg-sm-1 am-avg-md-1 am-avg-lg-1" >
			  <?php $si=0;$sp_flagnum=0;foreach($sv as $s_v){ ?>
			    <li class="am-list-item-dated">
		          <?php echo $svshow->seo_link(array('type'=>'P','id'=>$s_v['Product']['id'],'name'=>$s_v['ProductI18n']['name'],'sub_name'=>$s_v['ProductI18n']['name']));?>
				  <span class="am-list-date"><?php if(isset($s_v['Product']['shop_price'])){echo $s_v['Product']['shop_price'];}?></span>
		        </li>
			  <?php $si++; $sp_flagnum++;$sp_flagnum2++; if($si==12){break;}}?>
			  </ul>
			</div>
		  <?php $sp_first=false;}?>
	  </div>
	  <?php }?>
	<?php }?>
<?php }else if($configs['product-list-Show-Type']==2){?>
	
	<?php if(isset($brand)&&sizeof($brand)>0){ ?>
		<div class="am-margin-bottom10">
			<div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
			  <h2 class="am-titlebar-title"><span class="am-padding-left7"><?php echo $ld["brand"];?></span><span class="pro_more"></span></h2>
		    </div>
		    <div class="am-g am-list-news-bd" style="margin:0;">
			<ul class="am-list am-avg-sm-1 am-avg-md-1 am-avg-lg-1">
			<?php foreach($brand as $v){ ?>
				<li class="am-list-item-desced am-list-item-thumbed am-list-item-thumb-left">
					<div class="am-col am-u-sm-4 am-list-thumb">
			  			<a title="<?php echo $v['BrandI18n']['name'] ?>" href="<?php echo $html->url('/brands/'.$v['Brand']['id']); ?>"><img alt="" src="<?php echo $v['BrandI18n']['img01'] ?>" style="width:210px;height:210px;" /></a>
					</div>
					<div class="am-col am-u-sm-8 am-list-main">
					  <h3 class="am-list-item-hd"><a title="<?php echo $v['BrandI18n']['name'] ?>" href="<?php echo $html->url('/brands/'.$v['Brand']['id']); ?>"><?php echo $v['BrandI18n']['name'] ?></a></h3>
					</div>
			    </li>
			<?php } ?>
				</ul>
			</div>
		</div>
		<?php } ?>
	
	
  <?php if(!empty($products_tab)||!empty($sub_categories_product)){ ?>
    <?php $first=true;	$flagnum2=0;foreach($products_tab as $k=>$v){ ?>
	<div class="am-margin-bottom10">
	    <div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
		  <h2 class="am-titlebar-title"><span class="am-padding-left7"><?php echo $ld["".$k.""];?></span><span class="pro_more"><?php if(isset($tab_id)){ echo $svshow->seo_link(array('type'=>'PC','name'=>$tab_name,'sub_name'=>$ld['more'],'id'=>$tab_id));}?></span></h2>
	    </div>
		  <?php if(!empty($v)){?>
		  <div class="am-g am-list-news-bd" style="margin:0;">
			<ul class="am-list am-avg-sm-1 am-avg-md-1 am-avg-lg-1" >
			<?php  $i=0;	$flagnum=0;foreach($v as $p){
			  if(isset($showNum)&&$i==$showNum){ 
		     	break;
		      }?>
			  <li class="am-list-item-desced am-list-item-thumbed am-list-item-thumb-left">
				<div class="am-col am-u-sm-4 am-list-thumb">
				  <?php echo $svshow->seo_link(array('type'=>'P','id'=>$p['Product']['id'],'img'=>($p['Product']['img_detail']!=''?$p['Product']['img_detail']:$configs['products_default_image']),'name'=>$p['Product']['name'],'sub_name'=>$p['Product']['name']));?>
				</div>
				<div class="am-col am-u-sm-8 am-list-main">
				  <h3 class="am-list-item-hd">
		        	<?php echo $svshow->seo_link(array('type'=>'P','id'=>$p['Product']['id'],'name'=>$p['Product']['name'],'sub_name'=>$p['Product']['name']));?>
				  </h3>
				  <div class="am-list-item-text"><?php if(isset($p['Product']['shop_price'])){echo $p['Product']['shop_price'];}?></div>
				</div>
		      </li>
			<?php $i++;if($i==6){break;}}?>
			</ul>
			<?php  $flagnum2++;$flagnum++; }?>
		  </div>
	</div>
    <?php $first=false;$temp_num2=$flagnum2;}?>
  <?php }?>
  <!--子分类商品显示-->
  <?php if(isset($sub_categories_product)&&sizeof($sub_categories_product)>0){?>
	<?php $sp_first=true;$sp_flagnum2=isset($temp_num2)?$temp_num2:0;foreach($sub_categories_product as $sk=>$sv){ ?>
	<div class="am-margin-bottom10">
	  <div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
		<h2 class="am-titlebar-title"><span class="am-padding-left7"><?php echo $sk;?></span></h2>
	  </div>
	  <?php if(!empty($sv)){?>
		<div class="am-g am-list-news-bd" style="margin:0;">
		  <ul class="am-list am-avg-sm-1 am-avg-md-1 am-avg-lg-1" >
		  <?php $si=0;$sp_flagnum=0;foreach($sv as $s_v){ ?>
		    <li class="am-list-item-desced am-list-item-thumbed am-list-item-thumb-left">
			  <div class="am-col am-u-sm-4 am-list-thumb">
				<?php echo $svshow->seo_link(array('type'=>'P','id'=>$s_v['Product']['id'],'img'=>($s_v['Product']['img_detail']!=''?$s_v['Product']['img_detail']:$configs['products_default_image']),'name'=>$s_v['ProductI18n']['name'],'sub_name'=>$s_v['ProductI18n']['name']));?>
			  </div>
			  <div class="am-col am-u-sm-8 am-list-main">
				<h3 class="am-list-item-hd">
		          <?php echo $svshow->seo_link(array('type'=>'P','id'=>$s_v['Product']['id'],'name'=>$s_v['ProductI18n']['name'],'sub_name'=>$s_v['ProductI18n']['name']));?>
				</h3>
				<div class="am-list-item-text"><?php if(isset($s_v['Product']['shop_price'])){echo $s_v['Product']['shop_price'];}?></div>
			  </div>
	        </li>
		  <?php $si++; $sp_flagnum++;$sp_flagnum2++; if($si==12){break;}}?>
		  </ul>
		</div>
		  <?php $sp_first=false;}?>
	</div>
	<?php }?>
  <?php }?>
<?php }?>
			