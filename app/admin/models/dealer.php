<?php

/*****************************************************************************
 * svsys 经销商
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
class dealer extends AppModel
{
    public $name = 'Dealer';

    public function get_dealer_list()
    {
        $condition['status'] = 1;
        $provider_list = $this->find('all', array('conditions' => $condition, 'fields' => array('dealer.id,dealer.name')));

        return $provider_list;
    }

         /*
     * @var $categories_parent_format array 关联类别格式
     */
    public $dealer_parent_format = array();

    /*
     * @var $dealer_navigate_format array 关联游览格式
     */
    public $dealer_navigate_format = array();

    /*
     * @var $all_subcat array 关联所有的subcat
     */
    public $all_subcat = array();

    /*
     * @var $allinfo array 关联所有输入信息
     */
    public $allinfo = array();

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function tree($subcat_get = '0', $id = 'all')
    {
        //pr($id);
        $this->dealer_parent_format = array();
        $this->dealer_navigate_format = array();
        $this->all_subcat = array();
        $this->allinfo = array();
    //	$this->set_locale($locale);
        $condition['Dealer.status'] = '1';
    //	$conditions['type =']=$type;
        if ($id != 'all') {
            $conditions['Dealer.id !='] = $id;
        }//所有的经销商
        $dealer = $this->find('all', array('conditions' => $condition, 'order' => 'Dealer.orderby asc,Dealer.created asc'));
    //	pr($dealer);die;
        if (is_array($dealer)) {
            foreach ($dealer as $k => $v) {
                $this->dealer_parent_format[$v['Dealer']['parent_id']][] = $v;
            }
        }
    //	pr($this->dealer_parent_format);die;
        return $this->subcat_get($subcat_get);
    }

    /**
     * subcat_get方法，获得subcat.
     *
     * @param int $category_id 输入id
     *
     * @return array $subcat 根据id检索相对应的数据并返回
     */
    public function all_tree($subcat_get = '0', $id = 'all')
    {
        $this->dealer_parent_format = array();
        $this->dealer_navigate_format = array();
        $this->all_subcat = array();
        $this->allinfo = array();
        $condition = '';
        if ($id != 'all') {
            $condition['Dealer.id'] = $id;
        }//所有的经销商
        $dealer = $this->find('all', array('conditions' => $condition, 'order' => 'Dealer.orderby asc,Dealer.created asc'));
    //	pr($dealer);die;
        if (is_array($dealer)) {
            foreach ($dealer as $k => $v) {
                $this->dealer_parent_format[$v['Dealer']['parent_id']][] = $v;
            }
        }
    //	pr($this->dealer_parent_format);die;
        return $this->subcat_get($subcat_get);
    }
    public function subcat_get($parent_id)
    {
        $subcat = array();
       // pr($this->dealer_parent_format);die;
        if (isset($this->dealer_parent_format[$parent_id]) && is_array($this->dealer_parent_format[$parent_id])) {
            //判断parent_id = 0 的数据
            foreach ($this->dealer_parent_format[$parent_id] as $k => $v) {
                $dealer = $v; //parent_id 为 0 的数据
               // pr($dealer);
                if (isset($this->dealer_parent_format[$v['Dealer']['id']]) && is_array($this->dealer_parent_format[$v['Dealer']['id']])) {
                    $dealer['SubDealer'] = $this->subcat_get($v['Dealer']['id']);
                }
                $subcat[$k] = $dealer;
                $this->all_subcat[$v['Dealer']['id']][] = $v['Dealer']['id'];
                if (isset($this->all_subcat[$v['Dealer']['parent_id']])) {
                    $this->all_subcat[$v['Dealer']['parent_id']] = array_merge($this->all_subcat[$v['Dealer']['parent_id']], $this->all_subcat[$v['Dealer']['id']]);
                } else {
                    $this->all_subcat[$v['Dealer']['parent_id']] = $this->all_subcat[$v['Dealer']['id']];
                }
            }
        }

        return $subcat;
    }

    public function parenttree($parent_get = '0', $id = 'all')
    {
        //pr($id);
        $this->dealer_parent_format = array();
        $this->dealer_navigate_format = array();
        $this->all_subcat = array();
        $this->allinfo = array();
    //	$this->set_locale($locale);
        $condition['Dealer.status'] = '1';
    //	$conditions['type =']=$type;
        if ($id != 'all') {
            $conditions['Dealer.id !='] = $id;
        }
        $dealer = $this->find('all', array('conditions' => $condition, 'fields' => array('id', 'parent_id', 'name'), 'order' => 'Dealer.orderby asc,Dealer.created asc'));
        if (is_array($dealer)) {
            foreach ($dealer as $k => $v) {
                $this->dealer_parent_format[$v['Dealer']['id']][] = $v;
            }
        }

        return $this->parent_get($parent_get);
    }

    /**
     * subcat_get方法，获得subcat.
     *
     * @param int $category_id 输入id
     *
     * @return array $subcat 根据id检索相对应的数据并返回
     */
    public function parent_get($parent_id)
    {
        $subcat = array();
        if (isset($this->dealer_parent_format[$parent_id]) && is_array($this->dealer_parent_format[$parent_id])) {
            //判断parent_id = 0 的数据
            foreach ($this->dealer_parent_format[$parent_id] as $k => $v) {
                //	pr($v);die;
                $dealer = $v; //parent_id 为 0 的数据
               // pr($dealer);
                if (isset($this->dealer_parent_format[$v['Dealer']['parent_id']]) && is_array($this->dealer_parent_format[$v['Dealer']['parent_id']])) {
                    $dealer['SubDealer'] = $this->parent_get($v['Dealer']['parent_id']);
                }
                $subcat = $dealer;
                $this->all_subcat[$v['Dealer']['id']][] = $v['Dealer']['id'];
                if (isset($this->all_subcat[$v['Dealer']['parent_id']])) {
                    $this->all_subcat[$v['Dealer']['parent_id']] = array_merge($this->all_subcat[$v['Dealer']['id']], $this->all_subcat[$v['Dealer']['parent_id']]);
                } else {
                    $this->all_subcat[$v['Dealer']['parent_id']] = $this->all_subcat[$v['Dealer']['id']];
                }
            }
        }

        return $subcat;
    }
}
