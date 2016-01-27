<script type="text/javascript" src="/theme/default/js/ckplayer6.3/js/youku_jsapi.js" charset="utf-8"></script>
<script type="text/javascript" src="/theme/default/js/ckplayer6.3/ckplayer/ckplayer.js" charset="utf-8"></script>
<div id="<?php echo $sk?>" class=" <?php echo 'div_'.$sk; ?> am-u-lg-8 am-u-md-8 am-u-sm-12">		
	<?php if($code_infos[$sk]['type']=="module_article_video"){?>
    	<div class="">
        	<!--<h5><?php //echo$sm['ArticleI18n']['title'];?></h5>
        	视频标题-->
			<div class="am-titlebar am-titlebar-default am-no-layout" data-am-widget="titlebar"  style="margin:0;">
			  <h2 class="am-titlebar-title">
				<?php echo $sm['ArticleI18n']['title'];?>
			  </h2>
			</div>
			<!--视频标题 end-->
        </div>
    <?php if(isset($sm)){?>	
		<!--视频播放-->
		<?php if(!isset($sm['Article'])||($sm['Article']['video']==""&&$sm['Article']['upload_video']=="")){ ?>
			<script>
				function no_video(){
					alert("没有该视频!");
				}
			</script>
			<div class="this_video">
				<a href="javascript:void(0);" onclick="no_video()"><img style="width:100%; height:100%;"src="<?php if(isset($video['ArticleI18n']['img01'])&&$video['ArticleI18n']['img01']!=""){echo $video['ArticleI18n']['img01'];}else{echo '/theme/default/img/shipin_1.jpg';}?>" />
				</a>
			</div>
		<?php }else{
			if(isset($sm['Article']['video_competence'])){
				$video_competence=explode(',',$sm['Article']['video_competence']);				
			}
			if(isset($_SESSION['User']['User']['rank'])&&$_SESSION['User']['User']['rank']==0){
				$user_video_type=2;
			}else{
				$user_video_type=isset($_SESSION['User']['User']['rank'])?$_SESSION['User']['User']['rank']:"";
			}
			$max_video_type=0;
			foreach($video_competence as $k=>$v){
				if($v>$max_video_type){
					$max_video_type=$v;
				}
			}
			if(isset($video_competence)&&(in_array($user_video_type,$video_competence)||($user_video_type>$max_video_type))){?>
			<div id='youkuplayer' class="youkuplayer" style="position:relative;z-index:100;">	
				<!--<embed style="" src="https://gitcafe.com/kawaiiushio/antiads/raw/master/loader.swf?showAD=0&VideoIDS=<?php echo $sm['Article']['video'];?>" allowfullscreen="true" quality="high" width="680" height="423" align="middle" allowscriptaccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" flashvars="isShowRelatedVideo=false&showAd=0&show_pre=1&show_next=1&isAutoPlay=false&isDebug=false&UserID=&winType=interior&playMovie=true&MMControl=false&MMout=false&RecordCode=1001,1002,1003,1004,1005,1006,2001,3001,3002,3003,3004,3005,3007,3008,9999" play="true" loop="true" menu="true">-->
			<!--<div id="youkuplayer" style="float:left;width:640px;height:360px">
				<img src="/theme/default/img/shipin_1.jpg"/>
			</div>-->
			<div id="a1"></div>
			</div>
			<?php }else{ ?>
			<div class="this_video" >
				<a href="javascript:void(0);" onclick="check_competence('<?php echo $user_video_type;?>','<?php echo $sm['Article']['video_competence'];?>')">
					<img src="<?php if(isset($sm['ArticleI18n']['img01'])&&$sm['ArticleI18n']['img01']!=""){echo $sm['ArticleI18n']['img01'];}else{echo '/theme/default/img/shipin_1.jpg';}?>"/>
				</a>
			</div>
		<?php }}?>
		<!--视频播放 end-->
	<?php }?>
    <?php }?>	
</div>	

