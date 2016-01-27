<style>
	.am-panel{box-shadow: 0 0px 0px}
</style>
<?php if(isset($region_list) && sizeof($region_list)>0){foreach($region_list as $k=>$v){ ?>
	<div class="am-panel am-panel-body sub_region_<?php echo $region_id; ?>" style="padding-left:30px;">
		<div class="am-panel-bd" style="padding:0px;">					
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
				<?php echo $v['RegionI18n']['name'] ?>&nbsp;
				<?php if(isset($region_child_list[$v['Region']['id']])&&sizeof($region_child_list[$v['Region']['id']])>0){ ?>
    			<a onclick="region('<?php echo $v['Region']['id']; ?>',this)"  style="cursor:pointer;"><?php echo $ld['view_sub_region']?></a>
    			<?php }?>
			</div>	
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['Region']['abbreviated'] ?>&nbsp;</div>	
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['Region']['orderby'] ?>&nbsp;</div>	
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
				<?php 
					if($svshow->operator_privilege('region_view')){?> 
			<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/regions/'.$v['Region']['id']); ?>"> <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
					<?php }
					if($svshow->operator_privilege('region_edit')){?> 
				      <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:void(0);" onclick="list_delete_submit(admin_webroot+'regions/remove/<?php echo $v['Region']['id'] ?>');">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a><?php }?>&nbsp;
			</div>	
			<div style="clear:both;"></div>
		</div>			
	</div>
<?php }} ?>