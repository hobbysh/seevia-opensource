<?php if($code_infos[$sk]['type']=="module_home_topic"){ ?>
<div class="am-g am-g-fixed">
  <div class="am-panel am-panel-default" style="margin:0;" >
	<div class="am-panel-hd my-head"style="margin:0;"><?php echo $code_infos[$sk]['name'];?></div>
	<div  class="am-panel-bd"style="margin:0;">
	  <div >
	  <?php foreach($sm as $k=>$t){?>
	  <div class="am-u-lg-3 am-u-md-3 am-u-sm-4 amaze_home_center2" >
		<h3 class="detail-h3"style="margin-bottom:0px;">
		  <a title="<?php echo $t['TopicI18n']['title'];?>" href="<?php echo $html->url('/topics/'.$t['Topic']['id']);?>"><img alt="" src="<?php echo $t['TopicI18n']['img01'];?>"></a>
		</h3>
		<p class="detail-p" style="margin-top:0px;">
		  <?php echo $svshow->link($t['TopicI18n']['title'],'/topics/'.$t['Topic']['id'],array('title'=>$t['TopicI18n']['title']));?>
		</p>
	  </div>
	  <?php }?>
	  </div>
	  <div class="am-topic"></div>
	</div>
  </div>
</div>
<?php }?>
