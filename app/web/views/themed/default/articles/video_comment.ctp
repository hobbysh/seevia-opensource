<?php ob_start();?>
<ul class="am-comments-list am-comments-list-flip">
  <?php $comment=count($reply_list);$i=0; $flag=-1; if(!empty($reply_list)){foreach($reply_list as $kk=>$vv){$i++;
	if($vv['Comment']['parent_id']==$comment_id){$flag++;?>
  <li class="am-comment">
    <a target="_blank" title="<?php echo $vv['User']['name'];?>" href="<?php echo $html->url('/user_socials/index/'.$vv['Comment']['user_id']); ?>">
	  <img src="<?php echo isset($vv['User']['img01'])&&$vv['User']['img01']!=""?$vv['User']['img01']:'/theme/default/img/no_head.png';?>" alt="<?php echo $vv['User']['name'];?>" class="am-comment-avatar" width="48" height="48"/>
    </a>
	<div class="am-comment-main">
      <header class="am-comment-hd">
        <div class="am-comment-meta">
          <a href="<?php echo $html->url('/user_socials/index/'.$vv['Comment']['user_id']); ?>" class="am-comment-author"><?php echo $vv['User']['name'];?></a>
          评论于 <time><?php $time=explode(" ",$vv['Comment']['created']);$ymd=explode("-",$time[0]);$his=explode(":",$time[1]);echo $his[0].":".$his[1]." ".$ymd[1].'/'.$ymd[2];?></time>
        </div>
      </header>
	  <div class="am-comment-bd">
	    <?php echo $vv['Comment']['content'];?>
	  </div>
	</div>
  </li>
  <?php }}}?>
</ul>
<?php 
$out2 = ob_get_contents();ob_end_clean();  
	$comment=array("comment_num"=>$comment_num,"comment"=>$out2);
	echo json_encode($comment);//pr($out2); ob_end_flush();?>		