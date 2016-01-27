<?php ob_start();?>
<ul class="am-comments-list am-comments-list-flip">
  <?php if(!empty($reply_list)){foreach($reply_list as $kk=>$vv){
	if($vv['Blog']['parent_id']==$acticle_id){?>
  <li class="am-comment">
    <a target="_blank" title="<?php echo $vv['User']['name'];?>" href="<?php echo $html->url('/user_socials/index/'.$vv['User']['id']); ?>">
	  <img src="<?php echo isset($vv['User']['img01'])&&$vv['User']['img01']!=""?$vv['User']['img01']:'/theme/default/img/no_head.png';?>" alt="<?php echo $vv['User']['name'];?>" class="am-comment-avatar" width="48" height="48"/>
    </a>
	<div class="am-comment-main">
      <header class="am-comment-hd">
        <div class="am-comment-meta">
          <a href="#link-to-user" class="am-comment-author"><?php echo $vv['User']['name'];?></a>
          评论于 <time><?php $time=explode(" ",$vv['Blog']['created']);$ymd=explode("-",$time[0]);$his=explode(":",$time[1]);echo $his[0].":".$his[1]." ".$ymd[1].'/'.$ymd[2];?></time>
        </div>
      </header>
	  <div class="am-comment-bd">
	    <?php echo $vv['Blog']['content'];?>
		<div class="commentActionBar">
		  <a class="commentReplyLink" id="<?php echo 'reply'.($vv['Blog']['id'])?>" href="javascript:void(0);"><?php echo $ld['reply'] ?></a>
		</div>
		<div id="<?php echo 'answer'.($vv['Blog']['id']);?>" class="comment_reply" style="display: none;">
		  <div id="ds-thread" >
			<div id="ds-reset">
			  <div class="ds-replybox ds-inline-replybox " >
				<div class="ds-textarea-wrapper ds-rounded-top">
				  <textarea name='answer'id="answer_<?php echo $vv['Blog']['parent_id'].'-'.($vv['Blog']['id']);?>" ><?php echo $ld['reply'] ?>@<?php echo $vv['User']['name'];?>:</textarea>
				  <input type="hidden" value="<?php echo $ld['reply'] ?>@<?php echo $vv['User']['name'];?>:" id="hide_<?php echo $vv['Blog']['parent_id'].'-'.($vv['Blog']['id']);?>" />
				</div>
				<div id="comments_button" class="ds-post-toolbar huifupinglun">
				  <div class="ds-post-options ds-gradient-bg"></div>
				  <button id="<?php echo 'hfpl-'.($vv['Blog']['parent_id']).'-'.($vv['Blog']['id']);?>" class="ds-post-button" type="button"><?php echo LOCALE=="eng"?$ld['reply'].' '.$ld['comment']:$ld['reply'].$ld['comment']?></button>
				</div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
  </li>
  <?php }}}?>
</ul>
<script>
var set_headimg=function(){
	var _img=$(".commentUserImage");
    for(var i=0;i<_img.length;i++){
		var _ImgObj = new Image(); //判断图片是否存在  
	    _ImgObj.src = _img[i].src; 
	    //没有图片，则返回-1  
	    if (_ImgObj.fileSize > 0 || (_ImgObj.width > 0 && _ImgObj.height > 0)) { 
	    } else { 
	        _img[i].src="/theme/default/img/no_head.png";
	    }
    }
};
set_headimg();
</script>
<?php 
$out1 = ob_get_contents();ob_end_clean();  
	$comment=array("comment_num"=>$comment_num,"comment"=>$out1);
	echo json_encode($comment);//pr($out1); ob_end_flush();?>		