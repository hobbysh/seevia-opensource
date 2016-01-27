<?php

/**
 * 文章分类.
 */
class ArticleGallery extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name ArticleGallery 关于文章扩展分类的模块
     */
    public $name = 'ArticleGallery';

    public $hasMany = array(
            'ArticleGalleryI18n' => array('className' => 'ArticleGalleryI18n',
                                    'conditions' => '',
                                    'order' => '',
                                    'dependent' => true,
                                    'foreignKey' => 'article_gallery_id',
            ),
    );
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('ArticleGallery.article_id' => $id), 'order' => 'ArticleGallery.orderby'));
        foreach ($lists as $k => $v) {
            foreach ($v['ArticleGalleryI18n']as $key => $val) {
                $lists[$k]['ArticleGalleryI18n'][$val['locale']] = $val;
            }
        }

        return $lists;
    }
}
