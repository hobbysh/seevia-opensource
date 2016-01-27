<?php  if($this->params['controller']=="articles" && $this->params['action']=="category"){?>
   <div style="margin-left:30px;max-width:70%;margin-bottom:18px; width:70%;" >
       <?php echo $sm['category']['CategoryArticleI18n']['detail'];?>
  </div>
 <?php }?>
 <?php $cid=0; if($this->params['controller']=="articles" && $this->params['action']=="category"){$cid=$this->params['id'];}?>
<div id="article_category" class="am-u-md-3 am-hide-sm-only am-fr">
<?php if($code_infos[$sk]['type']=="module_article_category"){ $article_categories_tree = $sm['article_categories_tree'];?>
  <?php if($sm['category']['CategoryArticle']['tree_show_type']==0){ 
  	if(isset($sm['direct_subids']['0'])){
  	  foreach($sm['direct_subids']['0'] as $k=>$v){   ?>
  <div class="am-titlebar am-titlebar-default">
    <h2 class="am-titlebar-title <?php if($cid==$v){echo 'am-active';}?>">
      <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$v]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$v]['CategoryArticle']['id']));?>" >
      	<?php echo $sm['assoc'][$v]['CategoryArticleI18n']['name']?>
      </a>
    </h2>
  </div>
  <?php if(!empty($sm['direct_subids'][$v])){?>
    <ul class="am-nav">
	<?php foreach($sm['direct_subids'][$v] as $kk=>$vv){?>
	  <li class="<?php if($cid==$vv){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vv]['CategoryArticle']['id']));?>" ><?php echo $sm['assoc'][$vv]['CategoryArticleI18n']['name']?></a>
	  </li>
	  <?php if(!empty($sm['direct_subids'][$vv])){foreach($sm['direct_subids'][$vv] as $kkk=>$vvv){?>
	  <li class="third_category <?php if($cid==$vvv){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vvv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vvv]['CategoryArticle']['id']));?>" >
	      <?php echo $sm['assoc'][$vvv]['CategoryArticleI18n']['name']?>
	    </a>
	  </li>
	  <?php }}?>
	<?php }?>
	</ul>
  <?php }?>
  <?php	}}}else if($sm['category']['CategoryArticle']['tree_show_type']==1){
  	if(isset($sm['direct_subids'][$sm['category']['CategoryArticle']['parent_id']])){
  	  foreach($sm['direct_subids'][$sm['category']['CategoryArticle']['parent_id']] as $k=>$v){?>
  <div class="am-titlebar am-titlebar-default">
    <h2 class="am-titlebar-title <?php if($cid==$v){echo 'am-active';}?>">
      <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$v]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$v]['CategoryArticle']['id']));?>" >
      	<?php echo $sm['assoc'][$v]['CategoryArticleI18n']['name']?>
      </a>
    </h2>
  </div>
  <?php if(!empty($sm['direct_subids'][$v])){?>
    <ul class="am-nav">
	<?php foreach($sm['direct_subids'][$v] as $kk=>$vv){?>
	  <li class="<?php if($cid==$vv){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vv]['CategoryArticle']['id']));?>" ><?php echo $sm['assoc'][$vv]['CategoryArticleI18n']['name']?></a>
	  </li>
	  <?php if(!empty($sm['direct_subids'][$vv])){foreach($sm['direct_subids'][$vv] as $kkk=>$vvv){?>
	  <li class="third_category <?php if($cid==$vvv){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vvv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vvv]['CategoryArticle']['id']));?>" >
	      <?php echo $sm['assoc'][$vvv]['CategoryArticleI18n']['name']?>
	    </a>
	  </li>
	  <?php }}?>
	<?php }?>
	</ul>
  <?php }?>
  <?php	}}}else if($sm['category']['CategoryArticle']['tree_show_type']==2){
  	if(isset($sm['direct_subids'][$sm['category']['CategoryArticle']['id']])){foreach($sm['direct_subids'][$sm['category']['CategoryArticle']['id']] as $k=>$v){
  	?>
  <div class="am-titlebar am-titlebar-default">
    <h2 class="am-titlebar-title <?php if($cid==$v){echo 'am-active';}?>">
      <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$v]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$v]['CategoryArticle']['id']));?>" >
      	<?php echo $sm['assoc'][$v]['CategoryArticleI18n']['name']?>
      </a>
    </h2>
  </div>
  <?php if(!empty($sm['direct_subids'][$v])){?>
    <ul class="am-nav">
	<?php foreach($sm['direct_subids'][$v] as $kk=>$vv){?>
	  <li class="<?php if($cid==$vv){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vv]['CategoryArticle']['id']));?>" ><?php echo $sm['assoc'][$vv]['CategoryArticleI18n']['name']?></a>
	  </li>
	  <?php if(!empty($sm['direct_subids'][$vv])){foreach($sm['direct_subids'][$vv] as $kkk=>$vvv){?>
	  <li class="third_category <?php if($cid==$vvv){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vvv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vvv]['CategoryArticle']['id']));?>" >
	      <?php echo $sm['assoc'][$vvv]['CategoryArticleI18n']['name']?>
	    </a>
	  </li>
	  <?php }}?>
	<?php }?>
	</ul>
  <?php }?>		  
  <?php }}}?>	  
  	  
  <?php foreach($article_categories_tree as $k=>$v){?>
  <div class="am-titlebar am-titlebar-default" style="display:none;">
    <h2 class="am-titlebar-title <?php if($cid==$v['CategoryArticle']['id']){echo 'am-active';}?>">
	  <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$v['CategoryArticleI18n']['name'],'id'=>$v['CategoryArticle']['id']));?>" ><?php echo $v['CategoryArticleI18n']['name']?></a>
    </h2>
  </div>
  <!-- 2 -->
  <?php if(!empty($v['SubCategory'])){?>
  <ul class="am-nav" style="display:none;">
	<?php foreach($v['SubCategory'] as $kk=>$vv){?>
	<li class="<?php if($cid==$vv['CategoryArticle']['id']){echo 'am-active';}?>">
	  <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$vv['CategoryArticleI18n']['name'],'id'=>$vv['CategoryArticle']['id']));?>" ><?php echo $vv['CategoryArticleI18n']['name']?></a>
	</li>
	<!-- 3 -->
	<?php if(!empty($vv['SubCategory'])){?>
	  <?php foreach($vv['SubCategory'] as $kkk=>$vvv){?>
	  <li class="third_category <?php if($cid==$vvv['CategoryArticle']['id']){echo 'am-active';}?>">
	    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$vvv['CategoryArticleI18n']['name'],'id'=>$vvv['CategoryArticle']['id']));?>" ><?php echo $vvv['CategoryArticleI18n']['name']?></a>
	  </li>
	  <?php }?>
	<?php }?>
	<!-- 3 end -->
	<?php }?>
  </ul>
  <?php }?>
  <!-- 2 end -->
  <?php }?>
