<?php //pr($sm);?>
<div class="am-g am-g-fixed">
  <?php if($code_infos[$sk]['type']=="module_pages"){?>
  <div class="static_pages">
	<div class="am-u-lg-12 auto_zoom">
		<?php //echo $sm['0']['PageI18n']['title'];?>
        <?php //echo $sm['0']['PageI18n']['content'];?>
	  <?php if(isset($sm['0'])){?>
	  <?php if(!empty($sm['0']['PageI18n']['img01'])){?>
	    <div class="am-text-center"><img style="margin:0 auto" src="<?php echo $sm['0']['PageI18n']['img01'];?>"/></div>
	  <?php } if(!empty($sm['0']['PageI18n']['img02'])){?>
		<div class="am-text-center"><img style="margin:0 auto" src="<?php echo $sm['0']['PageI18n']['img02'];?>"/></div>
	  <?php } if(!empty($sm['0']['PageI18n']['content'])){echo $sm['0']['PageI18n']['content'];} ?>
	  <?php }else{echo "<div class='not_exist'>".$ld['no_content']."</div>";}?>
	</div>
  </div>
  <?php }?>
</div>
