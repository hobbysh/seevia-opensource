<?php

/*****************************************************************************
 * svcms  广告条模型
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
class page extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';

    /*
     * @var $name 广告条
     */
    public $name = 'Page';
    /*
     * @var $hasOne array 文章的多语言模块
     */
    public $hasOne = array(
            'PageI18n' => array('className' => 'PageI18n',
                                'conditions' => '',
                                'order' => '',
                                'dependent' => true,
                                'foreignKey' => 'page_id',
            ),
    );

    public function set_locale($locale)
    {
        $conditions = " PageI18n.locale = '".$locale."'";
        $this->hasOne['PageI18n']['conditions'] = $conditions;
    }
    //数组结构调整
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => "Page.id = '".$id."'"));
        $lists_formated = array();
        //pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['Page'] = $v['Page'];
            $lists_formated['PageI18n'][] = $v['PageI18n'];
            foreach ($lists_formated['PageI18n'] as $key => $val) {
                $lists_formated['PageI18n'][$val['locale']] = $val;
            }
        }
        //pr($lists_formated);
        return $lists_formated;
    }
    /**
     * 函数get_module_infos方法，获取静态页面模块内容数据.
     *
     * @param  查询参数集合
     *
     * @return $page_infos 根据param，返回静态页面模块内容
     */
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
//		if(isset($params['ControllerObj'])){
//			if(isset($params['ControllerObj']->configs['article_category_page_list_number'])){
//				$limit=$params['ControllerObj']->configs['article_category_page_list_number'];
//			}
//		}
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['parameters'])) {
            $conditions['Page.id'] = $params['parameters'];
        }
        $conditions['Page.status'] = 1;
        $conditions['PageI18n.locale'] = LOCALE;
        //分页start
        //$total=$this->find('count',array('conditions'=>$conditions));
        //App::import('Component','Paginationmodel');
        //$pagination = new PaginationModelComponent();

        //get参数
        //$parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        //$parameters['route'] = array('controller'=>'articles','action'=>'index','page'=>$page,'limit'=>$limit);
        //分页参数
        //$options = Array('page'=>$page,'show'=>$limit,'modelClass'=>$this->name,'total'=>$total);
        //pr($conditions);die;
        //$pages = $pagination->init($conditions, $parameters, $options); // Added
        //pr($pages);
        //分页end
        $page_infos = $this->find('all', array('conditions' => $conditions, 'order' => 'Page.'.$order, 'fields' => 'Page.id,PageI18n.title,PageI18n.subtitle,PageI18n.content,PageI18n.img01,PageI18n.img02,PageI18n.meta_description'));
        $page['page'] = $page_infos;
        //$page_infos['paging']=$pages;
        return $page_infos;
    }
}
