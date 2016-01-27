<?php
	$next_page="";
	$paging=isset($sm['paging'])?$sm['paging']:$array();
	if(!empty($paging)&&$pagination->setPaging($paging)){
		$rightArrow = $ld['next']." ›";
		$next_page = $pagination->nextPage($rightArrow,false);
	}
?>
<div class="am-u-md-8" id="product_wrapper">
  <pre id="next_page" class="am-hide"><?php  echo $next_page; ?></pre>
  <h2><?php echo $code_infos[$sk]['name'];?></h2>
  <?php if($code_infos[$sk]['type']=="module_topic"){ ?>

  <ul class="am-list blog-list am-avg-sm-1 am-topic-list" id="product_events">
	<?php foreach($sm['topic'] as $k=>$t){?>
	<li>
	<?php if(!empty($t['TopicI18n']['img01'])){?>
	  <div class="am-u-lg-4 am-u-md-4 am-u-sm-3"><a title="<?php echo $t['TopicI18n']['title'];?>" href="<?php echo $html->url('/topics/'.$t['Topic']['id']);?>"><img class="am-img-responsive" alt="" src="<?php echo $t['TopicI18n']['img01'];?>"></a></div>
	<?php }?>
	  <div class="am-u-lg-8 am-u-md-6 am-u-sm-9"><?php echo $svshow->link($t['TopicI18n']['title'],'/topics/'.$t['Topic']['id'],array('title'=>$t['TopicI18n']['title']));?></div>
	  
	  <div class="am-u-lg-8 am-u-md-6 am-u-sm-9">
		<time><?php echo $ld['time'];?>:<?php echo date("Y/m/d",strtotime($t["Topic"]["start_time"])); ?></time>
	  </div>
	</li>
	<?php }?>
  </ul>
  <?php }?>
 <!-- 分页 -->
<?php  if($sm['paging']['pageCount']>=1){?>
  <div class="pages am-pagination-right am-hide-sm-only">
  <?php
  if($pagination->setPaging($sm['paging'])):
    $leftArrow = "‹ ".$ld['previous'];
    $rightArrow = $ld['next']." ›";
    $prev = $pagination->prevPage($leftArrow,false);
    $prev = $prev?$prev:$leftArrow;
    $next = $pagination->nextPage($rightArrow,false);
    $next = $next?$next:$rightArrow;
    $pages = $pagination->pageNumbers("  ");
    //echo $pagination->result()."<br>";
    echo $prev." ".$pages." ".$next;
    //echo $pagination->resultsPerPage(NULL, ' ');
  endif;
  ?>
  </div>
  <div class="pull-action loading am-show-sm-only"><span class="am-icon-spinner am-icon-spin am-icon-lg"></span></div>
  <?php }?>


</div>



<style type='text/css'>
.pull-action{display:none;text-align: center;}
.pull-action.loading{display:block;height: 45px;line-height: 45px;color: #999;}
.pull-action.error{display:block;height: 45px;line-height: 45px;color: #0e90d2;}
</style>

<script type="text/javascript">
if($(window).width()<641){
var nextHref=$("#next_page a").prop("href");
var ajaxPageLock=true;
// 给浏览器窗口绑定 scroll 事件
$(window).bind("scroll",function(){
	
	var AjaxLoadPro=function(){
    		// Ajax 翻页
            $.ajax( {
                url: nextHref,
                type: "get",
                success: function(data) {
                	ajaxPageLock=true;
                	var newElems=$(data).find("#product_events").html();
                	nextHref =$(data).find("#next_page a").prop("href");
                	$("#product_events").append(newElems);
                	
                	$("#product_wrapper  .pull-action").removeClass('loading');
                }
            });
    	};
	
	
    // 判断窗口的滚动条是否接近页面底部
    if( ($(document).scrollTop() + $(window).height()) > ($(document).height() - 10) && ajaxPageLock) {
    	 ajaxPageLock=false;
        // 判断下一页链接是否为空
        if( nextHref != undefined) {
        	$("#product_wrapper .pull-action").addClass('loading');
        	setTimeout(AjaxLoadPro, 1000);
        } else {
        	$("#product_wrapper .pull-action").removeClass('loading');
        	$("#product_wrapper .pull-action span").remove();
        	$("#product_wrapper .pull-action").html('木有了噢，最后一页了！');
        	$("#product_wrapper .pull-action").addClass('error');
        	
        	setTimeout(function(){
        		$("#product_wrapper .pull-action").remove();
        	}, 5000);
        	
        }
    }
});
}
</script>