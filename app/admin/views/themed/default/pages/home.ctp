<link href="/plugins/portal/css/main.css" rel="stylesheet" type="text/css" />
<link href="/plugins/portal/css/jquery-fallr-1.3.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/plugins/portal/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/plugins/portal/js/ui/ui.core.min.js"></script>
<script type="text/javascript" src="/plugins/portal/js/ui/ui.sortable.min.js"></script>
<script type="text/javascript" src="/plugins/portal/js/jquery-fallr-1.3.pack.js"></script>
<script type="text/javascript" src="/plugins/portal/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="/plugins/portal/js/Jh.js"></script>
<div class="am-container page_home">
    <div class="am-g am-text-right"><a href="<?php echo $html->url('/portals/index') ?>" class="am-btn am-btn-default"><?php echo $ld['portal_management'] ?></a></div>
    <div class="am-cf"></div>
    <div id="portal_pancel"></div>
</div>
<style type="text/css">
/*横屏*/
@media screen 
and (min-device-width : 0px)
and (max-device-width : 765px) 
and (orientation : landscape){
	.page_home{min-height:200px;}
}
/*竖屏*/
@media screen 
and (min-device-width : 0px)
and (max-device-width : 765px) 
and (orientation : portrait){
	.page_home{min-height:280px;}
}
</style>
<script type="text/javascript">
$(function(){
    <?php if(!empty($Portal_list)){ ?>
        var feeds=<?php echo json_encode($Portal_list);?>;
        Jh.fn.init(feeds);
        Jh.Portal.init(feeds);
    <?php } ?>
})
</script>