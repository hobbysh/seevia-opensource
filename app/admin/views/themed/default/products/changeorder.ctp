<?php
$ProductGalleryMaxLength=5;
//$ProductGalleryMaxLength=isset($configs['products_detail_page_gallery_number'])&&$configs['products_detail_page_gallery_number']>5?$configs['products_detail_page_gallery_number']:5;//商品相册最大限制
$ProductGalleryLength=isset($product_gallery)?sizeof($product_gallery):5;//当前商品相册数
?>
<ul class="am-avg-lg-6 am-avg-md-3 am-avg-sm-2 gallerytables" id="gallery-tables">
	<li style="font-weight:bold;">
		<div style="height:120px;"><?php echo $ld['picture_preview'] ?></div>
		<div>&nbsp;</div>
		<?php foreach($backend_locales as $k => $v){?>
		<div style="padding:18px 0px;"><?php echo $k==0?$ld['image_description']:'&nbsp;';?></div>
		<?php } ?>
		<div style="padding-top:5px;"><?php echo $ld['sort']?></div>
	</li>
	<?php
		for($i=0;$i<ceil($ProductGalleryMaxLength/5);$i++){ 
			for($j=$i*5;$j<$i*5+5;$j++){
				if($j%5==0&&$j!=0){continue;}
				if($j>$ProductGalleryMaxLength-1){break;}$k=$j+1;
	?>
		<li>
			<!-- 添加图片 -->
                   <div class="img_select" onclick="select_img('product_add_img_0<?php echo $j+1;?>')"><?php if(!isset($product_gallery[$j]['ProductGallery']['img_thumb'])){?><p style="padding-top:50px;" id="product_add_img_0<?php echo $j+1;?>_pic"><?php echo "+".$ld['add_picture'] ?></p><?php }?>
                                <?php if(isset($product_gallery[$j]['ProductGallery']['img_thumb'])) $img_thumb_format = explode("http://",$product_gallery[$j]['ProductGallery']['img_thumb']); ?>
                                <?php if(isset($product_gallery[$j]['ProductGallery']['img_thumb'])&&isset($img_thumb_format)&&count($img_thumb_format)==1){
                                    echo $html->image((isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_thumb']!="")?IMG_HOST.$product_gallery[$j]['ProductGallery']['img_thumb']:"/media/default_no_photo.png",array('id'=>"show_product_add_img_0$k","onclick"=>"select_img('product_add_img_0$k')"));
                                }else{
                                    echo $html->image((isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_thumb']!="")?$product_gallery[$j]['ProductGallery']['img_thumb']:"/media/default_no_photo.png",array('id'=>"show_product_add_img_0$k","onclick"=>"select_img('product_add_img_0$k')"));
                                }?>
                        </div>
		<div style="padding-right:20px;max-width:170px;">
			<div  id='td_<?php echo $j+1;?>' class="am-text-center" style="white-space:nowrap">
			<?php
				if(isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_thumb']!=""){
					if(isset($product_info['Product']['img_thumb'])&&$product_gallery[$j]['ProductGallery']['img_thumb']==$product_info['Product']['img_thumb']){
					echo $ld['default_picture'];
				}else{
					echo $html->link($ld['set_as_default_picture'],"/products/set_default_picture/".$product_gallery[$j]['ProductGallery']['id']."/".(isset($product_info['Product']['id'])?$product_info['Product']['id']:''),'',false,false);
				}
			?>
				<a href="javascript:void(0);" onclick="delProImg('<?php echo $product_gallery[$j]['ProductGallery']['id'];?>','<?php echo $j+1;?>','<?php echo isset($product_info['Product']['id'])?$product_info['Product']['id']:'';?>')"><?php echo $ld["delete"];?></a><?php } ?>&nbsp;
			</div>
		</div> 
			 <?php foreach($backend_locales as $k => $v){?>
			 		<div class="am-padding-left-0" style="padding-right:5px;margin-right:5px;">
			 				 <input style="float:left;margin-top:10px;margin-bottom:10px;max-width:130px;" type="text" id ="ProductGalleryI18n_<?php echo $j+1;?>_<?php echo $k;?>_description" name="data[product_gallery_data][<?php echo $j;?>][ProductGalleryI18n][<?php echo $k;?>][description]" value="<?php echo !empty($product_gallery[$j]['ProductGalleryI18n'][$v['Language']['locale']]['description'])?$product_gallery[$j]['ProductGalleryI18n'][$v['Language']['locale']]['description']:'';?>"/><?php if(sizeof($backend_locales)>1){?><span style="margin-top:20px;" class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?>
                        				<input type="hidden" name="data[product_gallery_data][<?php echo $j?>][ProductGalleryI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale'];?>"/>
                        				<div class="am-cf"></div>
			 		</div>
			 <?php } ?>
			 <input type="hidden" name="data[product_gallery_data][<?php echo $j?>][ProductGallery][img_thumb]" id="product_add_img_0<?php echo $j+1;?>" value="<?php echo (isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_thumb']!="")?$product_gallery[$j]['ProductGallery']['img_thumb']:'';?>"><input type="hidden" name="data[product_gallery_data][<?php echo $j?>][ProductGallery][img_detail]" id="img_detail_product_add_img_0<?php echo $j+1;?>" value="<?php echo (isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_detail']!="")?$product_gallery[$j]['ProductGallery']['img_detail']:'';?>"><input type="hidden" name="data[product_gallery_data][<?php echo $j?>][ProductGallery][img_big]" id="img_big_product_add_img_0<?php echo $j+1;?>" value="<?php echo (isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_big']!="")?$product_gallery[$j]['ProductGallery']['img_big']:'';?>"><input type="hidden" name="data[product_gallery_data][<?php echo $j?>][ProductGallery][img_original]" id="img_original_product_add_img_0<?php echo $j+1;?>" value="<?php echo (isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['img_original']!="")?$product_gallery[$j]['ProductGallery']['img_original']:'';?>"><input type="hidden" name="data[product_gallery_data][<?php echo $j?>][ProductGallery][id]" value="<?php echo (isset($product_gallery[$j])&&$product_gallery[$j]['ProductGallery']['id']!="")?$product_gallery[$j]['ProductGallery']['id']:'';?>">
	<?php if($j<sizeof($product_gallery)-1){ ?>
		<div style="padding-top:5px;">
			<input type="button" value=">>" style="margin-bottom:5px;" class="am-btn am-btn-success am-btn-xs" onClick="changeOrder(<?php echo $product_gallery[$j]['ProductGallery']['product_id']; ?>,<?php echo $product_gallery[$j]['ProductGallery']['id']; ?>)" />
		</div>
	<?php } ?>
		</li>
	<?php }} ?>
</ul>