<?php
  $next_page="";
  if(isset($paging)&&$pagination->setPaging($paging)){
    $rightArrow = $ld['next']." ›";
    $next_page = $pagination->nextPage($rightArrow,false);
  }
?>

<?php //pr($sm['paging']);?>
<div class="am-u-md-9" id="product_wrapper">
  <button style="margin:1rem 0;" class="am-btn am-btn-sm am-btn-secondary am-show-sm-only" data-am-offcanvas="{target: '#a_category', effect: 'push'}"><span >文章分类</span> </button>
  <!--<h2><?php echo $code_infos[$sk]['name'].":".$sm['category_name'];?></h2>-->
  <?php if(isset($configs['content-list-Show-Type']) && $configs['content-list-Show-Type']==1){?>
  <pre id="next_page" class="am-hide"><?php  echo $next_page; ?></pre>
  <ul id="product_events" class="am-list am-list-striped" style="min-height:110px;">
	<?php if($code_infos[$sk]['type']=="module_category_article"){if(sizeof($sm['category_article'])>0){foreach($sm['category_article'] as $a){?>
	<li>
	  <?php echo $svshow->seo_link(array('type'=>'A', 'name'=>$a['ArticleI18n']['title'], 'sub_name'=>$a['ArticleI18n']['title'], 'id'=>$a['Article']['id']));?>
	</li>
	<?php }}else{ echo $ld['common_001'];}}?>
  </ul>
  <?php }else if($configs['content-list-Show-Type']==2){?>
  <div data-am-widget="list_news" class="am-list-news am-list-news-default">
    <div class="am-list-news-bd">
      <ul class="am-list">
		<?php if($code_infos[$sk]['type']=="module_category_article"){if(sizeof($sm['category_article'])>0){foreach($sm['category_article'] as $a){?>
        <li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-left">
          <div class="am-col am-u-sm-2 am-list-thumb">
            <?php echo $svshow->seo_link(array('type'=>'A','id'=>$a['Article']['id'],'img'=>($a['ArticleI18n']['img01']!=''?$a['ArticleI18n']['img01']:$configs['products_default_image']),'name'=>$a['ArticleI18n']['title'],'sub_name'=>$a['ArticleI18n']['title']));?>
          </div>
          <div class="am-col am-u-sm-9 am-list-main">
            <h3 class="am-list-item-hd">
              <?php echo $svshow->seo_link(array('type'=>'A', 'name'=>$a['ArticleI18n']['title'], 'sub_name'=>$a['ArticleI18n']['title'], 'id'=>$a['Article']['id']));?>
            </h3>
            <div class="am-list-item-text"><?php echo $a['ArticleI18n']['subtitle'];?></div>
          </div>
        </li>
		<?php }}else{ echo $ld['common_001'];}}?>
      </ul>
  	</div>
  </div>
  <?php }else if($configs['content-list-Show-Type']==3){?>
  <ul id="am-gallery-list" class="am-avg-sm-3 am-avg-md-3 am-avg-lg-3 gallery-list">
	<?php if($code_infos[$sk]['type']=="module_category_article"){if(sizeof($sm['category_article'])>0){foreach($sm['category_article'] as $a){?>
	<li>
	  <a href="<?php echo $html->url('/articles/'.$a['Article']['id']); ?>" title="<?php echo $a['ArticleI18n']['title'];?>">
		<img class="am-img-thumbnail am-img-bdrs" src="<?php echo $a['ArticleI18n']['img01']!=''?$a['ArticleI18n']['img01']:$configs['products_default_image']; ?>"/>
		<div class="gallery-title"><?php echo $svshow->cut_str($a['ArticleI18n']['title'],10);?></div>
	  </a>
	</li>
	<?php }}else{ echo $ld['common_001'];}}?>
  </ul>
  <?php }?>
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