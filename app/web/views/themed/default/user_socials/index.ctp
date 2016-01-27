<?php $wordarr=""; if(isset($word)&&$word!=""){
		foreach($word as $k=>$v){
			$wordarr.=$v['BlockWord']['word'].",";
		}
		
	}?>
	<input type="hidden" id="word" value="<?php echo $wordarr;?>">
<?php if(isset($_SESSION['User']['User']['id']) && ($_SESSION['User']['User']['id']==$id)){?>
<?php echo $form->create('UserSocials',array('action'=>'release','name'=>"ReleaseForm",'id'=>"ReleaseForm",'class'=>"am-form","type"=>"post",'enctype'=>'multipart/form-data'));?>
<div id="comment_main" class="am-comment-main">
  <div class="send_weibo" node-type="wrap">
	<div class="title_area clearfix">
	  <div class="num S_txt2" node-type="num">
		<span id="checklen"><span>140</span></span> /<strong class="am-text-secondary">140</strong>
	  </div>
	</div>
  </div>
<div id="ds-thread">
  <div id="ds-reset">
	<div class="ds-textarea-wrapper ds-rounded-top" >
	  <input id="id" type="hidden" name="data[Blog][user_id]" value="<?php echo isset($user['User']['id'])?$user['User']['id']:'';?>">
	  <textarea onkeyup="strLenCalc($(this),'checklen',280);" class="am-input-sm" id="input_detail"  title=""></textarea>
	  <input type="hidden" id="hid" name="data[Blog][content]" value="">
	</div>
	<div class="ds-post-toolbar">
	  <div class="ds-post-options ds-gradient-bg">
		<span class="func" style="float:right;margin-right:10px;">
			<span class="tongbu"><span class="share_wz" style=""><?php echo $ld['simultaneously_published_to'] ?></span>
				<?php
				if(isset($UserApp_list)&&sizeof($UserApp_list)>0){ 
						foreach($UserApp_list as $k=>$v){
				?>
				<a style="" class="share_icon"  onclick="checktoken('<?php echo $v['UserApp']['type']; ?>')" href="javascript:void(0);"><img id="<?php echo $v['UserApp']['type']; ?>_icon" src="/theme/default/img/<?php echo $v['img'] ?>" style="width:16px;height:16px;<?php echo $v['status']=='0'?'filter: Alpha(opacity=10);-moz-opacity:.1;opacity:0.3;':''; ?>" /></a>
				<?php }} ?>
			</span>
		</span>
		<span class="kind_detail">
			<a class="S_func1" href="javascript:void(0);">
			<i id="biaoqing" class="W_ico16 icon_sw_face"></i>
			</a>
			<a class="S_func1" style="margin-left:5px;" href="javascript:void(0);">
			<i class="W_ico16 icon_sw_img" id="pictureUploadButton"></i>
				<div class="expression ">
			<?php 
				foreach($Expression as $k=>$v){
				echo "<div class='picks' id='[@F_".($k+1)."@]'><img src='/theme/default/img/gif/F_".($k+1).".gif' title='".$v."' /></div>";
				}
			?>
					<div style="clear:both"></div></div>
			<div id="pictureFile" name="upfile">
				<div id="pictureFilebox" name="upfile" style="width:100%;">
					<span class="btn_close"><img src="/theme/default/img/btn_close_img.png"/></span>
					<div id="pictureFileName"  name="upfile" style="" ><input id="uppic" type="file" name="upfile" onchange="filesize(this)" /></div>
				</div>
			</div>
			</a>
		</span>

	  </div>
	  <button id="res_btn" class="ds-post-button" type="button"><?php echo $ld['release'] ?></button>
	  <div class="ds-toolbar-buttons">
	  </div>
	</div>
  </div>
</div>

</div>
<?php echo $form->end();?>
<?php }?>

