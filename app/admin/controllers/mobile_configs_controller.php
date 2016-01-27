<?php

/*****************************************************************************
 * Seevia 专题介绍
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
class MobileConfigsController extends AppController
{
    public $name = 'MobileConfigs';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript','Tinymce','fck','Ckeditor');
    public $uses = array('MobileAppTheme');
    public function wap_config($id = 1)
    {
        $this->navigations[] = array('name' => $this->ld['mobilephone_set'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['wap_set'],'url' => '/mobile_configs/wap_config/');

        $this->set('title_for_layout', $this->ld['wap_set'].' - '.$this->configs['shop_name']);
        $theme_info = $this->MobileAppTheme->find('first', array('conditions' => array('id' => $id)));
        if (!empty($theme_info)) {
            $this->data = json_decode($theme_info['MobileAppTheme']['css_array'], true);
        }
        if ($this->RequestHandler->isPost()) {
            $color_config = array();
            $color_config['id'] = 1;
            $color_config['css_array'] = json_encode($_REQUEST);
            $css = '';
            $css .= $this->color_change('.ui-title', 'color', $_REQUEST['header_font_color']);
            $css .= $this->color_change('.ui-title', 'text-shadow', $_REQUEST['header_font_shadow_color']);
            $css .= $this->color_change('.ui-header', 'background', $_REQUEST['header_background_color1'], $_REQUEST['header_background_color2']);
            $css .= $this->color_change('.ui-header', 'border-color', $_REQUEST['header_frame_color']);
            $css .= $this->color_change('.ui-collapsible-heading .ui-btn', 'color', $_REQUEST['title_font_color']);
            $css .= $this->color_change('.ui-collapsible-heading .ui-btn', 'text-shadow', $_REQUEST['title_font_shadow_color']);
            $css .= $this->color_change('.ui-collapsible-heading .ui-btn', 'background', $_REQUEST['title_background_color1'], $_REQUEST['title_background_color2']);
            $css .= $this->color_change('.ui-collapsible-heading .ui-btn,.ui-collapsible-content', 'border-color', $_REQUEST['title_frame_color']);

            $css .= $this->color_change('.ui-collapsible-content .ui-listview .ui-btn .ui-link-inherit', 'color', $_REQUEST['home_list_font_color']);
            $css .= $this->color_change('.ui-collapsible-content .ui-listview .ui-btn .ui-link-inherit', 'text-shadow', $_REQUEST['home_list_font_shadow_color']);
            $css .= $this->color_change('.ui-collapsible-content .ui-listview .ui-btn', 'background', $_REQUEST['home_list_background_color1'], $_REQUEST['home_list_background_color2']);
            $css .= $this->color_change('.ui-collapsible-content .ui-listview .ui-btn', 'border-color', $_REQUEST['home_list_frame_color']);

            $css .= $this->color_change('.ui-footer .ui-link', 'color', $_REQUEST['foot_font_color']);
            $css .= $this->color_change('.ui-footer .ui-link', 'text-shadow', $_REQUEST['foot_font_shadow_color']);
            $css .= $this->color_change('.ui-footer', 'background', $_REQUEST['foot_background_color1'], $_REQUEST['foot_background_color2']);
            $css .= $this->color_change('.ui-footer', 'border-color', $_REQUEST['foot_frame_color']);
            $css .= $this->color_change('.ui-footer .workselected', 'background', $_REQUEST['foot_hightlight_background_color1'], $_REQUEST['foot_hightlight_background_color2']);//底部高亮背景
                $css .= $this->color_change('.ui-footer .workselected .ui-link', 'color', $_REQUEST['foot_hightlight_font_color']);
                //$css .= $this->color_change(".ui-footer .workselected .ui-link","text-shadow",$_REQUEST['foot_hightlight_shadow_color']);


                $css .= $this->color_change('.homeproducttopic .ui-collapsible-content', 'background', $_REQUEST['home_product_background_color1'], $_REQUEST['home_product_background_color2']);
            $css .= $this->color_change('.homeproducttopic .ui-collapsible-content a', 'border-color', $_REQUEST['home_product_frame_color']);
            $css .= $this->color_change('.homeproducttopic .ui-collapsible-content a span', 'background', $_REQUEST['home_product_price_background_color1'], $_REQUEST['home_product_price_background_color2']);
            $css .= $this->color_change('.homeproducttopic .ui-collapsible-content a span', 'color', $_REQUEST['home_product_price_font_color']);

            $css .= $this->color_change('.ui-header .ui-btn, .ui-footer .ui-btn', 'background', $_REQUEST['head_button_background_color1'], $_REQUEST['head_button_background_color2']);
            $css .= $this->color_change('.ui-header .ui-btn, .ui-footer .ui-btn', 'color', $_REQUEST['head_button_font_color']);
            $css .= $this->color_change('.ui-header .ui-btn, .ui-footer .ui-btn', 'text-shadow', $_REQUEST['head_button_font_shadow_color']);
            $css .= $this->color_change('.ui-header .ui-btn, .ui-footer .ui-btn', 'border-color', $_REQUEST['head_button_frame_color']);

            $css .= $this->color_change('.ui-listview .ui-btn', 'background', $_REQUEST['list_background_color1'], $_REQUEST['list_background_color2']);
            $css .= $this->color_change('.ui-listview .ui-btn .ui-link-inherit', 'color', $_REQUEST['list_font_color']);
            $css .= $this->color_change('.ui-listview .ui-btn .ui-link-inherit', 'text-shadow', $_REQUEST['list_font_shadow_color']);
            $css .= $this->color_change('.pro_img', 'background', $_REQUEST['product_img_background_color1'], $_REQUEST['product_img_background_color2']);
            $css .= $this->color_change('.pro_img', 'border-color', $_REQUEST['product_img_background_frame_color']);
            $css .= $this->color_change('.pro_img span', 'border-color', $_REQUEST['product_img_frame_color']);
            $css .= $this->color_change('.picdate', 'background', $_REQUEST['product_attr_background_color1'], $_REQUEST['product_attr_background_color2']);
            $css .= $this->color_change('.picdate', 'color', $_REQUEST['product_attr_font_color']);
            $css .= $this->color_change('.picdate', 'text-shadow', $_REQUEST['product_attr_font_shadow_color']);
            $css .= $this->color_change('.picdate .newpic i', 'color', $_REQUEST['product_attr_price_color']);
            $css .= $this->color_change('.picdate .newpic i', 'text-shadow', $_REQUEST['product_attr_price_shadow_color']);
            $css .= $this->color_change('.picdate', 'border-color', $_REQUEST['product_attr_frame_color']);//详细页属性边框
                $css .= $this->color_change('.pro_date', 'background', $_REQUEST['product_desc_background_color1'], $_REQUEST['product_desc_background_color2']);
            $css .= $this->color_change('.pro_date', 'color', $_REQUEST['product_desc_font_color']);
            $css .= $this->color_change('.pro_date', 'text-shadow', $_REQUEST['product_desc_font_shadow_color']);
            $css .= $this->color_change('.pro_date', 'border-color', $_REQUEST['product_desc_frame_color']);

            $css .= $this->color_change('.per_date span, .next_date span', 'color', $_REQUEST['next_product_name_color']);
            $css .= $this->color_change('.per_date span, .next_date span', 'text-shadow', $_REQUEST['next_product_name_shadow_color']);
            $css .= $this->color_change('#last_pro_price, #next_pro_price', 'color', $_REQUEST['next_product_price_color']);
            $css .= $this->color_change('#last_pro_price, #next_pro_price', 'text-shadow', $_REQUEST['next_product_price_shadow_color']);
                //$_REQUEST['next_shadow_color']
                $css .= $this->color_change('.per_date .ui-btn, .next_date .ui-btn', 'color', $_REQUEST['next_color']);//详细页按钮背景
                $css .= $this->color_change('.per_date .ui-btn .ui-btn-text, .next_date .ui-btn .ui-btn-text', 'text-shadow', $_REQUEST['next_shadow_color']);
            $css .= $this->color_change('.per_date .ui-btn, .next_date .ui-btn', 'background', $_REQUEST['product_button_background_color1'], $_REQUEST['product_button_background_color2']);
            $css .= $this->color_change('.per_date .ui-btn, .next_date .ui-btn', 'border-color', $_REQUEST['product_button_frame_color']);
            $css .= $_REQUEST['custom_css'];
            $css .= '.ui-collapsible-heading .ui-icon-minus{ background-image:url('.$_REQUEST['arrow_up'].')}';
            $css .= '.ui-collapsible-heading .ui-icon-plus{ background-image:url('.$_REQUEST['arrow_down'].')}';
            $css .= '.homeproducttopic .ui-collapsible-content .z{ background-image:url('.$_REQUEST['arrow_left'].'),url('.$_REQUEST['arrow_right'].');}';
            $css .= '.homearticle .ui-collapsible-content .ui-listview .ui-icon-arrow-r,.ui-btn-icon-left .ui-btn-inner .ui-icon-arrow-r, .ui-btn-icon-right .ui-btn-inner .ui-icon-arrow-r{ background:url('.$_REQUEST['arrow_list'].') no-repeat 50%;}';

            $color_config['css'] = $css;

            $color_config['arrow_up'] = $_REQUEST['arrow_up'];
            $color_config['arrow_down'] = $_REQUEST['arrow_down'];
            $color_config['arrow_right'] = $_REQUEST['arrow_right'];
            $color_config['arrow_left'] = $_REQUEST['arrow_left'];
            $color_config['arrow_list'] = $_REQUEST['arrow_list'];

            $this->MobileAppTheme->save($color_config);
            $this->redirect('/mobile_configs/wap_config');
        }
    }
    //保存定制颜色数据
    public function color_change($sele, $attr, $valu, $valu2 = '')
    {
        if ($valu == '') {
            return '';
        }
        if ($attr == 'text-shadow') {
            //return $sele."{".$attr.":".$valu."0 1px 0!important}";
            //$tmp = $sele."{".$attr.":"."none!important}";
            $tmp = $sele.'{'.$attr.':'.$valu.'!important}';

            return $tmp;
        } elseif ($valu2 == '') {
            return $sele.'{'.$attr.':'.$valu.'!important}';
        } else {
            if ($attr == 'color') {
                return $sele.'{'.$attr.':'.$valu.'!important}';
            } elseif ($attr == 'background') {
                $tmp = $sele.'{'.$attr.':'.'-webkit-linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';
                $tmp .= $sele.'{'.$attr.':'.'-moz-linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';
                $tmp .= $sele.'{'.$attr.':'.'-ms-linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';
                $tmp .= $sele.'{'.$attr.':'.'-o-linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';
                $tmp .= $sele.'{'.$attr.':'.'linear-gradient(top,'.$valu.','.$valu2.')'.'!important}';

                return $tmp;
            } elseif ($attr == 'border-color') {
                return $sele.'{'.$attr.':'.$valu.'!important}';
            }
        }
    }
}
