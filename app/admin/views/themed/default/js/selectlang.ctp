<?php 
if(isset($ld_js)){ 
	foreach($ld_js as $k=>$v){
		echo "var ".$v['Dictionary']['name']."='".$v['Dictionary']['value']."';";
	}
}
if(isset($configs['price_format'])&&$configs['price_format']!=""){
		echo "var js_config_price_format='".$configs['price_format']."';";
}
?>