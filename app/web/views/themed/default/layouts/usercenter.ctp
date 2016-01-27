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
<div id="wrapper">
    <?php
        if(isset($open_config)){
            echo isset($open_config['HEADER-AREA-INFORMATION'])?$open_config['HEADER-AREA-INFORMATION']['value']:'';
        }
        echo $this->element('header');
    ?>
	<div class="am-cf am-g am-g-fixed">
        <?php echo $this->element("ur_here");?>
		<?php echo $this->element('users_menu');?>
		<?php echo $this->element('users_offcanvas');?>
		
		<div class="am-u-lg-9 am-u-md-8 am-u-sm-12" style="background:#fff;padding:0;">
		  <button style="margin:1rem;" class="am-btn am-btn-sm am-btn-secondary am-show-sm-only" data-am-offcanvas="{target: '#doc-oc-demo2', effect: 'push'}"><span ><?php echo $ld['user_center'];?></span> </button>
		  <?php echo $content_for_layout;?>
		</div>
	</div>
	<!-- content end -->
<?php
    echo $this->element('footer');
    echo $this->element('wechat_action');
    echo $this->element('popup_login_register');
    echo $this->element('alert_message');
    if(isset($open_config)){
        echo isset($open_config['BOTTOM-AREA-INFORMATION'])?$open_config['BOTTOM-AREA-INFORMATION']['value']:'';
    }
?>
</div>
</body>
</html>