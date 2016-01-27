<?php

/**
 * 邮件订阅模型.
 */
class NewsletterList extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name NewsletterList 邮件地址表
     */
    public $name = 'NewsletterList';

    //检查email是否重用
    /**
     * check_unique_email方法，检查email是否重用.
     *
     * @param $email 输入$email
     * @param $id 输入$id
     *
     * @return $data 判断id是否等于0，如果等于0、输入email值，并且使状态值为1（有效）；如果id不等于0、则使id不等于已存在的id并且输入email值。将结果返回
     */
    public function check_unique_email($email, $id = 0)
    {
        if ($id == 0) {
            $condition = " NewsletterList.email='$email' and NewsletterList.status = '1'";
        } else {
            $condition = " NewsletterList.id <> $id and NewsletterList.email='$email'";
        }
        $data = $this->find('count', array('conditions' => $condition));

        return $data;
    }

    /**
     * check_unique_email_by_email方法，通过Email检查email是否重用.
     *
     * @param $email 输入$email
     * @param $id 输入$id
     *
     * @return $data 判断id是否等于0，如果等于0、输入email值；如果id不等于0、则使id不等于已存在的id并且输入email值。将结果返回
     */
    public function check_unique_email_by_email($email, $id = 0)
    {
        if ($id == 0) {
            $condition = " NewsletterList.email='$email'";
        } else {
            $condition = " NewsletterList.id <> $id and NewsletterList.email='$email'";
        }
        $data = $this->findCount($condition);

        return $data;
    }
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        if (isset($params['ControllerObj'])) {
            if (isset($params['ControllerObj']->configs['article_category_page_list_number'])) {
                $limit = $params['ControllerObj']->configs['article_category_page_list_number'];
            }
        }
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        $result = array('flag' => true);

        return $result;
    }
}
