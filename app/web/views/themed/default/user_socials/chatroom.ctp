<?php
	$wordarr=""; if(isset($word)&&$word!=""){
		foreach($word as $k=>$v){
			$wordarr.=$v['BlockWord']['word'].",";
		}
	}
?>
<div class="am-user-chatromm">
	<input type="hidden" id="word" value="<?php echo $wordarr;?>">
	<input type="hidden" id="user" value="<?php echo $_SESSION['User']['User']['id'];?>">
	<input type="hidden" id="to_user" value="<?php echo isset($ids)?$ids:'';?>">
	<div class="am-comment-main" style="width:83%;margin:0 auto;margin-left:64px;">
		<div class="send_weibo" node-type="wrap">
			<div class="title_area clearfix" style="width:100%;text-align:left;padding:2px 5px;">
				<div class="num S_txt2">
					<strong style="color:#3bb4f2;"><?php echo isset($userInfo)?$userInfo['User']['name'].':':''; ?></strong>
				</div>
			</div>
		</div>
		<div id="ds-thread">
		  <div id="ds-reset">
			<div class="ds-textarea-wrapper ds-rounded-top" >
			  <input id="id" type="hidden" name="data[Blog][user_id]" value="<?php echo isset($user['User']['id'])?$user['User']['id']:'';?>">
			  <textarea style="resize:none;" class="am-input-lg" id="contenttext"></textarea>
			</div>
			<div class="ds-post-toolbar">
			  <div class="ds-post-options ds-gradient-bg"></div>
			  <button id="send_btn" class="ds-post-button" type="button"><?php echo $ld['send'] ?></button>
			  <div class="ds-toolbar-buttons">
			  </div>
			</div>
		  </div>
		</div>
	</div>
	<div class="chat_history">
	<?php if(isset($chat_history)&&$chat_history!=""){?>
		<ul class="am-comments-list am-comments-list-flip">
		<?php foreach($chat_history as $k=>$v){ ?>
			<li class="am-comment <?php if($v['User']['id']!=$_SESSION['User']['User']['id']){echo 'am-comment-flip';}?>">
				<a href="javascript:void(0);"><img width="48" height="48" class="am-comment-avatar" src="<?php echo $v['User']['img01']!=''?$v['User']['img01']:'/theme/default/img/no_head.png';?>"></a>
				<div class="am-comment-main">
					<header class="am-comment-hd">
						<div class="am-comment-meta">
							<time><?php $time=explode(" ",$v['UserChat']['created']);$ymd=explode("-",$time[0]);$his=explode(":",$time[1]);echo $his[0].":".$his[1]." ".$ymd[1].'/'.$ymd[2];?></time>
						</div>
					</header>
					<div class="am-comment-bd"><?php echo $v['UserChat']['content'];?></div>
				</div>
			</li>
		<?php } ?>
		</ul>
	<?php } ?>
	</div>
</div>

<script type="text/javascript">
var word=$("#word").val();
if(word.length>0){
	word=word.substring(0,word.length-1);
}
$(function(){
	$("#send_btn").click(function(){
		var id=$("#user").val();
		var toid=$("#to_user").val();
		var text=$("#contenttext").val();
		if(text==""||text==null){
			alert(message_content_empty);
			return false;
		}
		text=CheckKeyword(word,text);
		$.ajax({ url: "/user_socials/private_comment/",
	    		dataType:"json",
	    		type:"POST",
	    		data: {'to_user_id':toid,'content':text},
	    		success: function(data){
	    			if(data=="1"){
	    				$("#contenttext").val("");
	    				var myDate = new Date();
						var time_str=myDate.getHours()+":"+myDate.getMinutes()+" "+myDate.getMonth()+"/"+myDate.getDate();
						var show_html="<li class='am-comment'><a target='_blank' href='javascript:void(0);'><img width='48' height='48' src='<?php  echo $_SESSION['User']['User']['img01']!=""?$_SESSION['User']['User']['img01']:'/theme/default/img/no_head.png'; ?>' class='am-comment-avatar'></a><div class='am-comment-main'><header class='am-comment-hd'><div class='am-comment-meta'><time>"+time_str+"</time></div></header><div class='am-comment-bd'>"+text+"</div></div></li>";
						$(".chat_history ul").html(show_html+$(".chat_history ul").html());
	    			}else{
	    				alert(send_failed);
	    			}
	  			}
	  		});
	});
})
</script>