<div id="cddtab" class="am-tabs" data-am-tabs="{noSwipe: 1}">
  <ul class="am-tabs-nav am-nav am-nav-tabs">
    <li class="<?php if($pagetype=='all'||$pagetype=='diary'||$pagetype=='action'){echo 'am-active';}else{echo '';} ?>"><a href="#tab2-1">Feeds
	  <span style="<?php isset($userlikenum)&&$userlikenum>0?"display:block;":"display:none"; ?>" ><?php echo (int)$userlikenum<9999?$userlikenum:'9999';?></span></a>
	</li>
	<?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
    <li class="<?php if($pagetype=='baobei'){echo 'am-active';}else{echo '';} ?>"><a href="#tab2-2"><?php echo $ld['treasure']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="am-tabs-bd">
    <div class="am-tab-panel <?php if($pagetype=='all'||$pagetype=='diary'||$pagetype=='action'){echo 'am-active';}else{echo '';} ?>" id="tab2-1">
	  <div style="width:100%;height:46px;padding-top:16px;">
		<span class="am-classificatory">
		  <span class="classificatory"><?php echo $ld['classificatory'] ?></span>
			<span class="am-btn-group am-btn-group-xs">
  			  <button id="s1" type="button" class="am-btn am-btn-default am-round sorting <?php echo isset($pagetype)&&($pagetype=='all'||$pagetype=='baobei')?'am-active':''; ?>"><?php echo $ld['all'] ?></button>
			  <button id="s2" type="button" class="am-btn am-btn-default am-round sorting <?php echo isset($pagetype)&& $pagetype=='diary'?'am-active':''; ?>"><?php echo $ld['diary'] ?></button>
			  <button id="s3" type="button" class="am-btn am-btn-default am-round sorting <?php echo isset($pagetype)&& $pagetype=='action'?'am-active':''; ?>"><?php echo $ld['action'] ?></button>
			</span>
			<!--隐私设置-->
			<?php if(isset($_SESSION['User'])&&$_SESSION['User']['User']['id']==$id){ ?>
			<span><a style="margin-left:10px;" target="_blank" href="<?php echo $html->url('/user_socials/privacy_settings'); ?>"><?php echo $ld['privacy_settings'] ?></a></span>
			<?php } ?>
		</span>
	  </div>
	  <!--用户日志-->
	  <input type="hidden" id="userid" value="<?php echo $user_id;?>">
	  <!--所有-->
	  <div class="all" style="clear:both;<?php echo isset($pagetype)&&($pagetype=='all'||$pagetype=='baobei')?'display:block;':'display:none;'; ?>">
	  <?php if(isset($log_set) && ($log_set==2) ||($log_set==1 && !empty($fan_like)) || $fan_like==1){?>
	  <ul class="am-comments-list am-comments-list-flip">
	    <?php if(isset($all_list) && sizeof($all_list)>0){foreach($all_list as $k=>$v){ if(isset($v["Blog"])&&$v['Blog']!=""){?>
		<li class="am-comment">
		  <a target="_blank" title="<?php echo $v['User']['name'];?>" href="<?php echo $html->url('/user_socials/index/'.$v['User']['id']); ?>">
    		<img src="<?php echo isset($v['User']['img01'])&&$v['User']['img01']!=""?$v['User']['img01']:'/theme/default/img/no_head.png';?>" alt="<?php echo $v['User']['name'];?>" class="am-comment-avatar" width="48" height="48"/>
  		  </a>
		  <div class="am-comment-main">
		    <header class="am-comment-hd">
		      <div class="am-comment-meta">
		        <a href="#link-to-user" class="am-comment-author"><?php echo $v['User']['name'];?></a>
		        <?php echo $ld['publish_on'] ?> <time><?php $time=explode(" ",$v['Blog']['created']);$ymd=explode("-",$time[0]);$his=explode(":",$time[1]);echo $his[0].":".$his[1]." ".$ymd[1].'/'.$ymd[2];?></time>
		      </div>
		    </header>
		    <div class="am-comment-bd">
			  <div class="am-u-lg-11 am-u-md-11">
			    <?php echo $v['Blog']['content'];?>
			  </div>
			  <?php if(!empty($v['Blog']['img'])){?>
			  <div style="max-width:320px;">
			  <figure data-am-widget="figure" class="am am-figure am-figure-default am-u-lg-12" data-am-figure="{  pureview: 'auto' }">
  			  	<img width="100%" src="<?php echo $v['Blog']['img']?>" data-rel="<?php echo $v['Blog']['img']?>" alt="" />
			  </figure>
			  </div>
			  <?php } ?>
			
			  <div class="WB_handle">
				<a href="javascript:void(0);" id="test_common-<?php echo $v['Blog']['id']?>"><?php echo $ld['comment'] ?> <span><?php echo $v['Blog']['comment_num'];?></span></a>
			  </div>
			  <div class="comment_box_<?php echo $v['Blog']['id'];?>" style="display:none;">
				<div id="ds-thread" >
  				  <div id="ds-reset">
				    <div class="ds-replybox ds-inline-replybox " >
					  <div class="ds-textarea-wrapper ds-rounded-top comment_box_<?php echo $v['Blog']['id'];?>">
					    <textarea name="message"></textarea>
					  </div>
					  <div id="comments_button" class="ds-post-toolbar">
					    <div class="ds-post-options ds-gradient-bg"></div>
					    <button id="comments_button-<?php echo $v['Blog']['id']?>" class="ds-post-button" type="button"><?php echo $ld['comment'] ?></button>
					  </div>
				    </div>
				  </div>
				</div>
				<?php if(isset($comment_set) && ($comment_set==2) ||($comment_set==1 && !empty($fan_like)) || $fan_like==1){?>
				<div class="comments_list" style="<?php echo isset($_SESSION['User'])?'':'padding:0px;'; ?>"></div>
				<?php }else if($fan_like !=1 && $comment_set==0){ echo $ld['privacy_and_confidentiality'];	}else{echo $ld['without_permission'];}?>
			  </div>
			</div>
		  </div>
		</li>
	    <?php }else if(isset($v['UserAction'])){ ?>
		<li class="am-comment">
		  <a target="_blank" title="<?php echo $v['User']['name'];?>" href="<?php echo $html->url('/user_socials/index/'.$v['User']['id']); ?>">
    		<img src="<?php echo isset($v['User']['img01'])&&$v['User']['img01']!=""?$v['User']['img01']:'/theme/default/img/no_head.png';?>" alt="<?php echo $v['User']['name'];?>" class="am-comment-avatar" width="48" height="48"/>
  		  </a>
		  <div class="am-comment-main">
		    <header class="am-comment-hd">
		      <div class="am-comment-meta">
		        <a href="#link-to-user" class="am-comment-author"><?php echo $v['User']['name'];?></a>
		        &nbsp;&nbsp;&nbsp;<?php echo $ld['at']?> <time><?php $time=explode(" ",$v['UserAction']['created']);$ymd=explode("-",$time[0]);$his=explode(":",$time[1]);echo $his[0].":".$his[1]." ".$ymd[1].'/'.$ymd[2];?></time>
		      </div>
		    </header>
		    <div class="am-comment-bd">
			  <?php echo $v['UserAction']['content']; ?>
			</div>
		  </div>
		</li>
	    <?php }}}else{?>
 		<li style="margin-top:20%;text-align:center;"><?php echo isset($_SESSION['User'])&&$_SESSION['User']['User']['id']==$id?$ld['no_log_your']:$ld['no_log_he']; ?></li>
		<?php }?>
	  </ul>
	  <?php }else if($fan_like !=1 && $log_set==0){ echo $ld['privacy_and_confidentiality'];}else{echo $ld['without_permission'];}?>
	  </div>
	  <!--所有end-->
	  <!--日志-->
	  <div class="diary" style="clear:both;<?php echo isset($pagetype)&&$pagetype=='diary'?'display:block;':'display:none;'; ?>">
	  <?php if(isset($log_set) && ($log_set==2) ||($log_set==1 && !empty($fan_like)) || $fan_like==1){?>
		<ul class="am-comments-list am-comments-list-flip">
		<?php if(isset($blog_list) && sizeof($blog_list)>0){foreach($blog_list as $k=>$v){ ?>
		  <li class="am-comment">
			<a target="_blank" title="<?php echo $v['User']['name'];?>" href="<?php echo $html->url('/user_socials/index/'.$v['User']['id']); ?>">
    		  <img src="<?php echo isset($v['User']['img01'])&&$v['User']['img01']!=""?$v['User']['img01']:'/theme/default/img/no_head.png';?>" alt="<?php echo $v['User']['name'];?>" class="am-comment-avatar" width="48" height="48"/>
  			</a>
			<div class="am-comment-main">
		      <header class="am-comment-hd">
		    	<div class="am-comment-meta">
		          <a href="#link-to-user" class="am-comment-author"><?php echo $v['User']['name'];?></a>
		          <?php echo $ld['publish_on'] ?> <time><?php $time=explode(" ",$v['Blog']['created']);$ymd=explode("-",$time[0]);$his=explode(":",$time[1]);echo $his[0].":".$his[1]." ".$ymd[1].'/'.$ymd[2];?></time>
		    	</div>
		      </header>
		      <div class="am-comment-bd">
				<div class="am-u-lg-11 am-u-md-11">
				  <?php echo $v['Blog']['content'];?>
				</div>
			    <?php if(!empty($v['Blog']['img'])){?>
				<div style="max-width:320px;">
			  	<figure data-am-widget="figure" class="am am-figure am-figure-default am-u-lg-12" data-am-figure="{pureview: 'auto'}">
  			  	  <img width="100%" src="<?php echo $v['Blog']['img']?>" data-rel="<?php echo $v['Blog']['img']?>" alt="" />
			  	</figure>
				</div>
			  	<?php } ?>
			    <div class="WB_handle">
				  <a href="javascript:void(0);" id="test_common-<?php echo $v['Blog']['id']?>"><?php echo $ld['comment'] ?> <span><?php echo $v['Blog']['comment_num'];?></span></a>
			  	</div>
			  	<div class="comment_box_<?php echo $v['Blog']['id'];?>" style="display:none;">
				  <div id="ds-thread" >
  				  	<div id="ds-reset">
				      <div class="ds-replybox ds-inline-replybox " >
					  	<div class="ds-textarea-wrapper ds-rounded-top comment_box_<?php echo $v['Blog']['id'];?>">
					      <textarea name="message"></textarea>
					  	</div>
					  	<div id="comments_button" class="ds-post-toolbar">
					      <div class="ds-post-options ds-gradient-bg"></div>
					      <button id="comments_button-<?php echo $v['Blog']['id']?>" class="ds-post-button" type="button"><?php echo $ld['comment'] ?></button>
					  	</div>
				      </div>
				  	</div>
				  </div>
				  <?php if(isset($comment_set) && ($comment_set==2) ||($comment_set==1 && !empty($fan_like)) || $fan_like==1){?>
				  <div class="comments_list" style="<?php echo isset($_SESSION['User'])?'':'padding:0px;'; ?>"></div>
				  <?php }else if($fan_like !=1 && $comment_set==0){ echo $ld['privacy_and_confidentiality'];	}else{echo $ld['without_permission'];}?>
			  	</div>
			  </div>
		  	</div>
		  </li>
		<?php }}else{?>
 		  <li style="margin-top:20%;text-align:center;"><?php echo isset($_SESSION['User'])&&$_SESSION['User']['User']['id']==$id?$ld['no_log_your']:$ld['no_log_he']; ?></li>
		<?php }?>
		</ul>
	  <?php }else if($fan_like !=1 && $log_set==0){ echo $ld['privacy_and_confidentiality'];}else{echo $ld['without_permission'];}?>
	  </div>
	  <!--日志end-->
	  <!--操作-->
	  <div class="action" style="clear:both;<?php echo isset($pagetype)&&$pagetype=='action'?'display:block;':'display:none;'; ?>">
	  <?php if(isset($log_set) && ($log_set==2) ||($log_set==1 && !empty($fan_like)) || $fan_like==1){?>
		<ul class="am-comments-list am-comments-list-flip">
		<?php if(isset($action_list) && sizeof($action_list)>0){foreach($action_list as $k=>$v){ ?>
		  <li class="am-comment">
		  	<a target="_blank" title="<?php echo $v['User']['name'];?>" href="<?php echo $html->url('/user_socials/index/'.$v['User']['id']); ?>">
    		  <img src="<?php echo isset($v['User']['img01'])&&$v['User']['img01']!=""?$v['User']['img01']:'/theme/default/img/no_head.png';?>" alt="<?php echo $v['User']['name'];?>" class="am-comment-avatar" width="48" height="48"/>
  		  	</a>
		  	<div class="am-comment-main">
		      <header class="am-comment-hd">
		        <div class="am-comment-meta">
		          <a href="#link-to-user" class="am-comment-author"><?php echo $v['User']['name'];?></a>
		          &nbsp;&nbsp;&nbsp;<?php echo $ld['at']?><time><?php $time=explode(" ",$v['UserAction']['created']);$ymd=explode("-",$time[0]);$his=explode(":",$time[1]);echo $his[0].":".$his[1]." ".$ymd[1].'/'.$ymd[2];?></time>
		      	</div>
		      </header>
		      <div class="am-comment-bd">
			  	<?php echo $v['UserAction']['content']; ?>
			  </div>
		  	</div>
		  </li>
		<?php }}else{?>
 		  <li style="margin-top:20%;text-align:center;"><?php echo isset($_SESSION['User'])&&$_SESSION['User']['User']['id']==$id?$ld['no_collection_your']:$ld['no_collection_he']; ?></li>
		<?php }?>
		</ul>
	  <?php }else if($fan_like !=1 && $log_set==0){ echo $ld['privacy_and_confidentiality'];}else{echo $ld['without_permission'];}?>
	  </div>
	  <!--操作end-->
    </div>
    <?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
    <div class="am-tab-panel <?php if($pagetype=='baobei'){echo 'am-active';}else{echo '';} ?>" id="tab2-2">
	  <div style="width: 100%;padding-top:16px;">
		<span class="pro_classificatory" >
		  <span class="classificatory" style=""><?php echo $ld['classificatory'] ?></span>
			<span class="am-btn-group am-btn-group-xs">
  			  <button type="button" class="am-btn am-btn-default am-round like">&nbsp;<?php echo $ld['like'] ?>&nbsp;</button>
			  <!--<button  type="button" class="am-btn am-btn-default am-round ">已<?php echo $ld['buy'] ?></button>-->
			</span>
		</span>
	  </div>
	  <div style="display:block;" class="productslist like_list">
		<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-3 am-gallery-overlay" data-am-gallery="{ }">
		<?php if(isset($like_set) && ($like_set==2) ||($like_set==1 && !empty($fan_like)) || $fan_like==1){?>		
		  <?php $flagnum=0; if(isset($like_list) &&sizeof($like_list)>0){foreach($like_list as $k=>$v){ ?>
		  <li id="item<?php echo $v['Product']['id'];?>">
			<div class="am-gallery-item">
			  <span class="dislike_icon am-gallery-like" style="">
			    <img id="<?php echo $v['Product']['id'];?>" style="width:15px;height:15px;" src="/theme/default/img/like_icon.png" />
			    <span style="" id="<?php echo 'like_num'.$v['Product']['id'];?>" class="like_num">
				  <?php if(isset($v['Product']['like_num'])){echo $v['Product']['like_num'];}else{echo '0';}?>
				</span>
			  </span>
			  <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>($v['Product']['img_detail']!=''?$v['Product']['img_detail']:$configs['products_default_image']),'name'=>$v['ProductI18n']['name'],'sub_name'=>'Product name','target'=>'_blank'));?>
	      	  <h3 class="am-gallery-title">
	            <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name'],'target'=>'_blank'));?>
			  </h3>
			</div>
		  </li>
		  <?php }?>
		  <?php	$flagnum++;	}else{
			echo "<div class='not_products_collection'>".$ld['not_products_collection']."</div>";
			}?>
		</ul>
		<div class="am-pagination-right" style="clear:both;margin-top:15px;"><?php echo $this->element('pager');?></div>
		<?php }else if($fan_like !=1 && $like_set==0){ echo $ld['privacy_and_confidentiality'];	}else{echo $ld['without_permission'];}?>
	  </div>
			<div style="display:none" class="productslist buy_list">
				<h1></h1>
				<ul style="width:700px;">
				<?php if(isset($like_set) && ($like_set==2) ||($like_set==1 && !empty($fan_like)) || $fan_like==1){?>		
				<?php $flagnum=0; if(isset($buy_list) && sizeof($buy_list)>0){foreach($buy_list as $k=>$v){?>
				<li class="item" id="item<?php echo $v['Product']['id'];?>">
					<div class="item_box">
						<div id="picture<?php echo $v['Product']['id'];?>" class="picture" style=" z-index:1; position:static;"><?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>$v['Product']['img_original'],'name'=>$v['ProductI18n']['name'],'sub_name'=>'Product name','target'=>'_blank'));?></div>
						<span class="dislike_icon" style="margin-left:10px;margin-top:-200px;position: absolute;cursor:pointer;"><img id="<?php echo $v['Product']['id'];?>" style="width:15px;height:15px;" src="/theme/default/img/like_icon.png" /><span style="margin-left:7px;position: absolute;font-size: 11px;top: -2px;" id="<?php echo 'like_num'.$v['Product']['id'];?>" class="like_num"><?php echo $v['Product']['like_stat'];?></span></span>
						<div id="name<?php echo $v['Product']['id'];?>" class="name" style="width:210px;"><div style="float:left;" class="ellipsis"><?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name'],'target'=>'_blank'));?></div>
						<div style="float:right;"><span class="price">￥<?php echo $v['Product']['shop_price'];?><?php echo $ld['app_yuan'] ?></span></div></div>
					</div>
					<div class="suspension" id="suspension<?php echo $v['Product']['id'];?>" style=" display:none; margin-top:-98px;position:relative; z-index:999; z-index:2"><div class="suspension_bj"></div><div style=" position:relative;padding-left:10px;width:50px; z-index:9999; margin-top:-27px; float:left; color:#ffffff"><img class="p_comment" id="<?php echo 'comment'.$v['Product']['id'];?>" src="/theme/default/img/pinglun_lv.png" /><font style="padding-left:2px;vertical-align:bottom; margin-top:-30px">12</font></div><div style="width:140px; float:left">&nbsp;</div><div style="width:60px; position:relative;margin-top:-27px; float:left; text-decoration:underline; color:#ffffff"><img src="/theme/default/img/gouwuche_lv.png"></div><div style="width:40px; float:right; margin-top:-28px;position:relative;color:#ffffff; z-index:9999; text-decoration:underline"><span class="ajax_cart" id="<?php echo $v['Product']['id'];?>" style="padding-left:2px; margin-top:-20px">Add</span></div></div>
				</li>
				<?php	$flagnum++; }}else{
					echo "<div style='clear:both;font-size:14px;text-align:center;'>".$ld['not_products_collection']."</div>";
				} ?>	
				</ul>
				<div class="am-pagination-right" style="clear:both;margin-top:15px;"><?php echo $this->element('pager');?></div>
				<?php }else if($fan_like !=1 && $like_set==0){ echo $ld['privacy_and_confidentiality'];	}else{echo $ld['without_permission'];}?>
			</div>
    </div>
    <?php } ?>
  </div>
