<style>
#sousuo{padding-left:12px;padding-right:12px;}

</style>
<header id="amz-header">
  <div class="am-g" style="padding-right:8px;">
    <?php if($this->params['controller']=="categories" && $this->params['action']="view"){ ?>
        <button style="margin:1rem 0; float:left;margin-left:5px;" class="am-btn am-btn-sm am-btn-secondary am-show-sm-only" data-am-offcanvas="{target: '#prodcut_category', effect: 'push'}"><span >商品分类</span> </button>
    <?php } ?>
    <h1 class="am-topbar-brand am-hide-sm-only">
        <?php if(!empty($configs['shop_logo'])){
          echo $svshow->link($svshow->image($configs['shop_logo'],LOCALE),"/",array("style"=>"height:50px;display:block;overflow:hidden;position:relative;top:-1px;"));
        }else{
          echo $svshow->link($svshow->image('/theme/default/img/'.$template_style.'/logo.jpg',LOCALE),"/",array("style"=>"height:50px;display:block;overflow:hidden;position:relative;top:-3px;"));
        }?>
    </h1>
    <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-secondary am-show-sm-only am-btn-lg"
            data-am-collapse="{target: '#collapse-head'}" style="margin-right:8px;padding:8px 15px;padding-top:7px;"><span class="am-sr-only" >导航切换</span> <span
        class="am-icon-bars changenav"></span></button>
<!-- sm搜索框 -->
<div class="am-show-sm-only am-fr">
<button
  type="button"
  class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-secondary am-show-sm-only am-btn-lg"
  data-am-modal="{target: '#doc-modal-1', closeViaDimmer: 0,height: 105}" style="padding:7px 15px;padding-bottom:8px;">
  <span class="am-icon-search"></span>
</button>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="doc-modal-1">
  <div class="am-modal-dialog">
    <div class="am-modal-hd"><span>搜索</span>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close style="top:0">&times;</a>
    </div>
    <div class="am-modal-bd am-u-sm-12">
          <div class="am-input-group am-u-sm-12 am-input-group-sm am-fr am-search-sm" >
        <input type="text" placeholder="请输入关键字" autofocus="autofocus" class="am-form-field" AUTOCOMPLETE="OFF" id="search_keyword" name="keyword" value="<?php echo isset($keyword)?$keyword:''; ?>" />
        <span class="am-input-group-btn">
          <button class="am-btn am-btn-secondary am-btn-sm" style="border:none"  type="button" ><span class="am-icon-search"></span></button>
        </span>
      </div>
       <div class="am-input-group am-u-sm-12 search_date"></div>
    </div>
  </div>
