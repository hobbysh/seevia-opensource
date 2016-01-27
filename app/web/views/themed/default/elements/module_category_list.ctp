<?php if(!empty($sm)){$CategoryProductData=array();$cid=0; ?>
<?php if($this->params['controller']=="categories" && $this->params['action']="view"){
        $cid=$this->params['id'];
}?>
  <div class="am-u-md-3 am-hide-sm-only category_list" style="margin-bottom:10px;">
  <?php if($code_infos[$sk]['type']=="module_category_list"){?>
	<?php foreach($sm['product_categories_tree'] as $k=>$v){
        if($cid==$v['CategoryProduct']['id']){$CategoryProductData=$v;}
        if($v['CategoryProduct']['id']==$sm['top_categroy_id']|| true){ ?>
    <div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
      <h2 class="am-titlebar-title">
		<a href="<?php echo $svshow->seo_link_url(array('type'=>'PC','name'=>$v['CategoryProductI18n']['name'],'id'=>$v['CategoryProduct']['id']));?>" ><?php echo $v['CategoryProductI18n']['name']?></a>
      </h2>
	</div>
	<ul class="am-nav">
	  <!-- 2级分类 -->
	  <?php if(!empty($v['SubCategory'])){?>
		<?php foreach($v['SubCategory'] as $kk=>$vv){?>
		<li class="<?php if($cid==$vv['CategoryProduct']['id']){$CategoryProductData=$vv;echo 'am-active';}?>">
		  <a href="<?php echo $svshow->seo_link_url(array('type'=>'PC','name'=>$vv['CategoryProductI18n']['name'],'id'=>$vv['CategoryProduct']['id']));?>" >
			<?php echo $vv['CategoryProductI18n']['name']?>
		  </a>
		</li>
		<!-- 3级分类 -->
		<?php if(!empty($vv['SubCategory'])){foreach($vv['SubCategory'] as $kkk=>$vvv){?>
		<li style="margin:0 0 0 25px;" class="<?php if($cid==$vvv['CategoryProduct']['id']){$CategoryProductData=$vvv;echo 'am-active';}?>">
		  <a class="titles" style='' href="<?php echo $svshow->seo_link_url(array('type'=>'PC','name'=>$vvv['CategoryProductI18n']['name'],'id'=>$vvv['CategoryProduct']['id']));?>" >
			<?php echo $vvv['CategoryProductI18n']['name']; ?>
		  </a>
		</li>
		<?php }}?>
		<!-- 3级分类 end -->
		<?php }?>
	  <?php }?>
	</ul>
	<?php }}?>
  <?php }?>
  </div>
  <div id="prodcut_category" class="am-user-menu am-offcanvas">
    <div class="am-offcanvas-bar category_list" style="background:#fff;">
    <?php if($code_infos[$sk]['type']=="module_category_list"){?>
	  <?php foreach($sm['product_categories_tree'] as $k=>$v){ if($v['CategoryProduct']['id']==$sm['top_categroy_id']|| true){?>
      <div data-am-widget="titlebar" class="am-titlebar am-titlebar-default">
        <h2 class="am-titlebar-title">
		  <a href="<?php echo $svshow->seo_link_url(array('type'=>'PC','name'=>$v['CategoryProductI18n']['name'],'id'=>$v['CategoryProduct']['id']));?>" ><?php echo $v['CategoryProductI18n']['name']?></a>
        </h2>
	  </div>
	  <ul class="am-nav">
		<!-- 2级分类 -->
		<?php if(!empty($v['SubCategory'])){?>
		  <?php foreach($v['SubCategory'] as $kk=>$vv){?>
		  <li class="<?php if($cid==$vv['CategoryProduct']['id']){echo 'am-active';}?>">
		    <a style=" " href="<?php echo $svshow->seo_link_url(array('type'=>'PC','name'=>$vv['CategoryProductI18n']['name'],'id'=>$vv['CategoryProduct']['id']));?>" >
			  <?php echo $vv['CategoryProductI18n']['name']?>
		    </a>
		  </li>
		  <!-- 3级分类 -->
		  <?php if(!empty($vv['SubCategory'])){foreach($vv['SubCategory'] as $kkk=>$vvv){?>
		  <li style="margin:0 0 0 25px;" class="<?php if($cid==$vvv['CategoryProduct']['id']){echo 'am-active';}?>">
			<a class="titles" style='' href="<?php echo $svshow->seo_link_url(array('type'=>'PC','name'=>$vvv['CategoryProductI18n']['name'],'id'=>$vvv['CategoryProduct']['id']));?>" >
			  <?php echo $vvv['CategoryProductI18n']['name']; ?>
			</a>
		  </li>
		  <?php }}?>
		  <!-- 3级分类 end -->
		  <?php }?>
		<?php }?>
	  </ul>
	  <?php }}?>
	<?php }?>
    </div>
  </div>
<?php }?>
<!-- 分类信息 -->
<?php if(!empty($CategoryProductData)){ ?>
<div class="am-u-lg-9 am-u-md-9 am-u-sm-12 CategoryProductData">
    <div class="am-titlebar am-titlebar-default am-no-layout" data-am-widget="titlebar">
        <h2 class="am-titlebar-title"><span class="am-padding-left7"><?php echo isset($CategoryProductData['CategoryProductI18n']['name'])?$CategoryProductData['CategoryProductI18n']['name']:''; ?></span></h2>
    </div>
    <div class="CategoryProductData_detail"><?php echo isset($CategoryProductData['CategoryProductI18n']['detail'])?$CategoryProductData['CategoryProductI18n']['detail']:''; ?></div>
</div>
<?php } ?>
<!-- 分类信息 -->