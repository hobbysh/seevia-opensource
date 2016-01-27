<?php

/*****************************************************************************
 * svcms �ֲ�
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
class FlashImage extends AppModel
{
    /*
     * @var $useDbConfig ���ݿ�����
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name Article �������µ�ģ��
     */
    public $name = 'FlashImage';
    /**
     *get_module_infos������ģ�����.
     *
     *@param $params,��ѯ����
     *�����ֲ�ͼƬ
     */
    public function get_module_infos($module_flash_infos)
    {
        if (!empty($module_flash_infos) && !empty($module_flash_infos['FlashImage'])) {
            $flash_image = $this->find('all', array('conditions' => array('FlashImage.flash_id' => $module_flash_infos['Flash']['id']), 'fields' => array('FlashImage.image', 'FlashImage.title', 'FlashImage.url')));
        }

        return $flash_image;
    }
}
