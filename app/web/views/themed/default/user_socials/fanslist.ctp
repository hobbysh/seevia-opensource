<?php
	$wordarr=""; if(isset($word)&&$word!=""){
		foreach($word as $k=>$v){
			$wordarr.=$v['BlockWord']['word'].",";
		}
	}
?>
<input type="hidden" id="word" value="<?php echo $wordarr;?>">
<input type="hidden" id="is_me" value="<?php echo $is_me; ?>">
<div class="am-user-fanslist">
	<div class="am-tabs" data-am-tabs="{noSwipe: 1}">
	  <ul class="am-tabs-nav am-nav am-nav-tabs">
	    <li class="<?php if((isset($fanslist_type)&&$fanslist_type==2)||!isset($fanslist_type)){echo ' am-active';} ?>"><a href="javascript:void(0)"><?php echo $ld['fans'] ?></a></li>
	    <li class="<?php if(isset($fanslist_type)&&$fanslist_type==1){echo ' am-active';} ?>"><a href="javascript:void(0)"><?php echo $ld['focus'] ?></a></li>
	  </ul>
	  <div class="am-tabs-bd">
	    <div class="am-tab-panel <?php if((isset($fanslist_type)&&$fanslist_type==2)||!isset($fanslist_type)){echo ' am-active';} ?>">
	      <?php if(isset($fans_list)&&!empty($fans_list)){ ?>
	      <ul class="am-comments-list am-comments-list-flip">
	      	<?php foreach($fans_list as $k=>$v){?>
	      		<li class="am-comment">
					<a href="<?php echo $html->url('/user_socials/index/'.$v['UserFans']['fan_id']); ?>"><img width="48" height="48" class="am-comment-avatar" src="<?php echo isset($v['User']['img01'])&&$v['User']['img01']!=''?$v['User']['img01']:'/theme/default/img/no_head.png';?>"></a>
					<div class="am-comment-main">
						<header class="am-comment-hd">
		      				<div class="am-comment-meta">
								<a href="<?php echo $html->url('/user_socials/index/'.$v['UserFans']['fan_id']); ?>" class="am-comment-author"><?php echo $v['User']['name'];?></a>
							</div>
					    </header>
						<div class="am-comment-bd">
							<div class="am-g">
								<div class="am-u-sm-12 am-u-lg-8">
									<span><?php echo $ld['focus'] ?>&nbsp;<?php echo isset($v['fans_info']['focus'])?$v['fans_info']['focus']:'0';?></span>
									<span><?php echo $ld['fans'] ?>&nbsp;<?php echo isset($v['fans_info']['fans'])?$v['fans_info']['fans']:'0';?></span>
									<span><?php echo $ld['diary'] ?>&nbsp;<?php echo isset($v['fans_info']['blog'])?$v['fans_info']['blog']:'0';?></span>
								</div>
								<div class="am-u-sm-12 am-u-lg-12 am-btn-group-xs">
					<?php if($is_me==0||($is_me==2&&$user_id!=$v['UserFans']['fan_id']&&!in_array($v['UserFans']['fan_id'],$my_focus_list))){ ?>
									<button class="am-btn am-btn-default sorting" onclick="mutual_concern(this,'<?php echo $v['UserFans']['fan_id'] ?>',2)" type="button"><?php echo $ld['mutual_concern'] ?></button>
					<?php }else if($is_me==1||($is_me==2&&in_array($v['UserFans']['fan_id'],$my_focus_list))){ ?>
									<button class="am-btn am-btn-default sorting" onclick="cancel_focus(this,'<?php echo $v['UserFans']['fan_id'] ?>',2)"  type="button"><?php echo $ld['cancel_focus'] ?></button>
					<?php } ?>
					<?php if($is_me==2&&$user_id!=$v['UserFans']['fan_id']){?>	
									<button class="am-btn am-btn-default sorting showreply" id="sendmsg_<?php echo $v['UserFans']['id'];?>" type="button"><?php echo $ld['send_private_messages'] ?></button>
					<?php } ?>
								</div>
							</div>
							<div id="reply_info-<?php echo $v['UserFans']['id'];?>" class="reply_info" style="display:none;">
								<div id="ds-thread">
									<div id="ds-reset">
										<div class="ds-replybox ds-inline-replybox" >
											<div class="ds-textarea-wrapper ds-rounded-top">
												<textarea name="message"></textarea>
											</div>
											<div class="ds-post-toolbar comments_button">
												<div class="ds-post-options ds-gradient-bg"></div>
												<button class="ds-post-button" alt="<?php echo $v['UserFans']['fan_id'];?>;<?php echo $v['UserFans']['id'];?>" type="button"><?php echo $ld['send'] ?></button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</li>
	      	<?php } ?>
	      </ul>
	      <?php }else{ ?>
	      	  <div style="clear:both;text-align:center;padding-top:20%;"><?php echo $ld['no_fans'] ?></div>
	      <?php } ?>
	    </div>
	    <div class="am-tab-panel <?php if(isset($fanslist_type)&&$fanslist_type==1){echo ' am-active';} ?>">
	    	<?php if(isset($focus_list)&&!empty($focus_list)){ ?>
	    		<ul class="am-comments-list am-comments-list-flip">
	    		<?php foreach($focus_list as $k=>$v){?>
	    			<li class="am-comment">
					<a href="<?php echo $html->url('/user_socials/index/'.$v['UserFans']['user_id']); ?>"><img width="48" height="48" class="am-comment-avatar" src="<?php echo isset($v['User']['img01'])&&$v['User']['img01']!=''?$v['User']['img01']:'/theme/default/img/no_head.png';?>"></a>
					<div class="am-comment-main">
						<header class="am-comment-hd">
		      				<div class="am-comment-meta">
								<a href="<?php echo $html->url('/user_socials/index/'.$v['UserFans']['user_id']); ?>" class="am-comment-author"><?php echo $v['User']['name'];?></a>
							</div>
					    </header>
						<div class="am-comment-bd">
							<div class="am-g">
								<div class="am-u-sm-12 am-u-lg-8">
									<span><?php echo $ld['focus'] ?>&nbsp;<?php echo isset($v['fans_info']['focus'])?$v['fans_info']['focus']:'0';?></span>
									<span><?php echo $ld['fans'] ?>&nbsp;<?php echo isset($v['fans_info']['fans'])?$v['fans_info']['fans']:'0';?></span>
									<span><?php echo $ld['diary'] ?>&nbsp;<?php echo isset($v['fans_info']['blog'])?$v['fans_info']['blog']:'0';?></span>
								</div>
								<div class="am-u-sm-12 am-u-lg-12 am-btn-group-xs">
					<?php if($is_me==0||($is_me==2&&$user_id!=$v['UserFans']['user_id']&&!in_array($v['UserFans']['user_id'],$my_focus_list))){ ?>
									<button class="am-btn am-btn-default sorting" onclick="mutual_concern(this,'<?php echo $v['UserFans']['user_id'] ?>',2)" type="button"><?php echo $ld['mutual_concern'] ?></button>
					<?php }else if($is_me==1||($is_me==2&&in_array($v['UserFans']['user_id'],$my_focus_list))){ ?>
									<button class="am-btn am-btn-default sorting" onclick="cancel_focus(this,'<?php echo $v['UserFans']['user_id'] ?>',2)"  type="button"><?php echo $ld['cancel_focus'] ?></button>
					<?php } ?>
					<?php if($is_me==2&&$user_id!=$v['UserFans']['user_id']){?>
									<button class="am-btn am-btn-default sorting showreply" id="sendmsg_<?php echo $v['UserFans']['id'];?>" type="button"><?php echo $ld['send_private_messages'] ?></button>
					<?php } ?>
								</div>
							</div>
							<div id="reply_info-<?php echo $v['UserFans']['id'];?>" class="reply_info" style="display:none;">
								<div id="ds-thread">
									<div id="ds-reset">
										<div class="ds-replybox ds-inline-replybox" >
											<div class="ds-textarea-wrapper ds-rounded-top">
												<textarea name="message"></textarea>
											</div>
											<div class="ds-post-toolbar comments_button">
												<div class="ds-post-options ds-gradient-bg"></div>
												<button class="ds-post-button" alt="<?php echo $v['UserFans']['user_id'];?>;<?php echo $v['UserFans']['id'];?>" type="button"><?php echo $ld['send'] ?></button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</li>
	    		<?php } ?>
	    		</ul>
	    	<?php }else{ ?>
	    		<div style="clear:both;text-align:center;padding-top:20%;"><?php echo $ld['no_focus'] ?></div>
	    	<?php } ?>
	    </div>
	  </div>
	</div>
