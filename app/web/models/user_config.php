<?php

/**
 * Seevia 用户设置模型.
 */
class UserConfig extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'UserConfig';
    public $hasOne = array('UserConfigI18n' => array('className' => 'UserConfigI18n',
            'conditions' => array('locale' => LOCALE),
            'dependent' => true,
            'foreignKey' => 'user_config_id',
        ),
    );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions ['UserConfigI18n.locale'] = $locale;
        $this->locale = $locale;
        $this->hasOne['UserConfigI18n']['conditions'] = $conditions;
    }

    public function get_myconfig($user_id)
    {
        $condition = " UserConfig.user_id = '".$user_id."'";
        $configs = $this->findAll($condition);

        return $configs;
    }

    public function get_user_config_info($user_config_code)
    {
        $condition = "UserConfig.user_id = 0 and UserConfig.code = '".$user_config_code."'";
        $user_config_info = $this->find($condition);
    }
}
