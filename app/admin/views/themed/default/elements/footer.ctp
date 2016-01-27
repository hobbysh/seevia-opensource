<!-- footer -->
<footer class="footer">
  <p>
	<?php if(isset($configs['copyright-display']) && $configs['copyright-display']!=""){ 
		echo "Copyright &nbsp;&nbsp;".date("Y")."&nbsp;&nbsp;".$configs['copyright-display']."&nbsp;&nbsp;版权所有";
	}else{
		printf($ld['all_rights_reserved'],date("Y"));
	}?>&nbsp;&nbsp;&nbsp;&nbsp;
	<?php echo Product."&nbsp;&nbsp;".Version."&nbsp;&nbsp;"; ?> <?php
		if(isset($configs['memory_useage'])&&$configs['memory_useage']=="1"){
			echo $ld['memory_used'].$memory_useage."MB&emsp;";
		}
		echo round(getMicrotime() - $GLOBALS['TIME_START'], 4) . "s&emsp;";
		if(isset($GLOBALS['SQL_TIME']) && isset($GLOBALS['SQL_COUNT'])){
			echo "(default) ".$GLOBALS['SQL_COUNT']." queries took ".$GLOBALS['SQL_TIME']. "ms&emsp;Gzip";
		}
		echo "Gzip ";
		echo (isset($gzip_is_start) && $gzip_is_start == 1)?$ld['used']:$ld['unused'];
	?>
  </p>
</footer>
<div data-am-widget="gotop" class="am-gotop am-gotop-fixed" >
  <a href="#top" title=""><i class="am-gotop-icon am-icon-chevron-up"></i></a>
</div>