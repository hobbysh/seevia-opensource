<?php 
/*****************************************************************************
 * SV-Cart 菜单管理
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
.am-yes{color:#5eb95e;}
.am-no{color:#dd514c;}
</style>
<div class="am-g am-other_action">
	<div class="am-fr am-u-lg-6 am-u-md-6 am-u-sm-3" style="text-align:right;margin-bottom:10px;">
		<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/menus/view/0'); ?>"><span class="am-icon-plus"></span> <?php echo $ld['add'] ?></a>
	</div>
</div>
<div class="">
	<div class="am-panel-group am-panel-tree" id="accordion">
		
		<div class="am-panel am-panel-default am-panel-header">
		    <div class="am-panel-hd">
		      <div class="am-panel-title">
				 <div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['menu_name'] ?></div>
				 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['code'] ?></div>
	   			 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-show-lg-only"><?php echo $ld['link_address'] ?></div>
				 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-show-lg-only"><?php echo $ld['versions'] ?></div>
				 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['orderby'] ?></div>
				 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['status'] ?></div>
	             <div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['operate'] ?></div>
				 <div style="clear:both;"></div>
		      </div>
		    </div>
		</div>
		<?php if(isset($menus_tree) && sizeof($menus_tree)>0){foreach($menus_tree as $k => $v){ ?>
		<div>
		<div class="am-panel am-panel-default am-panel-body">
		    <div class="am-panel-bd">
				 <div class="am-u-lg-2 am-u-md-3 am-u-sm-3">
					<span data-am-collapse="{parent: '#accordion', target: '#menu_<?php echo $v['OperatorMenu']['id']?>'}" class="<?php echo (isset($v['SubMenu']) && !empty($v['SubMenu']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;
					<?php echo $v['OperatorMenuI18n']['name'];?>
				</div>
				 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['OperatorMenu']['operator_action_code']?>&nbsp;</div>
	   			 <div class="am-u-lg-2 am-show-lg-only"><?php echo $v['OperatorMenu']['link']?>&nbsp;</div>
				 <div class="am-u-lg-2 am-show-lg-only"><?php echo $v['OperatorMenu']['section']?>&nbsp;</div>
				 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['OperatorMenu']['orderby']?></div>
				 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">&nbsp;<span class="<?php echo (!empty($v['OperatorMenu']['status'])&&$v['OperatorMenu']['status'])?'am-icon-check am-yes':'am-icon-close am-no'; ?>"></span></div>
	             <div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><a class="am-btn am-btn-success am-btn-sm am-radius" href="<?php echo $html->url('/menus/view/'.$v['OperatorMenu']['id']); ?>"><?php echo $ld['edit']; ?></a>&nbsp;<a class="am-btn am-btn-danger am-btn-sm am-radius" href="javascript:void(0);" onclick="list_delete_submit('<?php echo $admin_webroot; ?>menus/remove/<?php echo $v['OperatorMenu']['id']; ?>')"><?php echo $ld['delete']; ?></a></div>
				 <div style="clear:both;"></div>
		    </div>
		    <?php if(isset($v['SubMenu']) && !empty($v['SubMenu'])){?>
		    <div class="am-panel-collapse am-collapse am-panel-child" id="menu_<?php echo $v['OperatorMenu']['id']?>">
		    	<?php foreach($v['SubMenu'] as $kk=>$vv){  ?>
				<div class="am-panel-bd am-panel-childbd">
					 <div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $vv['OperatorMenuI18n']['name'];?></div>
					 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $vv['OperatorMenu']['operator_action_code']?></div>
		   			 <div class="am-u-lg-2 am-show-lg-only"><?php echo $vv['OperatorMenu']['link']?></div>
					 <div class="am-u-lg-2 am-show-lg-only"><?php echo $vv['OperatorMenu']['section']?></div>
					 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $vv['OperatorMenu']['orderby']?></div>
					 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">&nbsp;&nbsp;<span class="<?php echo (!empty($vv['OperatorMenu']['status'])&&$vv['OperatorMenu']['status'])?'am-icon-check am-yes':'am-icon-close am-no'; ?>"></span></div>
		             <div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><a class="am-btn am-btn-success am-btn-sm am-radius" href="<?php echo $html->url('/menus/view/'.$vv['OperatorMenu']['id']); ?>"><?php echo $ld['edit']; ?></a>&nbsp;<?php echo $html->link($ld['delete'],"javascript:;",array("class"=>"am-btn am-btn-danger am-btn-sm am-radius","onclick"=>"list_delete_submit('{$admin_webroot}menus/remove/{$vv['OperatorMenu']['id']}');"));?></div>
					 <div style="clear:both;"></div>
		    	</div>
		    	<?php } ?>
		    </div>
		    <?php } ?>
		</div>
		</div>	
		<?php }} ?>
	</div>
</div>
<script type="text/javascript">
$(function(){
	var $collapse =  $('.am-panel-child');
	$collapse.on('opened.collapse.amui', function() {
		var parentbody=$(this).parent();
		var collapseoobj=parentbody.find(".am-icon-plus");
		collapseoobj.removeClass("am-icon-plus");
		collapseoobj.addClass("am-icon-minus")
	});
		
	$collapse.on('closed.collapse.amui', function() {
		var parentbody=$(this).parent();
		var collapseoobj=parentbody.find(".am-icon-minus");
		collapseoobj.removeClass("am-icon-minus");
		collapseoobj.addClass("am-icon-plus")
	});
})
</script>