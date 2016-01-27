<!-- \\192.168.10.211\core\co\views\themes\default\elements\module_brand.ctp -->
<?php if($code_infos[$sk]['type']=="module_brand"){ ?>
<div class="detail">
  <div class="am-g am-g-fixed">
    <div class="am-u-lg-12">
	  <h2 class="detail-h2"><?php echo $code_infos[$sk]['name'];?></h2>
	  <div class="am-container">
		<?php foreach($sm['brand'] as $k=>$b){?>
		<div class="am-u-lg-3 am-u-md-4 am-u-sm-6 detail-mb">
	      <h3 class="detail-h3">
		  <!--<i class="am-icon-mobile am-icon-sm"></i>-->
			<?php echo $svshow->seo_link(array('type'=>'BV','id'=>$b['Brand']['id'],'img'=>(!empty($b['BrandI18n']['img01'])?$b['BrandI18n']['img01']:'/theme/default/img/default.jpg'),'name'=>$b['BrandI18n']['name'],'sub_name'=>$b['BrandI18n']['name']));?>
		  </h3>
		  <p class="detail-p">
			<?php echo $svshow->seo_link(array('type'=>'BV','name'=>isset($b['BrandI18n']['name'])?$b['BrandI18n']['name']:'','id'=>$b['Brand']['id']));?>
		  </p>
		</div>
		<?php }?>
	  </div>
	</div>
  </div>
  <?php  if($sm['paging']['pageCount']>=1){?>
  <div class="pages am-pagination-right">
	<?php
	if($pagination->setPaging($sm['paging'])):
		$leftArrow = "‹ ".$ld['previous'];
		$rightArrow = $ld['next']." ›";
		$prev = $pagination->prevPage($leftArrow,false);
		$prev = $prev?$prev:$leftArrow;
		$next = $pagination->nextPage($rightArrow,false);
		$next = $next?$next:$rightArrow;
		$pages = $pagination->pageNumbers("	 ");
		//echo $pagination->result()."<br>";
		echo $prev." ".$pages." ".$next;
		//echo $pagination->resultsPerPage(NULL, ' ');
		endif;
	?>
  </div>
  <?php }?>		
</div>
<?php }?>
