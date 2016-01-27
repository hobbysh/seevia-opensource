<?php

/*****************************************************************************
 * svcms ���Թ���
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
class contact extends AppModel
{
    /*
     * @var $useDbConfig ���ݿ�����
     */
    public $useDbConfig = 'cms';
    public $name = 'Contact';

    /*
    *��ȡ��ҵ����֪��ʽ����
    * @param  ��ѯ��������
    * @return $sel_list ����param��������ҵ����֪��ʽ����
    */
    public function get_contact_sel_list($params)
    {
        $sel_list = array();
        if (isset($params['industry'])) {
            $industry = preg_split('/[\n\r\t\s]+/i', $params['industry']);
            $sel_list['industry'] = array_filter($industry);
        }
        if (isset($params['learn_us'])) {
            $learn_us = preg_split('/[\n\r\t\s]+/i', $params['learn_us']);
            $sel_list['learn_us'] = array_filter($learn_us);
        }

        return $sel_list;
    }
}
