<div class="am-container">
  <?php if($this->params['controller']=="static_pages" && $this->params['action']="view" && $this->params['url']['url']=="/"){}else{echo $this->element("ur_here");}?>
  <div class="static_pages auto_zoom">
	<?php if(isset($page) && (!empty($page['PageI18n']['img01']) || !empty($page['PageI18n']['img02']) || !empty($page['PageI18n']['content']))){?>
	<?php if(!empty($page['PageI18n']['img01'])){?>
	  <img src="<?php echo $page['PageI18n']['img01'];?>"/>
	<?php } if(!empty($page['PageI18n']['img02'])){?>
	  <img src="<?php echo $page['PageI18n']['img02'];?>"/>
	<?php } if(!empty($page['PageI18n']['content'])){echo $page['PageI18n']['content'];} ?>
	<?php }else{echo "<div class='not_exist'>".$ld['no_content']."</div>";}?>
  </div>
</div>
<script type="text/javascript">
var wechat_shareTitle="<?php echo $page['PageI18n']['title'] ?>";
var wechat_descContent="<?php echo $svshow->emptyreplace($page['PageI18n']['content']); ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php if($svshow->imgfilehave($server_host.(str_replace($server_host,'',$page['PageI18n']['img01'])))){ ?>
var wechat_imgUrl="<?php echo $server_host.(str_replace($server_host,'',$page['PageI18n']['img01'])); ?>";
<?php } ?>
</script>