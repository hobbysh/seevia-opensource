<?php

/*****************************************************************************
 * svcms  �����ģ��
 * ===========================================================================
 * ��Ȩ����  �Ϻ�ʵ������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�ã�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ===========================================================================
 * $����: �Ϻ�ʵ��$
 * $Id$
*****************************************************************************/
class page extends AppModel
{
    /*
     * @var $useDbConfig ���ݿ�����
     */
    public $useDbConfig = 'cms';

    /*
     * @var $name �����
     */
    public $name = 'Page';
    /*
     * @var $hasOne array ���µĶ�����ģ��
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
    //����ṹ����
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
     * ����get_module_infos��������ȡ��̬ҳ��ģ����������.
     *
     * @param  ��ѯ��������
     *
     * @return $page_infos ����param�����ؾ�̬ҳ��ģ������
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
        //��ҳstart
        //$total=$this->find('count',array('conditions'=>$conditions));
        //App::import('Component','Paginationmodel');
        //$pagination = new PaginationModelComponent();

        //get����
        //$parameters['get'] = array();
        //��ַ·�ɲ�������control,action�Ĳ�����Ӧ��
        //$parameters['route'] = array('controller'=>'articles','action'=>'index','page'=>$page,'limit'=>$limit);
        //��ҳ����
        //$options = Array('page'=>$page,'show'=>$limit,'modelClass'=>$this->name,'total'=>$total);
        //pr($conditions);die;
        //$pages = $pagination->init($conditions, $parameters, $options); // Added
        //pr($pages);
        //��ҳend
        $page_infos = $this->find('all', array('conditions' => $conditions, 'order' => 'Page.'.$order, 'fields' => 'Page.id,PageI18n.title,PageI18n.subtitle,PageI18n.content,PageI18n.img01,PageI18n.img02,PageI18n.meta_description'));
        $page['page'] = $page_infos;
        //$page_infos['paging']=$pages;
        return $page_infos;
    }
}
