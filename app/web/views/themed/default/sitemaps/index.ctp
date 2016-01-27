<div class="am-g am-g-fixed">
  <div class="am-panel-group" id="accordion">
	<div class="am-panel am-panel-default">
	  <div class="am-panel-hd">
	    <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-1'}">
	      <?php echo $ld['categories']?>
	    </h4>
	  </div>
	  <div id="do-not-say-1" class="am-panel-collapse am-collapse am-in">
	    <div class="am-panel-bd">
	      <ul data-am-widget="gallery" class="am-gallery am-avg-sm-2
	  		am-avg-md-3 am-avg-lg-5 am-gallery-default" data-am-gallery="{ pureview: true }">
	       	<?php $i=0; foreach($product_categories_tree as $k=>$v){?>
			  <li>
			    <dl><dt><?php echo $svshow->seo_link(array('type'=>'PC','name'=>$v['CategoryProductI18n']['name'],'id'=>$v['CategoryProduct']['id']));?></dt>
				<?php if(!empty($v['SubCategory'])){?>
				<?php foreach($v['SubCategory'] as $kk=>$vv){?>
				  <dd><?php echo $svshow->seo_link(array('type'=>'PC','name'=>$vv['CategoryProductI18n']['name'],'id'=>$vv['CategoryProduct']['id']));?></dd>
				<?php if(!empty($vv['SubCategory'])){?>
				<?php foreach($vv['SubCategory'] as $kkk=>$vvv){?>
				  <span style="width:100%;padding-left:20px;"><?php echo $svshow->seo_link(array('type'=>'PC','name'=>$vvv['CategoryProductI18n']['name'],'id'=>$vvv['CategoryProduct']['id']));?></span><br>
				<?php }?>
				<?php  }?>
				<?php }?>
				<?php  }?>
			    </dl>
			  </li>
			<?php $i++;}?>
		  </ul>
	    </div>
	  </div>
	</div>
  </div>
  <div class="am-panel-group" id="accordion">
	<div class="am-panel am-panel-default">
	  <div class="am-panel-hd">
	    <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-2'}">
	      <?php echo $ld['article_categories']?>
	    </h4>
	  </div>
	  <div id="do-not-say-2" class="am-panel-collapse am-collapse am-in">
	    <div class="am-panel-bd">
	      <ul data-am-widget="gallery" class="am-gallery am-avg-sm-2
	  		am-avg-md-3 am-avg-lg-5 am-gallery-default" data-am-gallery="{ pureview: true }">
			<?php $j=0;foreach($article_lists as $k=>$v){if($k==0 || empty($article_categories_assoc[$k]))continue;?>
			<li>
			  <dl>
				<dt><?php if(isset($article_categories_assoc[$k]['CategoryProductI18n']['name'])){echo $article_categories_assoc[$k]['CategoryProductI18n']['name'];}?></dt>
				<?php $i=1;foreach($v as $kk=>$vv){ if($i>10){break;} $i++;?>
				<dd><?php echo $svshow->seo_link(array('type'=>'A','name'=>$vv['ArticleI18n']['title'],'sub_name'=>$vv['ArticleI18n']['title'],'id'=>$vv['Article']['id']));?></dd>
				<?php }?>
			  </dl>
			</li>
			<?php $j++;}?>
		  </ul>
	    </div>
	  </div>
	</div>
  </div>
</div>
