
<?php //pr($sm['brand']); 
	$brand_category=array();
	if(isset($sm['category'])&&$sm['category']!=""){foreach($sm['category'] as $k=>$v){
		if(isset($sm['brand'])&&$sm['brand']!=""){
			foreach($sm['brand'] as $kk=>$vv){
				if($vv['Brand']['category_type_id']==$v['CategoryType']['id']){
					$brand_category[$v['CategoryType']['id']][]=$vv;
				}
			}
		}
	}}
?>
<div class="am-u-lg-2 am-u-md-2 am-hide-sm-only ">
<section data-am-widget="accordion" class="am-accordion am-accordion-gapped"
data-am-accordion='{"multiple": true }'>
<?php if($code_infos[$sk]['type']=="module_brand_category"){ ?>
  <?php
  		if(isset($sm['category'])&&$sm['category']!=""){foreach($sm['category'] as $k=>$v){
  			if(!isset($brand_category[$v['CategoryType']['id']])||empty($brand_category[$v['CategoryType']['id']])){continue;}
  ?>
  <dl class="am-accordion-item am-active">
	<dt class="am-accordion-title"><?php echo $v['CategoryTypeI18n']['name']; ?></dt>
	<!-- 2 -->
	<dd class="am-accordion-content am-collapse am-in">
	  <?php foreach($brand_category[$v['CategoryType']['id']] as $kk=>$vv){ ?>
		<div style="width:100%;">
		<?php
			echo $svshow->link($vv['BrandI18n']['name'],"/brands/view/".$vv['Brand']['id']);
			//echo $svshow->seo_link(array('type'=>'BV','id'=>$vv['Brand']['id'],'img'=>$vv['BrandI18n']['img01'],'name'=>$vv['BrandI18n']['name'],'sub_name'=>''));
			?>
		</div>
	  <?php }?>
	</dd>
	<!-- 2 end -->
  </dl>
  <?php }?>
<?php }}?>
</section>
</div>