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
<!--[if lte IE 9]>
<p class="browsehappy">为了获得更好的体验,建议您升级浏览器!</p>
<![endif]-->
<?php echo $this->element('header');?>
<!-- content -->
  <div class="am-container">
	<div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">404</strong> / <small>Not Found</small></div>
    </div>
  <!--错误提示页-->
	<div class="am-g">
	  <div class="am-u-sm-12">
		<h2 class="am-text-center am-text-xxxl am-margin-top-lg">404. Not Found</h2>
		<p class="am-text-center"><?php echo $ld['no_page'] ?></p>
		<pre class="page-404 am-text-center">
          .----.
       _.'__    `.
   .--($)($$)---/#\
 .' @          /###\
 :         ,   #####
  `-..__.-' _.-\###/
        `;_:    `"'
      .'"""""`.
     /,  ya ,\\
    //  404!  \\
    `-._______.-'
    ___`. | .'___
   (______|______)
        </pre>
		<div class="am-text-center"><span><?php echo $html->link($ld['back_page'],"javascript:history.go(-1);",'',false,false);?> | <?php echo $html->link($ld['home'], '/')?> | <?php echo $html->link($ld['back_to_login_page'], '/users/login')?></span></div>
	  </div>
	</div>
  <!--错误提示页-->
  </div>
<?php //echo $content_for_layout;?>
<!-- content end -->
<?php echo $this->element('footer');?>
</body>
</html>

