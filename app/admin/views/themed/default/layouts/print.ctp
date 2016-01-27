<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="chrome=1;IE=7" />
<?php echo $html->charset(); ?>
<title><?php echo $title_for_layout; ?></title>
<script type="text/javascript" src="/admin/js/selectlang/<?php echo $backend_locale;?>"></script>
<link href="/plugins/AmazeUI/css/amazeui.min.css" type="text/css" rel="stylesheet">
<link href="/plugins/AmazeUI/css/app.css" type="text/css" rel="stylesheet">
<link href="/plugins/AmazeUI/css/admin.css" type="text/css" rel="stylesheet">
<script src="/plugins/AmazeUI/js/jquery.min.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/amazeui.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/utils.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/listtable.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/common.js" type="text/javascript"></script>
<style>
#SearchForm{display:none;}
#tablelist{display:block;}
.printcontent h1{text-align:center;margin:15px;}
.operate{display:none;}
.pages{display:none;}
</style>
</head>
<body class="printcontent">
<div class="am-container" style="max-width:1200px;">
    <h1><?php echo ($title_for_layout)?$title_for_layout:null; ?></h1>
    <?php echo isset($print_for_layout) ? $print_for_layout : $content_for_layout; ?>
    <div class="preprintn am-fr">
    	<input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['print']?>" onclick="window.print();"/>
    </div>
    <div style="clear:both;"></div>
</div>
<script type="text/javascript">
	//inita();
    //window.onload = function() { window.print(); }
</script>
</body>
</html>