<?php }?>
</div>
<div id="a_category" class="am-user-menu am-offcanvas">
  <div class="am-offcanvas-bar category_list" style="background:#fff;">
    <?php if($code_infos[$sk]['type']=="module_article_category"){ $article_categories_tree = $sm['article_categories_tree'];?>
	  <?php if($sm['category']['CategoryArticle']['tree_show_type']==0){ 
	  	if(isset($sm['direct_subids']['0'])){
	  	  foreach($sm['direct_subids']['0'] as $k=>$v){ ?>
	  <div class="am-titlebar am-titlebar-default">
	    <h2 class="am-titlebar-title <?php if($cid==$v){echo 'am-active';}?>">
	      <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$v]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$v]['CategoryArticle']['id']));?>" >
	      	<?php echo $sm['assoc'][$v]['CategoryArticleI18n']['name']?>
	      </a>
	    </h2>
	  </div>
	  <?php if(!empty($sm['direct_subids'][$v])){?>
	    <ul class="am-nav">
		<?php foreach($sm['direct_subids'][$v] as $kk=>$vv){?>
		  <li class="<?php if($cid==$vv){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vv]['CategoryArticle']['id']));?>" ><?php echo $sm['assoc'][$vv]['CategoryArticleI18n']['name']?></a>
		  </li>
		  <?php if(!empty($sm['direct_subids'][$vv])){foreach($sm['direct_subids'][$vv] as $kkk=>$vvv){?>
		  <li class="third_category <?php if($cid==$vvv){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vvv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vvv]['CategoryArticle']['id']));?>" >
		      <?php echo $sm['assoc'][$vvv]['CategoryArticleI18n']['name']?>
		    </a>
		  </li>
		  <?php }}?>
		<?php }?>
		</ul>
	  <?php }?>
	  <?php	}}}else if($sm['category']['CategoryArticle']['tree_show_type']==1){
	  	if(isset($sm['direct_subids'][$sm['category']['CategoryArticle']['parent_id']])){
	  	  foreach($sm['direct_subids'][$sm['category']['CategoryArticle']['parent_id']] as $k=>$v){?>
	  <div class="am-titlebar am-titlebar-default">
	    <h2 class="am-titlebar-title <?php if($cid==$v){echo 'am-active';}?>">
	      <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$v]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$v]['CategoryArticle']['id']));?>" >
	      	<?php echo $sm['assoc'][$v]['CategoryArticleI18n']['name']?>
	      </a>
	    </h2>
	  </div>
	  <?php if(!empty($sm['direct_subids'][$v])){?>
	    <ul class="am-nav">
		<?php foreach($sm['direct_subids'][$v] as $kk=>$vv){?>
		  <li class="<?php if($cid==$vv){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vv]['CategoryArticle']['id']));?>" ><?php echo $sm['assoc'][$vv]['CategoryArticleI18n']['name']?></a>
		  </li>
		  <?php if(!empty($sm['direct_subids'][$vv])){foreach($sm['direct_subids'][$vv] as $kkk=>$vvv){?>
		  <li class="third_category <?php if($cid==$vvv){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vvv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vvv]['CategoryArticle']['id']));?>" >
		      <?php echo $sm['assoc'][$vvv]['CategoryArticleI18n']['name']?>
		    </a>
		  </li>
		  <?php }}?>
		<?php }?>
		</ul>
	  <?php }?>
	  <?php	}}}else if($sm['category']['CategoryArticle']['tree_show_type']==2){
	  	if(isset($sm['direct_subids'][$sm['category']['CategoryArticle']['id']])){foreach($sm['direct_subids'][$sm['category']['CategoryArticle']['id']] as $k=>$v){
	  	?>
	  <div class="am-titlebar am-titlebar-default">
	    <h2 class="am-titlebar-title <?php if($cid==$v){echo 'am-active';}?>">
	      <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$v]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$v]['CategoryArticle']['id']));?>" >
	      	<?php echo $sm['assoc'][$v]['CategoryArticleI18n']['name']?>
	      </a>
	    </h2>
	  </div>
	  <?php if(!empty($sm['direct_subids'][$v])){?>
	    <ul class="am-nav">
		<?php foreach($sm['direct_subids'][$v] as $kk=>$vv){?>
		  <li class="<?php if($cid==$vv){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vv]['CategoryArticle']['id']));?>" ><?php echo $sm['assoc'][$vv]['CategoryArticleI18n']['name']?></a>
		  </li>
		  <?php if(!empty($sm['direct_subids'][$vv])){foreach($sm['direct_subids'][$vv] as $kkk=>$vvv){?>
		  <li class="third_category <?php if($cid==$vvv){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$sm['assoc'][$vvv]['CategoryArticleI18n']['name'],'id'=>$sm['assoc'][$vvv]['CategoryArticle']['id']));?>" >
		      <?php echo $sm['assoc'][$vvv]['CategoryArticleI18n']['name']?>
		    </a>
		  </li>
		  <?php }}?>
		<?php }?>
		</ul>
	  <?php }?>		  
	  <?php }}}?>	  
	  	  
	  <?php foreach($article_categories_tree as $k=>$v){?>
	  <div class="am-titlebar am-titlebar-default" style="display:none;">
	    <h2 class="am-titlebar-title <?php if($cid==$v['CategoryArticle']['id']){echo 'am-active';}?>">
		  <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$v['CategoryArticleI18n']['name'],'id'=>$v['CategoryArticle']['id']));?>" ><?php echo $v['CategoryArticleI18n']['name']?></a>
	    </h2>
	  </div>
	  <!-- 2 -->
	  <?php if(!empty($v['SubCategory'])){?>
	  <ul class="am-nav" style="display:none;">
		<?php foreach($v['SubCategory'] as $kk=>$vv){?>
		<li class="<?php if($cid==$vv['CategoryArticle']['id']){echo 'am-active';}?>">
		  <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$vv['CategoryArticleI18n']['name'],'id'=>$vv['CategoryArticle']['id']));?>" ><?php echo $vv['CategoryArticleI18n']['name']?></a>
		</li>
		<!-- 3 -->
		<?php if(!empty($vv['SubCategory'])){?>
		  <?php foreach($vv['SubCategory'] as $kkk=>$vvv){?>
		  <li class="third_category <?php if($cid==$vvv['CategoryArticle']['id']){echo 'am-active';}?>">
		    <a href="<?php echo $svshow->seo_link_url(array('type'=>'AC','name'=>$vvv['CategoryArticleI18n']['name'],'id'=>$vvv['CategoryArticle']['id']));?>" ><?php echo $vvv['CategoryArticleI18n']['name']?></a>
		  </li>
		  <?php }?>
		<?php }?>
		<!-- 3 end -->
		<?php }?>
	  </ul>
	  <?php }?>
	  <!-- 2 end -->
	  <?php }?>
	<?php }?>
  </div>
</div>
<?php if(isset($CategoryArticleInfo['CategoryArticle'])){
    $CategoryArticleI18n_detail=$svshow->emptyreplace($CategoryArticleInfo['CategoryArticleI18n']['detail']);
    if(empty($CategoryArticleI18n_detail)){
        $CategoryArticleI18n_detail=$CategoryArticleInfo['CategoryArticleI18n']['meta_description'];
    }
?>
<script type="text/javascript">
var wechat_shareTitle="<?php echo $CategoryArticleInfo['CategoryArticleI18n']['name'] ?>";
var wechat_descContent="<?php echo $CategoryArticleI18n_detail; ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php if($svshow->imgfilehave($server_host.(str_replace($server_host,'',$CategoryArticleInfo['CategoryArticle']['img01'])))){ ?>
var wechat_imgUrl="<?php echo $server_host.(str_replace($server_host,'',$CategoryArticleInfo['CategoryArticle']['img01'])); ?>";
<?php } ?>
</script>
<?php } ?>
