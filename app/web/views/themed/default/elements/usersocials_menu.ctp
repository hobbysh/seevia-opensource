<div class="am-u-lg-3 am-u-md-4 am-u-sm-12">
    <div class="am-panel am-panel-default">
	  <div class="am-panel-hd"><?php echo $ld["user_center"] ?></div>
	  <div class="am-panel-bd">
		<div>
		  <div class="am-user-head">
			<img title="<?php echo $user['User']['name']; ?>" class="am-u-sm-centered" src="<?php echo isset($user['User']['img01'])&&$user['User']['img01']!=""?$user['User']['img01']:"/theme/default/img/no_head.png";?>">
		    <!-- 头像编辑链接浮动窗口 -->
			<?php if(isset($_SESSION['User'])&&$user['User']['id']==$_SESSION['User']['User']['id']){ ?>
			<div class="am-popover am-popover-bottom" id="am-user-avatar">
			  <div class="am-popover-inner"><a href="<?php echo $html->url('/users/edit_headimg'); ?>"><?php echo $ld['editing_avatar'] ?></a></div>
			</div>
			<?php } ?>
			<!-- 头像编辑链接浮动窗口 -->
		  </div>
		  <h2 class="am-user-name"><?php echo $user['User']['name'];?></h2>
		  <hr class="am-margin-vertical-xs"/>
		  <ul class="am-gallery am-avg-sm-3 am-avg-md-3 am-avg-lg-3 am-gallery-default" >
			<li class="am-text-center">
			  <a id="focus" target="_blank" href="<?php echo $html->url('/user_socials/fanslist/?id='.$user['User']['id'].'&&type=1'); ?>">
				<span><?php echo $ld['focus'] ?></span><br><strong class="am-margin-horizontal-xs"><?php echo $focus; ?></strong>
			  </a>
			</li>
			<li class="follower am-text-center">
			  <a id="fans" target="_blank" href="<?php echo $html->url('/user_socials/fanslist/?id='.$user['User']['id'].'&&type=2'); ?>" name="place">
			    <span><?php echo $ld['fans'] ?></span><br><strong class="am-margin-horizontal-xs"><?php echo $fans;?></strong>
			  </a>
			</li>
			<li class="W_no_border am-text-center">
			  <a id="blog" target="_blank" href="<?php echo $html->url('/user_socials/index/'.$user['User']['id']); ?>" name="profile_tab">
				<span><?php echo $ld['diary'] ?></span><br><strong class="am-margin-horizontal-xs"><?php echo $blog; ?></strong>
			  </a>
			</li>
		  </ul>
		  <hr class="am-margin-vertical-xs"/>
		  <ul class="am-gallery am-gallery-default am-pagination-centered">
			<?php if(isset($_SESSION['User']['User']['id']) && ($_SESSION['User']['User']['id']==$id)){?>
			<li class="zhanghao am-u-lg-12 am-u-md-12 am-u-sm-12 am-btn am-btn-secondary"><a target="_blank" href="<?php echo $html->url('/users/edit'); ?>" class="user_a"><?php echo $ld['account_setup'] ?></a></li>
			<li class="xiaoxi am-u-lg-12 am-u-md-12 am-u-sm-12 am-btn am-btn-secondary"><a target="_blank" href="<?php echo $html->url('/user_socials/message_index?id='.$user['User']['id']);?>" class="user_a"><?php echo $ld['msg']; ?></a></li>
			<?php }else{?>
			<li class="zhanghao am-u-lg-12 am-u-md-12 am-u-sm-12 am-btn am-btn-secondary">
			  <?php  $flag=0; if(isset($_SESSION['User'])){//判断是否登录
				if(isset($user_list)&&sizeof($user_list)>0){
				  foreach($user_list as $k=>$v){ 					            				
					if($v['UserFans']['fan_id']==$_SESSION['User']['User']['id']&&$v['UserFans']['user_id']==$user['User']['id']){
					  $flag++;
					}
				  }
				  if($flag>0){ ?>
				<a class="user_a del_my_focus" href="javascript:void(0);"><?php echo $ld['cancel_focus'] ?></a>
			  <?php }else{ ?>
				<a style="cursor:pointer" href="javascript:void(0);" class="user_a fansadd"><?php echo $ld['plus_interest'] ?></a>
			  <?php }}else{ ?>
				<a style="cursor:pointer" href="javascript:void(0);" class="user_a fansadd"><?php echo $ld['plus_interest'] ?></a>
			  <?php }}else{ ?>
				<a style="cursor:pointer" href="javascript:void(0);" onclick="ajax_login_show();" class="user_a fansadd1"><?php echo $ld['plus_interest'] ?></a>
			  <?php } ?>
				<input type="hidden" value="<?php echo $user['User']['id'];?>">
			</li>
			<li class="xiaoxi am-u-lg-12 am-u-md-12 am-u-sm-12 am-btn am-btn-secondary">
			  <?php if(isset($_SESSION['User']['User']['id'])){?>
			  <a href="<?php echo $html->url('/user_socials/chatroom/'.$user['User']['id'],array('target'=>'_blank')); ?>" class="user_a"><?php echo $ld['send'].$ld['msg'] ?></a>
			  <?php }else{?>
			  <a class="user_a fansadd1" href="javascript:void(0)" onclick="ajax_login_show();"><?php echo $ld['send'].$ld['msg'] ?></a>
			  <?php }?>
			</li>
			<?php }?>
		  </ul>
          <?php if(isset($topicInfo) && sizeof($topicInfo)>0){?>
		  <div data-am-widget="list_news" class="am-list-news am-list-news-default" style="clear:both;">
		    <div class="am-list-news-hd am-cf">
		      <h2><?php echo $ld['latest_activity']; ?></h2>
		      <span class="am-list-news-more am-fr"><a class="more" target='_blank' href="<?php echo $html->url('/topics/');?>"><?php echo $ld['more'] ?></a></span>
		    </div>
		    <div class="am-list-news-bd">
		      <ul class="am-list">
			  <?php if(isset($topicInfo)&&count($topicInfo)>0) {foreach($topicInfo as $k=>$v){ ?>
			    <li class="am-g am-list-item-dated"><a target='_blank' href='<?php echo $html->url("/topics/".$v["Topic"]["id"]); ?>' title='<?php echo $v["TopicI18n"]["title"]; ?>'><?php echo $v["TopicI18n"]["title"]; ?></a></li>
			  <?php }}?>
		      </ul>
		    </div>
		  </div>
          <?php } ?>
		  <?php if(isset($visitor_list) && sizeof($visitor_list)>0){?>
		  <div class="am-list-news am-list-news-default am-no-layout" data-am-widget="list_news">
			<div class="am-list-news-hd am-cf">
		      <h2><?php echo $ld['recent_visit'] ?></h2>
			</div>
			<hr class="am-margin-vertical-xs"/>
		  <ul data-am-widget="gallery" class="am-avg-sm-6 am-avg-md-6 am-avg-lg-6 am-gallery-default" data-am-gallery="{ }">
			<?php foreach($visitor_list as $k=>$v){
				$img=empty($v['User']['img01'])?"/theme/default/img/no_head.png":$v['User']['img01'];
			  ?>
  			<li><?php echo $svshow->link($svshow->image($img,LOCALE,array('style'=>'width:100%;')),"/user_socials/index/".$v['User']['id'],array('target'=>'_blank'));?></li>
			<?php }?>
		  </ul>
		  </div>
		  <?php }?>
		</div>
	  </div>
	</div>
