<?php
echo $javascript->link('/skins/default/js/calendar/language/'.$backend_locale);
echo $javascript->link('/skins/default/js/calendar/calendar');
echo $html->css('/skins/default/css/calendar/calendar');
echo $javascript->link('/skins/default/js/utils');
echo $javascript->link('/skins/default/js/jscolor/jscolor');

echo $html->css('/skins/default/css/jqm/css/jquery.mobile.min');
echo $html->css('/skins/default/css/jqm/css/jquery.mobile.theme.min');
echo $html->css('/skins/default/css/jqm/css/base');
echo $html->css('/skins/default/css/jqm/css/index');
//echo $html->css('jqm/css/article');
echo $html->css('/skins/default/css/jqm/css/jquery.mobile.theme.min');
echo $javascript->link('/skins/default/js/jqm/jquery.min');
?>
<?php echo $html->css('/skins/default/css/codemirror');?>
<?php echo $html->css('/skins/default/css/docs');?>
<?php echo $javascript->link('/skins/default/js/codemirror');?>
<?php echo $javascript->link('/skins/default/js/css');?>
<style>
	.tablemain { width:60%; margin-right: 340px; }
	#tablemain .lefttable { width:100%; }
	.tablemain .lefttable th { width:60%; padding-right:8px; line-height: 31px; *line-height: 36px; }
	.tablemain .lefttable th::after { content:""; }
	.tablemain .lefttable td { padding:0; padding:3px 5px; }
	.tablemain .lefttable input[type="text"] { width:100%; width:95%; font-size: 16px; line-height: 24px; border-color:#D8D8D8; border-width: 1px; border-style:none; padding-left:5px; /*margin-left:5px; text-indent:5px; /*border-left:none; border-right:none; border-top:none;*/ *height:30px; }
	.tablemain .lefttable td input[type="text"]:last-child { border-top-style:solid; }
	.tablemain .lefttable td input[type="text"]:first-child { border-top-style:none; }
	#tablemain textarea { width:81%; height:200px; *width:300px; }
	.tablemain .btnouter { *padding-right:6px; }

	.tablemain .alonetable{ float: left; }
	.tablemain .alonetable td input { width:98%; *width:300px; }

	.ui-header-fixed, .ui-footer-fixed { position:static; }
	.ui-mobile [data-role="page"], .ui-mobile [data-role="dialog"], .ui-page { display:block; position:static; }
	.ui-icon-searchfield::after { background:none; }
.homeprod .ui-collapsible-content .ui-listview .ui-icon-arrow-r{}
	.ui-icon, .ui-icon-searchfield::after{}
	.ui-header .ui-btn-left, .ui-footer .ui-btn-left { left: auto; top: auto; }
	.ui-header .ui-btn {margin-top: 5px;margin-left: 5px;}

	/*.per_date .ui-btn-inner, .next_date .ui-btn-inner { background:#F1F1F1; }*/
	.per_date .ui-btn, .next_date .ui-btn { /**background:#F1F1F1;*/ filter:alpha(opacity=0); }

	.homeproducttopic .ui-collapsible-content .z {background: url(../images/arrowb-left.png) no-repeat 0 50%,url(../images/arrowb-right.png) no-repeat 100% 50%;}

	#phonereview { width:50%;width: 330px;position: absolute;top: 30px;top: 0;right: 30px; }
	#phonereview  {  }
	.phonereview { display:none; *visibility:hidden; }
	.phonereviewshow { display:block; *visibility:visible; }

	.ui-listview .ui-btn { /*background:none; *background:url(http://thm.ioco.cn/themed/admin/img/blank.gif); */ *zoom:1; }
	.pro_img,.picdate,.pro_date,.ui-collapsible-heading .ui-btn,.homeproducttopic .ui-collapsible-content{ *zoom:1; }
	.mobile_css .tablemain .lefttable th,.mobile_css .tablemain .righttable th,.mobile_css .tablemain .alonetable th{text-align: right;width: 55%;}
	.mobile_css .tablemain .lefttable td,.mobile_css .tablemain .righttable td,.mobile_css .tablemain .alonetable td .color{margin-bottom:5px;}
</style>
<style id="phonestyle">
</style>
<style id="phonestylecustom">
</style>
<div class="mobile_css">
<?php echo $form->create('themes',array('action'=>'mobile_css_config/'));?>
<div id="tablemain" class="tablemain">
	<div>
		<h2><?php echo $ld['common_part']?></h2>
		<div class="show_border">
			<table class="lefttable">
				<tr>
					<th><?php echo $ld['head_background_color']?><br /><?php echo $ld['head_gradient_background']?></th>
					<td><input type="text" class="color" name="header_background_color1" id="header_background_color1" value="<?php echo isset($this->data)?$this->data['header_background_color1']:'';?>" /><input type="text" class="color" name="header_background_color2" id="header_background_color2" value="<?php echo isset($this->data)?$this->data['header_background_color2']:'';?>"  /></td>
				</tr>
				<tr>
					<th><?php echo $ld['head_font_color']?><br /><?php echo $ld['head_font_shadow']?></th>
					<td><input type="text" class="color" name="header_font_color" id="header_font_color" value="<?php echo isset($this->data)?$this->data['header_font_color']:'';?>"  /><input type="text" class="color" name="header_font_shadow_color" id="header_font_shadow_color" value="<?php echo isset($this->data)?$this->data['header_font_shadow_color']:'';?>"  /></td>
				</tr>
				<tr>
					<th><?php echo $ld['head_border_color']?></th>
					<td><input type="text" class="color" name="header_frame_color" id="header_frame_color" value="<?php echo isset($this->data)?$this->data['header_frame_color']:'';?>"  /></td>
				</tr>
				<tr>
					<th><?php echo $ld['bottom_background_color']?><br /><?php echo $ld['bottom_gradient_background']?></th>
					<td><input type="text" class="color" name="foot_background_color1" id="foot_background_color1" value="<?php echo isset($this->data)?$this->data['foot_background_color1']:'';?>"  /><input type="text" class="color" name="foot_background_color2" id="foot_background_color2" value="<?php echo isset($this->data)?$this->data['foot_background_color2']:'';?>"  /></td>
				</tr>
				<tr>
					<th><?php echo $ld['bottom_font_color']?><br /><?php echo $ld['bottom_font_shadow']?></th>
					<td><input type="text" class="color" name="foot_font_color" id="foot_font_color" value="<?php echo isset($this->data)?$this->data['foot_font_color']:'';?>"  /><input type="text" class="color" name="foot_font_shadow_color" id="foot_font_shadow_color" value="<?php echo isset($this->data)?$this->data['foot_font_shadow_color']:'';?>"  /></td>
				</tr>
				<tr>
					<th><?php echo $ld['bottom_border_color']?></th>
					<td><input type="text" class="color" name="foot_frame_color" id="foot_frame_color" value="<?php echo isset($this->data)?$this->data['foot_frame_color']:'';?>"  /></td>
				</tr>
				<tr>
					<th><?php echo $ld['highlight_background']?><br /><?php echo $ld['bottom_highlight_background']?></th>
					<td><input type="text" class="color" name="foot_hightlight_background_color1" id="foot_hightlight_background_color1" value="<?php echo isset($this->data)?$this->data['foot_hightlight_background_color1']:'';?>"  /><input type="text" class="color" name="foot_hightlight_background_color2" id="foot_hightlight_background_color2" value="<?php echo isset($this->data)?$this->data['foot_hightlight_background_color2']:'';?>"  /></td>
				</tr>
				<tr>
					<th><?php echo $ld['bottom_highlight_font']?></th>
					<td><input type="text" class="color" name="foot_hightlight_font_color" id="foot_hightlight_font_color" value="<?php echo isset($this->data)?$this->data['foot_hightlight_font_color']:'';?>"  /></td>
				</tr>
			</table>
		</div>
		<div class="btnouter">
			<input type="submit" value="<?php echo $ld['d_submit']?>" /><input type="reset" value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
	
	<div>
		<h2>样式编辑</h2>
		<div class="show_border">
			<textarea id="mobile_comment_css" name="mobile_comment_css" style="width:98%;height:600px;max-width:98%;min-width:98%;min-height:600px;"><?php echo isset($this->data['mobile_comment_css'])?$this->data['mobile_comment_css']:'';; ?></textarea>
		</div>
		<div class="btnouter">
			<input type="submit" value="<?php echo $ld['d_submit']?>" /><input type="reset" value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
</div>
<?php echo $form->end();?>
<div id="phonereview">
	<div id="phonereview0" class="phonereviewshow phonereview">
		<div data-role="page" id="page" data-url="page" tabindex="0" class="ui-page ui-body-c ui-page-header-fixed ui-page-footer-fixed ui-page-active" style="min-height: 444px; padding-top: 0; padding-bottom: 0; ">
			<div data-role="header" class="header ui-header ui-bar-a ui-header-fixed slidedown" data-position="fixed" data-id="myheader" role="banner">
				<h1 id="shopName" class="ui-title" role="heading" aria-level="1">爱屋格林</h1>
			</div>
			<div data-role="content" class="ui-content" role="main">
				<div data-role="fieldcontain" class="ui-field-contain ui-body ui-br">
					<div class="ui-input-search ui-shadow-inset ui-btn-corner-all ui-btn-shadow ui-icon-searchfield ui-body-c"><input type="text" data-type="search" name="keyword" id="keyword" value="" placeholder="请输入" class="ui-input-text ui-body-c"><a href="javascript:;" class="ui-input-clear ui-btn ui-btn-up-c ui-shadow ui-btn-corner-all ui-fullsize ui-btn-icon-notext ui-input-clear-hidden" title="clear text" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-icon="delete" data-iconpos="notext" data-theme="c" data-mini="false"><span class="ui-btn-inner ui-btn-corner-all"><span class="ui-btn-text">clear text</span><span class="ui-icon ui-icon-delete ui-icon-shadow">&nbsp;</span></span></a></div>
					<a onclick="getProducts('')" class="ui-link"> </a>
				</div>
				<div data-role="collapsible" data-collapsed="false" data-content-theme="c" data-theme="c" class="homeproducttopic ui-collapsible"><h3 class="ui-collapsible-heading"><a href="javascript:;" class="ui-collapsible-heading-toggle ui-btn ui-fullsize ui-btn-icon-left ui-corner-top ui-btn-up-c" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-icon="plus" data-iconpos="left" data-theme="c" data-mini="false"><span class="ui-btn-inner ui-corner-top"><span class="ui-btn-text">商品<span class="ui-collapsible-heading-status"> click to collapse contents</span></span><span class="ui-icon ui-icon-shadow ui-icon-minus">&nbsp;</span></span></a></h3><div class="ui-collapsible-content ui-body-c ui-corner-bottom" aria-hidden="false">

					<div class="z">
						<div class="za">
							<div class="zaa" id="asdf">
								<div id="homeProduct">
									<a onclick="getProductView('37')" href="javascript:;" data-ajax="false"><img src="http://img.ioco.cn/i/2011/11/img_ioco01_com/www.via1998.com/small/1/191add73384a48fcfca03f164fd261b8d.jpg_310x310[1]" alt="EVERGREEN 小咖啡杯套装 陶瓷咖啡杯 欧式星巴克风"><span>￥98.00</span></a>
									<a onclick="getProductView('36')" href="javascript:;" data-ajax="false"><img src="http://img.ioco.cn/i/2011/11/img_ioco01_com/www.via1998.com/small/1/1173f418db9fae53e8bd7a53906ab9e4a.jpg_310x310[1]" alt="爱屋.格林 不锈钢架小咖啡杯套装 陶瓷咖啡杯 欧式风格3MG101094"><span>￥98.00</span></a>
									<a onclick="getProductView('34')" href="javascript:;" data-ajax="false"><img src="http://img.ioco.cn/i/2011/11/img_ioco01_com/www.via1998.com/small/1/1f370379cc2c26fc55c2d86079bd87fa4.jpg" alt="艺术葡萄酒杯 蛋形玻璃杯 杯具3SL3294R"><span>￥112.00</span></a>
								</div>
							</div>
						</div>
					</div>
				</div></div>
				<div data-role="collapsible" data-collapsed="false" data-content-theme="c" data-theme="c" class="homeprod ui-collapsible" id="topic_div"><h3 class="ui-collapsible-heading"><a href="javascript:;" class="ui-collapsible-heading-toggle ui-btn ui-fullsize ui-btn-icon-left ui-corner-top ui-btn-up-c" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-icon="plus" data-iconpos="left" data-theme="c" data-mini="false"><span class="ui-btn-inner ui-corner-top"><span class="ui-btn-text">专题<span class="ui-collapsible-heading-status"> click to collapse contents</span></span><span class="ui-icon ui-icon-shadow ui-icon-minus">&nbsp;</span></span></a></h3><div class="ui-collapsible-content ui-body-c ui-corner-bottom" aria-hidden="false">
					<ul data-role="listview" data-inset="true" id="topic_list" class="ui-listview ui-listview-inset ui-corner-all ui-shadow">
						<li data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-corner-top ui-corner-bottom ui-btn-up-c"><div class="ui-btn-inner ui-li ui-corner-top"><div class="ui-btn-text"><a href="javascript:;" class="ui-link-inherit">圣诞专题</a></div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div></li>
					</ul>
				</div></div>
				<div data-role="collapsible" data-collapsed="false" data-content-theme="c" data-theme="c" class="homearticle ui-collapsible"><h3 class="ui-collapsible-heading"><a href="javascript:;" class="ui-collapsible-heading-toggle ui-btn ui-fullsize ui-btn-icon-left ui-corner-top ui-btn-up-c" data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="span" data-icon="plus" data-iconpos="left" data-theme="c" data-mini="false"><span class="ui-btn-inner ui-corner-top"><span class="ui-btn-text">最新消息<span class="ui-collapsible-heading-status"> click to collapse contents</span></span><span class="ui-icon ui-icon-shadow ui-icon-minus">&nbsp;</span></span></a></h3><div class="ui-collapsible-content ui-body-c ui-corner-bottom" aria-hidden="false">
					<ul data-role="listview" data-inset="true" id="homeArticle" class="ui-listview ui-listview-inset ui-corner-all ui-shadow">
						<li data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-up-c ui-btn-icon-right ui-li-has-arrow ui-li"><div class="ui-btn-inner ui-li"><div class="ui-btn-text"><a data-ajax="false" onclick="getArticleView('61')" href="javascript:;" class="ui-link-inherit">联系我们</a></div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div></li>
						<li data-corners="false" data-shadow="false" data-iconshadow="true" data-wrapperels="div" data-icon="arrow-r" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-up-c ui-btn-icon-right ui-li-has-arrow ui-li ui-corner-bottom"><div class="ui-btn-inner ui-li"><div class="ui-btn-text"><a href="javascript:;" data-inline="true" class="ui-link-inherit">+更多新闻</a></div><span class="ui-icon ui-icon-arrow-r ui-icon-shadow">&nbsp;</span></div></li></ul>
				</div></div>
			</div>
			<div data-role="footer" data-position="fixed" data-id="myfooter" class="ui-footer ui-bar-a ui-footer-fixed slideup" role="contentinfo">
				<div class="work">
				<div class="workselected height33"><a href="javascript:;" class="fl1 ui-link" data-ajax="false"><span>首页</span></a></div>
				<div class="height33"><a href="javascript:;" class="fl2 ui-link" data-ajax="false"><span>分类</span></a></div>
				<div class="contact_status height33" style="display: none; "><a href="javascript:;" class="fl3 ui-link" data-ajax="false"><span>联系我们</span></a></div>
				<div class="height33"><a href="javascript:;" class="fl4 ui-link" data-ajax="false"><span>关于我们</span></a></div>
				</div>
			</div>
		</div>
	</div>

	
</div>
</div>

<script type="text/javascript">
var editor = CodeMirror.fromTextArea(document.getElementById("mobile_comment_css"), {
        lineNumbers: true
      });

function providers_input_checks(){
	var providers_name_obj = document.getElementById("providers_name");
	if(providers_name_obj.value==""){
		alert("<?php echo $ld['supply_018']?>");
		return false;
	}
	return true;
}
var phonecolor;
var phonecolorstyle="";
var phonebgimage="";
var phonecolorFn = function(){
phonecolor = {
header_font_color:[".ui-title","color",$("#header_font_color").val()],
header_font_shadow_color:[".ui-title","text-shadow",$("#header_font_shadow_color").val()],
header_background_color1:[".ui-header","background",$("#header_background_color1").val(),$("#header_background_color2").val()],
header_frame_color:[".ui-header","border-color",$("#header_frame_color").val()],
title_font_color:[".ui-collapsible-heading .ui-btn","color",$("#title_font_color").val()],
title_font_shadow_color:[".ui-collapsible-heading .ui-btn","text-shadow",$("#title_font_shadow_color").val()],
title_background_color1:[".ui-collapsible-heading .ui-btn","background",$("#title_background_color1").val(),$("#title_background_color2").val()],
title_frame_color:[".ui-collapsible-heading .ui-btn,.ui-collapsible-content","border-color",$("#title_frame_color").val()],
home_list_font_color:[".ui-collapsible-content .ui-listview .ui-btn .ui-link-inherit","color",$("#home_list_font_color").val()],
home_list_font_shadow_color:[".ui-collapsible-content .ui-listview .ui-btn .ui-link-inherit","text-shadow",$("#home_list_font_shadow_color").val()],
home_list_background_color1:[".ui-collapsible-content .ui-listview .ui-btn","background",$("#home_list_background_color1").val(),$("#home_list_background_color2").val()],
home_list_frame_color:[".ui-collapsible-content .ui-listview .ui-btn","border-color",$("#home_list_frame_color").val()],
foot_font_color:[".ui-footer .ui-link","color",$("#foot_font_color").val()],
foot_font_shadow_color:[".ui-footer .ui-link","text-shadow",$("#foot_font_shadow_color").val()],
foot_background_color1:[".ui-footer","background",$("#foot_background_color1").val(),$("#foot_background_color2").val()],
foot_frame_color:[".ui-footer","border-color",$("#foot_frame_color").val()],
foot_hightlight_background_color1:[".ui-footer .workselected","background",$("#foot_hightlight_background_color1").val(),$("#foot_hightlight_background_color2").val()],
foot_hightlight_font_color:[".ui-footer .workselected .ui-link","color",$("#foot_hightlight_font_color").val()],
home_product_background_color1:[".homeproducttopic .ui-collapsible-content","background",$("#home_product_background_color1").val(),$("#home_product_background_color2").val()],
home_product_frame_color:[".homeproducttopic .ui-collapsible-content a","border-color",$("#home_product_frame_color").val()],
home_product_price_background_color1:[".homeproducttopic .ui-collapsible-content a span","background",$("#home_product_price_background_color1").val(),$("#home_product_price_background_color2").val()],
home_product_price_font_color:[".homeproducttopic .ui-collapsible-content a span","color",$("#home_product_price_font_color").val()],
home_product_price_font_shadow_color:[".homeproducttopic .ui-collapsible-content a span","text-shadow",$("#home_product_price_font_shadow_color").val()],
head_button_background_color1:[".ui-header .ui-btn, .ui-footer .ui-btn","background",$("#head_button_background_color1").val(),$("#head_button_background_color2").val()],
head_button_font_color:[".ui-header .ui-btn, .ui-footer .ui-btn","color",$("#head_button_font_color").val()],
head_button_font_shadow_color:[".ui-header .ui-btn, .ui-footer .ui-btn","text-shadow",$("#head_button_font_shadow_color").val()],
head_button_frame_color:[".ui-header .ui-btn, .ui-footer .ui-btn","border-color",$("#head_button_frame_color").val()],
list_background_color1:[".ui-listview .ui-btn","background",$("#list_background_color1").val(),$("#list_background_color2").val()],
list_font_color:[".ui-listview .ui-btn .ui-link-inherit","color",$("#list_font_color").val()],
list_font_shadow_color:[".ui-listview .ui-btn .ui-link-inherit","text-shadow",$("#list_font_shadow_color").val()],
list_frame_color:[".ui-listview .ui-btn","border-color",$("#list_frame_color").val()],
product_img_background_color1:[".pro_img","background",$("#product_img_background_color1").val(),$("#product_img_background_color2").val()],
product_img_background_frame_color:[".pro_img","border-color",$("#product_img_background_frame_color").val()],
product_img_frame_color:[".pro_img span","border-color",$("#product_img_frame_color").val()],
product_attr_background_color1:[".picdate","background",$("#product_attr_background_color1").val(),$("#product_attr_background_color2").val()],
product_attr_font_color:[".picdate","color",$("#product_attr_font_color").val()],
product_attr_font_shadow_color:[".picdate","text-shadow",$("#product_attr_font_shadow_color").val()],
product_attr_price_color:[".picdate .newpic i","color",$("#product_attr_price_color").val()],
product_attr_price_shadow_color:[".picdate .newpic i","text-shadow",$("#product_attr_price_shadow_color").val()],
product_attr_frame_color:[".picdate","border-color",$("#product_attr_frame_color").val()],
product_desc_background_color1:[".pro_date","background",$("#product_desc_background_color1").val(),$("#product_desc_background_color2").val()],
product_desc_font_color:[".pro_date","color",$("#product_desc_font_color").val()],
product_desc_font_shadow_color:[".pro_date","text-shadow",$("#product_desc_font_shadow_color").val()],
product_desc_frame_color:[".pro_date","border-color",$("#product_desc_frame_color").val()],
next_product_name_color:[".per_date span, .next_date span","color",$("#next_product_name_color").val()],
next_product_name_shadow_color:[".per_date span, .next_date span","text-shadow",$("#next_product_name_shadow_color").val()],
next_product_price_color:["#last_pro_price, #next_pro_price","color",$("#next_product_price_color").val()],
next_product_price_shadow_color:["#last_pro_price, #next_pro_price","text-shadow",$("#next_product_price_shadow_color").val()],
next_color:[".per_date .ui-btn .ui-btn-text, .next_date .ui-btn .ui-btn-text","color",$("#next_color").val()],
next_shadow_color:[".per_date .ui-btn .ui-btn-text, .next_date .ui-btn .ui-btn-text","text-shadow",$("#next_shadow_color").val()],
product_button_background_color1:[".per_date .ui-btn, .next_date .ui-btn","background",$("#product_button_background_color1").val(),$("#product_button_background_color2").val()],
product_button_frame_color:[".per_date .ui-btn, .next_date .ui-btn","border-color",$("#product_button_frame_color").val()]
};
phonebgimage = [
".ui-collapsible-heading .ui-icon-minus",".ui-collapsible-heading .ui-icon-plus",".homeproducttopic .ui-collapsible-content .z",".homeproducttopic .ui-collapsible-content .z",".homearticle .ui-collapsible-content .ui-listview .ui-icon-arrow-r,.ui-btn-icon-left .ui-btn-inner .ui-icon-arrow-r, .ui-btn-icon-right .ui-btn-inner .ui-icon-arrow-r"
];
	phonecolorstyle="";
	for(var item in phonecolor){
		if(phonecolor[item][2]){
			if(phonecolor[item][1]=="text-shadow"){
				phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + phonecolor[item][2] + "!important}";
			}else if(phonecolor[item][3]==''){
				phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + phonecolor[item][2] + "!important}";
			}else{
				if(phonecolor[item][1]=="color"){
					phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + phonecolor[item][2] + "!important}";
				}else if(phonecolor[item][1]=="background"){
					phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + "-webkit-linear-gradient(top," + phonecolor[item][2] + ',' + phonecolor[item][3] + ")" + "!important}";
					phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + "-moz-linear-gradient(top," + phonecolor[item][2] + ',' + phonecolor[item][3] + ")" + "!important}";
					phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + "-ms-linear-gradient(top," + phonecolor[item][2] + ',' + phonecolor[item][3] + ")" + "!important}";
					phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + "-o-linear-gradient(top," + phonecolor[item][2] + ',' + phonecolor[item][3] + ")" + "!important}";
					phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + "linear-gradient(top," + phonecolor[item][2] + ',' + phonecolor[item][3] + ")" + "!important}";
					phonecolorstyle += phonecolor[item][0] + "{" + "filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='" + phonecolor[item][2] + "', endColorstr='" + phonecolor[item][3] + "')" + "!important;*background:none;}";
				}else if(phonecolor[item][1]=="border-color"){
					phonecolorstyle += phonecolor[item][0] + "{" + phonecolor[item][1] + ":" + phonecolor[item][2] + "!important}";
				}
			}
		}
	}

	$("#tablemain .alonetable input.background").each(function(index, element) {
		if (index==2){
			phonecolorstyle += phonebgimage[index] + "{ background-image:url(" + $(this).val() + ")";
		}else if (index==3 && $(this).val()){
			phonecolorstyle += ",url(" + $(this).val() + ");}";
		}else if (index==3) {
			phonecolorstyle += ";}";
		}else if (index==4) {
			phonecolorstyle += phonebgimage[index] + "{ background:url(" + $(this).val() + ") no-repeat 50%;}";
		}else {
			phonecolorstyle += phonebgimage[index] + "{ background-image:url(" + $(this).val() + ")}";
		}
		//$("#next_product_name_color").val()
	});
	if($.browser.msie){
			$("#phonestyle").remove();
	        $('<style type="text/css" id="phonestyle">' + phonecolorstyle + '</style>').appendTo("head");
	} else {
		$("#phonestyle").html(phonecolorstyle);
	}
}
$(document).ready(function(){
	$(".mobile_css").css("min-height",$("#phonereview").height())
	$("#phonereview").appendTo("article");
/*
	if($.browser.msie){
			$("#phonestylecustom").remove();
	        $('<style type="text/css" id="phonestylecustom">' + $("#custom_css").html() + '</style>').appendTo("head");
	} else {
		$("#phonestylecustom").html($("#custom_css").html());
	}
*/
	phonecolorFn();
});

$("input.color").on("blur",phonecolorFn);
$("input.background").on("blur",phonecolorFn);

$(document).on("click","#tablemenu li",function(){
	$("#tablemenu li").each(function(index, element) {
		if($(this)[0].className.match("show")){
			var tmp = index;
			if(tmp){
				tmp--;
			}
			$("#phonereview .phonereviewshow").removeClass("phonereviewshow");
			$("#phonereview .phonereview").eq(tmp).addClass("phonereviewshow");
		};
	});
});

$(document).on("click","#tablemain h2",function(){
	$("#tablemenu li").each(function(index, element) {
		if($(this)[0].className.match("show")){
			var tmp = index;
			if(tmp){
				tmp--;
			}
			$("#phonereview .phonereviewshow").removeClass("phonereviewshow");
			$("#phonereview .phonereview").eq(tmp).addClass("phonereviewshow");
		};
	});

});
</script>
