<style>
.am-form-group {margin-bottom:0px;}
.btnouter{margin:50px;}
</style>
<div>
	<div class="am-text-right am-btn-group-xs" style="margin-right:10px;margin-bottom:10px;">
	
		<a class="am-btn am-btn-default am-btn-sm"  href="<?php echo $html->url('/articles/'); ?>">
			 <?php echo $ld['article_list'] ?>
		</a>
	
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/articles/view/'); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a>
	</div>
	<div>
		<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
			<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
		    	<li><a href="#bulk_upload_article"><?php echo $ld['bulk_upload_article']?></a></li>
			</ul>
		</div>
		<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
		<?php echo $form->create('articles',array('action'=>'/uploadarticlespreview/','onsubmit'=>'return category_check();','name'=>"uploadarticlesForm","enctype"=>"multipart/form-data"));?>
			<div id="bulk_upload_article" class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['bulk_upload_article']?>
					</h4>
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">		
						<div class="am-form-group">
			    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:18px;"><?php echo $ld['article_categories']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9"  style="margin-bottom:10px;">
									<select id="category_id" name="category_id" data-am-selected>
										<option value="0"><?php echo $ld['select_categories']?></option>
										<?php if(isset($categories_tree) && sizeof($categories_tree)>0){?><?php foreach($categories_tree as $first_k=>$first_v){?>
										<option value="<?php echo $first_v['CategoryArticle']['id'];?>"><?php echo $first_v['CategoryArticleI18n']['name'];?></option>
										<?php if(isset($first_v['SubCategory']) && sizeof($first_v['SubCategory'])>0){?><?php foreach($first_v['SubCategory'] as $second_k=>$second_v){?>
										<option value="<?php echo $second_v['CategoryArticle']['id'];?>">&nbsp;&nbsp;<?php echo $second_v['CategoryArticleI18n']['name'];?></option>
										<?php if(isset($second_v['SubCategory']) && sizeof($second_v['SubCategory'])>0){?><?php foreach($second_v['SubCategory'] as $third_k=>$third_v){?>
										<option value="<?php echo $third_v['CategoryArticle']['id'];?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $third_v['CategoryArticleI18n']['name'];?></option>
										<?php }}}}}}?>
									</select>
			    				</div>
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:21px;"><?php echo $ld['csv_file_bulk_upload']?></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<p style="margin:10px 0px;"><input name="file" id="file" size="40" type="file" style="height:22px;" onchange="checkFile()"/></p>
									<p style="padding:6px 0;"><?php echo $ld['articles_upload_file_encod']?></p>
			    				</div>
			    			</div>
			    		</div>	
			    		<?php if(isset($profilefiled_codes)&&sizeof($profilefiled_codes)>0&&!empty($profilefiled_codes)){?>
						<div class="am-form-group">
			    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"></label>
			    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
			    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
									<?php echo $html->link($ld['download_example_batch_csv'],"/articles/download_csv_example/",'',false,false);?>
			    				</div>
			    			</div>
			    		</div>	
			    		<?php }?>	
					</div>
					<div class="btnouter">
						<button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
					</div>	
				</div>
			</div>		
		<?php echo $form->end();?>				
		</div>
	</div>
</div>	
	
<script type="text/javascript">
function category_check(){
	if(document.getElementById('category_id').value=="0"){
		alert("<?php echo $ld['select_article_category']?>");
		return false;
	}
	return true;
}

function checkFile() {
	var obj = document.getElementById('file');
	var suffix = obj.value.match(/^(.*)(\.)(.{1,8})$/)[3];
	if(suffix != 'csv'&&suffix != 'CSV'){
 		alert("<?php echo $ld['file_format_csv']?>");
 		obj.value="";
 		return false;
	}
}
</script>