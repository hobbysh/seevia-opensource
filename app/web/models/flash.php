<?php

/**
 * Flash轮播模型.
 */
class flash extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name Flash flash表
     */
    public $name = 'Flash';
    /*
     * @var $hasMany array 关联flash的图片表
     */
    public $hasMany = array('FlashImage' => array('className' => 'FlashImage',
            'conditions' => array('locale' => LOCALE,'status' => 1),
            'fields' => array('image', 'title', 'url'),
            'order' => ' orderby ',
            'dependent' => true,
            'foreignKey' => 'flash_id',
        ),
    );
    /*
     * @var $cacheQueries true 是否开启缓存：是。
     */
    public $cacheQueries = true;
    /**
     *set_locale方法，设置语言环境.
     *
     *@param $locale
     */
    public function set_locale($locale)
    {
        $conditions = " and FlashImage.locale = '".$locale."'";
        $this->hasMany['FlashImage']['conditions'] = $this->hasMany['FlashImage']['conditions'] .= $conditions;
    }
    /**
     *函数get_module_infos方法，轮播模块相关.
     *
     *@param $params,查询条件
     *
     *@return $module_flash_infos 返回轮播内容
     */
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['flash_type'])) {
            $conditions['Flash.page'] = $params['flash_type'];
        } else {
            $conditions['Flash.page'] = 'H';
        }
        if (isset($params['flash_type_id'])) {
            $conditions['Flash.page_id'] = $params['flash_type_id'];
        } elseif (isset($params['type_id'])) {
            $conditions['Flash.page_id'] = $params['type_id'];
        }
        $conditions['Flash.type'] = '0';
        $module_flash_infos = $this->find('first', array('conditions' => $conditions, 'fields' => array('Flash.width', 'Flash.height', 'Flash.page_id')));
//	   	echo "<pre>";
//	   	print_r($module_flash_infos);
//	   	if(!empty($module_flash_infos)&&!empty($module_flash_infos['FlashImage'])){
//			$flash_image = $this->FlashImage->find('all',array('conditions'=>array('FlashImage.flash_id'=>$module_flash_infos['Flash']['id']),'fields'=>array('FlashImage.image','FlashImage.title','FlashImage.url')));
//		}
        return $module_flash_infos;
    }

    /**
     *get_mobile_flash方法，模块相关.
     *
     *@param $params,查询条件
     */
    public function get_mobile_flash($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['flash_type'])) {
            $conditions['Flash.page'] = $params['flash_type'];
        } else {
            $conditions['Flash.page'] = 'H';
        }
        if (isset($params['flash_type_id'])) {
            $conditions['Flash.page_id'] = $params['flash_type_id'];
        } elseif (isset($params['type_id'])) {
            $conditions['Flash.page_id'] = $params['type_id'];
        }
        $conditions['Flash.type'] = '1';
        $module_flash_infos = $this->find('first', array('conditions' => $conditions, 'fields' => array('Flash.width', 'Flash.height', 'Flash.page_id')));

        return $module_flash_infos;
    }
}
