
<?php $server_URL=substr(WWW_ROOT,0,strlen(WWW_ROOT)-1); ?>
<?php if(!empty($sm['FlashImage']) ){?>
  <div class="am-slider am-slider-a3 am-no-layout mobile_flash am-show-sm-only" style="margin-bottom:35px;margin-top: 50px;">
    <ul class="am-slides">
      <?php if(sizeof($sm['FlashImage']) > 0){foreach($sm['FlashImage'] as $k=>$v){ 			if($v['image']==""||!is_file($server_URL.$v['image'])){continue;}?>
	  <li><?php if(isset($v['url'])&& $v['url']!=""){?><a href="<?php echo $html->url($v['url']);?>" target="_Blank"  title="<?php echo $v['title']; ?>"><?php echo $svshow->image($v['image']);?></a><?php }else{ ?><?php echo $svshow->image($v['image'],array('alt'=>$v['title']));?><?php }?></li>
	  <?php }}?>
    </ul>
  </div>
<script>$(function(){$(".am-slider").flexslider()});</script>
<?php }?>
