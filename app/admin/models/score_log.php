<?php

/*****************************************************************************
 * svoms 评分日志
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
class ScoreLog extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'ScoreLog';

    public function getInfoById($id, $locale = 'chi')
    {
        $data = array();
        $cond['ScoreLog.user_id'] = $id;
        $_data = $this->find('all', array('conditions' => $cond, 'order' => 'ScoreLog.type,ScoreLog.type_id,ScoreLog.score_id,ScoreLog.created desc'));
        if (!empty($_data)) {
            $score_ids = array();
            $score_type_ids = array();
            foreach ($_data as $v) {
                $score_ids[$v['ScoreLog']['score_id']] = $v['ScoreLog']['score_id'];
                $score_type_ids[$v['ScoreLog']['type']][$v['ScoreLog']['type_id']] = $v['ScoreLog']['type_id'];
            }
            $ScoreI18n = ClassRegistry::init('ScoreI18n');
            $score_cond['ScoreI18n.score_id'] = $score_ids;
            $score_cond['ScoreI18n.locale'] = $locale;
            $score_list = $ScoreI18n->find('list', array('conditions' => $score_cond, 'fields' => array('ScoreI18n.score_id', 'ScoreI18n.name')));
            if (isset($score_type_ids['P'])) {
                $ProductI18n = ClassRegistry::init('ProductI18n');
                $pro_cond['ProductI18n.product_id'] = $score_type_ids['P'];
                $pro_cond['ProductI18n.locale'] = $locale;
                $pro_list = $ProductI18n->find('list', array('conditions' => $pro_cond, 'fields' => array('ProductI18n.product_id', 'ProductI18n.name')));
            }
            if (isset($score_type_ids['A'])) {
                $ArticleI18n = ClassRegistry::init('ArticleI18n');
                $article_cond['ArticleI18n.article_id'] = $score_type_ids['A'];
                $article_cond['ArticleI18n.locale'] = $locale;
                $article_list = $ArticleI18n->find('list', array('conditions' => $article_cond, 'fields' => array('ArticleI18n.article_id', 'ArticleI18n.title')));
            }
            foreach ($_data as $k => $v) {
                if ($v['ScoreLog']['type'] == 'P') {
                    $v['ScoreLog']['object'] = isset($pro_list[$v['ScoreLog']['type_id']]) ? $pro_list[$v['ScoreLog']['type_id']] : '';
                } elseif ($v['ScoreLog']['type'] == 'A') {
                    $v['ScoreLog']['object'] = isset($article_list[$v['ScoreLog']['type_id']]) ? $article_list[$v['ScoreLog']['type_id']] : '';
                } else {
                    $v['ScoreLog']['object'] = '';
                }
                $v['ScoreLog']['score'] = isset($score_list[$v['ScoreLog']['score_id']]) ? $score_list[$v['ScoreLog']['score_id']] : '';
                if (!isset($data[$v['ScoreLog']['type'].'-'.$v['ScoreLog']['type_id']]['object'])) {
                    $data[$v['ScoreLog']['type'].'-'.$v['ScoreLog']['type_id'].'-'.$id]['object'] = array(
                        'type' => $v['ScoreLog']['type'],
                        'type_id' => $v['ScoreLog']['type_id'],
                        'object' => $v['ScoreLog']['object'],
                        'created' => $v['ScoreLog']['created'],
                    );
                }
                $data[$v['ScoreLog']['type'].'-'.$v['ScoreLog']['type_id'].'-'.$id]['LogList'][] = $v;
            }
        }

        return $data;
    }
}
