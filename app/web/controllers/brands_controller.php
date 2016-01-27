<?php

/*****************************************************************************
 * Seevia 用户收藏
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为BrandsController的控制器
 *品牌控制器.
 *
 *@var
 *@var
 *@var
 *@var
 *@var
 *@var
 */
class BrandsController extends AppController
{
    public $name = 'Brands';
    public $components = array('Pagination'); // Added 
    public $helpers = array('Pagination','Flash'); // Added 
    public $uses = array('Brand','Product','Flash','UserRank','ProductsCategory','ProductRank','ProductLocalePrice','ProductI18n');
    public $cacheQueries = true;
    public $cacheAction = '1 hour';
    /**
     *显示页.
     *
     *@param $id 输入id
     *@param $page 输入页
     *@param $orderby 输入类型
     *@param $rownum 输入行数
     *@param $showtype 输入显示类型
     */
    public function index($page = 1)
    {
        $this->ur_heres[] = array('name' => $this->ld['brand_list'] , 'url' => '/brands');
        $this->set('ur_heres', $this->ur_heres);
        $this->params['page'] = $page;
        $this->layout = 'default_full';
        $this->pageTitle = $this->ld['brand_list'].' - '.$this->configs['shop_title'];
        $this->page_init($this->params);
    }
    public function view($id = '', $page = 1, $orderby = 0, $rownum = 0, $showtype = 0)
    {
        $orderby = UrlDecode($orderby);
        $rownum = UrlDecode($rownum);
        $showtype = UrlDecode($showtype);

        $flag = 1;
        $this->data['to_page_id'] = $id;
        if (!isset($_GET['page'])) {
            $_GET['page'] = $page;
        }
        $this->data['get_page'] = $_GET['page'];

        // Configure::write('debug', 0);
        if (!is_numeric($id) || $id < 1) {
            $this->pageTitle = $this->ld['parameter_error'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['parameter_error'], '/', 5);
            $flag = 0;
        }
        //取得该品牌信息
        $brand_info = $this->Brand->find('first', array('conditions' => array('Brand.id' => $id), 'fields' => 'Brand.id,Brand.status,BrandI18n.name,BrandI18n.meta_keywords,BrandI18n.meta_description,BrandI18n.description'));
        $this->set('meta_description', $brand_info['BrandI18n']['meta_description']);
        $this->set('meta_keywords', $brand_info['BrandI18n']['meta_keywords']);
        $this->params['id'] = $id;
        if (empty($brand_info)) {
            $this->pageTitle = $this->ld['brand'].$this->ld['not_exist'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['brand'].$this->ld['not_exist'], '/', 5);
            $flag = 0;
        } elseif ($brand_info['Brand']['status'] == 0) {
            $this->pageTitle = $this->ld['brand'].$this->ld['not_exist'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['brand'].$this->ld['not_exist'], '/', 5);
            $flag = 0;
        }

        if ($flag == 1) {
            $this->pageTitle = $brand_info['BrandI18n']['name'].' - '.$this->configs['shop_title'];
            //当前位置
            $navigations = $brand_info;
            $this->ur_heres[] = array('name' => $navigations['BrandI18n']['name'],'url' => '/brands/'.$navigations['Brand']['id']);
            $this->set('ur_heres', $this->ur_heres);
            if (empty($rownum) && $rownum == 0) {
                $rownum = isset($this->configs['products_list_num']) ? $this->configs['products_list_num'] : ((!empty($rownum)) ? $rownum : 20);
            }
            if (empty($showtype) && $showtype == 0) {
                $showtype = isset($this->configs['products_list_showtype']) ? $this->configs['products_list_showtype'] : ((!empty($showtype)) ? $showtype : 'L');
            }
             //取商店设置商品排序
            if (empty($orderby) && $orderby == 0) {
                $orderby = isset($this->configs['products_category_page_orderby_type']) ? $this->configs['products_category_page_orderby_type'].' '.$this->configs['products_category_page_orderby_method'] : ((!empty($orderby)) ? $orderby : 'created '.$this->configs['products_category_page_orderby_method']);
            }

            if ($rownum == 'all') {
                $rownum_sql = 99999;
            } else {
                $rownum_sql = $rownum;
            }

            $this->data['orderby'] = $orderby;
            $this->data['rownum'] = $rownum;
            $this->data['showtype'] = $showtype;
        }
        if (isset($this->configs['enable_one_step_buy']) && $this->configs['enable_one_step_buy'] == 1) {
            $js_languages = array('enable_one_step_buy' => '1','enter_positive_integer' => $this->ld['be_integer']);
            $this->set('js_languages', $js_languages);
        } else {
            $js_languages = array('enable_one_step_buy' => '0','enter_positive_integer' => $this->ld['be_integer']);
            $this->set('js_languages', $js_languages);
        }
        $this->set('description', $brand_info['BrandI18n']['description']);

        $this->page_init($this->params);
    }
}
