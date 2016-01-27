<?php ob_start();?>
<li style="display: inline-block;margin: 10px;vertical-align: top;">
	<label>
	<a class="img" href="javascript:void(0)"><img style="width: 120px; height: 120px;" id="img1" src="<?php echo !stristr($image_data['img']['img_small'],IMG_HOST)?IMG_HOST.$image_data['img']['img_small']:$image_data['img']['img_small'];?>" onclick="selected_image(this,'<?php echo $image_data['img']['img_detail'];?>','<?php echo $image_data['img']['img_original'];?>','<?php echo $image_data['img']['name'];?>');"> </a>
	<p class="imgname"><span onclick="javascript:listTable.edit(this, 'image_spaces/update_photo_name/', <?php echo $image_data['img']['id'];?>)"><?php echo $image_data['img']['name'];?></span></p>
	<p class="imgbtn"><a class="am-btn am-btn-default  am-btn-xs"  href="/admin/image_spaces/view/<?php echo $image_data['img']['id'];?>" target="_blank"><?php echo $ld['picture_preview'];?></a>&nbsp;<a class="am-btn am-btn-default am-btn-xs " href="javascript:;" onclick="photo_copy(event,'<?php if(!stristr($image_data[img][img_original],IMG_HOST)){$image_data[img][img_original] = IMG_HOST.$image_data[img][img_original];}echo $server_host.$image_data[img][img_original];?>')"><?php echo $ld['copy'];?></a>&nbsp;<a class="am-btn am-btn-danger am-btn-xs" onclick="if(confirm('<?php echo $ld[confirm_delete_photo];?>')){confirm_remove_img(this,<?php echo $image_data['img']['id']?>);}" href="javascript:;"><?php echo $ld['delete']?></a></p>
	</label>
</li>
<?php 
$result['error']=false;
$result['img'] = ob_get_contents();
ob_end_clean();
echo json_encode($result);
?>