</div>

<style type="text/css">

</style>
<script>
	
	function checktoken(type){
		$.ajax({ url: "/synchros/checktoken/"+type,
			dataType:"json",
			type:"POST",
			success: function(data){
				if(data.flag==0){
					window.location.href='/synchros/opauth/'+type.toLowerCase();
				}else if(data.status=='1'){
					$(".tongbu #"+type+"_icon").attr("style","width:16px;height:16px;");
				}else if(data.status=='0'){
					$(".tongbu #"+type+"_icon").attr("style","width:16px;height:16px;filter: Alpha(opacity=10);-moz-opacity:.1;opacity:0.1;");
				}
		    }
		});
	}


	var Url="<?php echo $server_host; ?>/theme/default/img/gif/";//表情图片路径
	//表情数组
	var Expression=new Array("/微笑","/撇嘴","/好色","/发呆","/得意","/流泪","/害羞","/睡觉","/尴尬","/呲牙","/惊讶","/冷汗","/抓狂","/偷笑","/可爱","/傲慢","/犯困","/流汗","/大兵","/咒骂","/折磨/","/衰","/擦汗","/抠鼻","/鼓掌","/坏笑","/左哼哼","/右哼哼","/鄙视","/委屈","/阴险","/亲亲","/可怜","/爱情","/飞吻","/怄火","/回头","/献吻","/左太极");
	
	//多次替换
	String.prototype.replaceAll = function (findText, repText){
	    var newRegExp = new RegExp(findText, 'gm');
	    return this.replace(newRegExp, repText);
	}
	
	//表情文字替换
	function replace_content(con){
		for(var i=0;i<Expression.length;i++){
			con = con.replaceAll(Expression[i], "<img src=" + Url + "F_"+(i+1)+".gif />");
		}
		return con;
	}
	
	var clicks = true;
	$("#biaoqing").click(function(){
		$("#pictureFile").hide();
		$(".expression").css("background","#fff");
		if($(".expression").css("display")=="block"){
			$(".expression").css("display","none");	
		}
		else{
			$(".expression").css("display","block");
			clicks=false;
		}
			
	});
	
	$("#pictureUploadButton").click(function(){
		if($("#pictureFile").css("display")=="block"){
			$("#pictureFile").css("display","none");	
		}
		else{
			$("#pictureFile").css("display","block");	
		}
	});
	var dobj=$("#pictureFile");

	$("#pictureFilebox span").click(function(){
		$("#pictureFile").hide();
	});
	document.body.onclick = function(){
	    if(clicks){
	       	$(".expression").css("display","none");
	    }
	    clicks = true;
	}

	var word=$("#word").val();
	if(word.length>0){
		word=word.substring(0,word.length-1);
	}
	var pagetype="s1";
	//日志发布的提示语句特效
	var blog_default_value=want_to_say_what_today+"...";
	
    if (typeof blog_default_value !== 'undefined'){
	
        if ($('#input_detail').val() == "") {
			$('#input_detail').val(blog_default_value);
            //$('#input_detail').attr("value", blog_default_value);
        }
        $('#input_detail').focus(function () {
			$('#input_detail').css("color", "#000");
            if($('#input_detail').val() == blog_default_value) {
                $('#input_detail').val("");
            }
        });

        $('#input_detail').blur(function () {
			$('#input_detail').css("color", "#555");
            if($('#input_detail').val() == "") {
                $('#input_detail').val(blog_default_value);
            }
        });
        
        $(".picks").click(function(){
			var titles=$(this).children().attr("title");
			var ids=$(this).attr("id");
			
			if($("#input_detail").val()==blog_default_value){
				$("#input_detail").val(titles);
				strLenCalc($("#input_detail"),'checklen',280);
			}
			else{
				$("#input_detail").val($("#input_detail").val()+titles);
			}
		});
    }
	//日记（日志）发布
	var foo=function(){
		var con=CheckKeyword(word,$("#input_detail").val());//屏蔽非法文字
		con=replace_content(con);
		if(con==blog_default_value){
			con="";
		}
		
		$("#hid").val(con);
		$("#ReleaseForm").submit();	
	}
