<?php 
/*****************************************************************************
 * SV-Cart 权限管理
 * ===========================================================================
 * 版权所有 上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn [^]
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
?>
<style type="text/css">
.am-panel-bd {padding: 0.5rem;}
 .am-yes{color:#5eb95e;}
 .am-no{color:#dd514c;}

</style>
<div class="am-g am-other_action">
	<div class="am-fr am-u-lg-6 am-u-md-6 am-u-sm-3" style="text-align:right;margin-bottom:10px;">
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/operator_actions/view/0'); ?>"><span class="am-icon-plus"></span> <?php echo $ld['add'] ?></a>
	</div>
</div>
<div class="">
	<div class="am-panel-group am-panel-tree" id="accordion">
	<!--标题栏-->
		<div class="am-panel am-panel-default am-panel-header">
		    <div class="am-panel-hd">
		      <div class="am-panel-title">
				 <div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['z_action_name'];?></div>
				 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['code'];?></div>
	   			 <div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['versions'];?></div>
				 <div class="am-u-lg-1 am-u-md-2 am-u-sm-2"><?php echo $ld['status'];?></div>
				 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['orderby'];?></div>
				 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 "><?php echo $ld['operate'];?></div>
				 <div style="clear:both;"></div>
		      </div>
		    </div>
		</div>
	<!--一级 菜单-->
		<?php if(isset($action_tree) && sizeof($action_tree)>0){foreach($action_tree as $k => $v){//pr($v);?>
		<div>
		<div class="am-panel am-panel-default am-panel-body" >
		    <div class="am-panel-bd fuji">
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<span data-am-collapse="{parent: '#accordion', target: '#action_<?php echo $v['OperatorAction']['id']?>'}" class="<?php echo (isset($v['SubAction'])&&!empty($v['SubAction']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;
					<?php echo $html->link($v['OperatorActionI18n']['name'],"view/{$v['OperatorAction']['id']}",array(),false,false);?>
				</div>
				 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['OperatorAction']['code']?>&nbsp;</div>
	   			 <div class="am-u-lg-2 am-show-lg-only"><?php echo $v['OperatorAction']['section']?>&nbsp;</div>
				 <div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
					<span class="<?php echo (!empty($v['OperatorAction']['status'])&&$v['OperatorAction']['status'])?'am-icon-check am-yes':'am-icon-close am-no'; ?>"></span>&nbsp;
				</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['OperatorAction']['orderby']?>&nbsp;</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
				    <a class="am-btn am-btn-success am-btn-sm am-radius" href="<?php echo $html->url('/operator_actions/view/'.$v['OperatorAction']['id']); ?>">
				    <?php echo $ld['edit']; ?>
				    </a>&nbsp;
				    <a class="am-btn am-btn-danger am-btn-sm am-radius" href="javascript:void(0);" onclick="list_delete_submit('<?php echo $admin_webroot; ?>operator_actions/remove/<?php echo $v['OperatorAction']['id']; ?>')">
				    	<?php echo $ld['delete']; ?>
				    </a>
				</div>
				<div style="clear:both;"></div>
		    </div>
		<!--二级 菜单-->
			<?php if(isset($v['SubAction']) && sizeof($v['SubAction'])>0){?>
		    <div class="am-panel-collapse am-collapse am-panel-child" id="action_<?php echo $v['OperatorAction']['id']?>">
				<?php foreach($v['SubAction'] as $kk=>$vv){?>
				<div class="am-panel-bd am-panel-childbd actionn_<?php echo $vv['OperatorAction']['id']?>">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
						<label style="padding-left:20px;">
						<span data-am-collapse="{parent: '#action_<?php echo $v['OperatorAction']['id']; ?>', target: '#actionn_<?php echo $vv['OperatorAction']['id']?>'}" class="<?php echo (isset($vv['SubAction']) && !empty($vv['SubAction']))?"am-icon-plus":"am-icon-minus";?>" ></span>&nbsp;
						<?php echo $html->link($vv['OperatorActionI18n']['name'],"view/{$vv['OperatorAction']['id']}",array(),false,false);?>&nbsp;
						</label>
					</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $vv['OperatorAction']['code']?>&nbsp;</div>
		   			<div class="am-u-lg-2 am-show-lg-only"><?php echo $vv['OperatorAction']['section']?>&nbsp;</div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
						<span class="<?php echo (!empty($vv['OperatorAction']['status'])&&$vv['OperatorAction']['status'])?'am-icon-check am-yes':'am-icon-close am-no'; ?>"></span>&nbsp;&nbsp;
					</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $vv['OperatorAction']['orderby']?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
						<a class="am-btn am-btn-success am-btn-sm am-radius am-text-left" href="<?php echo $html->url('/operator_actions/view/'.$vv['OperatorAction']['id']); ?>"><?php echo $ld['edit']; ?></a>&nbsp;
						<?php echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-danger am-btn-sm am-radius am-text-left","onclick"=>"list_delete_submit('{$admin_webroot}operator_actions/remove/{$vv['OperatorAction']['id']}')"));?>
					</div>
					<div style="clear:both;"></div>
		    	</div>
		<!--三级 菜单-->			
				<?php if(isset($vv['SubAction']) && sizeof($vv['SubAction'])>0){?>
				<div class="am-panel-collapse am-collapse am-panel-subchild" id="actionn_<?php echo $vv['OperatorAction']['id']?>">
				<?php foreach($vv['SubAction'] as $lk=>$lv){?>
				<div class="am-panel-bd am-panel-childbd">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
						<label style="padding-left:60px;">						
						<?php echo $html->link($lv['OperatorActionI18n']['name'],"view/{$lv['OperatorAction']['id']}",array(),false,false);?>&nbsp;
						</label>
					</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $lv['OperatorAction']['code']?>&nbsp;</div>
		   			<div class="am-u-lg-2 am-show-lg-only"><?php echo $lv['OperatorAction']['section']?>&nbsp;</div>
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-2">
						<span class="<?php echo (!empty($lv['OperatorAction']['status'])&&$lv['OperatorAction']['status'])?'am-icon-check am-yes':'am-icon-close am-no'; ?>"></span>&nbsp;&nbsp;
					</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $lv['OperatorAction']['orderby']?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
						<a class="am-btn am-btn-success am-btn-sm am-radius am-text-left" href="<?php echo $html->url('/operator_actions/view/'.$lv['OperatorAction']['id']); ?>"><?php echo $ld['edit']; ?></a>&nbsp;
						<?php echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-danger am-btn-sm am-radius am-text-left","onclick"=>"list_delete_submit('{$admin_webroot}operator_actions/remove/{$lv['OperatorAction']['id']}');"));?>
					</div>
					<div style="clear:both;"></div>
		    	</div>
				<?php }?>
				</div>
				<?php }?>
				<?php }?>
			</div>	
		   <?php }?>		
		</div>
		</div>
		<?php }}?> 		
	</div>
</div>	
<script type="text/javascript">
$(function(){
	var $collapse =  $('.am-panel-child');
	var $subchild =  $('.am-panel-subchild');
	$collapse.on('opened.collapse.amui', function() {
		var parentbody=$(this).parent().find(".fuji");
		var collapseoobj=parentbody.find(".am-icon-plus");
		collapseoobj.removeClass("am-icon-plus");
		collapseoobj.addClass("am-icon-minus");
	});
	$collapse.on('closed.collapse.amui', function() {
		var parentbody=$(this).parent().find(".fuji");
		var collapseoobj=parentbody.find(".am-icon-minus");
		collapseoobj.removeClass("am-icon-minus");
		collapseoobj.addClass("am-icon-plus")
	});
	
	$subchild.on('opened.collapse.amui', function() {
		var am_panel_child_className=$(this).attr('id');
		var parentbody2=$(this).parent().find("."+am_panel_child_className);
		var collapseoobj2=parentbody2.find(".am-icon-plus");
		collapseoobj2.removeClass("am-icon-plus");
		collapseoobj2.addClass("am-icon-minus")
	});
	$subchild.on('closed.collapse.amui', function() {
		var am_panel_child_className=$(this).attr('id');
		var parentbody2=$(this).parent().find("."+am_panel_child_className);
		var collapseoobj2=parentbody2.find(".am-icon-minus");
		collapseoobj2.removeClass("am-icon-minus");
		collapseoobj2.addClass("am-icon-plus")
	});
	
})
</script>	