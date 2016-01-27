<?php
if(isset($ld_js)){ foreach($ld_js as $k=>$v){
	echo "var ".$v['LanguageDictionary']['name']."='".$v['LanguageDictionary']['value']."';";
}
if(isset($configs['price_format'])&&$configs['price_format']!=""){
	echo "var js_config_price_format='".$configs['price_format']."';";
}
if(isset($configs['detail_page_img_auto_scaling'])){
	echo "var j_config_detail_page_img_auto_scaling='".$configs['detail_page_img_auto_scaling']."';";
}
$nextWeek = time() + (7 * 24 * 60 * 60);
$day_tmp=date('Y-m-d', $nextWeek);
$today=date('Y-m-d');
$year = substr($day_tmp, 0,4); 
$month = substr($day_tmp, 5, 2); 
$day = substr($day_tmp, 8, 2); 
$year1 = substr($today, 0,4); 
$month1 = substr($today, 5, 2); 
$day1 = substr($today, 8, 2); 
header("Last-Modified: ".gmdate("M d Y H:i:s", mktime (0,0,0,$day1,$month1,$year1)));
header("Expires: ".gmdate("M d Y H:i:s", mktime (0,0,0,$day,$month,$year)));
header("Cache-Control: max-age=3");
}?>