</div>

<!--初始化导航栏，选择点中的链接-->
<?php if(isset($selected_menu)){?>
	<?php echo $selected_menu?>
<script type="text/javascript">linkClick($('#<?php echo $selected_menu?>'));</script>
<?php }?>

<script>
$(document).ready(function(){
	var window_height=$(window).height();//窗口高度
	$(".am-tab-panel").css("min-height",(window_height*0.6)+"px");
    
    $('.am-user-head').mouseover(function(){
		$("#am-user-avatar.am-popover").fadeIn();
	});
    
    $('.am-user-head').mouseout(function(){
		$("#am-user-avatar.am-popover").fadeOut(100);
	});
    
	<?php if(isset($_SESSION['User']['User']['id']) && ($_SESSION['User']['User']['id']==$id)){?>
	$.ajax({ url: "/user_socials/get_message_count/",
		dataType:"json",
		type:"POST",
		success: function(data){
			var count=parseInt(data.c_count)+parseInt(data.m_count);
			if(count>0){
				$(".xiaoxi a").html("<?php echo $ld['msg'] ?>("+count+")");
			}
			//$(".xiaoxi a").css("color","#FFFFFF");
			//$(".xiaoxi a").css("font-size","12px");
		}
	});
	<?php } ?>
	
	var foo=function(){
		return false;
	}
	
	$(".fansadd").click(function(){
		var fansadd=$(this);
		var fans=$(this).parent().find("[type=hidden]").val();
		$.ajax({ url: "/user_socials/fans_add/",
			dataType:"json",
			type:"POST",
			data: {'fans_id':fans },
			success: function(){
			    fansadd.html("<?php echo $ld['cancel_focus'] ?>");
    			fansadd.attr("class","user_a del_my_focus");
    			$("#fans").children().eq(0).html(parseInt($("#fans").children().eq(0).html())+1);
    			window.top.location.reload();
			}
		});
	});
	
	//取消关注点击事件
	$(".del_my_focus").click(function(){
		var fansdel=$(this);
		var unfans=$(this).parent().find("[type=hidden]").val();
		$.ajax({ url: "/user_socials/unfo/",
			dataType:"json",
			type:"POST",
			data: {'fans_id':unfans },
			success: function(data){
				$("#fans").children().eq(0).html((parseInt($("#fans").children().eq(0).html())-1));
				fansdel.html("<?php echo $ld['plus_interest'] ?>");
				fansdel.attr("class","user_a fansadd");
				window.top.location.reload();
			}
		});
	});
	
  	$(".fansadd1").click(function(){
		$("#popup_login").click();
  	});
});
</script>