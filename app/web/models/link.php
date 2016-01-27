<?php

/**
 * 友情链接模型.
 */
class link extends AppModel
{
    public $useDbConfig = 'cms';
    /*
     * @var $name 友情链接名称
     */
    public $name = 'Link';
    /*
     * @var $cacheQueries true 是否开启缓存：是。
     */
    public $cacheQueries = true;
    /*
     * @var $cacheAction 1day 缓存时间：1天。
     */
    public $cacheAction = '1 day';
    /*
     * @var $hasOne array 关联联系方式多语言表
     */
    public $hasOne = array('LinkI18n' => array('className' => 'LinkI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => 'Link.id',
            'dependent' => true,
            'foreignKey' => 'link_id',
        ),
    );

    //数组结构调整
    /**
     * localeformat方法，数组结构调整.
     *
     * @param $id 输入id
     *
     * @return $lists_formated 读取所有id等于输入id的数据并重新整合放到一个数组中输出。
     */
    public function localeformat($id)
    {
        $lists = $this->findAll("Link.id = '".$id."'");
        //	pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['Link'] = $v['Link'];
            $lists_formated['LinkI18n'][] = $v['LinkI18n'];
            foreach ($lists_formated['LinkI18n'] as $key => $val) {
                $lists_formated['LinkI18n'][$val['locale']] = $val;
            }
        }
        //	pr($lists_formated);
        return $lists_formated;
    }

    /**
     * find_link方法，数组结构调整.
     *
     * @param $locale 输入语言编码
     *
     * @return $link 赋值使状态为是，然后重新排列数据，返回数组
     */
    /**
     * get_module_infos方法，获取模块数据.
     *
     * @param  查询参数集合
     *
     * @return $link 根据param，返回数组
     */
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'orderby';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['type_id'])) {
            $conditions['Link.type'] = $params['type_id'];
        }
        $conditions['Link.status'] = 1;
        $link_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Link.'.$order, 'fields' => 'Link.id,Link.type,Link.target,Link.created,LinkI18n.img01,LinkI18n.name,LinkI18n.url'));

        return $link_infos;
    }
}
