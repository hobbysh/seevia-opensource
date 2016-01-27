<?php
	$wordarr=""; if(isset($word)&&$word!=""){
		foreach($word as $k=>$v){
			$wordarr.=$v['BlockWord']['word'].",";
		}
	}
?>
<div class="am-user-message">
	<input type="hidden" id="word" value="<?php echo $wordarr;?>" />
	<?php if(isset($_SESSION['User']['User']['id'])){ ?>
	<div class="am-tabs" data-am-tabs="{noSwipe: 1}">
	  <ul class="am-tabs-nav am-nav am-nav-tabs">
	    <li class="am-active"><a href="javascript: void(0)"><?php echo $ld['message_list'] ?></a></li>
	    <li><a href="javascript: void(0)"><?php echo $ld['station_letter'] ?></a></li>
	  </ul>
	  <div class="am-tabs-bd">
	    <div class="am-tab-panel am-active">
	      <?php if(isset($chat_list)&&sizeof($chat_list)>0){ ?>
			<ul class="am-comments-list am-comments-list-flip">
			<?php foreach($chat_list as $k=>$v){ ?>
				<li class="am-comment">
					<a href="<?php echo $html->url('/user_socials/index/'.(isset($v['User']['id'])?$v['User']['id']:'')); ?>" title="<?php echo $v['User']['name'] ?>" target="_blank"><img width="48" height="48" class="am-comment-avatar" alt="<?php echo $v['User']['name'] ?>" src="<?php  echo isset($v['User']['img01'])&&$v['User']['img01']!=""?$v['User']['img01']:'/theme/default/img/no_head.png'; ?>"></a>
					<div class="am-comment-main">
						<header class="am-comment-hd">
		      				<div class="am-comment-meta">
							<a href="<?php echo $html->url('/user_socials/index/'.(isset($v['User']['id'])?$v['User']['id']:'')); ?>" class="am-comment-author"><?php echo $v['User']['name'];?></a>&nbsp; <time><?php $time=explode(" ",$v['UserChat']['created']);$ymd=explode("-",$time[0]);$his=explode(":",$time[1]);echo $his[0].":".$his[1]." ".$ymd[1].'/'.$ymd[2];?></time>
							</div>
					    </header>
						<div class="am-comment-bd">
							<?php echo $v['UserChat']['content'];?>
							<div class="WB_handle am-text-right">
								<a href="javascript:void(0);" class="chat_history"><input type="hidden" value="<?php echo $v['UserChat']['user_id']==$id?$v['UserChat']['to_user_id']:($id!=$v['UserChat']['to_user_id']?$id:$v['UserChat']['user_id']); ?>"><span><?php printf($ld['message_count'],isset($v['message_list'])?sizeof($v['message_list']):'0');?></span></a>
								<a href="javascript:void(0);" style="margin-left:5px;" class="showreply" alt="<?php echo $v['UserChat']['id']?>" id="replylist_<?php echo $v['UserChat']['user_id']==$id?$v['UserChat']['to_user_id']:$v['UserChat']['user_id']; ?>"><?php echo $ld['reply']; echo isset($v['no_read'])?"(".$v['no_read'].")":"(0)"; ?></a>
						    </div>
							<div id="userchat_list_<?php echo $v['UserChat']['id'];?>" style="display:none;">
								<div id="ds-thread">
									<div id="ds-reset">
										<div class="ds-replybox ds-inline-replybox " >
											<div class="ds-textarea-wrapper ds-rounded-top comment_box_<?php echo $v['UserChat']['id'];?>">
												<textarea name="message"></textarea>
											</div>
											<div class="ds-post-toolbar comments_button">
												<div class="ds-post-options ds-gradient-bg"></div>
												<button id="comments_button-<?php echo $v['UserChat']['user_id']==$id?$v['UserChat']['to_user_id']:$v['UserChat']['user_id']; ?>" alt="<?php echo $v['UserChat']['id'];?>" class="ds-post-button" type="button"><?php echo $ld['reply'] ?></button>
											</div>
										</div>
									</div>
								</div>
								<?php if(isset($v['message_list'])&&sizeof($v['message_list'])>0){ ?>
								<div class="chat_list_<?php echo $v['UserChat']['id'];?>">
									<ul class="am-comments-list am-comments-list-flip">
									<?php foreach($v['message_list'] as $vv){ ?>
										<li class="am-comment">
											<a href="<?php echo $html->url('/user_socials/index/'.(isset($vv['User']['id'])?$vv['User']['id']:'')); ?>" title="<?php echo $vv['User']['name'] ?>" target="_blank"><img width="48" height="48" class="am-comment-avatar" alt="<?php echo $vv['User']['name'] ?>" src="<?php  echo isset($vv['User']['img01'])&&$vv['User']['img01']!=""?$vv['User']['img01']:'/theme/default/img/no_head.png'; ?>"></a>
											<div class="am-comment-main">
												<header class="am-comment-hd">
								      				<div class="am-comment-meta">
														<a href="<?php echo $html->url('/user_socials/index/'.(isset($vv['User']['id'])?$vv['User']['id']:'')); ?>" class="am-comment-author"><?php echo $vv['User']['name'];?></a>&nbsp; <time><?php $time=explode(" ",$vv['UserChat']['created']);$ymd=explode("-",$time[0]);$his=explode(":",$time[1]);echo $his[0].":".$his[1]." ".$ymd[1].'/'.$ymd[2];?></time>
													</div>
											    </header>
												<div class="am-comment-bd"><?php echo $vv['UserChat']['content'];?></div>
											</div>
										</li>
									<?php } ?>
									</ul>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</li>
			<?php } ?>
			</ul>
		  <?php }else{ ?>
		  		<div style="clear:both;text-align:center;padding-top:20%;"><?php echo $ld['no_msg'] ?></div>
		  <?php } ?>
	    </div>
	    <div class="am-tab-panel">
	      <?php if(isset($message)&&sizeof($message)>0){ ?>
	        <ul class="am-comments-list am-comments-list-flip">
			<?php foreach($message as $k=>$v){ ?>
				<li class="am-comment">
					<a href="javascript:void(0);"><img width="48" height="48" class="am-comment-avatar" src="/theme/default/img/no_head.png"></a>
					<div class="am-comment-main">
						<header class="am-comment-hd">
		      				<div class="am-comment-meta">
							<a href="javascript:void(0);" class="am-comment-author"><?php echo $ld['administrator']; ?></a>&nbsp; <time><?php $time=explode(" ",$v['UserMessage']['created']);$ymd=explode("-",$time[0]);$his=explode(":",$time[1]);echo $his[0].":".$his[1]." ".$ymd[1].'/'.$ymd[2];?></time>
							</div>
					    </header>
						<div class="am-comment-bd"><?php echo $v['UserMessage']['msg_content'];?></div>
					</div>
				</li>
			<?php } ?>
	        </ul>
	      <?php }else{ ?>
	      		<div style="clear:both;text-align:center;padding-top:20%;"><?php echo $ld['no_station_letter'] ?></div>
	      <?php } ?>
	    </div>
	  </div>
	</div>
	<?php }else{ echo $ld['time_out_relogin'];} ?>
</div>
<script type="text/javascript">
var word=$("#word").val();
word=word.substring(0,word.length-1);
$(function(){
	$(".showreply").click(function(){
		var linkobj=$(this);
		var chat_id=$(this).attr("alt");
		var id_str=$(this).attr("id");
		var id_str_arr=id_str.split("_");
		var from_user_id=id_str_arr[1];
		if($("#userchat_list_"+chat_id).css("display")=="none"){
			$("#userchat_list_"+chat_id).css("display","block");
		}else{
			$("#userchat_list_"+chat_id).css("display","none");
		}
		$.ajax({ url: "/user_socials/is_read/",
    		dataType:"json",
    		type:"POST",
    		data: {'id':from_user_id },
    		success: function(data){
    			if(data=="1"){
	    			linkobj.html("<?php echo $ld['reply']; ?>(0)");
    			}
  			}
  		});
	});
	
	$(".comments_button button").click(function(){
		var chat_id=$(this).attr("alt");
		var chat_to_id_txt=$(this).attr("id");
		var chat_to_id_atr=chat_to_id_txt.split("-");
		var chat_to_id=chat_to_id_atr[1];
		var textareaobj=$(this).parent().parent().find("textarea");
		var content=textareaobj.val();
		if(content==null||content==""){
			alert(message_content_empty);
			return false;
		}
		content=CheckKeyword(word,content);
		$.ajax({ url: "/user_socials/private_comment/",
	    		dataType:"json",
	    		type:"POST",
	    		data: {'to_user_id':chat_to_id,'content':content },
	    		success: function(data){
	    			if(data=="1"){
	    				textareaobj.val("");
	    				var myDate = new Date();
						var time_str=myDate.getHours()+":"+myDate.getMinutes()+" "+myDate.getMonth()+"/"+myDate.getDate();
						var show_html="<li class='am-comment'><a target='_blank' title='<?php echo $_SESSION['User']['User']['name'] ?>' href='<?php echo $html->url('/user_socials/index/'.($_SESSION['User']['User']['id'])); ?>'><img width='48' height='48' src='<?php  echo $_SESSION['User']['User']['img01']!=""?$_SESSION['User']['User']['img01']:'/theme/default/img/no_head.png'; ?>' alt='<?php echo $_SESSION['User']['User']['name'] ?>' class='am-comment-avatar'></a><div class='am-comment-main'><header class='am-comment-hd'><div class='am-comment-meta'><a class='am-comment-author' href='<?php echo $html->url('/user_socials/index/'.($_SESSION['User']['User']['id'])); ?>'><?php echo $_SESSION['User']['User']['name'] ?></a>&nbsp;&nbsp;<time>"+time_str+"</time></div></header><div class='am-comment-bd'>"+content+"</div></div></li>";
						var old_html=$(".chat_list_"+chat_id+" ul").html();
						$(".chat_list_"+chat_id+" ul").html(show_html+old_html);
	    			}else{
	    				alert(send_failed);
	    			}
	  			}
	  		});
	});
	
	
	$(".chat_history").click(function(){
		var id=$(this).find("input[type=hidden]").val();
		if(id!=""){
			window.open("<?php echo $html->url('/user_socials/chatroom/'); ?>"+id,"_blank");
		}
	});
})
</script>