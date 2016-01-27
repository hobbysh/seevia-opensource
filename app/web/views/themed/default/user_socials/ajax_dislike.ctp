<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-3 am-gallery-overlay" data-am-gallery="{ }">	
		  <?php $flagnum=0; if(isset($like_list) &&sizeof($like_list)>0){foreach($like_list as $k=>$v){ ?>
		  <li id="item<?php echo $v['Product']['id'];?>">
			<div class="am-gallery-item">
			  <span class="dislike_icon am-gallery-like" style="">
			    <img id="<?php echo $v['Product']['id'];?>" style="width:15px;height:15px;" src="/theme/default/img/like_icon.png" />
			    <span style="" id="<?php echo 'like_num'.$v['Product']['id'];?>" class="like_num">
				  <?php if(isset($v['Product']['like_num'])){echo $v['Product']['like_num'];}else{echo '0';}?>
				</span>
			  </span>
			  <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>($v['Product']['img_detail']!=''?$v['Product']['img_detail']:$configs['products_default_image']),'name'=>$v['ProductI18n']['name'],'sub_name'=>'Product name','target'=>'_blank'));?>
	      	  <h3 class="am-gallery-title">
	            <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name'],'target'=>'_blank'));?>
			  </h3>
			</div>
		  </li>
		  <?php }?>
		  <?php	$flagnum++;	}else{
			echo "<div style='clear:both;font-size:14px;text-align:center;margin-top:20%;'>".$ld['not_products_collection']."</div>";
			}?>
		</ul>
		<div class="am-pagination-right" style="clear:both;margin-top:15px;"><?php echo $this->element('pager');?></div>