<?php
/**
 * Soap component for handling soap requests in Cake.
 *
 * @author      Hobbysh (hobbysh@gmail.com)
 * @copyright   Copyright 2009, 上海实玮网络科技有限公司
 * @license     http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class WechatComponent extends Component
{
    public $name = 'Wechat';
    public $components = array('RequestHandler');
    
    /**
     * @var 商家appkey
     */
    public $appKey;

    /**
     * @var 商家appsecret
     */
    public $appSecret;
    
    /**
     * @var 商家Token
     */
    public $appToken;
    
    /*
        调用接口
    */
    public function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            $data = $this->to_josn($data);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        return json_decode($output, true);
    }

    /*
        $data  需要转换josn提交的数据
    */
    public function to_josn($data)
    {
        $this->arrayRecursive($data, 'urlencode');
        $json = json_encode($data);

        return urldecode($json);
    }

    /************************************************************** 
    * 对数组中所有元素做处理,保留中文 
    * @param string &$array 要处理的数组
    * @param string $function 要执行的函数 
    * @return boolean $apply_to_keys_also 是否也应用到key上 
    * @access public 
    * 
    *************************************************************/
    public function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        --$recursive_counter;
    }
    
    /*
        接口调试
    */
    function api_test($shop,$api_url,$data=null){
        $result=array();
        try{
            $access_token=$shop['app_token'];
            $api_url=$api_url."?access_token=".$access_token;
            $result=$this->https_request($api_url,$data);
        }catch (Exception $e){
            $result=array();
            $result['errcode']='-1';
            $result['errmsg']=$e->getMessage();
        }
        return $result;
    }
    
    
    /*
        商品下载
    */
    function api_get_product_all($shop,$data=null){
        $result=array();
        try{
            $access_token=$shop['app_token'];
            $api_url="https://api.weixin.qq.com/merchant/getbystatus?access_token=".$access_token;
            $result=$this->https_request($api_url,$data);
        }catch (Exception $e){
            $result=array();
            $result['errcode']='-1';
            $result['errmsg']=$e->getMessage();
        }
        return $result;
    }
    
    /*
        订单下载
    */
    function api_get_order_all($shop,$data=null){
        $result=array();
        try{
            $access_token=$shop['app_token'];
            $api_url="https://api.weixin.qq.com/merchant/order/getbyfilter?access_token=".$access_token;
            $result=$this->https_request($api_url,$data);
        }catch (Exception $e){
            $result=array();
            $result['errcode']='-1';
            $result['errmsg']=$e->getMessage();
        }
        return $result;
    }
    
    /*
        订单发货
    */
    function delivery_send($shop,$data=null){
        $result=array();
        try{
            $access_token=$shop['app_token'];
            $api_url="https://api.weixin.qq.com/merchant/order/setdelivery?access_token=".$access_token;
            $result=$this->https_request($api_url,$data);
        }catch (Exception $e){
            $result=array();
            $result['errcode']='-1';
            $result['errmsg']=$e->getMessage();
        }
        return $result;
    }
    
    /*
        增加商品库存
    */
    function stock_add($shop,$data=null){
        $result=array();
        try{
            $access_token=$shop['app_token'];
            $api_url="https://api.weixin.qq.com/merchant/stock/add?access_token=".$access_token;
            $result=$this->https_request($api_url,$data);
        }catch (Exception $e){
            $result=array();
            $result['errcode']='-1';
            $result['errmsg']=$e->getMessage();
        }
        return $result;
    }
    
    /*
        减少商品库存
    */
    function stock_reduce($shop,$data=null){
        $result=array();
        try{
            $access_token=$shop['app_token'];
            $api_url="https://api.weixin.qq.com/merchant/stock/reduce?access_token=".$access_token;
            $result=$this->https_request($api_url,$data);
        }catch (Exception $e){
            $result=array();
            $result['errcode']='-1';
            $result['errmsg']=$e->getMessage();
        }
        return $result;
    }
    
    /*
        商品下架
    */
    function product_soldout($shop,$data=null){
        $result=array();
        try{
            $access_token=$shop['app_token'];
            $api_url="https://api.weixin.qq.com/merchant/modproductstatus?access_token=".$access_token;
            $result=$this->https_request($api_url,$data);
        }catch (Exception $e){
            $result=array();
            $result['errcode']='-1';
            $result['errmsg']=$e->getMessage();
        }
    }
}
