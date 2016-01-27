<div id="doc-oc-demo2" class="am-user-menu am-offcanvas">
  <div class="am-offcanvas-bar">
    <ul class="am-list admin-sidebar-list">
      <li class="admin-user-img">
    	<img title="<?php echo $user_list['User']['name']; ?>" src="<?php echo isset($user_list['User']['img01'])&&$user_list['User']['img01']!=""?$user_list['User']['img01']:"/theme/default/img/no_head.png";?>" />
    	<!-- 头像编辑链接浮动窗口 -->
<div class="am-popover am-popover-bottom" id="am-user-avatar-offcanvas">
	<div class="am-popover-inner"><a href="<?php echo $html->url('/users/edit_headimg'); ?>"><?php echo $ld['editing_avatar'] ?></a></div>
</div>
<!-- 头像编辑链接浮动窗口 -->
      </li>
      <li><a href="<?php echo $html->url('/user_socials/index/'.$user_list['User']['id']); ?>"><span class="am-icon-home"></span>&nbsp;<?php echo $ld['user_center'] ?></a></li>
      <li class="admin-parent">
        <a data-am-collapse="{target: '#collapse-nav'}" class="am-cf"><span class="am-icon-file"></span>&nbsp;<?php echo $ld['account_account'] ?><span class="am-icon-angle-right am-fr am-margin-right"></span></a>
        <ul id="collapse-nav" class="am-list am-collapse admin-sidebar-sub am-in">
          <li><a class="am-cf" href="<?php echo $html->url('/users/edit'); ?>"><span class="am-icon-user"></span>&nbsp;<?php echo $ld['account_profile'] ?><span class="am-icon-star am-fr am-margin-right admin-icon-yellow"></span></a></li>
          <li><a href="<?php echo $html->url('/users/edit_pwd'); ?>"><span class="am-icon-pencil-square-o"></span>&nbsp;<?php echo $ld['change_password'] ?></a></li>
          <li><a href="<?php echo $html->url('/addresses'); ?>"><span class="am-icon-th"></span>&nbsp;<?php echo $ld['account_address_book'] ?><span class="am-badge am-badge-secondary am-margin-right am-fr"></span></a></li>
          <li><a href="<?php echo $html->url('/user_socials/privacy_settings'); ?>"><span class="am-icon-bug"></span>&nbsp;<?php echo $ld['privacy_settings'] ?></a></li>
          <li><a href="<?php echo $html->url('/user_socials/share_settings'); ?>"><span class="am-icon-slideshare"></span>&nbsp;<?php echo $ld['share_settings'] ?></a></li>
          <li><a href="<?php echo $html->url('/users/deposit'); ?>"><span class="am-icon-money"></span>&nbsp;<?php echo $ld['user_deposit'] ?></a></li>
		  <li><a href="<?php echo $html->url('/users/enquiries'); ?>"><span class="am-icon-table"></span>&nbsp;<?php echo $ld['enquiry'] ?></a></li>
          <?php if(constant("Product")=="AllInOne" && False){ ?><li><a href="<?php echo $html->url('/user_styles/'); ?>"><span class="am-icon-table"></span>&nbsp;<?php echo $ld['user_template'] ?></a></li><?php } ?>
        </ul>
      </li>
      <li><a href="<?php echo $html->url('/favorites'); ?>"><span class="am-icon-heart"></span>&nbsp;<?php echo $ld['account_my_wishlist'] ?></a></li>
      <?php if(constant("Product")=="AllInOne"){ ?>
      <li><a href="<?php echo $html->url('/orders'); ?>"><span class="am-icon-shopping-cart"></span>&nbsp;<?php echo $ld['account_orders'] ?></a></li>
      <li><a href="<?php echo $html->url('/coupons/user_index'); ?>"><span class="am-icon-gift"></span>&nbsp;<?php echo$ld['user_002'];?></a></li>
      <?php } ?>
      <li><a href="<?php echo $html->url('/users/logout'); ?>"><span class="am-icon-sign-out"></span>&nbsp;<?php echo $ld['logout'] ?></a></li>
    </ul>
  </div>
</div>
