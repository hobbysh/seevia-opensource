<div class="am-u-lg-10 am-u-md-10 am-u-sm-12">
  <?php if($code_infos[$sk]['type']=="module_brand_info"){?>
  <article class="blog-main">
    <h3 class="am-article-title am-pagination-centered"><?php echo$sm['BrandI18n']['name'];?></h3>
    <h4 class="am-article-meta am-pagination-centered"><time><?php echo date("Y-m-d", strtotime($sm['Brand']['created']));?></time></h4>
    <div>
	  <?php if(!empty($sm['BrandI18n']['img01'])){?>
      <div class="am-u-lg-4">
        <p><img src="<?php echo $sm['BrandI18n']['img01'];?>"></p>
      </div>
	  <div class="am-u-lg-8">
        <?php echo $sm['BrandI18n']['description'];?>
	  </div>
	  <?php }else{?>
	  <div class="am-u-lg-12">
        <?php echo $sm['BrandI18n']['description'];?>
	  </div>
	  <?php }?>
	</div>
  </article>
  <?php }?>
</div>