</div>
</div>

    <div class="am-collapse am-topbar-collapse" id="collapse-head">
      <?php if(isset($navigations['T'])){
      ?>
      <ul class="am-nav am-nav-pills am-topbar-nav am-top-nav">
      <?php $navigations_t_count=count($navigations['T']);
        foreach($navigations['T'] as $k=>$v){?>
      <?php if(isset($v['SubMenu']) && sizeof($v['SubMenu']) >0) {  //含二级菜单 
          ?>
          <li class="am-dropdown" data-am-dropdown>
          <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
            <?php echo (isset($v['NavigationI18n']['name']))?$v['NavigationI18n']['name']:"-";?><span class="am-icon-caret-down" style="margin-left:8px;"></span>
          </a>
          <ul class="am-dropdown-content" style="margin-top:-2px;left:-20px;">
            <li class="am-dropdown-header" style="padding:6px 10px"><?php echo $v['NavigationI18n']['name'];?></li>
            <?php foreach($v['SubMenu'] as $kk=>$vv){ ?>
            <li><?php echo $svshow->link($vv['NavigationI18n']['name'],$vv['NavigationI18n']['url'],array('target'=>$vv['Navigation']['target']));?></li>
            <?php }  // foreach top2?>
          </ul>
        </li>
  <?php }?>
  <?php if(!isset($v['SubMenu']) ) { ?>
    <li><?php echo $svshow->link($v['NavigationI18n']['name'],$v['NavigationI18n']['url'],array('target'=>$v['Navigation']['target']));?></li>
        <?php }?>
          
        <?php } // foreach top1?>
      </ul>
  <?php }?>
    <div class="am-topbar-right am-show-sm-only am-topbar am-container am-bottom-nav">
        <?php if(isset($navigations['B'])){?>
  <ul class="am-nav am-nav-pills am-topbar-nav" >
    <?php $navigations_t_count=count($navigations['B']);
      foreach($navigations['B'] as $k=>$v){?>
      <?php if(isset($v['SubMenu']) && sizeof($v['SubMenu']) >0) {  //含二级菜单 ?>
    <li class="am-dropdown am-dropdown-up" data-am-dropdown>
      <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
        <?php echo (isset($v['NavigationI18n']['name']))?$v['NavigationI18n']['name']:"-";?><span class="am-icon-caret-up"></span>
      </a>
      <ul class="am-dropdown-content">
        <li class="am-dropdown-header" style="padding:6px 10px"><?php echo $v['NavigationI18n']['name'];?></li>
        <?php foreach($v['SubMenu'] as $kk=>$vv){ ?>
          <li><?php echo $svshow->link($vv['NavigationI18n']['name'],$vv['NavigationI18n']['url'],array('target'=>$vv['Navigation']['target']));?></li>
        <?php }  // foreach top2?>
      </ul>
    </li>
  <?php }?>
  <?php if(!isset($v['SubMenu']) ) { ?>
  <li><?php echo $svshow->link($v['NavigationI18n']['name'],$v['NavigationI18n']['url'],array('target'=>$v['Navigation']['target']));?></li>
    <?php }?>
  <?php } // foreach top1?>
  </ul>
 <?php }?>
    </div>
  <?php if(count($languages)>1){?>
  <div class="am-topbar-right">
      <a href="javascript:void(0)" class="language_change" data-am-modal="{target: '#language',width: 400, height: 225}"><?php echo $ld['language_switcher'];?></a>
    </div>
    <?php }?>
  <!-- 用户中心登录按钮 -->
  <?php if(constant("Version")=="allinone"){?>
    <div id="shoppingcart"><?php echo $svshow->link(empty($_SESSION['svcart']['products'])? $ld['cart'].'(0)':$ld['cart'].'('.count($_SESSION['svcart']['products']).')',"/carts");?></div>
  <?php }?> 
  <?php if(isset($_SESSION['User']['User']) && !empty($_SESSION['User']['User'])){?>
    <div class="am-topbar-right">
        <button class="am-btn am-btn-primary am-topbar-btn am-btn-sm" onclick="users_logout()"><span class="am-icon-arrow-right"></span>&nbsp;<?php echo $ld['logout'];?></button>
      </div>
      <div class="am-topbar-right">
        <button class="am-btn am-btn-secondary am-topbar-btn am-btn-sm" onclick="users_edit()"><span class="am-icon-user"></span> <?php echo $_SESSION['User']['User']['name'];?></button>
      </div>
  <?php }else{?>
    <?php if(isset($configs['enable_registration_closed']) && $configs['enable_registration_closed']==0){?>
      <div class="am-topbar-right am-login-btn">
        	<button class="am-btn am-btn-primary am-topbar-btn am-btn-sm" onclick="users_login()"><span class="am-icon-user"></span>&nbsp;<?php echo $ld['login'];?></button>
      </div>
    <?php }?>
  <?php }?>
  <!-- 用户中心登录按钮end -->
    <!-- 搜索框 -->
    <div class="am-topbar-right am-u-lg-2 am-u-md-3 am-hide-sm-only am-search" >
      <form action="<?php echo $html->url('/searchs/keyword'); ?>" method="get" id="am-search-form">
      <div class="am-input-group am-input-group-sm am-u-lg-12 am-u-md-11 am-u-sm-12 am-fr"  >
        <input type="text" class="am-form-field" AUTOCOMPLETE="OFF" id="search_keyword" name="keyword" value="<?php echo isset($keyword)?$keyword:''; ?>" />
        <span class="am-input-group-btn">
          <button class="am-btn am-btn-secondary am-btn-sm"  type="button"><span  class="am-icon-search"></span></button>
        </span>
      </div>
      <div class="am-input-group am-u-sm-12 search_date"></div>
    </form>
  </div>
     <!-- 搜索框 end -->
    </div>
  </div>



 <!-- 搜索框 end -->
  <input type='hidden' id='local' value="<?php echo LOCALE;?>">
</header>
<div style="clear:both;"></div>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="language">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
    <?php echo $ld['language_switcher'];?>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
  <!-- 多语言选择 -->
  <?php if(count($languages)>1){?>
    <div id="language">
    <?php $languages_count=count($languages);$i=0;foreach($languages as $k=>$v){?>
    
    <?php echo $svshow->link($v['Language']['name'],(LOCALE==$k)?'javascript:void(0);':$v['Language']['url'],array('class'=>(LOCALE==$k)?'color':'am-btn am-btn-secondary am-btn-lg'));?>
    <?php if($i<$languages_count-1){?>
    <br>
    <?php }?>
    <?php $i++;}?>
    <!-- 记录当前语言 -->
    <input type='hidden' id='local' value="<?php echo LOCALE;?>">
    <!-- 记录当前语言 end -->
    </div>
  <?php }?>
  <!-- 多语言选择 end -->
    </div>
  </div>
</div>
<script>
var url_base='<?php echo $this->base;?>';
var js_login_user_data=null;
<?php if(isset($_SESSION['User'])){ echo "js_login_user_data=".json_encode($_SESSION['User']).";"; } ?>
function users_login(){
window.location.href=url_base+'/users/login';
}
function users_edit(){
window.location.href=url_base+'/users/edit';
}
function users_logout(){
window.location.href=url_base+'/users/logout';
}
</script>