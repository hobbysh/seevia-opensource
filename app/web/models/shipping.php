<?php

/**
 * 配送方式模型.
 *
 * @var 用来解决PHP4中的一些奇怪的类名
 * @var 设置模型关联
 */
class shipping extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'Shipping';
    public $hasOne = array('ShippingI18n' => array('className' => 'ShippingI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'shipping_id',
        ),
    );

    /**
     *函数set_locale 设置配送方式语言.
     *
     *@param $locale 语言类型
     *@param $conditions 配送语言信息
     */
    public function set_locale($locale)
    {
        $conditions = " ShippingI18n.locale = '".$locale."'";
        $this->hasOne['ShippingI18n']['conditions'] = $conditions;
    }
    /**
     * 函数availables 语言判断配送方式是否可用.
     *
     * @param $lists 获取配送方式判断列表
     *
     * @return $lists 配送方式判断列表
     */
    public function availables()
    {
        $lists = $this->findall("Shipping.status = '1' ", '', 'Shipping.orderby asc');
        foreach ($lists as $k => $v) {
            $lists[$k]['Shipping']['fee'] = 0;
        }

        return $lists;
    }

    /**
     * 函数USPSParcelRate 用于计算包裹费用.
     *
     * @param $weight 包裹重量
     * @param $dest_zip zip的目的地
     * @param $userName 用户名
     * @param $orig_zip zip的发源
     * @param $url URL地址
     * @param $ch 函数初始化
     * @param $data 数据
     * @param $result 介绍结果
     * @param $xml_parser xml解析
     * @param $params 包裹信息
     * @param $level 计算标准
     * @param $start_level 起始标准为1
     *
     * @return $params 包裹费用信息
     */
    public function USPSParcelRate($weight, $dest_zip, $userName, $orig_zip)
    {
        $url = 'http://Production.ShippingAPIs.com/ShippingAPI.dll';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $data = "API=RateV3&XML=<RateV3Request USERID=\"$userName\"><Package ID=\"1ST\"><Service>PRIORITY</Service><ZipOrigination>$orig_zip</ZipOrigination><ZipDestination>$dest_zip</ZipDestination><Pounds>$weight</Pounds><Ounces>0</Ounces><Size>REGULAR</Size><Machinable>TRUE</Machinable></Package></RateV3Request>";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        $data = strstr($result, '<?');
        $xml_parser = xml_parser_create();
        xml_parse_into_struct($xml_parser, $data, $vals, $index);
        xml_parser_free($xml_parser);
        $params = array();
        $level = array();
        foreach ($vals as $xml_elem) {
            if ($xml_elem['type'] == 'open') {
                if (array_key_exists('attributes', $xml_elem)) {
                    list($level[$xml_elem['level']], $extra) = array_values($xml_elem['attributes']);
                } else {
                    $level[$xml_elem['level']] = $xml_elem['tag'];
                }
            }
            if ($xml_elem['type'] == 'complete') {
                $start_level = 1;
                $php_stmt = '$params';
                while ($start_level < $xml_elem['level']) {
                    $php_stmt .= '[$level['.$start_level.']]';
                    ++$start_level;
                }
                $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
                eval($php_stmt);
            }
        }
        curl_close($ch);

        return $params['RATEV3RESPONSE']['1ST']['1']['RATE'];
    }
}
