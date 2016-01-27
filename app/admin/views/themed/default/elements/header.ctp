<header class="am-topbar am-topbar-fixed-top">
  <div class="am-container">
	<div class="am-u-lg-1 am-u-md-2 am-u-sm-7">
	  <button class="am-btn am-btn-sm am-btn-success am-btn-xs am-show-sm-only am-fl am-menu-openbtn" data-am-offcanvas="{target: '#doc-oc-demo2', effect: 'push'}" style="margin-top:8px;"><span class="am-sr-only">�����л�</span>
<span class="am-icon-bars"></span></button><?php  if(!empty($configs['admin_logo'])){
       	echo "<a href='/admin/pages/home' class='am-hide-sm-only' style='height:50px;display:block;'><img style='width:100%;height:100%;' src=".$configs['admin_logo']." /></a>";
      }else{
       	echo $svshow->link($svshow->image('/img/logo.jpg',array("style"=>"width:100%;height:100%;")),"/admin/pages/home",array("style"=>"height:50px;display:block;","class"=>'am-hide-sm-only'));
      }?>
	</div>
	<div class="am-menu-parent am-u-lg-9 am-u-md-8 am-u-sm-1">
	<nav data-am-widget="menu" class="am-menu am-menu-dropdown2" data-am-menu-collapse>
	  <a href="javascript:void(0)" class="am-menu-toggle">
	    <i class="am-menu-toggle-icon am-icon-bars"></i>
	  </a>
	  <?php if(isset($menus)&&sizeof($menus)>0){ ?>
	    <ul class="am-menu-nav am-avg-lg-3 am-avg-md-3 am-avg-sm-2 am-collapse">
	    <?php foreach($menus as $k=>$v){ ?> 
	    	<li class="<?php echo isset($v['SubMenu'])&&!empty($v['SubMenu'])?'am-parent':'' ?>"><a href="javascript:void(0);"><?php echo $v['OperatorMenuI18n']['name'] ?></a>
	    		<?php if(isset($v['SubMenu'])&&sizeof($v['SubMenu'])>0){ ?>
	    			<ul class="am-menu-sub am-collapse am-avg-sm-2" >
	    				<?php foreach($v['SubMenu'] as $kk=>$vv){ ?>
						<li >
						<a href="<?php echo $html->url($vv['OperatorMenu']['link']); ?>" style="min-width:150px;"><?php echo $vv['OperatorMenuI18n']['name']; ?></a>
						</li>
	    				<?php } ?>
	    			</ul>
	    		<?php } ?>
	    	</li>
	    <?php } ?>
	    </ul>
	  <?php } ?>
	</nav>
  </div>
  		
  <div class="am-u-lg-2 am-u-md-2 am-u-sm-5">
        <div class="am-dropdown am-fr" data-am-dropdown>
        	<div id="admin_userinfo" style="margin:0; height: 50px; line-height: 20px;"><span class="am-icon-user"></span>&nbsp;<?php echo $html->link($admin["name"] , "javascript:void(0);" , array('class'=>'am-dropdown-toggle username','id'=>'admin_username','escape'=>false),false);?></div>
		  <ul class="am-dropdown-content">
			<li><?php echo $html->link($ld["alter_password"], "/pages/edit");?></li>
			<li><?php echo $html->link($ld["log_out"], "/pages/logout");?></li>
		  </ul>
		</div>
    </div>
  </div>
</header>
<div id="doc-oc-demo2" class="am-offcanvas am-header-menu">
  <div class="am-offcanvas-bar">
    <div class="am-offcanvas-content">
    	<?php if(isset($menus)&&sizeof($menus)>0){ ?>
    		<?php foreach($menus as $k=>$v){ ?>
    			<a id="nav_link_<?php echo $v['OperatorMenu']['id']; ?>" class="first_nav" data-am-collapse href="#second-nav-<?php echo $v['OperatorMenu']['id']; ?>"><?php echo $v['OperatorMenuI18n']['name']; ?></a>
    			<?php if(isset($v['SubMenu'])&&sizeof($v['SubMenu'])>0){ ?>
    				<nav id="nav<?php echo $v['OperatorMenu']['id']; ?>">
    					<ul id="second-nav-<?php echo $v['OperatorMenu']['id']; ?>" class="am-nav am-collapse am-cate-3">
    						<?php foreach($v['SubMenu'] as $kk=>$vv){ ?>
		    				<li><a href="<?php echo $html->url($vv['OperatorMenu']['link']); ?>" class="second_nav am-collapsed"><?php echo $vv['OperatorMenuI18n']['name']; ?></a>
		    					</li>
		    				<?php } ?>
    					</ul>
    				</nav>
    			<?php } ?>
    		<?php } ?>
    	<?php } ?>
    </div>
  </div>
</div>
<div class="head_div" style="clear:both;"></div>
<script type="text/javascript">
$("#admin_username").dropdown({justify: '#admin_userinfo'});

$('#doc-oc-demo2 .am-cate-3').on("open.collapse.amui", function() {
	$("a.first_nav").addClass("am-open");
});

$('#doc-oc-demo2 .am-cate-3').on("close.collapse.amui", function() {
	$("a.first_nav").removeClass("am-open");
});
</script>