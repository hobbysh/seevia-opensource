<?php
	$next_page="";
	if(isset($paging)&&$pagination->setPaging($paging)){
		$rightArrow = $ld['next']." ›";
		$next_page = $pagination->nextPage($rightArrow,false);
	}
?>




<div class="am-u-lg-9 am-u-md-9 am-u-sm-12" id="product_wrapper">
	<pre id="next_page" class="am-hide"><?php  echo $next_page; ?></pre>
<?php if(!empty($products)){?>
<ul data-am-widget="gallery" class="am-gallery am-avg-sm-2 am-avg-md-3 am-avg-lg-3 am-gallery-overlay" data-am-gallery="{ }" id="product_events">
  <?php $flagnum=0;foreach($products as $k=>$v){?>
  <li>
    <div class="am-gallery-item">
      <?php if(isset($configs['show_product_like'])&&$configs['show_product_like']=='1'){ ?>
	  <span class="like_icon am-gallery-like" style="">
	  	<img id="<?php echo $v['Product']['id'];?>" src="/theme/default/img/like_icon.png" />
	  	<span style="" id="<?php echo 'like_num'.$v['Product']['id'];?>" class="like_num">
	      <?php if(isset($v['Product']['like_num'])){echo $v['Product']['like_num'];}else{echo '0';}?>
	  	</span>
	  </span>
	  <?php } ?>
	  <?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'img'=>($v['Product']['img_detail']!=''?$v['Product']['img_detail']:$configs['products_default_image']),'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name']));?>
	  <h3 class="am-gallery-title">
      	<?php echo $svshow->seo_link(array('type'=>'P','id'=>$v['Product']['id'],'name'=>$v['ProductI18n']['name'],'sub_name'=>$v['ProductI18n']['name']));?>
  	  </h3>
  	</div>
  	<div class="am-g pro_price pro_unit">
      	<?php if(isset($configs['show_product_price_onlist'])&&$configs['show_product_price_onlist']=='1'){ if(isset($v['price_range'])){echo $svshow->price_format($v['price_range']['min_price'],$configs['price_format'])." -".$svshow->price_format($v['price_range']['max_price'],$configs['price_format']);}else{echo $svshow->price_format($v['Product']['shop_price'],$configs['price_format']);}} ?>
      	<?php if(isset($configs['show_product_unit'])&&$configs['show_product_unit']=='1'&&!empty($v['Product']['unit'])){ echo "/".$v['Product']['unit'];} ?>
	</div>
  </li>
  <?php $flagnum++;}?>
</ul>
<?php }else{echo "<h2 class='detail-h2'>".$ld['no_related_products']."</h2>";}?>
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