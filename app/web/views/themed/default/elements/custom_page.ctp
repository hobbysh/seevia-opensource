<?php
 $module_element_type = "";
	if(isset($position_moduel_infos['top'])&&$position_moduel_infos['top']!=""){
		if(isset($PageModules)){	
			foreach($PageModules as $pk=>$subp){
				foreach($subp as $sk=>$sm){
					if(empty($sm)&&$code_infos[$sk]['function']!=""){
						continue;
					}
					$module_element_type = $code_infos[$sk]['type'];
					if(isset($code_infos[$sk]['file_name'])&&$code_infos[$sk]['file_name']!=""){
						echo "<!--  ".$code_infos[$sk]['file_name'].'/'.$module_element_type . " start -->\r\n";
						echo $this->element($code_infos[$sk]['file_name'].'/'.$module_element_type,array('sk'=>$sk,'sm'=>$sm,'module_element_type'=>$module_element_type));
						echo "<!--  ".$code_infos[$sk]['file_name'].'/'.$module_element_type . " end -->\r\n";
					}else{
						echo "<!--  ".$module_element_type . " start -->\r\n";
						echo $this->element($module_element_type,array('sk'=>$sk,'sm'=>$sm,'module_element_type'=>$module_element_type));
						echo "<!--  ".$module_element_type . " end -->\r\n";
					}
				}
			}
}}?>
<?php
	$module_element_type = "";
if(isset($position_moduel_infos['right'])&&$position_moduel_infos['right']!=""){
	foreach($position_moduel_infos['right'] as $rk=>$rm){?>
	<div id="<?php echo $rk?>" class="am-g am-g-fixed">
			<!--Çø·Ö-->
			<?php echo ($code_infos[$rk]['title']) ? '<h1><span>'.$code_infos[$rk]['title'].'</span></h1>' : '';?>
			<?php
				if(isset($subPageModules[$rk])){
					foreach($subPageModules[$rk] as $pk=>$subp){
						foreach($subp as $sk=>$sm){
							if(empty($sm)&&$code_infos[$sk]['function']!=""){
								continue;
							}
							$module_element_type = $code_infos[$sk]['type'];
							if(isset($code_infos[$sk]['file_name'])&&$code_infos[$sk]['file_name']!=""){
								echo $this->element($code_infos[$sk]['file_name'].'/'.$module_element_type,array('sk'=>$sk,'sm'=>$sm,'module_element_type'=>$module_element_type));
							}else{
								echo $this->element($module_element_type,array('sk'=>$sk,'sm'=>$sm,'module_element_type'=>$module_element_type));
							}
						}
					}
				}
			?>
			
	</div>
<?php }}?>