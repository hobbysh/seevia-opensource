<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title_for_layout; ?></title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/png" href="/favicon.png">
  <meta name="apple-mobile-web-app-title" content="<?php echo $title_for_layout; ?>" />
<script type="text/javascript">
	var admin_webroot = "<?php echo $admin_webroot;?>";
	var webroot = "<?php echo $webroot;?>";
	var backend_locale = "<?php echo $backend_locale;?>";
	var ip = "<?php echo $_SERVER['REMOTE_ADDR'] ?>";
</script>
<?php
	if($backend_locale=='eng'){
		$language = 'en';
	}elseif($backend_locale=='chi'){
		$language = 'zh-CN';
	}elseif($backend_locale=='jpn'){
		$language = 'jp';
	};
?>
<script type="text/javascript" src="/admin/js/selectlang/<?php echo $backend_locale;?>"></script>
<link href="/plugins/AmazeUI/css/amazeui.min.css" type="text/css" rel="stylesheet">
<link href="/plugins/AmazeUI/css/app.css" type="text/css" rel="stylesheet">
<link href="/plugins/AmazeUI/css/admin.css" type="text/css" rel="stylesheet">
<script src="/plugins/AmazeUI/js/jquery.min.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/amazeui.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/utils.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/listtable.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/common.js" type="text/javascript"></script>
<?php
	if(Configure::read('debug')==0&&$configs['is_cache']){
		
	}else{
		echo $html->css('/skins/default/css/seevia.amazeui');
		echo $javascript->link('/skins/default/js/seevia.amazeui');
	}
?>

</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">为了获得更好的体验,建议您升级浏览器!</p>
<![endif]-->
<!-- content -->

<div class="am-container">
	<?php echo $content_for_layout;?>
</div>
    
<!-- content end -->

<?php echo $this->element('footer');?>
</body>
</html>