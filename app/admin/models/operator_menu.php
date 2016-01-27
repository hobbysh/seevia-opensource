<?php

/**
 * 操作员菜单模型.
 */
class OperatorMenu extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';

    /*
     * @var $name OperatorMenu 操作员菜单
     */
    public $name = 'OperatorMenu';

    /*
     * @var $name actions_parent_format 制作树用的
     */
    public $actions_parent_format = array();

    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasOne = array('OperatorMenuI18n' => array('className' => 'OperatorMenuI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'operator_menu_id',
                        ),
                    );

    /*
     * @var $name cache_config 缓存用
     */
    public $cache_config = 'day';

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " OperatorMenuI18n.locale = '".$locale."'";
        $this->hasOne['OperatorMenuI18n']['conditions'] = $conditions;
    }
    /**
     * tree方法，菜单树.
     *
     * @param string $actions 权限字符串
     * @param string $locale  输入语言
     *
     * @return array $this->allinfo[$type] 返回所有的输入值
     */
    public function tree($actions = 'all', $locale, $app_codes, $action_codes)
    {
        $menu_formatcode = array();
        if ($actions != 'all' && $actions != 'tree') {
            $conditions['or']['OperatorMenu.level <'] = $actions;
        } elseif ($actions == 'all') {
            $conditions['or']['OperatorMenu.status'] = '1';
        }
        $conditions['and']['OperatorMenu.operator_action_code <>'] = null;
        if (isset($_GET['dev'])) {
            $_SESSION['dev'] = $_GET['dev'];
        }
        if ($action_codes == 'all' && isset($_SESSION['dev']) && $_SESSION['dev'] == 1) {
        } elseif ($action_codes == 'all') {
            $conditions['and']['OperatorMenu.operator_action_code <>'] = $action_codes;
        }
        $actions_arr = $this->find('all', array(
            'conditions' => array($conditions),
            'fields' => array('OperatorMenu.operator_action_code,OperatorMenu.link,OperatorMenu.app_code,OperatorMenu.orderby,OperatorMenu.parent_id,OperatorMenu.id,OperatorMenu.level,OperatorMenu.section,OperatorMenuI18n.name,OperatorMenu.status'),
            'order' => array('orderby asc'), )
        );
        //pr($actions_arr);
        $this->actions_parent_format = array();//先致空
        if (is_array($actions_arr)) {
            foreach ($actions_arr as $k => $v) {
                //echo 	$v['OperatorMenuI18n']['name'];

                //判断应用

                if ($v['OperatorMenu']['app_code'] != null && !in_array($v['OperatorMenu']['app_code'], $app_codes)) {
                    //	echo $v['OperatorMenuI18n']['name']."-".$v['OperatorMenu']['app_code']."<br>";
                    continue;
                } else {
                    //	echo $v['OperatorMenuI18n']['name']."+".$v['OperatorMenu']['app_code']."<br>";
                }

                //判断权限
                if ($action_codes != 'all') {
                    if ($v['OperatorMenu']['operator_action_code'] != null && !in_array($v['OperatorMenu']['operator_action_code'], $action_codes)) {
                        continue;
                    }
                }

                $v['OperatorMenu']['name'] = $v['OperatorMenuI18n']['name'];
                $this->actions_parent_format[$v['OperatorMenu']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get('0');
    }

    /**
     * subcat_get方法，获得subcat.
     *
     * @param int $category_id 输入id
     *
     * @return array $subcat 根据id检索相对应的数据并返回
     */
    public function subcat_get($action_id)
    {
        $subcat = array();
        if (isset($this->actions_parent_format[$action_id]) && is_array($this->actions_parent_format[$action_id])) {
            foreach ($this->actions_parent_format[$action_id] as $k => $v) {
                $action = $v;
                if (isset($this->actions_parent_format[$v['OperatorMenu']['id']]) && is_array($this->actions_parent_format[$v['OperatorMenu']['id']])) {
                    $action['SubMenu'] = $this->subcat_get($v['OperatorMenu']['id']);
                } else {
                    $action['SubMenu'] = '';
                }
                $subcat[$k] = $action;
            }
        }

        return $subcat;
    }
    /**
     * localeformat方法，数组结构调整.
     *
     * @param string $id 输入菜单编号
     *
     * @return $lists_formated 返回菜单所有语言的信息
     */
    public function localeformat($id)
    {
        $this->hasOne['OperatorMenuI18n']['conditions'] = '';
        $lists = $this->find('all', array('conditions' => array('OperatorMenu.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['OperatorMenu'] = $v['OperatorMenu'];
            $lists_formated['OperatorMenuI18n'][] = $v['OperatorMenuI18n'];
            foreach ($lists_formated['OperatorMenuI18n'] as $key => $val) {
                $lists_formated['OperatorMenuI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
}