$(document).ready(function(){
	$(".WB_text a").attr('target','_blank');
	$('#cddtab').tabs();
	
	function showsorting(id){
		$(".all").css("display","none");
		$(".diary").css("display","none");
		$(".action").css("display","none");
		switch(id){
			case "s1":
				$(".all").css("display","block");
				break;
			case "s2":
				$(".diary").css("display","block");
				break;
			case "s3":
				$(".action").css("display","block");
				break;
			default:
				$(".all").css("display","block");
				break;
		}
	}
	$(".like_list").on("click",'.dislike_icon img',function(){
        var like_user_id="<?php echo $id; ?>";
    	if(js_login_user_data==null){
    		//未登录
    		$("#popup_login").click();
    	}else if(like_user_id!=js_login_user_data['User']['id']){
    		alert("你无权删除别人赞的宝贝！");
    	}else{ 
    		var type=$(this).attr("id");
    		var userid=js_login_user_data['User']['id'];
    		if (confirm("真的要删除赞的宝贝吗?")){
    			//已登录
    			$.ajax({ url: "/user_socials/ajax_dislike",
    					type:"POST",
    					dataType:"html", 
    					context: $(".like_icons .like_num"), 
    					data: { 'type_id': type, 'user_id':userid,'loacle':$("#local").val()},
    					success: function(data){
    						$(".productslist.like_list").html(data);
      					}
      			});
			}else{
				return false;
			}
        }
   	});

    //评论隐藏显示特效
    $(".WB_handle a").click(function(){
    	var id=$(this).attr('id').split("-");
    	var comment_box=$(this).parent().parent().find(".comment_box_"+id[1]);
    	if(comment_box.css("display") == "none"){
    		comment_box.css("display","block");
            comment_box.find("#ds-thread").css("display","block");
    		$.ajax({ url: "<?php echo $html->url('/user_socials/article_comment/')?>"+id[1],
	    		dataType:"json",
	    		type:"GET",
	    		data: { 'parent_id': id[1]},
	    		context: $(".comment_box_"+id[1]+" .comments_list"),
	    		success: function(data){
		    		$(".comment_box_"+id[1]+" .comments_list").html(data.comment);
		    		$("#test_common-"+id[1]+" span").html(data.comment_num);
	  			}
	  		});
    	}else{
    		comment_box.css("display","none");
    	}
    });
    
    //评论ajax刷新(先插入数据)(点击之后做ajax传值刷新评论)
    $("#comments_button button").click(function(){
    	if(js_login_user_data!=null){
    	    var id=$(this).attr('id').split("-");
            var comment_box=$(this).parent().parent().parent().parent().parent();
        	//获取评论内容
        	var content=$(this).parent().parent().find("textarea");
        	if(content.val() != ""){
    	    	//关键字屏蔽
    	    	var contents=content.val();
    	    	contents=CheckKeyword(word,contents);
    	    	var userid=js_login_user_data['User']['id'];
    	    	$.ajax({ url: "<?php echo $html->url('/user_socials/article_comment/')?>"+id[1],
    	    		dataType:"json",
    	    		type:"POST",
    	    		data: { 'parent_id': id[1], 'content': contents,'user_id':userid },
    	    		context: $(".comment_box_"+id[1]+" .comments_list"),
    	    		success: function(data){
    		    		$(".comment_box_"+id[1]+" .comments_list").html(data.comment);
    		    		$("#test_common-"+id[1]+" span").html(data.comment_num);
    	  			}
    	  		});
    	  		content.val("");
                comment_box.find("#ds-thread").css("display","none");
      		}else{
      			alert("写点东西吧，评论内容不能为空哦。");
      		}
  	    }else{
  			$("#popup_login").click();
        }
    });
    //删除评论
    $(".WB_detail").mouseover(function(){
    	//显示隐藏的x
    	$(this).find(".WB_info span").css("display","block");	
    });
    $(".WB_detail").mouseleave(function(){
    	//隐藏显示的x
    	$(this).find(".WB_info span").css("display","none");	
    });
    	
    $(".WB_detail .WB_info span").mouseover(function(){
    	$(this).css("background","#ff9c0f").css("color","#fff");
    });
    $(".WB_detail .WB_info span").mouseleave(function(){
    	$(this).css("background","").css("color","#333333");
    });
    $(".WB_detail .WB_info span").click(function(){
        var page_user="<?php echo $id; ?>";
	    if(js_login_user_data==null){
			//未登录
			$(".denglu").click();
		}else if(page_user!=js_login_user_data['User']['id']){
			alert("<?php echo $ld['without_permission']; ?>")
		}else{ 
        	if (confirm(confirm_delete)){
    	    	var id=$(this).attr("id");
    	    	window.location.href="<?php echo $html->url('/user_socials/del_comment/'); ?>"+id;
        	}
	    }
    });	
    
    //回复评论框的显示和隐藏(ajax生成的用on绑定父级不是ajax生成的对象，第二参数传绑定的按钮)
	$(".am-comment-bd").on("click",'.commentReplyLink',function(){
		var id=$(this).attr("id");
		id=id.replace("reply","answer");
		var comment_reply=$(this).parent().parent();
		if(comment_reply.find("#"+id).css("display")=="none"){
			comment_reply.find("#"+id).css("display","block");
		}else{
			comment_reply.find("#"+id).css("display","none");
		}
	});
	
	//回复评论按钮事件
	$(".am-comment-bd").on("click",'.huifupinglun button',function(){
		if(js_login_user_data!=null){
    		var id=$(this).attr('id').split("-");
    		var comment_reply_div=$(this).parents("div.comment_reply");
    		//获取评论内容
        	var content=comment_reply_div.find("#answer_"+id[1]+"-"+id[2]);
        	var hide=comment_reply_div.find("#hide_"+id[1]+"-"+id[2]);
       		var content_val=$.trim(content.val());
        	if(content_val != hide.val()){
    	    	var userid=js_login_user_data['User']['id'];
    	    	$.ajax({ url: "<?php echo $html->url('/user_socials/article_comment/')?>"+id[1],
    	    		dataType:"json",
    	    		type:"POST",
    	    		data: { 'parent_id': id[1], 'content': content.val(),'user_id':userid },
    	    		context: comment_reply_div.find(".comment_box_"+id[1]+" .comments_list"),
    	    		success: function(data){
    	    			$(".comment_box_"+id[1]+" .comments_list").html(data.comment);
    	    			$("#test_common-"+id[1]+" span").html(data.comment_num);
    	  			}
    	  		});
    	  		content.val("");
      		}else{
      			alert(j_empty_content);
      		}
  		}else{
  			$("#popup_login").click();
  		}
	});
    
});
function shopcart_hidden(){
	$("#shopcart_response").hide("slide", { direction: "up" }, 200);
}		
function strLenCalc(obj, checklen, maxlen) {
	var v = obj.val(), charlen = 0, maxlen = !maxlen ? 200 : maxlen, curlen = maxlen, len = v.length;
	for(var i = 0; i < v.length; i++) {
		if(v.charCodeAt(i) < 0 || v.charCodeAt(i) > 255) {
			curlen -= 1;
		}
	}
	if(curlen >= len) {
		$("#"+checklen).html(Math.floor((curlen-len)/2)).css('color', '#000000');
		if(img_size){
			$("#res_btn").bind("click",foo);
		}else{
			$("#res_btn").unbind("click",foo);
		}
	} else {
		$("#"+checklen).html(Math.ceil((len-curlen)/2)).css('color', '#FF0000');
		$("#res_btn").unbind("click",foo);
	}
}
$("#res_btn").click(function(){
	if(img_size){
		return true;
	}else{
		return false;
	}
});

