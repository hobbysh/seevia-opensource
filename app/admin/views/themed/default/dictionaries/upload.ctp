<style>
.am-form-group {margin-bottom:0px;}
.btnouter{margin:50px;}
</style>
<div>   
	<div class="am-text-right  am-btn-group-xs" style="margin-right:10px;margin-bottom:10px">
		<?php echo $html->link($ld['dictionaries'].$ld['list'],'/dictionaries',array("class"=>"am-btn am-btn-default am-btn-sm"),'',false,false).'&nbsp;';?>
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/users/view/'); ?>">
			<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		</a>
	</div>
	<div>
		<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
			<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
		    	<li><a href="#batch_upload_user"><?php echo $ld['bulk_upload'].$ld['dictionaries'] ?></a></li>
		    	<?php if(isset($uploads_list)&&sizeof($uploads_list)>0){ ?><li><?php echo $ld['preview']?></li>	<?php } ?>
			</ul>
		</div>
		<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
			<?php echo $form->create('dictionaries',array('action'=>'/uploadpreview/','name'=>"uploadusersForm","enctype"=>"multipart/form-data"));?>
				<div id="batch_upload_user" class="am-panel am-panel-default">
			  		<div class="am-panel-hd">
						<h4 class="am-panel-title">
							<?php echo $ld['bulk_upload'].$ld['dictionaries']?>
						</h4>
				    </div>
				    <div class="am-panel-collapse am-collapse am-in">
			      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
							<div class="am-form-group">
				    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['csv_file_bulk_upload']?></label>
				    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9"  style="margin-bottom:10px;">
										<p style="margin:10px 0px;"><input name="file" id="file" size="40" type="file" style="height:22px;;" onchange="checkFile()"/></p>
										<p style="padding:6px 0px;"><?php echo $ld['articles_upload_file_encod']?></p>
				    				</div>
				    			</div>
				    		</div>
						
								<div class="am-form-group">
					    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
											<?php echo $html->link($ld['download_example_batch_csv'],"/dictionaries/download_csv_example/",'',false,false);?>
					    				</div>
					    			</div>
					    		</div>	
				    	
						</div>
						<div class="btnouter">
							  <input type="hidden" value="1" name="sub1"/>
							<button type="submit"  name="upload_submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
							<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
						</div>	
					</div>
				</div>
			<?php echo $form->end();?>
		</div>
	</div>
</div>	
<script type="text/javascript">
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