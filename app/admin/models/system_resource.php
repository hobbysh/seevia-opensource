<?php

/**
 * 系统资源库模型.
 */
class SystemResource extends AppModel
{
    /*
     * @var $name SystemResource 系统资源库
     */            public $useDbConfig = 'default';
    public $useTable = 'resources';
    public $name = 'SystemResource';
    public $locale = '';
    /*
     * @var $hasOne array 关联系统资源库多语言表
     */
    public $hasOne = array('SystemResourceI18n' => array('className' => 'SystemResourceI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'resource_id',
                        ),
                    );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $this->locale = $locale;
        $conditions = " SystemResourceI18n.locale = '".$locale."'";
        $this->hasOne['SystemResourceI18n']['conditions'] = $conditions;
    }
    /**
     * resource_formated方法，系统资源数据指定代码信息库数据获取.
     *
     * @param string $code        信息代码数组
     * @param string $locale      语言代码
     * @param bool   $parent_show 是否包括父信息
     *
     * @return array $sr_parent_data 返回系统资源数据指定代码信息库数据获取
     */
    public function resource_formated($code, $locale, $parent_show = true)
    {
        $condition = '';
        $condition['SystemResource.code'] = $code;
        $condition['SystemResource.status'] = 1;
        $condition['SystemResourceI18n.locale'] = $locale;
        $this->locale = $locale;
        $node['config'] = 'node';
        $node['use'] = true;
        $system_resource_data_parent = $this->find('all', array('cache' => $node, 'conditions' => $condition, 'fields' => array('SystemResource.status', 'SystemResource.id', 'SystemResource.code')));
        $parent_id_array = array();
        $system_resource_data_parent_format = array();
        foreach ($system_resource_data_parent as $k => $v) {
            $parent_id_array[] = $v['SystemResource']['id'];
            $system_resource_data_parent_format[$v['SystemResource']['id']] = $v;
        }
        $condition = '';
        $condition['SystemResource.parent_id'] = $parent_id_array;
        $condition['SystemResourceI18n.locale'] = $locale;
        $condition['SystemResource.status'] = 1;
        $system_resource_data = $this->find('all', array('cache' => $node, 'conditions' => $condition, 'order' => 'SystemResource.orderby asc', 'fields' => array('SystemResource.id', 'SystemResourceI18n.name', 'SystemResource.resource_value', 'SystemResource.parent_id', 'SystemResource.code')));
        $sr_parent_data = array();
        foreach ($system_resource_data as $k => $v) {
            $sr_parent_data[$system_resource_data_parent_format[$v['SystemResource']['parent_id']]['SystemResource']['code']][$v['SystemResource']['resource_value']] = $v['SystemResourceI18n']['name'];
        }

        return $sr_parent_data;
    }

    public function find_assoc($condition, $condition2 = array())
    {
        $node['config'] = 'node';
        $node['use'] = true;
       //	$this->set_locale($this->locale);
        $conditions = $this->find(array('cache' => $node, 'code' => $condition));
        $condition2['SystemResource.parent_id'] = $conditions['SystemResource']['id'];
        $tree = $this->find('all', array('cache' => $node, 'conditions' => $condition2));
        $array_assoc = array();
        foreach ($tree as $k => $v) {
            $array_assoc[$v['SystemResource']['resource_value']] = $v['SystemResourceI18n']['name'];
        }

        return $array_assoc;
    }

    public function find($type, $params = array())
    {
        $params['cache']['use'] = true;

        return parent::find($type, $params);
    }
}
