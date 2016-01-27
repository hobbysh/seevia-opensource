
<script type="text/javascript">

</script>
	<?php if(isset($material) && !empty($material)>0){?>
		<div class="header">
			<h1 class="article-name"><?php if(isset($material['OpenElement']['title'])){echo $material['OpenElement']['title'];}?></h1>
			<span class="create_time"><?php if(isset($material['OpenElement']['created'])){echo date("Y-m-d",strtotime($material['OpenElement']['created']));}?></span>		
		</div>
		<div class="cover auto_zoom">
			<img src="<?php if(isset($material['OpenElement']['media_url'])){echo $material['OpenElement']['media_url'];}?>" />
		</div>
		<div class="cont auto_zoom">
			<p><?php if(isset($material['OpenElement']['description'])){echo $material['OpenElement']['description'];}?>&nbsp;</p>
		</div>
		<div style="clear:both;"></div>
	<?php }?>
<script type="text/javascript">
var wechat_shareTitle="<?php echo $material['OpenElement']['title'] ?>";
var wechat_descContent="<?php echo $svshow->emptyreplace($material['OpenElement']['description']); ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php if($svshow->imgfilehave($server_host.$material['OpenElement']['media_url'])){ ?>
var wechat_imgUrl="<?php echo $server_host.$material['OpenElement']['media_url'] ?>";
<?php } ?>
</script>