<?php

    class EcFlagWebserviceComponent extends Object
    {
        public $name = 'EcFlagWebservice'; // the name of your component
        public $uses = array('Application','ApplicationConfig','ApplicationConfigI18n');
        public $wsdl = '';
        public $login = '';
        public $password1 = '';
        public $username = '';
        public $password2 = '';
        public $token = '';

        public function startup($controller)
        {
            $this->controller = $controller;
            if (isset($this->controller->apps['Applications']['APP-API-WEBSERVICE']['status']) && $this->controller->apps['Applications']['APP-API-WEBSERVICE']['status'] == 1) {
                $app_value = $this->controller->apps['Applications']['APP-API-WEBSERVICE']['configs'];
                if (!empty($app_value)) {
                    $this->wsdl = $app_value['APP-API-WEBSERVICE-LOGISTICS-API-URL'];
                    $this->login = $app_value['APP-API-WEBSERVICE-HTTP-AUTH-NAME'];
                    $this->password1 = $app_value['APP-API-WEBSERVICE-HTTP-AUTH-PASSWORD'];
                    $this->username = $app_value['APP-API-WEBSERVICE-LOGISTICS-VISIT-USERNAME'];
                    $this->password2 = $app_value['APP-API-WEBSERVICE-LOGISTICS-VISIT-PASSWORD'];
                }
            }
        }
        public function __construct()
        {
            //			$this->wsdl = 'http://nbflag.myevergreen.com.cn:8080/Service1.asmx?wsdl';
//			$this->login = 'ecflag';
//			$this->password1 = 'eve@1234';
//			$this->username = 'changqing';
//			$this->password2 = '3BB1CE8770990392B7BC77155E33F928';
        }
        public function UserLoad()
        {
            try {
                //	$client = new SoapClient($this->wsdl,array("login"=>$this->login,"password"=>$this->password1,"proxy_host"=>$this->proxy_host,"proxy_port"=>$this->proxy_port));
                $client = new SoapClient($this->wsdl, array('login' => $this->login, 'password' => $this->password1));

                $user_load_result = $client->__call('UserLoad', array('UserLoad' => array('username' => $this->username, 'password' => $this->password2)));
                $user_load_result = (array) $user_load_result;
                $user_load_result_str = $user_load_result['UserLoadResult'];
                $user_load_result_array1 = explode(':', $user_load_result_str);
                $user_load_result_array2 = explode(',', $user_load_result_str);
                if ($user_load_result_array1[0] == 1) {
                    $this->token = $user_load_result_array2[1];

                    return true;
                } else {
                    return;//$user_load_result_str;
                }
            } catch (Exception $e) {
                return false;
            }
        }
        /**1. 查询锁定订单
        **功能：根据订单的锁定状态，返回升序EC_Order_ID
        **参数：1）lock           锁定状态
        **     2）count          最大返回订单数
        **     3）EC_Order_ID    从这个订单开始升序查询
        */
        public function GetOrderByLock($lock, $count, $EC_Order_ID)
        {
            $user_load_info = $this->UserLoad();
            if ($user_load_info === true) {
                //登入成功
//				$client = new SoapClient($this->wsdl,array("login"=>$this->login,"password"=>$this->password1,"proxy_host"=>$this->proxy_host,"proxy_port"=>$this->proxy_port));
                $client = new SoapClient($this->wsdl, array('login' => $this->login, 'password' => $this->password1));
                $get_order_by_lock_result = $client->__call('GetOrderByLock', array('GetOrderByLock' => array('token' => $this->token, 'Lock' => $lock, 'count' => $count, 'EC_Order_ID' => $EC_Order_ID)));
                $get_order_by_lock_result = (array) $get_order_by_lock_result;

                return $get_order_by_lock_result;
            } else {
                //登入失败
                return $user_load_info;
            }
        }

        /**	2. 查询订单详情
         **	功能：根据订单号，返回订单详情
         **	参数：1）EC_Order_ID   要查询的订单号（一个或者多个，中间用|来分隔）
         **	返回：状态代码:状态消息[,EC_Order_ID1|Lock|Order_Status_ID;...EC_Order_ID9|Lock|Order_Status_ID;...].
         */
        public function GetOrderDetail($EC_Order_ID)
        {
            $user_load_info = $this->UserLoad();
            if ($user_load_info === true) {
                //登入成功 

//				$client = new SoapClient($this->wsdl,array("login"=>$this->login,"password"=>$this->password1,"proxy_host"=>$this->proxy_host,"proxy_port"=>$this->proxy_port));
                $client = new SoapClient($this->wsdl, array('login' => $this->login, 'password' => $this->password1));
                $get_order_by_lock_result = $client->__call('GetOrderDetail', array('GetOrderDetail' => array('token' => $this->token, 'EC_Order_ID' => $EC_Order_ID)));
                $get_order_by_lock_result = (array) $get_order_by_lock_result;

                return $get_order_by_lock_result;
            } else {
                //登入失败

                return $user_load_info;
            }
        }

        /*'3. 锁定单个订单
        '功能：根据订单号，锁定订单
        '参数：1）EC_Order_ID   要锁定的订单号
        '返回：状态代码:状态消息*/
        public function LockOrder($EC_Order_ID)
        {
            $user_load_info = $this->UserLoad();
            if ($user_load_info === true) {
                //����ɹ�
//				$client = new SoapClient($this->wsdl,array("login"=>$this->login,"password"=>$this->password1,"proxy_host"=>$this->proxy_host,"proxy_port"=>$this->proxy_port));
                $client = new SoapClient($this->wsdl, array('login' => $this->login, 'password' => $this->password1));
                $order_lock_result = $client->__call('LockOrder', array('LockOrder' => array('token' => $this->token, 'EC_Order_ID' => $EC_Order_ID)));
                $order_lock_result = (array) $order_lock_result;

                return $order_lock_result;
            } else {
                //登入失败
                return $user_load_info;
            }
        }

        /**4. 解锁单个订单
        **功能：根据订单号，解锁订单
        **参数：1）EC_Order_ID   要解锁的订单号
        **返回：状态代码:状态消息
        */
        public function UnLockOrder($EC_Order_ID)
        {
            $user_load_info = $this->UserLoad();
            if ($user_load_info === true) {
                //				$client = new SoapClient($this->wsdl,array("login"=>$this->login,"password"=>$this->password1,"proxy_host"=>$this->proxy_host,"proxy_port"=>$this->proxy_port));
                $client = new SoapClient($this->wsdl, array('login' => $this->login, 'password' => $this->password1));
                $un_order_lock_result = $client->__call('UnLockOrder', array('UnLockOrder' => array('token' => $this->token, 'EC_Order_ID' => $EC_Order_ID)));
                $un_order_lock_result = (array) $un_order_lock_result;

                return $un_order_lock_result;
            } else {
                //登入失败
                return $user_load_info;
            }
        }
        /**	5. 商品库存查询
         **	功能：根据ItemID，查询库存数量
         **	参数：1）ItemID   要查询的ItemID（一个或者多个，中间用|来分隔）
         **	返回：状态代码:状态消息[,InventoryName|ItemID|Quantity|Lock_Qty|Location|Notes;...InventoryName|...|Notes;...].
         */
        public function GetProductInventory($ItemID)
        {
            $user_load_info = $this->UserLoad();
            if ($user_load_info === true) {
                //登入成功 
                $client = new SoapClient($this->wsdl, array('login' => $this->login, 'password' => $this->password1));
                $get_order_by_lock_result = $client->__call('GetProductInventory', array('GetProductInventory' => array('token' => $this->token, 'ItemID' => $ItemID)));
                $get_order_by_lock_result = (array) $get_order_by_lock_result;
                $ec_product_sku = array();
                $ec_product = explode(',', $get_order_by_lock_result['GetProductInventoryResult']);
                if (isset($ec_product[0]) && $ec_product[0] == '1:ok' && isset($ec_product[1])) {
                    $ec_product_array = explode(';', $ec_product[1]);
                    foreach ($ec_product_array as $eck => $ecv) {
                        $ecflag_product = explode('|', $ecv);
                        if (!empty($ecflag_product[1])) {
                            $ec_product_sku[$ecflag_product[1]][$eck]['warhouse_name'] = isset($ecflag_product[0]) ? $ecflag_product[0] : '';
                            $ec_product_sku[$ecflag_product[1]][$eck]['product_code'] = isset($ecflag_product[1]) ? $ecflag_product[1] : '';
                            $ec_product_sku[$ecflag_product[1]][$eck]['product_quantity'] = isset($ecflag_product[2]) ? $ecflag_product[2] : '';
                            $ec_product_sku[$ecflag_product[1]][$eck]['ec_lock_qty'] = isset($ecflag_product[3]) ? $ecflag_product[3] : '';
                            $ec_product_sku[$ecflag_product[1]][$eck]['ec_location'] = isset($ecflag_product[4]) ? $ecflag_product[4] : '';
                            $ec_product_sku[$ecflag_product[1]][$eck]['ec_notes'] = isset($ecflag_product[5]) ? $ecflag_product[5] : '';
                        }
                    }
                }

                return $ec_product_sku;
            } else {
                //登入失败

                return $user_load_info;
            }
        }
        /**	6. 商品调仓请求
         **	功能：根据FromWarehouse，ToWarehouse，ItemID，Qty 进行调仓
         **	参数：1) FromWarehouse    从什么仓库调货（1=虚拟仓，2=零售仓）
         **        2) ToWarehouse      要调到什么仓库去（1=虚拟仓，2=零售仓）
         **        3) ItemID           要调的商品货号
         **        4) Qty              调仓数量
         **	返回：状态代码:状态消息.
         */
        public function AdjustInventory($FromWarehouse = 1, $ToWarehouse = 2, $ItemID = '', $Qty = '10', $remark = '')
        {
            $user_load_info = $this->UserLoad();
            if ($user_load_info === true) {
                //登入成功 
                $client = new SoapClient($this->wsdl, array('login' => $this->login, 'password' => $this->password1));

                $get_inventory_result = $client->__call('AdjustInventory', array('AdjustInventory' => array('token' => $this->token, 'FromWarehouse' => $FromWarehouse, 'ToWarehouse' => $ToWarehouse, 'ItemID' => $ItemID, 'Qty' => $Qty)));
            //	$get_inventory_result = $client->__call('AdjustInventory',array("AdjustInventory"=>array("token"=>$this->token,"FromWarehouse"=>$FromWarehouse,"ToWarehouse"=>$ToWarehouse,"ItemID"=>$ItemID,"Qty"=>$Qty,"Remark"=>$remark)));
//				pr($get_inventory_result);die;
                $get_inventory_result = (array) $get_inventory_result;

                return $get_inventory_result;
            } else {
                //登入失败

                return $user_load_info;
            }
        }
    }
    /*
    $service = new EcFlagWebservice();
    $user_load_info = $service->GetOrderByLock(false,20,50858836742908);
    echo "<pre>";
    print_r($user_load_info);
    
    $service = new EcFlagWebservice();
    $user_load_info = $service->GetOrderDetail(50858836742908);
    echo "<pre>";
    print_r($user_load_info);
    
    $service = new EcFlagWebservice();
    $user_load_info = $service->LockOrder(5085883);
    echo "<pre>";
    print_r($user_load_info);

    $service = new EcFlagWebservice();
    $user_load_info = $service->UnLockOrder(5085883);
    echo "<pre>";
    print_r($user_load_info);
*/
    /*	echo "<pre>";
        $username = "changqing";
        $password = "3BB1CE8770990392B7BC77155E33F928";
        $wsdl = "http://192.168.123.123/EC_Flag_WebService/Service1.asmx?wsdl";
        $client = new SoapClient($wsdl,array('proxy_host'=>"192.168.10.30",'proxy_port'=>3129));
        $vem = $client->__call('UserLoad',array($username,$password));
        print_r($vem);
    */;
