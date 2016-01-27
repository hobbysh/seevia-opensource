<?php if($paging['pageCount']>0){?>
<div class="pages am-pagination-right" style="margin-top:100px;">
   <a href="/jobs/index/1/<?php echo $paging['importParams']['route']['limit'].'/'.$paging['importParams']['route']['department_id'].'/'.$paging['importParams']['route']['type_id'].'/'.$paging['importParams']['route']['order'].'/'.$paging['importParams']['route']['address'].'/'.$paging['importParams']['route']['name']?>">首页</a>
	<?php
	if($pagination->setPaging($paging)):
		$leftArrow = "‹ ".$ld['previous'];
		$rightArrow = $ld['next']." ›";
		$prev = $pagination->prevPage($leftArrow,false);
		$prev = $prev?$prev:$leftArrow;
		$next = $pagination->nextPage($rightArrow,true);
		$next = $next?$next:$rightArrow;
		$pages = $pagination->pageNumbers("	 ");
		//echo $pagination->result()."<br>";
		echo $prev." ".$pages." "."".$next."";
		//echo $pagination->resultsPerPage(NULL, ' ');
	endif;
	?>
   <a href="<?php echo $html->url('/jobs/index/'.$paging['pageCount'].'/'.$paging['importParams']['route']['limit'].'/'.$paging['importParams']['route']['department_id'].'/'.$paging['importParams']['route']['type_id'].'/'.$paging['importParams']['route']['order'].'/'.$paging['importParams']['route']['address'].'/'.$paging['importParams']['route']['name']);?>">尾页</a>
   <span><?php echo $paging['Defaults']['page'];?></span>/<span style="margin-right:20px;"><?php echo $paging['pageCount'];?></span>
</div>
<?php }?>
