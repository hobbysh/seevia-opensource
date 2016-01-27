<?php
	ob_start();
	$showflag=false;
?>
	<ul class="am-dropdown-content">
	<?php if(isset($search_data['P'])&&sizeof($search_data['P'])>0){$showflag=true; ?>
		<li class="am-dropdown-header"><?php echo $ld['product'] ?></li>
		<?php foreach($search_data['P'] as $v){ ?>
		<li><?php echo $html->link($v['name'],"/products/".$v['id']) ?></li>
		<?php } ?>
	<?php } ?>
	<?php if(isset($search_data['A'])&&sizeof($search_data['A'])>0){$showflag=true; ?>
		<li class="am-dropdown-header"><?php echo $ld['article'] ?></li>
		<?php foreach($search_data['A'] as $v){ ?>
		<li><?php echo $html->link($v['name'],"/articles/".$v['id']) ?></li>
		<?php } ?>
	<?php } ?>
	</ul>
<?php 
	$out1 = ob_get_contents();ob_end_clean();  
	$result=array("data"=>$out1,"showflag"=>$showflag);
	die(json_encode($result));
?>