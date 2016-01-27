<div class="crumbs">
    <span><?php echo $ld['current_location'];?></span>
	<?php
	if(isset($navigations) && sizeof($navigations)>0){
		foreach($navigations as $k=>$v){
			$nav = $k==count($navigations)-1?"":"&nbsp;"."<em>&gt;</em>";
			if(!isset($v['url']) || $v['url']==''){
				echo"&nbsp;". "<span>" .$v['name']."</span>".$nav."&nbsp;";
			}else{
			 	echo $html->link($v['name'],$v['url']).$nav;
			}
		}
	}else{
		echo $ld['home'];
	}?>
</div>