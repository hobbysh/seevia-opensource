<?php

/* * ***************************************************************************
 * Seevia 文章
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 * *************************************************************************** */

class ArticleGallery extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    public $name = 'ArticleGallery';
    public $hasOne = array(
        'ArticleGalleryI18n' => array('className' => 'ArticleGalleryI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'article_gallery_id',
        ),
    );
}