</script>
<script>
	var pag_size=10;//每页显示数量
	
	var all_page=1;//所有类当前页
	var all_count=$(".all .am-comments-list .am-comment").length;//所有类数据总数
	var all_pagnum=Math.ceil(all_count/pag_size);
	var all_list=$(".all .am-comments-list .am-comment");
	setpagediv(all_list,all_page,pag_size,all_pagnum,all_count,".all .am-comments-list .am-comment");
	
	var diary_page=1;
	var diary_count=$(".diary .am-comments-list .am-comment").length;//日志类数据总数
	var diary_pagnum=Math.ceil(diary_count/pag_size);
	var diary_list=$(".diary .am-comments-list .am-comment");
	setpagediv(diary_list,diary_page,pag_size,diary_pagnum,diary_count,".diary .am-comments-list .am-comment");
	
	var action_page=1;
	var action_count=$(".action .am-comments-list .am-comment").length;//动作类数据总数
	var action_pagnum=Math.ceil(action_count/pag_size);
	var action_list=$(".action .am-comments-list .am-comment");
	setpagediv(action_list,action_page,pag_size,action_pagnum,action_count,".action .am-comments-list .am-comment");
	

function setdivpage(page,page_class){
	var obj_count=$(page_class).length;
	var obj_pagnum=Math.ceil(obj_count/pag_size);
	var obj_list=$(page_class);
	setpagediv(obj_list,page,pag_size,obj_pagnum,obj_count,page_class);
}

