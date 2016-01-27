<?php

/*****************************************************************************
 * svcms
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
class navigation extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    public $name = 'Navigation';
    public $hasOne = array('NavigationI18n' => array('className' => 'NavigationI18n',
                              'conditions' => '',
                              'order' => 'locale desc',
                              'dependent' => true,
                              'foreignKey' => 'navigation_id',
                        ),
                  );

    public function set_locale($locale)
    {
        $conditions = " NavigationI18n.locale = '".$locale."'";
        $this->hasOne['NavigationI18n']['conditions'] = $conditions;
    }

    public $navigations_parent_format = array();
    public function alltree($condition, $orderby, $rownum, $page, $locale)
    {
        //
        $this->set_locale($locale);
        $actions = $this->find('all', array('conditions' => $condition, 'order' => $orderby, 'limit' => $rownum, 'page' => $page));
        $this->acionts_parent_format = array();//先致空
        if (is_array($actions)) {
            foreach ($actions as $k => $v) {
                $this->acionts_parent_format[$v['Navigation']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get('0');
    }
    public function subcat_get($action_id)
    {
        $subcat = array();
        if (isset($this->acionts_parent_format[$action_id]) && is_array($this->acionts_parent_format[$action_id])) {
            foreach ($this->acionts_parent_format[$action_id] as $k => $v) {
                $action = $v;
                if (isset($this->acionts_parent_format[$v['Navigation']['id']]) && is_array($this->acionts_parent_format[$v['Navigation']['id']])) {
                    $action['SubNavigation'] = $this->subcat_get($v['Navigation']['id']);
                }
                $subcat[$k] = $action;
            }
        }

        return $subcat;
    }

    //数组结构调整
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('Navigation.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Navigation'] = $v['Navigation'];
            $lists_formated['NavigationI18n'][] = $v['NavigationI18n'];
            foreach ($lists_formated['NavigationI18n'] as $key => $val) {
                $lists_formated['NavigationI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
    //seo 取链接地址
    public function seo_link_path($options = array())
    {
        $path = '';
        if (!empty($options['type'])) {
            //			switch($options['type']){
//				case 'P': //商品详细页
//					$path = "/".$this->url_name_format($options['name'])."-P".$options['id'].'.html';
//					break;
//				case 'PC': //商品分类页
//					$path = "/".$this->url_name_format($options['name'])."-PC".$options['id'].'.html';
//					break;
//				case 'A': //文章详细页
//					$path = "/".$this->url_name_format($options['name'])."-A".$options['id'].'.html';
//					break;
//				case 'AC': //文章分类页
//					$path = "/".$this->url_name_format($options['name'])."-AC".$options['id'].'.html';
//					break;
//				default :
//					$path = "";
//			}
            switch ($options['type']) {
                    case 'P': //商品详细页
                        $path = '/products/'.$options['id'].'';
                        break;
                    case 'PC': //商品分类页
                        $path = '/categories/'.$options['id'].'';
                        break;
                    case 'A': //文章详细页
                        $path = '/articles/'.$options['id'].'';
                        break;
                    case 'V'://视频页
                        $path = '/articles/video/'.$options['id'].'';
                        break;
                    case 'AC': //文章分类页
                        $path = '/articles/category/'.$options['id'].'';
                        break;
                    case 'TP': //文章分类页
                        $path = '/topics/'.$options['id'].'';
                        break;
                    case 'BV': //品牌页
                        $path = '/brands/'.$options['id'].'';
                        break;
                    case 'SM': //前台
                        $path = $options['id'].'';
                        break;
                    default :
                        $path = '';
                }
        }

        return $path;
    }

    //链接地址特殊字符格式化
    public function url_name_format($name)
    {
        $name = str_replace('\\', '-', $name);
        $name = str_replace(',', '-', $name);
        $name = str_replace('&', '-', $name);
        $name = str_replace('?', '-', $name);
        $name = str_replace('/', '-', $name);

        return urldecode($name);
    }
}
