<?php //pr($material) ?>

<div class="am-g am-container">
	<?php if(isset($material) && sizeof($material)>0){?>
	 <a style="padding:0" href="<?php if (isset($material['0']['OpenElement']['url'])){echo $material['0']['OpenElement']['url'];}?> ">  
	<ul data-am-widget="gallery" class="am-gallery am-first-article am-gallery-overlay" data-am-gallery="{ pureview: true;gallery:false }" >
      <li style="width:100%">
        <div class="am-gallery-item">
            
              <img src="<?php if (isset($material['0']['OpenElement']['media_url'])){echo $material['0']['OpenElement']['media_url'];}?>"/>
        
                <h3 class="am-gallery-title "><?php if(isset($material['0']['OpenElement']['title'])){echo $material['0']['OpenElement']['title'];}?></h3>
                <div class="am-gallery-desc"><?php if(isset($material['0']['OpenElement']['created'])){echo date("Y-m-d",strtotime($v['OpenElement']['created']));}?></div>    
        </div>
      </li>
    </ul>
    </a> 
    <?php } ?>
	<?php if(isset($material) && sizeof($material)>0){foreach($material as $k=>$v){if($k>0){?>
    <div data-am-widget="list_news" class="am-list-news am-list-news-default" >
  		<div class="am-list-news-bd">
  			<a style="padding:0" href="<?php if (isset($v['OpenElement']['url'])){echo $v['OpenElement']['url'];}?> ">  
 		 <ul class="am-list am-preview">
      		<li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-right">
        		<div class=" am-u-sm-8 am-list-main">
            		<div class="am-list-item-text"><?php if(isset($v['OpenElement']['title'])){echo $v['OpenElement']['title'];}?></div>
        		</div>
          <div class="am-u-sm-4 am-list-thumb">            
              <img src="<?php if(isset($v['OpenElement']['media_url'])){echo $v['OpenElement']['media_url'];}?>" />   
          </div>
      </li>
    </ul>
</a>
  </div>
</div>
	<?php }}}?>
	</div>

<style type="text/css">
.am-container li{height:auto;}
.am-preview li{border-bottom:0;}
.am-preview{margin-bottom:0;}
.am-g div.am-list-news:last-child{border-bottom:1px solid #dedede;}
.am-list-news{margin:0px 10px;}
.am-g .am-list-news:first-child{margin-top:10px}
.am-list-item-desced:hover,.am-first-article li:hover{opacity:0.8;}
</style>
<script type="text/javascript">
<?php if(isset($material[0]['OpenElement'])){ ?>
var wechat_shareTitle="<?php echo $material[0]['OpenElement']['title'] ?>";
var wechat_descContent="<?php echo $svshow->emptyreplace($material[0]['OpenElement']['description']); ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php if($svshow->imgfilehave($server_host.$material[0]['OpenElement']['media_url'])){ ?>
var wechat_imgUrl="<?php echo $server_host.$material[0]['OpenElement']['media_url'] ?>";
<?php }} ?>
</script>