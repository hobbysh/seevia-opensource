<?php echo $javascript->link('/skins/default/js/image_space');?>
<style>
  
	.imagebtn img{
	width:100%;
	height:100%;
	        
	} 
	
	.tablemenu {
	 
		float: left;
		border: 1px #CCC;
		border-style: solid none;
		line-height: 18px;
		margin-bottom: 20px;
	}
	       h2 {
		border: 1px solid #E1ECCE;
		padding: 2px 6px;
		line-height: 24px;
		text-align: left;
		font-size: 14px;
		font-weight: bold;
		color: #30AD47;
		background: #E9F8D9;
	}
	
	 /*
 *详情页 左边菜单 sm 不显示 
 */
   @media only screen and  (max-width:640px){
 
		.tablemenu {
			width:100%;
			height:100%;
			float: left;
			border: 1px #CCC;
			border-style: solid none;
			line-height: 18px;
			margin-bottom: 20px;
		}
		.imagemenu {
			width: 100%;
			float: left;
 	         }
 	 .imagebtn img{
              width:120px;
              height:100px;
              vertical-align:middle;
	 
	       
               } 
               .imagebtn {
               
                 text-align:center;
               }
            }
	ul, dl, dt, dd, li, p {
		border-style: none;
		font-size: 12px;
		list-style-type: none;
		margin: 0;
		padding: 0;
	}
	label {
		margin: 0 3px;
	}
	.tablemenu li label span {
		float: left;
	}
	.tablemenu li h2 {
		border: 1px solid #E1ECCE;
		line-height: 24px;
		text-align: left;
		margin: 0;
		font-weight: bold;
		color: #30AD47;
		background: #E9F8D9;
	}
	.tablemain h2 {
		border: 1px solid #E1ECCE;
		padding: 2px 6px;
		line-height: 24px;
		text-align: left;
		font-size: 14px;
		font-weight: bold;
		color: #30AD47;
		background: #E9F8D9;
	}
	.tablemenu li {
		font-size: 12px;
		color: black;
		background: #F5F7EA;
		line-height: 15px;
		min-height: 15px;
		padding: 5px;
	}
	.tablemenu li a {
		margin-right: 5px;
	}
 
</style>

<div class="tablemain am-show-sm-only am-u-sm-12">
 
			<h2 onclick="javascript:listTable.edit(this, 'image_spaces/update_photo_name/', <?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['id']?>)"><?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['name'];?></h2>
			<div class="am-text-center">
				<img   style="max-width:100%;" src="<?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['img_detail'];?>" />
		      </div><?php  //pr( $phpoto_category_gallery_info);?>
 </div>
	
<div class="am-u-lg-3 am-u-md-3 am-u-lg-3 " style="margin-top:10px;padding-left:5px;">
		      <div class="imagebtn am-u-lg-6 am-u-sm-6 am-u-md-6"><?php if(!empty($phpoto_category_gallery_info1)){?>
			 <?php echo $html->link($html->image($phpoto_category_gallery_info1["PhotoCategoryGallery"]["img_small"]),"/image_spaces/view/".$phpoto_category_gallery_info1["PhotoCategoryGallery"]["id"],array('escape'=>false),false,false);?> 
				<p class="am-text-center" ><?php echo $html->link($ld['picture_previous'],"/image_spaces/view/".$phpoto_category_gallery_info1["PhotoCategoryGallery"]["id"],false,false);?></p>
			<?php }?>
			   </div>
		   	   
			<div class="imagebtn am-u-lg-6 am-u-sm-6 am-u-md-6"><?php if(!empty($phpoto_category_gallery_info2)){?>
				<?php echo $html->link($html->image($phpoto_category_gallery_info2["PhotoCategoryGallery"]["img_small"]),"/image_spaces/view/".$phpoto_category_gallery_info2["PhotoCategoryGallery"]["id"],array('escape'=>false),false,false);?>
				<p class="am-text-center"><?php echo $html->link($ld['picture_next'],"/image_spaces/view/".$phpoto_category_gallery_info2["PhotoCategoryGallery"]["id"],false,false);?></p>
			<?php }?>
			</div>
 
		
		<ul class="tablemenu am-u-sm-12 am-u-lg-12 am-u-md-12" style="margin-top:10px;">
		<li><h2><?php echo $ld['image_attribute']?></h2></li>
		<li><label><span><?php echo $ld['photo_upload_time'];?></span><?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['created']?></label></li>
		<li><label><span><?php echo $ld['original_image_size'];?></span><?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['original_size']?>k</label></li>
		<li><label><span><?php echo $ld['original_image_size'];?></span><?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['original_pixel']?></label></li>
		<li><label><span><?php echo $ld['pictures_category']?></span><?php echo $cat?></label></li>
		<li><h2><?php echo $ld['image_size']?></h2></li>
		<li><label><span><?php echo $ld['original_image'];?></span></label>
		<a href="javascript:;" onclick="photo_copy1(event,'<?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['img_original'];?>')"><?php echo $ld['copy_link'];?></a><a href="<?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['img_original'];?>" target="_blank"><?php echo $ld['view'];?></a></li>
		<li><label><span><?php echo $ld['big_image'];?></span></label>
		<a href="javascript:;" onclick="photo_copy1(event,'<?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['img_big'];?>')"><?php echo $ld['copy_link'];?></a><a href="<?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['img_big'];?>" target="_blank"><?php echo $ld['view']?></a></li>
		<li><label><span><?php echo $ld['middle_image'];?></span></label>
		<a href="javascript:;" onclick="photo_copy1(event,'<?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['img_detail'];?>')"><?php echo $ld['copy_link'];?></a><a href="<?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['img_detail'];?>" target="_blank"><?php echo $ld['view']?></a></li>
		<li><label><span><?php echo $ld['thumb_image'];?></span></label>
		<a href="javascript:;" onclick="photo_copy1(event,'<?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['img_small'];?>')"><?php echo $ld['copy_link'];?></a><a href="<?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['img_small'];?>" target="_blank"><?php echo $ld['view']?></a></li>
		</ul>
</div>
			
			
<div class="am-u-lg-9 am-u-md-9  am-u-sm-9 am-hide-sm-only">
	 <div>
			<h2 onclick="javascript:listTable.edit(this, 'image_spaces/update_photo_name/', <?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['id']?>)"><?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['name'];?></h2>
			<div style="width:100%;" class="am-text-center">
				<img   style="max-width:100%;" src="<?php echo $phpoto_category_gallery_info['PhotoCategoryGallery']['img_original'];?>" />
		      </div>
	 </div>
</div>
<!--
<div id="tip-copy1" class="tip-copy-show tip-height-h0">
	<div class="tip-main">
		<div class="tip-title">
			<p><?php echo $ld['do_not_copy']?></p>
		</div>
		<input type="text" id="tip-copy1-text" style="width:330px;"><a title="<?php echo $ld['close_the_window']?>" class="close" onclick="var div=document.getElementById(&quot;tip-copy1&quot;);div.className+=&quot; hidden&quot;;" href="javascript:void(0);">×</a>
	</div>
</div>
-->