<?php 
/*****************************************************************************
 * SV-Cart 分页
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
?>
<?php if($paging['page']>0){?>
<div class="pages am-pagination-right">
	<label><?php echo $ld['page_record']?></label>
	<input class="am-text-center" type="text" size="2" value="<?php echo trim($paging['show'])?>" onkeypress="pagers_onkeypress(this,event)" onblur="pagers_onblur(this,event)" />
	<?php
        $pagination->setPaging($paging);
        if ($paging['page'] > 1){
            echo htmlspecialchars_decode($pagination->firstPage('|<',NULL,$ld['first_page']));
        }else{
            echo "<span>|<</span>";
        }
        if($paging['page'] > 1){
            echo htmlspecialchars_decode($pagination->prevPage('<',NULL,$ld['prev_page']));
        }else{
            echo '<span><</span>';
        }
        if($pagination->setPaging($paging)){
	        $pages = $pagination->pageNumbers(null,true,"<span>...</span>","<span>...</span>");
            echo "$pages";
	    }
        if($paging['page'] < $paging['pageCount']){
            echo htmlspecialchars_decode($pagination->nextPage('>',NULL,$ld['next_page']));
        }else{
            echo '<span>></span>';
        }
        if ($paging['page'] < $paging['pageCount']){
            echo htmlspecialchars_decode($pagination->lastPage('>|',NULL,$ld['last_page']));
        }else{
            echo "<span>>|</span>"; 
        }?>
	<label><?php printf($ld['total_current'],$paging['total'],$paging['show']*$paging['page']+1-$paging['show'],$paging['show']*$paging['page']); ?></label>
</div>
<?php } ?>