<div>
	<label><?php echo $name;?></label>
	<a class="am-btn am-btn-warning am-btn-sm am-radius am-fr" href="<?php echo $html->url('/navigations/view/0/'.(strtoupper($position))); ?>"  style="margin-bottom:10px;">
		<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
	</a>
	<div style="clear:both;"></div>
</div>
<div class="am-panel-group am-panel-tree" id="accordion">
	<div class="am-panel am-panel-default am-panel-header">
	    <div class="am-panel-hd">
	      	<div class="am-panel-title">
				<div class="am-u-lg-2 am--u-md-2 am-u-sm-2"><?php echo $ld['navigation_name']?></div>
				<div class="am-u-lg-3 am--u-md-3 am-u-sm-3"><?php echo $ld['url']?></div>
				<div class="am-u-lg-1 am--u-md-1 am-u-sm-1"><?php echo $ld['show']?></div>
				<div class="am-u-lg-1 am--u-md-1 am-u-sm-1"><?php echo $ld['new_window']?></div>
				<div class="am-u-lg-2 am--u-md-2 am-u-sm-2"><?php echo $ld['sort']?></div>
				<div class="am-u-lg-3 am--u-md-3 am-u-sm-3"><?php echo $ld['operate']?></div>
				<div style="clear:both;"></div>
			</div>
		</div>
	</div>
	<?php if(isset($navigations_data) && sizeof($navigations_data)>0){foreach($navigations_data as $k=>$v){ ?>
	<div>			
		<div class="am-panel am-panel-default am-panel-body">
			<div class="am-panel-bd am-g">
				<div class="am-u-lg-2 am--u-md-2 am-u-sm-2">
					<span data-am-collapse="{parent: '#accordion', target:'#navigation_<?php echo $v['Navigation']['id']?>'}" class="<?php echo (isset($v['SubNavigation'])&&!empty($v['SubNavigation']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;<?php echo $v['NavigationI18n']['name'];?>&nbsp;
				</div>
				<div class="am-u-lg-3 am--u-md-3 am-u-sm-3">
					<a href="<?php echo $v['NavigationI18n']['url'] ?>" target="_blank"><?php echo $v['NavigationI18n']['url'] ?></a>&nbsp;
				</div>
				<div class="am-u-lg-1 am--u-md-1 am-u-sm-1">
					<?php if ($v['Navigation']['status'] == 1){?>
						<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_status',<?php echo $v['Navigation']['id'];?>)">&nbsp;</span>
					<?php }elseif($v['Navigation']['status'] == 0){?>
						<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_status',<?php echo $v['Navigation']['id'];?>)">&nbsp;</span>	
					<?php }?>
				</div>
				<div class="am-u-lg-1 am--u-md-1 am-u-sm-1">
					<?php if ($v['Navigation']['target'] == '_blank'){?>
						<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_target',<?php echo $v['Navigation']['id'];?>)">&nbsp;</span>								
					<?php }elseif($v['Navigation']['target'] == '_self'){?>
						<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_target',<?php echo $v['Navigation']['id'];?>)">&nbsp;</span>
					<?php }?>
				</div>
				<div class="am-u-lg-2 am--u-md-2 am-u-sm-2">
					<?php if($k==0){?>
						<a onclick="changeOrder('down','<?php echo $v['Navigation']['id'];?>','0',this,'<?php echo $position; ?>')">&#9660;</a>&nbsp;
					<?php }elseif($k==(count($navigations_data)-1)){?>
						<a onclick="changeOrder('up','<?php echo $v['Navigation']['id'];?>','0',this,'<?php echo $position; ?>')" style="color:#cc0000;">&#9650;</a>&nbsp;
					<?php }else{?>
						<a onclick="changeOrder('up','<?php echo $v['Navigation']['id'];?>','0',this,'<?php echo $position; ?>')" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $v['Navigation']['id'];?>','0',this,'<?php echo $position; ?>') ">&#9660;</a>&nbsp;
					<?php }?>
				</div>
				<div class="am-u-lg-3 am--u-md-3 am-u-sm-3">
					<?php
					if($svshow->operator_privilege('navigations_edit')){
						echo $html->link($ld['edit'],"/navigations/view/{$v['Navigation']['id']}",array("class"=>"am-btn am-btn-success am-btn-sm am-radius")).'&nbsp;';}
					if($svshow->operator_privilege('navigations_remove')){
						echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-danger am-btn-sm am-radius","onclick"=>"list_delete_submit('{$admin_webroot}/navigations/remove/{$v['Navigation']['id']}');}"))?>
				</div>
				<div style="clear:both;"></div>
			</div>
		</div>
	</div>
	<?php if(!empty($v['SubNavigation']) && sizeof($v['SubNavigation'])>0){ ?>
		<div class="am-panel-collapse am-collapse am-panel-child" id="navigation_<?php echo $v['Navigation']['id']?>">
			<div class="am-panel am-panel-default am-panel-body">
          <?php foreach($v['SubNavigation'] as $kk=>$vv){ ?>
				<div class="am-panel-bd am-g">
					<div class="am-u-lg-2 am--u-md-2 am-u-sm-2">
						<span class="am-icon-minus" id="<?php echo $vv['Navigation']['id']?>"></span>&nbsp;<?php echo $html->link($vv['NavigationI18n']['name'],$server_host.$vv['NavigationI18n']['url'],array("target"=>"_blank"),false,false);?>
					</div>
					<div class="am-u-lg-3 am--u-md-3 am-u-sm-3">
						<?php echo $html->link($vv['NavigationI18n']['url'],$server_host.$vv['NavigationI18n']['url'],array("target"=>"_blank"),false,false);?>
					</div>
					<div class="am-u-lg-1 am--u-md-1 am-u-sm-1">
						<?php if ($vv['Navigation']['status'] == 1){?>
							<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_status',<?php echo $vv['Navigation']['id'];?>)">&nbsp;</span>
						<?php }elseif($vv['Navigation']['status'] == 0){?>
							<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_status',<?php echo $vv['Navigation']['id'];?>)">&nbsp;</span>	
						<?php }?>
					</div>
					<div class="am-u-lg-1 am--u-md-1 am-u-sm-1">
						<?php if ($v['Navigation']['target'] == '_blank'){?>
							<span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_target',<?php echo $vv['Navigation']['id'];?>)">&nbsp;</span>								
						<?php }elseif($v['Navigation']['target'] == '_self'){?>
							<span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'navigations/toggle_on_target',<?php echo $vv['Navigation']['id'];?>)">&nbsp;</span>
						<?php }?>
					</div>
					<div class="am-u-lg-2 am--u-md-2 am-u-sm-2">
						<?php if(count($v['SubNavigation'])==1){echo "-";}elseif($kk==0){?>
							<a onclick="changeOrder('down','<?php echo $vv['Navigation']['id'];?>','next',this,'<?php echo $position ?>')">&#9660;</a>
						<?php }elseif($kk==(count($v['SubNavigation'])-1)){?>
							<a onclick="changeOrder('up','<?php echo $vv['Navigation']['id'];?>','next',this,'<?php echo $position ?>')" style="color:#cc0000;">&#9650;</a>
						<?php }else{?>
							<a onclick="changeOrder('up','<?php echo $vv['Navigation']['id'];?>','next',this,'<?php echo $position ?>')" style="color:#cc0000;">&#9650;</a>&nbsp;<a onclick="changeOrder('down','<?php echo $vv['Navigation']['id'];?>','next',this,'<?php echo $position ?>')">&#9660;</a>
						<?php }?>
					</div>
					<div class="am-u-lg-3 am--u-md-3 am-u-sm-3">
						<?php if($svshow->operator_privilege('navigations_edit')){
						echo $html->link($ld['edit'],"/navigations/view/{$vv['Navigation']['id']}",array('class'=>'am-btn am-btn-success am-btn-sm am-radius')).'&nbsp;';}
							if($svshow->operator_privilege('navigations_remove')){echo 													$html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-danger am-btn-sm am-radius","onclick"=>"list_delete_submit('{$admin_webroot}navigations/remove/{$vv['Navigation']['id']}')"));}?>
					</div>
				</div>
               	<?php } ?>
			</div>
		</div>
	<?php } ?>
	<?php }}}?>	
</div>