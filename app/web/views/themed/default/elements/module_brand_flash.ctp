<?php //pr($sm);?>
<div class="am-u-md-10">
<?php if(!empty($sm['FlashImage']) ){?>
<div class="am-slider am-slider-default">
  <ul class="am-slides">
    	<?php if(sizeof($sm['FlashImage']) > 0){foreach($sm['FlashImage'] as $k=>$v){?>
		<li><?php if(isset($v['FlashImage']['url'])&& $v['FlashImage']['url']!=""){?><a href="<?php echo $html->url($v['FlashImage']['url']);?>" target="_Blank"><?php echo $svshow->image($v['image']);?></a><?php }else{ ?><?php echo $svshow->image($v['image']);?><?php }?></li>
		<?php }}?>
  </ul>
</div>
<script>$(function(){$(".am-slider").flexslider()});</script>
<?php }?>
</div>
