<style type="text/css">
	.status{ display:none }
	.popup .input_type { width: 104px; margin-right:4px; float: left; }
	.am-form .status[type="text"]{ display:none }
	[class*="am-u-"] + [class*="am-u-"]:last-child{float:left;}
</style>
<div class="am-modal-dialog">
    <div class="am-modal-hd"><?php echo $name;?>
      	<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close="{target: '#public_dialog'}" onclick="btnclick()">&times;</a>
    </div>
    <div class="am-modal-bd"  style="height:400px;overflow:auto;">
		<form id="information_form" name="information_form">
			<input type="hidden" name="code" id="code" value="<?php echo $code;?>">
			<input type="hidden" name="parent_id" value="<?php echo $parent_id;?>">
			<div id="basic_information" class="am-panel-collapse am-collapse am-in">
	      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
					<?php if(isset($informationresource_id_info)&&sizeof($informationresource_id_info)>0){ foreach($informationresource_id_info as $iik=>$ii){if($iik=='id'){ continue;}if(isset($backend_locales)&&sizeof($backend_locales)>0){ $j=0;foreach ($backend_locales as $k => $v){?>
			
					<?php if(isset($informationresource_info)&&isset($informationresource_info[$v['Language']['locale']])){
						foreach($informationresource_info[$v['Language']['locale']] as $ik=>$i){?>
								<?php if($ik==$iik){?>
								<div class="am-form-group" style="margin-bottom:5px;"> 
									<label class="input_type am-u-lg-5 am-u-md-4 am-u-sm-4" id="informationresource_span_<?php echo $informationresource_id_info[$ik][$v['Language']['locale']]?>" onclick="showInput(<?php echo $informationresource_id_info[$ik][$v['Language']['locale']];?>)">
										<?php echo $i;?>
									</label>
									<div  class="input_type am-u-lg-5 am-u-md-4 am-u-sm-4" style="display:none;">
										<input class="status" type="text" id="informationresource_value_<?php echo $informationresource_id_info[$ik][$v['Language']['locale']]?>" value="<?php echo $i;?>" onblur="editInforationresources(<?php echo $informationresource_id_info[$ik][$v['Language']['locale']]?>)" >
									</div>
									<label class="am-u-lg-5 am-u-md-4 am-u-sm-4 ">
										<?php echo $ld[$v['Language']['locale']]?>
									</label>
									<?php if($j==0){  $j++;?>
										<div class="am-u-lg-2 am-u-md-4 am-u-sm-4">
											<button type="button"  class="addbutton am-btn am-btn-danger am-btn-sm am-radius" onclick="removeInforationresources(<?php echo $informationresource_id_info[$ik][$v['Language']['locale']]?>)" value="<?php echo $ld['delete']?>" ><?php echo $ld['delete']?></button>
										</div>
									<?php }?>
								</div>
								<?php }?>
					<?php }}?>
                    <?php }}?> 
                    <hr >
					<?php }}?>
					<?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>	
						<div class="am-form-group">
                            <label class="am-u-lg-3 am-u-md-5 am-u-sm-5">&nbsp;</label>
					        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					        	<input type="text"  id="<?php echo $v['Language']['locale']?>" name="<?php echo $v['Language']['locale']?>"value="">
							</div>
							<?php if(sizeof($backend_locales)>1){ ?>
								<label class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld[$v['Language']['locale']]?></label>
							<?php }?>&nbsp;
							<?php if($k==0){?>
								<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
									<button type="button"  class="addbutton am-btn am-btn-success am-btn-sm am-radius" onclick="editInforationresources('')" value="<?php echo $ld['add']?>"><?php echo $ld['add']?></button>
								</div>
							<?php }?>
					    </div>
					<?php }}?>
				</div>
			</div>
		</form>
    </div>
</div>
<script>
function btnclick(){
	$("#public_dialog").modal('close');
}
</script>