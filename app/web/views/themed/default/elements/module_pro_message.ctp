<!--屏蔽的关键字-->
<?php $wordarr=""; if(isset($sm['word'])&&$sm['word']!=""){
	foreach($sm['word'] as $k=>$v){
		$wordarr.=$v['BlockWord']['word'].",";
	}
}
?>
<input type="hidden" id="word" value="<?php echo $wordarr;?>">
<div class="am-g am-g-fixed">
  <div class="am-panel am-panel-default" style="margin-top:10px;">
	<div class="am-panel-hd"><?php echo $code_infos[$sk]['name'];?></div>
	<div  class="am-panel-bd">
	  <div class="listbox am-padding-horizontal-sm">
      <!--商品提问-->
      <?php if($code_infos[$sk]['type']=="module_pro_message"){?>
    	<div class="am-form-group am-form" style="margin:0 auto;">
		  <input type="hidden" id="product_id" name="data[UserMessage][value_id]" value="<?php echo isset($sm['product_id'])?$sm['product_id']:""; ?>">
		  <?php if(empty($_SESSION['User']['User']['id'])){echo $ld['please_login']." <a class='log' onclick='ajax_login_show()' href='javascript:void(0);'>".$ld['login']."</a> ".$ld['perhaps']." <a class='sun' onclick='ajax_register_show()' href='javascript:void(0);'>".$ld['register']."</a>";}else{?>
		  <textarea rows="6" id="msg_content" name="data[UserMessage][msg_content]" <?php if(empty($_SESSION['User']['User']['id'])){echo " disabled='disabled'";}?>></textarea>
		  <p style="text-align:right;margin-top:10px;"><button class="am-btn am-btn-secondary message_adds" onclick="add_video_UserMessage();" type="submit"><?php echo $ld['release'];?></button></p>
		  <?php }?>
		</div>
		<ul class="am-comments-list">
		  <?php $i=0;if(!empty($sm['product_message'])&&sizeof($sm['product_message'])>0){ foreach($sm['product_message'] as $k=>$v){$i++; ?>
          <li class="am-comment">
            <a href="#link-to-user-home">
              <img src="<?php echo isset($v['User']['img01'])&&$v['User']['img01']!=""?$v['User']['img01']:"/theme/default/img/no_head.png";?>" alt="" class="am-comment-avatar" width="48" height="48">
            </a>
            <div class="am-comment-main">
              <header class="am-comment-hd">
                <div class="am-comment-meta">
                	<a href="#link-to-user" class="am-comment-author"><?php echo $v['UserMessage']['user_name']; ?></a> 评论于 <time><?php echo $v['UserMessage']['created'];?></time>
				  <button class="am-btn am-btn-default msg_reply am-hide"  id="<?php echo 'msg_reply'.($k)?>"><?php echo $ld['answer'];?></button>
                </div>
              </header>
              <div class="am-comment-bd">
                <p><?php echo $v['UserMessage']['msg_content'];?></p>
              </div>
            </div>
			<div id="<?php echo 'msg_answer'.($k);?>" style="display: none;">
			  	<div id="ds-thread" style="display: block;">
  				  <div id="ds-reset">
				    <div class="ds-replybox ds-inline-replybox ">
					  <div class="ds-textarea-wrapper ds-rounded-top" style="display: block;">
					    <textarea id="msg_reply_to_user<?php echo $k; ?>"></textarea>
					  </div>
					  <div class="ds-post-toolbar" id="comments_button">
					    <div class="ds-post-options ds-gradient-bg"></div>
					    <button type="button" class="ds-post-button msg_reply_to_user" id="msg_reply_to_user_buttom<?php echo $k; ?>"><?php echo $ld['reply'] ?></button><input type="hidden" id="message<?php echo $k; ?>" value="<?php echo $v['UserMessage']['id']; ?>">
					  </div>
				    </div>
				  </div>
				</div>
				<div class="message_list"  id="message_list_<?php echo $k; ?>">
				  <?php $i=0; if(!empty($v['Reply'])){$message=count($v['Reply']); foreach($v['Reply'] as $kk=>$vv){ $i++;?>
				  <div style="clear:both;" >
					<a href="#"><img src="/theme/default/img/no_head.png" class="am-comment-avatar" width="48" height="48" ></a>
					<div class="am-comment-main">
		              <header class="am-comment-hd">
		                <div class="am-comment-meta">
		                  <a href="#link-to-user" class="am-comment-author"><?php echo $ld['administrator']; ?></a>:回复 <time><?php echo $vv['UserMessage']['created'];?></time>
		                </div>
		              </header>
		              <div class="am-comment-bd">
		                <p><?php echo $vv['UserMessage']['msg_content'];?></p>
		              </div>
		            </div>
				  </div>		
				  <?php }}?>
				</div>
			</div>
          </li>
		  <?php }}?>
        </ul>
	  <?php }?>
	  </div>
	</div>
  </div>
</div>
<script type="text/javascript">
//提问提交	
function add_video_UserMessage(){
	//判断登录
	<?php if(empty($_SESSION['User']['User']['id'])){?>
		$(".denglu").click();
	<?php }else{?>
		if($("#msg_content").val()==""){
			alert("<?php echo $ld['message_content_empty']; ?>");
			return false;
		}else{
			var con=$("#msg_content").val();
			var product_id=$("#product_id").val();
			$.ajax({ url: "/products/ajax_add_message",
		    		dataType:"json",
		    		type:"POST",
		    		context: $("#msg_content"),
		    		data: { 'product_id': product_id, 'content': con },
		    		success: function(data){
	    				alert(data.message);
	    				$("#msg_content").val("");
	    				window.location.reload();
	  			}
	  		});
		}
	<?php }?>
}
//回复内容显示
$(".msg_reply").click(function(){
	var id=$(this).attr("id");
	id=id.replace("msg_reply","msg_answer");
	var message_id=id.replace("msg_answer","msg_reply_to_user_buttom_");
	var message_btn=id.replace("msg_answer","msg_reply_to_user_");
	//alert(message_id+" "+message_btn)
	if($("#"+id).css("display")=="none")
	{
		$("#"+id).css("display","block");
		$("#"+message_id).parent().css("display","block");
		$("#"+message_btn).parent().css("display","block");
	}
	else
	{
		$("#"+id).css("display","none");
		$("#"+message_id).parent().css("display","none");
		$("#"+message_btn).parent().css("display","none");
	}
});

//回复评论功能
$(".msg_reply_to_user").click(function(){	
	//判断登录
	<?php if(empty($_SESSION['User']['User']['id'])){?>
		$(".denglu").click();
	<?php }else{?>
	var id=$(this).attr("id");
	var textid=id.replace("msg_reply_to_user_buttom","msg_reply_to_user");
	var text=$("#"+textid).val();
	var message_list=id.replace("msg_reply_to_user_buttom","message_list_");
	if(text==""){
		alert("回答不能为空，写点什么吧!");
	}
	else{
		var message=id.replace("msg_reply_to_user_buttom","message");
		message_id=$("#"+message).val();
		$.ajax({ url: "/products/ajax_add_message/",
	    		dataType:"json",
	    		type:"POST",
	    		context: $("#"+message_list),
	    		data: { 'parent_id': message_id, 'content': text },
	    		success: function(data){
//				$("#"+message_list).html("");
//	    			$("#"+message_list).html(data.message);
//	    			$("#"+textid).val("");
//	    			$("#"+id).parent().css("display","none");
//	    			$("#"+textid).parent().css("display","none");
  			}
  		});
	}
	<?php }?>
});
</script>					