function setpagediv(obj,page,pagesize,pagenum,objcount,page_class){
	if(objcount>0){
		for(i=0;i<objcount;i++){
			obj[i].style.display="none";
		}
		var start=(page-1)*pagesize;
		var end=(start+pag_size-1)>(objcount-1)?(objcount-1):(start+pag_size-1);
		for(var i=start;i<=end;i++){
			obj[i].style.display="block";
		}
		createpageslist(obj,page,pagenum,page_class);
	}
}

function createpageslist(obj,current,totalPage,page_class) {
	var div=obj.parent();
	var outstr="";
	if(current==1 || (current==1 && totalPage==1)){
	  outstr+="<span><</span>";
	}else if(current >1 ){
	  outstr += "<a href='javascript:void(0);' onclick=\"setdivpage("+(current-1)+",'"+page_class+"')\"><</a>";
	}
	for (var i = 1; i <= totalPage; i++) {
	    if (i == 2 && current - 6 > 1) {
	        outstr += "...";
	        i = current - 6;
	    } else if (i == current + 6 && current + 6 < totalPage) {
	        outstr += "...";
	        i = totalPage - 1;
	    } else {
	        if (current == i) {
	            outstr += "<em>" + current + "</em>";
	        } else {
	            outstr += "<a href='javascript:void(0);' onclick=\"setdivpage("+i+",'"+page_class+"')\">"+i+"</a>";
	        }
	    }
	}
	if(current==totalPage ){
	  outstr+="<span>></span>";
	}else if(current < totalPage ){
	  outstr += "<a href='javascript:void(0);' onclick=\"setdivpage("+(current+1)+",'"+page_class+"')\">></a>";
	}
	if(div.find(".pages").length>0){
		div.find(".pages").html(outstr);
	}else{
		outstr="<div class='pages' style='padding:30px 0 35px 0;text-align:right;'>"+outstr;
		outstr=outstr+"</div>";
		div.html(div.html()+outstr);
	}
}
	$(".sorting").click(function(){
		var id=$(this).attr("id");
		$(".sorting ").removeClass("am-active");
		pagetype=id;
		showsorting(id);
	});
	
	function showsorting(id){
		$(".all").css("display","none");
		$(".diary").css("display","none");
		$(".action").css("display","none");
		switch(id){
			case "s1":
				$(".all").css("display","block");
				break;
			case "s2":
				$(".diary").css("display","block");
				break;
			case "s3":
				$(".action").css("display","block");
				break;
			default:
				$(".all").css("display","block");
				break;
		}
	}
</script>