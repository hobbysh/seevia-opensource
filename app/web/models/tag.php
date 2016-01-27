<?php

/**
 * 标签模型.
 */
class tag extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name 用来解决PHP4中的一些奇怪的类名
     * @var $hasOne 设置模型关联
     */
    public $name = 'Tag';
    public $hasOne = array('TagI18n' => array('className' => 'TagI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'tag_id',
        ),
    );

    /**
     * 函数localeformat,数组结构调整.
     *
     * @param $id 标签语言号
     * @param $lists 标签号列表
     * @param $lists_formated 标签列表
     *
     * @return $lists_formated 标签列表
     */
    public function localeformat($id)
    {
        $lists = $this->findAll("Tag.id = '".$id."'");
        foreach ($lists as $k => $v) {
            $lists_formated['Tag'] = $v['Tag'];
            $lists_formated['TagI18n'][] = $v['TagI18n'];
            foreach ($lists_formated['TagI18n'] as $key => $val) {
                $lists_formated['TagI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    public function get_tag($id, $user_id)
    {
        $tag = $this->find('Tag.id = '.$id.' and Tag.user_id = '.$user_id);

        return $tag;
    }

    /**
     * 这个tag 是用来 取lib特有的标签的.
     */
    public function get_tags_by_products($conditions, $limit, $page)
    {
        $tag = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'page' => $page));

        return $tag;
    }
}
