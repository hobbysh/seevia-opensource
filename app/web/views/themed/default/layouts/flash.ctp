<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php
	if(isset($title_for_layout)){
   		echo $title_for_layout;
   	}else{
    	echo $configs['shop_title'];
   	}?></title>
  <meta name="description" content="<?php if(isset($meta_description)){echo $meta_description;} ?>" />
  <meta name="keywords" content="<?php if(isset($meta_keywords)){echo $meta_keywords;} ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <?php if(isset($configs['shop_icon'])){?>
  <link rel="icon" type="image/png" href="<?php echo isset($configs['shop_icon'])?$configs['shop_icon']:'';?>">
  <?php }?>
  <link rel="apple-touch-icon-precomposed" href="/theme/default/img/seevia.png">
  <meta name="apple-mobile-web-app-title" content="<?php
	if(isset($title_for_layout)){
   		echo $title_for_layout;
   	}else{
    	echo $configs['shop_title'];
   	}?>" />
<?php
	echo isset($configs['head_content'])?$configs['head_content']:'';
?>
<script type="text/javascript" src="/js/selectlang/<?php echo LOCALE;?>"></script>
<link href="/plugins/AmazeUI/css/amazeui.min.css" type="text/css" rel="stylesheet">
<link href="/plugins/AmazeUI/css/app.css" type="text/css" rel="stylesheet">
<link href="/plugins/AmazeUI/css/admin.css" type="text/css" rel="stylesheet">
<script src="/plugins/AmazeUI/js/jquery.min.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/amazeui.min.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/utils.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/amazeui.lazyload.min.js" type="text/javascript"></script>
<?php
	//加载js
	if(Configure::read('debug')==0&&$configs['is_cache']){

	}else{
		echo $htmlSeevia->css(array('seevia.amazeui'));
	}
	echo $htmlSeevia->js(array("seevia.amazeui"));
?>
</head>
<body>
<div id="wrapper">
  <?php echo $this->element('header');?>
  <!-- content -->
  <div class="am-g am-container">
	<div class="am-cf am-u-flash">
	  <h3><?php echo $ld['information_tips']; ?></h3>
	  <hr />
	</div>
	<div id="sidebarbox">
	<!--信息提示页-->
	<?php if(!empty($_SESSION['cart_back_url'])){ $href=(empty($_SESSION['cart_back_url'])?$this->params['controller']:$_SESSION['cart_back_url']);}
	else{$href=$this->params['controller'];}?>
	  <div class="error" style="height:200px;">
		<ul>
		  <li>&nbsp;&nbsp;<a href="<?php echo $url; ?>" class="ojb"><?php echo $message; ?></a></li>
		</ul>
	  </div>
	<!---->
	</div>
  </div>
<script type="text/javascript">
function countDown(secs,surl){
	if(--secs>0){
		setTimeout("countDown("+secs+",'"+surl+"')",1000);
	}
	else{
		location.href=surl;
	}
}
$(window).load(function () { 
	countDown(5,'<?php echo isset($url)?$url:"/"; ?>');
	var min_h=$(window).height()*0.6;
	$("#sidebarbox").css("min-height",min_h+"px");
	$("#sidebarbox .error").css("min-height",(min_h*0.7)+"px").css("margin","10px auto");
	$(".ojb").parent().css("margin-top",(min_h/4-10)+'px');
});
</script>
	<!-- content end -->
	<?php echo $this->element('footer');?></div>
</body>
</html>