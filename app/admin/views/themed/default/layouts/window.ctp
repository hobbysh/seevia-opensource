<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1;IE=7" />
<?php echo $html->charset(); ?>
<title><?php echo $title_for_layout; ?></title>
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
<div class="am-container">
<?php
	echo "<article id='content' class='content ".$this->params['controller']." ".$this->params['action']."'>";
	echo $content_for_layout;
	echo "</article>";
?>
</div>
</body>
</html>