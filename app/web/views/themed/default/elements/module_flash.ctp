<?php if(!empty($sm['FlashImage']) ){?>
<?php if($this->params['controller']=='categories'){ ?>
<div class="am-u-md-9 am-u-sm-12 am-u-md-9">
  <div class="am-slider am-slider-default">
    <ul class="am-slides">
      <?php if(sizeof($sm['FlashImage']) > 0){foreach($sm['FlashImage'] as $k=>$v){?>
	  <li><?php if(isset($v['url'])&& $v['url']!=""){?>
	  	<a href="<?php echo $html->url($v['url']);?>" target="_Blank" title="<?php echo $v['title']; ?>">
	  	<?php echo $svshow->image($v['image']);?></a>
	  	<?php }else{ ?><?php echo $svshow->image($v['image'],array('alt'=>$v['title']));?><?php }?>
	  </li>
	  <?php }}?>
    </ul>
  </div>
</div>
<?php }else{ ?>
<div class="am-slider am-hide-sm-only  am-slider-default">
    <ul class="am-slides">
      <?php if(sizeof($sm['FlashImage']) > 0){foreach($sm['FlashImage'] as $k=>$v){?>
	  <li><?php if(isset($v['url'])&& $v['url']!=""){?>
	  	<a href="<?php echo $html->url($v['url']);?>" target="_Blank" title="<?php echo $v['title']; ?>">
	  	<?php echo $svshow->image($v['image']);?></a>
	  	<?php }else{ ?><?php echo $svshow->image($v['image'],array('alt'=>$v['title']));?><?php }?>
	  </li>
	  <?php }}?>
    </ul>
</div>
<?php } ?>
<script type="text/javascript">
$(function(){$(".am-slider").flexslider()});
</script>
<?php } ?>