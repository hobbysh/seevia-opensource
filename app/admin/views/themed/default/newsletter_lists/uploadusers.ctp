<p class=" am-u-md-12  am-btn-group-xs am-text-right" style="margin-top:10px;">
	<?php if($svshow->operator_privilege("users_add"))
	{?>
		
	<!--echo $html->link($ld['add_user'],'/newsletter_lists/view',array("class"=>"am-btn am-btn-warning am-radius am-btn-sm am-fr"),false,false);-->
          <a class="am-btn  am-btn-warning am-radius" href="<?php echo $html->url('/newsletter_lists/view')?>">
		         	  <span class="am-icon-plus"></span>&nbsp;<?php echo $ld['add_user'] ?>
		        </a>
       <?php }?>
	<?php 
	echo $html->link($ld["magazine_user"],'/newsletter_lists',array("class"=>"am-btn am-radius am-btn-sm  am-btn-default"),false,false);
		?>
</p>
<?php echo $form->create('newsletter_lists',array('action'=>'/uploaduserspreview/','name'=>"theForm","enctype"=>"multipart/form-data"));?>
<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" >
    <ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
        <li><a href="#upload"><?php echo $ld["batch_upload_user"];?></a></li>
    </ul>
</div>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9 am-fr" id="accordion" style="width:83%;">
    <div id="upload" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld["batch_upload_user"];?>
            </h4>
        </div>
        <div id="batch_upload_user" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table id="tablemain" class="am-table">
                    <tr>
                        <th><?php echo $ld['csv_file_bulk_upload']?></th>
                        <td><p><input name="file" id="file" size="40" type="file" style="height:22px;" onchange="checkFile()"/></p>
                            <p style="padding:6px 0;"><?php echo $ld['articles_upload_file_encod']?></p>
                        </td>
                    </tr>
                    <tr>
                        <?php if(isset($profilefiled_codes)&&sizeof($profilefiled_codes)>0&&!empty($profilefiled_codes)){?>
                    <tr><td></td><td><strong><?php echo $html->link($ld['download_example_batch_csv'],"/newsletter_lists/download_csv_example/",'',false,false);?></strong></td></tr>
                    <?php }?>
                </table>
                <div class="btnouter"><input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['d_submit']?>" /> <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" /></div>
            </div>
        </div>
    </div>
</div>
<?php $form->end();?>