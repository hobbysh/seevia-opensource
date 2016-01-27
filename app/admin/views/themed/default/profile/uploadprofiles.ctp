<p class=" am-btn-group-xs am-text-right" style="margin:10px 0 0 0">
		
	<?php echo $html->link($ld['file_allocation'],"/profiles/",array('class'=>'am-btn am-radius  am-btn-default am-btn-sm '),false,false);?>
		      &nbsp;
			<a class="am-btn  am-btn-warning am-radius" href="<?php echo $html->url('/profiles/view/0')?>">
		         	  <span class="am-icon-plus"></span>&nbsp;<?php echo $ld['add'] ?>
		        </a>	
</p>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="margin:10px 0 0 0">
  <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 1000; width: 15%;max-width:200px;">
  	<li><a href="#bulk"><?php echo $ld['bulk_upload'];?></a></li>
  </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
  <div id="bulk" class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title">
      	<?php echo $ld['bulk_upload']?>
      </h4>
    </div>
    <div id="bulk_upload" class="am-panel-collapse am-collapse am-in">
    	<div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
    	<?php echo $form->create('profiles',array('action'=>'/uploadprofilepreview/','name'=>"uploadprofileForm","enctype"=>"multipart/form-data"));?>
    		<table class="am-table">
				<tr>
					<th><?php echo $ld['csv_file_bulk_upload']?></th>
					<td><p><input name="file" id="file" size="40" type="file" style="height:22px;" onchange="checkFile()"/></p>
					</td>
				</tr>
			 <?php if(isset($profilefiled_codes)&&sizeof($profilefiled_codes)>0&&!empty($profilefiled_codes)){?>
				<tr><td></td><td><strong><?php echo $html->link($ld['download_example_batch_csv'],"/profiles/download_csv_example/",'',false,false);?></strong></td></tr>
			 <?php } ?>
			</table>
			<div class="btnouter">
				<input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['d_submit']?>" />
				<input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
			</div>
    	<?php echo $form->end();?>
		</div>
	</div>
 </div>
</div>
<script type="text/javascript">
function checkFile() {
	var obj = document.getElementById('file');
	var suffix = obj.value.match(/^(.*)(\.)(.{1,8})$/)[3];
	if(suffix != 'csv'&&suffix != 'CSV'){
 		alert("<?php echo $ld['file_format_csv']; ?>");
 		obj.value="";
 		return false;
	}
}
</script>