</div>
<script type="text/javascript">
var word=$("#word").val();
word=word.substring(0,word.length-1);
$(function(){
	$(".showreply").click(function(){
		var id_str=$(this).attr("id");
		var id_str_arr=id_str.split("_");
		if($("#reply_info-"+id_str_arr[1]).css("display")=="none"){
			$("#reply_info-"+id_str_arr[1]).css("display","block");
		}else{
			$("#reply_info-"+id_str_arr[1]).css("display","none")
		}
	});
	
	$(".reply_info .ds-post-button").click(function(){
		var id_str=$(this).attr("alt");
		var id_str_arr=id_str.split(";");
		var to_id=id_str_arr[0];
		var chat_id=id_str_arr[1];
		var textobj=$("#reply_info-"+chat_id+" textarea");
		var text=textobj.val();
		if(text==""){
			alert(message_content_empty);
		}else{
			text=CheckKeyword(word,text);
			$.ajax({ url: "/user_socials/private_comment/",
	    		dataType:"json",
	    		type:"POST",
	    		data: {'to_user_id':to_id,'content':text },
	    		success: function(data){
	    			if(data=="1"){	
	    				alert(send_success);
	    			}else{
	    				alert(send_failed);
	    			}
	    			$("#reply_info-"+chat_id).css("display","none");
	    			textobj.val("");
	  			}
	  		});
		}
	});
})


function mutual_concern(obj,id,type){
	var is_me=$("#is_me").val();
	if(is_me==0){
		alert("<?php echo $ld['please_login'].$ld['login']; ?>");
	}else{
		$.ajax({ url: "/user_socials/fans_add/",
			dataType:"json",
			type:"POST",
			data: {'fans_id':id },
			success: function(data){
				var url="<?php echo $html->url('/user_socials/fanslist/'.$id); ?>/"+type;
				window.location.href=url;
			}
		});
	}
}

function cancel_focus(obj,id,type){
	$.ajax({ url: "/user_socials/unfo/",
			dataType:"json",
			type:"POST",
			data: {'fans_id':id },
			success: function(data){
				var url="<?php echo $html->url('/user_socials/fanslist/'.$id); ?>/"+type;
				window.location.href=url;
			}
		});
}
</script>