<?php


echo $htmlSeevia->js(array(
						   'jquery-ui-1.8.18.custom.min',
						   'jquery.easing.1.3',
						   'jquery.booklet.1.3.1'
						   ));
//¼ÓÔØcss
//echo $htmlSeevia->css(array('jquery.booklet.1.3.1'));



?>
<div id="magazine" class="booklet">
	<?php if(isset($article_galleries) && sizeof($article_galleries) >0){ foreach($article_galleries as $ag){?>
		<div><img src="<?php echo $ag['ArticleGallery']['img_original'];?>"></div>
	<?php }}?>
	<?php //echo $desc; ?>
</div>
<?php $width = isset($configs['maga-width'])&&$configs['maga-width']?$configs['maga-width']:'';?>
<?php $height = isset($configs['maga-height'])&&$configs['maga-height']?$configs['maga-height']:'';?>
<div class="next_prev">
	<div class="prev"></div>
	<div class="next"></div>
</div>
<script>
$(function() {
	var width=<?php echo $width; ?>;
	var height=<?php echo $height; ?>;
	var options = {
		width:width,
		height:height,
		next:".next",
		prev:".prev",
		pageNumbers:false
};
		//alert(width+"px");
	$('#magazine').booklet(options);
	$('.next_prev').width(width+"px");
	
});

</script>