<script>
function check_competence(type,arr){
	if(type==""){
		alert("请先注册会员后，再登录后进行刚才的操作！");
	}else{
		if(arr.indexOf(type)==-1){
			alert("您的权限不可观看此视频");
		}
	}
}
$(function(){
	if(document.getElementById("a1")){
		<?php
			if(isset($_SESSION['User']['User']['rank'])&&$_SESSION['User']['User']['rank']==0){
				$user_video_type=2;
			}else{
				$user_video_type=isset($_SESSION['User']['User']['rank'])?$_SESSION['User']['User']['rank']:"";
			}
			//普通视频
			if(isset($sm['Article']['video_competence'])){
				$video_competence=explode(',',$sm['Article']['video_competence']);
			}
			if(isset($video_competence)&&in_array($user_video_type,$video_competence) || $video_competence[0]==""){ 
				if($sm['Article']['video']!=""){//存在外部链接id 
			?>
					player = new YKU.Player('youkuplayer',{
						client_id: "<?php echo $configs['client_id'];?>",
						vid: "<?php echo $sm['Article']['video'];?>",
						show_related: false
					});
		<?php
			}else{
			//使用内置上传
			?>
				var flashvars={
					f:"/articles/video_play/<?php echo $sm['Article']['id'];?>",
					s:'0',
					c:0,
					b:1
				};
					/*
					CKobject.embedSWF(播放器路径,容器id,播放器id/name,播放器宽,播放器高,flashvars的值,其它定义也可省略);
					下面三行是调用html5播放器用到的
					*/
					var Sys = {};
					var ua = navigator.userAgent.toLowerCase();
					var s;
					(s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
					(s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
					(s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
					(s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
					(s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
					if (!Sys.firefox){
						var video=["/articles/video_play/<?php echo $sm['Article']['id'];?>->video/mp4"];
						var support=['iPad','iPhone','ios','android+false','msie10+false'];
						CKobject.embedHTML5('youkuplayer','ckplayer_a1','100%','100%',video,flashvars,support);
					}
		var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always', wmode: 'transparent'};
		CKobject.embedSWF('/theme/default/js/ckplayer6.3/ckplayer/ckplayer.swf','a1','ckplayer_a1','100%','423',flashvars,params);
		<?php 
				}
			}else{ ?>
			//会员视频
				var flashvars={
					f:"/articles/video_play/<?php echo $sm['Article']['id'];?>",
					s:'0',
					c:0,
					b:1
				};
				/*
				CKobject.embedSWF(播放器路径,容器id,播放器id/name,播放器宽,播放器高,flashvars的值,其它定义也可省略);
				下面三行是调用html5播放器用到的
				*/
				var Sys = {};
				var ua = navigator.userAgent.toLowerCase();
				var s;
				(s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
				(s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
				(s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
				(s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
				(s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
				if (!Sys.firefox){
					var video=["/articles/video_play/<?php echo $sm['Article']['id'];?>->video/mp4"];
					var support=['iPad','iPhone','ios','android+false','msie10+false'];
					CKobject.embedHTML5('youkuplayer','ckplayer_a1','100%','100%',video,flashvars,support);
				}
		var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always', wmode: 'transparent'};
		CKobject.embedSWF('/theme/default/js/ckplayer6.3/ckplayer/ckplayer.swf','a1','ckplayer_a1','100%','423',flashvars,params);
		<?php } ?>
		
	}
	<?php if(isset($sm['Article']['video']) && $sm['Article']['video']!=""){?>
//	if(document.getElementById("a1")){
//		var defaultVid = "<?php if(isset($sm['Article']['video']))echo $sm['Article']['video'];?>";
//		var flashvars={
//			f:"/vendors/ckplayer6.3/ckplayer/yk.php?u=<?php if(isset($sm['Article']['video']))echo $sm['Article']['video'];?>",
//			s:'1',
//			c:0,
//			b:1
//		};
//		getYoukuMp4Url(
//		defaultVid, 
//		function(mp4Url)
//		{
//			/*
//			CKobject.embedSWF(播放器路径,容器id,播放器id/name,播放器宽,播放器高,flashvars的值,其它定义也可省略);
//			下面三行是调用html5播放器用到的
//			*/
//			var Sys = {};
//			var ua = navigator.userAgent.toLowerCase();
//			var s;
//			(s = ua.match(/msie ([\d.]+)/)) ? Sys.ie = s[1] :
//			(s = ua.match(/firefox\/([\d.]+)/)) ? Sys.firefox = s[1] :
//			(s = ua.match(/chrome\/([\d.]+)/)) ? Sys.chrome = s[1] :
//			(s = ua.match(/opera.([\d.]+)/)) ? Sys.opera = s[1] :
//			(s = ua.match(/version\/([\d.]+).*safari/)) ? Sys.safari = s[1] : 0;
//			if (!Sys.firefox){
//				var video=[mp4Url+"->video/mp4"];
//				var support=['iPad','iPhone','ios','android+false','msie10+false'];
//				CKobject.embedHTML5('youkuplayer','ckplayer_a1',680,423,video,flashvars,support);
//			}
//		}
//	);
//		var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always'};
//		CKobject.embedSWF('/vendors/ckplayer6.3/ckplayer/ckplayer.swf','a1','ckplayer_a1','680','423',flashvars,params);
//	}
	<?php }?>	
});	
</script>				