<?php 
	if($paging['pageCount']>0){?>
<div class="pages am-pagination-right">
	<?php
	if($pagination->setPaging($paging)):
		$leftArrow = "‹ ".$ld['previous'];
		$rightArrow = $ld['next']." ›";
		$prev = $pagination->prevPage($leftArrow,false);
		$prev = $prev?$prev:$leftArrow;
		$next = $pagination->nextPage($rightArrow,false);
		$next = $next?$next:$rightArrow;
		$pages = $pagination->pageNumbers("	 ");
		//echo $pagination->result()."<br>";
		echo $prev." ".$pages." ".$next;
		//echo $pagination->resultsPerPage(NULL, ' ');
	endif;
	?>
</div>
<?php }?>