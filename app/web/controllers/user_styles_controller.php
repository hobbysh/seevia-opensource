<?php

class UserStylesController extends AppController
{
    public $name = 'UserStyles';
    public $helpers = array('Html','Flash','Cache','Pagination');
    public $uses = array('Attribute','UserStyle','UserStyleValue','ProductStyle','ProductStyleI18n','ProductType','ProductTypeI18n','UserFans','Blog','StyleTypeGroup','StyleTypeGroupAttributeValue','Order','OrderProduct');
    public $components = array('RequestHandler','Cookie','Session','Pagination');

    public function index($page = 1, $limit = 10)
    {
        //登录验证
        $this->checkSessionUser();
        $this->layout = 'usercenter';            //引入模版
        $this->page_init();                        //页面初始化
        $this->pageTitle = $this->ld['user_template'].' - '.$this->configs['shop_title'];
        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/');
        $this->ur_heres[] = array('name' => $this->ld['user_template'], 'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);

        $id = $_SESSION['User']['User']['id'];
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($id);
        $this->set('focuscount', $focus);

        $condition['UserStyle.user_id'] = $id;
        $joins = array(
            array('table' => 'svoms_product_style_i18ns',
              'alias' => 'ProductStyleI18n',
              'type' => 'left',
              'conditions' => array("ProductStyleI18n.style_id = UserStyle.style_id and ProductStyleI18n.locale='".LOCALE."'"),
             ),
            array('table' => 'svoms_product_type_i18ns',
              'alias' => 'ProductTypeI18n',
              'type' => 'left',
              'conditions' => array("ProductTypeI18n.type_id = UserStyle.type_id and ProductTypeI18n.locale='".LOCALE."'"),
             ),
        );

        $fields[] = 'UserStyle.*';
        $fields[] = 'ProductStyleI18n.style_name';
        $fields[] = 'ProductTypeI18n.name';
        $user_style_info = $this->UserStyle->find('all', array('conditions' => $condition, 'fields' => $fields, 'joins' => $joins, 'order' => 'UserStyle.created desc'));

        $userstyle_ids = array();
        foreach ($user_style_info as $v) {
            $userstyle_ids[] = $v['UserStyle']['id'];
        }

        $order_pro_cond['Order.user_id'] = $id;
        $order_pro_cond['Order.status'] = 1;
        $order_pro_cond['Order.shipping_status'] = 2;
        $order_pro_cond['OrderProduct.user_style_id'] = $userstyle_ids;
        $order_pro_cond['ProductI18n.locale'] = LOCALE;
        $order_pro_info = $this->OrderProduct->find('all', array('conditions' => $order_pro_cond, 'order' => 'Order.created asc'));

        $user_style_ids = array();
        $order_pro_data = array();
        foreach ($order_pro_info as $k => $v) {
            $user_style_ids[] = $v['OrderProduct']['user_style_id'];
            $order_pro_data[$v['OrderProduct']['user_style_id']] = $v;
        }

        $product_type_data = array();
        $product_style_data = array();
        $user_style_data = array();
        foreach ($user_style_info as $v) {
            if (in_array($v['UserStyle']['id'], $user_style_ids)) {
                $product_type_data[$v['UserStyle']['type_id']] = $v['ProductTypeI18n']['name'];
                $product_style_data[$v['UserStyle']['style_id']] = $v['ProductStyleI18n']['style_name'];
                $user_style_data[$v['UserStyle']['type_id']][] = $v;
            }
        }
        $this->set('user_style_list', $user_style_data);
        $this->set('order_pro_data', $order_pro_data);
        $this->set('user_style_ids', $user_style_ids);

        $this->set('product_type_data', $product_type_data);
        $this->set('product_style_data', $product_style_data);
    }

    public function update_remark()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 0;
        if ($this->RequestHandler->isPost()) {
           if(!empty($this->data)){
           	     $this->data=$this->clean_xss($this->data);
	            if ($this->UserStyle->save($this->data)) {
	                $result['flag'] = 1;
	            }
            }
        }
        die(json_encode($result));
    }

    /*
    function view($user_style_id=0){
        //登录验证
        $this->checkSessionUser();
        $this->layout = "usercenter";			//引入模版
        $this->page_init();						//页面初始化
        
        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => "/users/");
        $this->ur_heres[] = array('name' => $this->ld['user_template'], 'url' => "/user_styles/");
        
        //获取我的信息
        $user_list=$this->User->find("first",array("conditions"=>array("User.id"=>$_SESSION['User']["User"]["id"])));
        if($user_list["User"]["rank"]>0){
            $rank_list=$this->UserRank->find("first",array("conditions"=>array("UserRank.id"=>$user_list["User"]["rank"])));
            $user_list["User"]["rank_name"]=$rank_list["UserRankI18n"]["name"];
        }
        $this->set('user_list',$user_list);
        
        $id=$_SESSION['User']["User"]["id"];
        //粉丝数量
        $fans=$this->UserFans->find_fanscount_byuserid($id);
        $this->set("fanscount",$fans);
        //日记数量
        $blog=$this->Blog->find_blogcount_byuserid($id);
        $this->set("blogcount",$blog);
        //关注数量
        $focus=$this->UserFans->find_focuscount_byuserid($id);
        $this->set("focuscount",$focus);
        
        if($this->RequestHandler->isPost()){
            if($this->data['UserStyle']['default_status']==1){
                //将其他默认状态改成0（相同版型，相同规格，相同属性组）
                $this->UserStyle->updateAll(array('UserStyle.default_status'=>'0'),array('user_id'=>$id,'style_id'=>$this->data['UserStyle']['style_id'] ,'type_id'=>$this->data['UserStyle']['type_id'],'attribute_code'=>$this->data['UserStyle']['attribute_code']));
            }
            $this->UserStyle->saveAll($this->data['UserStyle']);
            $user_style_id=$this->UserStyle->id;
            
            if(isset($this->data['UserStyleValue'])){
                $this->UserStyleValue->deleteAll(array("UserStyleValue.user_style_id"=>$user_style_id));
                foreach($this->data['UserStyleValue'] as $k=>$v){
                    $this->data['UserStyleValue'][$k]['user_style_id']=isset($user_style_id)?$user_style_id:0;
                }
                $this->UserStyleValue->saveAll($this->data['UserStyleValue']);
            }
            $this->redirect('/user_styles/');
        }
        
        
        $condition["UserStyle.user_id"]=$id;
        $condition["UserStyle.id"]=$user_style_id;
        $user_style_data=$this->UserStyle->find('first',array("conditions"=>$condition));
        if(empty($user_style_data)){
            $this->ur_heres[] = array('name' => $this->ld['add'].' - '.$this->ld['user_template'], 'url' => "");
            $this->pageTitle = $this->ld['add'].' - '.$this->ld['user_template'].' - '.$this->configs['shop_title'];
        }else{
            $this->ur_heres[] = array('name' => $this->ld['edit'].' - '.$this->ld['user_template'].' - '.$user_style_data['UserStyle']['user_style_name'], 'url' => "");
            $this->pageTitle = $this->ld['edit'].' - '.$this->ld['user_template'].' - '.$user_style_data['UserStyle']['user_style_name'].' - '.$this->configs['shop_title'];
            
            $style_id=$user_style_data['UserStyle']['style_id'];
            $product_type=$user_style_data['UserStyle']['type_id'];
            $StyleTypeGroup_list=$this->StyleTypeGroup->find("all",array("conditions"=> array("StyleTypeGroup.style_id"=>$style_id,"StyleTypeGroup.type_id"=>$product_type,"StyleTypeGroup.status"=>1),"order"=>"StyleTypeGroup.orderby asc","fields"=>"StyleTypeGroup.id,StyleTypeGroup.group_name"));
            $this->set('StyleTypeGroup_list',$StyleTypeGroup_list);
        }
        $this->set('ur_heres', $this->ur_heres);
        $this->set('user_style_data',$user_style_data);
        
        $ProductStyle_list=$this->ProductStyle->find('all',array('conditions'=>array('ProductStyle.status'=>1),'order'=>'ProductStyle.id'));
        $ProductType_list=$this->ProductType->find('all',array('conditions'=>array('ProductType.id !='=>0,'ProductType.status'=>1),'order'=>'ProductType.id'));
        $this->set('ProductStyle_list',$ProductStyle_list);
        $this->set('ProductType_list',$ProductType_list);
    }
    */
    //获取替换的尺寸模板
    public function change_measure()
    {
        if ($this->RequestHandler->isPost()) {
        	if (isset($_POST)) {
	        	$_POST=$this->clean_xss($_POST);
	        }
            Configure::write('debug', 1);
            $this->layout = 'ajax';
            //获取我的信息
            $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
            if ($user_list['User']['rank'] > 0) {
                $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
                $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
            }
            $this->set('user_list', $user_list);

            $id = $_SESSION['User']['User']['id'];
            if (isset($_POST['user_type_id'])) {
                $condition['UserStyle.type_id'] = $_POST['user_type_id'];
            }
            $condition['UserStyle.user_id'] = $id;
            $joins = array(
                array('table' => 'svoms_product_style_i18ns',
                  'alias' => 'ProductStyleI18n',
                  'type' => 'left',
                  'conditions' => array("ProductStyleI18n.style_id = UserStyle.style_id and ProductStyleI18n.locale='".LOCALE."'"),
                 ),
                array('table' => 'svoms_product_type_i18ns',
                  'alias' => 'ProductTypeI18n',
                  'type' => 'left',
                  'conditions' => array("ProductTypeI18n.type_id = UserStyle.type_id and ProductTypeI18n.locale='".LOCALE."'"),
                 ),
            );

            $fields[] = 'UserStyle.*';
            $fields[] = 'ProductStyleI18n.style_name';
            $fields[] = 'ProductTypeI18n.name';
            $user_style_info = $this->UserStyle->find('all', array('conditions' => $condition, 'fields' => $fields, 'joins' => $joins, 'order' => 'UserStyle.created desc'));

            $default_style_id = isset($_POST['user_style_id']) ? $_POST['user_style_id'] : 0;
            $userstyle_ids = array();
            foreach ($user_style_info as $k => $v) {
                //设置需要显示的默认选项
                if ($default_style_id != 0 && $default_style_id == $v['UserStyle']['id']) {
                    $user_style_info[$k]['UserStyle']['default_status'] = 1;
                } else {
                    $user_style_info[$k]['UserStyle']['default_status'] = 0;
                }
                $userstyle_ids[] = $v['UserStyle']['id'];
            }
            //pr($user_style_info);
            $order_pro_cond['Order.user_id'] = $id;
            $order_pro_cond['Order.status'] = 1;
            $order_pro_cond['Order.shipping_status'] = 2;
            $order_pro_cond['OrderProduct.user_style_id'] = $userstyle_ids;
            $order_pro_cond['ProductI18n.locale'] = LOCALE;
            $order_pro_info = $this->OrderProduct->find('all', array('conditions' => $order_pro_cond, 'order' => 'Order.created asc'));

            $user_style_ids = array();
            $order_pro_data = array();
            foreach ($order_pro_info as $k => $v) {
                $user_style_ids[] = $v['OrderProduct']['user_style_id'];
                $order_pro_data[$v['OrderProduct']['user_style_id']] = $v;
            }

            $product_type_data = array();
            $product_style_data = array();
            $user_style_data = array();
            foreach ($user_style_info as $v) {
                if (in_array($v['UserStyle']['id'], $user_style_ids)) {
                    $product_type_data[$v['UserStyle']['type_id']] = $v['ProductTypeI18n']['name'];
                    $product_style_data[$v['UserStyle']['style_id']] = $v['ProductStyleI18n']['style_name'];
                    $user_style_data[$v['UserStyle']['type_id']][] = $v;
                }
            }
            $this->set('user_style_list', $user_style_data);
            $this->set('order_pro_data', $order_pro_data);
            $this->set('user_style_ids', $user_style_ids);

            $this->set('product_type_data', $product_type_data);
            $this->set('product_style_data', $product_style_data);
        }
    }
    public function set_style_default()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
	if (isset($_POST)) {
		$_POST=$this->clean_xss($_POST);
	}
        $style_id = isset($_POST['id']) ? $_POST['id'] : 0;
        $default_status = isset($_POST['default_status']) ? $_POST['default_status'] : 0;
        $result['flag'] = 0;
        $result['content'] = $default_status;
        $user_style_info = $this->UserStyle->find('first', array('conditions' => array('UserStyle.id' => $style_id)));
        if (!empty($user_style_info) && isset($_SESSION['User'])) {
            $user_id = $_SESSION['User']['User']['id'];
            $user_style_info['UserStyle']['default_status'] = $default_status;
            $this->UserStyle->save($user_style_info);
            if ($default_status) {
                $this->UserStyle->updateAll(array('UserStyle.default_status' => '0'), array('UserStyle.id !=' => $user_style_info['UserStyle']['id'], 'UserStyle.user_id' => $user_id, 'UserStyle.style_id' => $user_style_info['UserStyle']['style_id'], 'type_id' => $user_style_info['UserStyle']['type_id'], 'attribute_code' => $user_style_info['UserStyle']['attribute_code']));
            }
            $result['flag'] = 1;
            $result['content'] = $default_status;
        }
        die(json_encode($result));
    }
    /*
    function remove($style_id){
        Configure::write('debug',0);
        $this->layout = "ajax";
        $user_style_info=$this->UserStyle->find('first',array('conditions'=>array('UserStyle.id'=>$style_id)));
        if(!empty($user_style_info)&&isset($_SESSION['User'])){
            $this->UserStyleValue->deleteAll(array('UserStyleValue.user_style_id'=>$style_id));
            $this->UserStyle->deleteAll(array('UserStyle.id'=>$style_id));
            $result['flag']=1;
        }
        die(json_encode($result));
    }
    
    function removeall(){
        Configure::write('debug',0);
        $this->layout = "ajax";
        $result['flag']=0;
        if(isset($_POST['userstyleIds'])&&!empty($_POST['userstyleIds'])&&isset($_SESSION['User'])){
            $userstyleIds_arr=split(";",$_POST['userstyleIds']);
            if(!empty($userstyleIds_arr)){
                $this->UserStyleValue->deleteAll(array('UserStyleValue.user_style_id'=>$userstyleIds_arr));
                $this->UserStyle->deleteAll(array('UserStyle.id'=>$userstyleIds_arr));
                $result['flag']=1;
            }
        }
        die(json_encode($result));
    }

    function get_StyleTypeGroup(){
        Configure::write('debug',0);
        $this->layout = "ajax";
        $result['flag']=0;
        $product_style_id=isset($_POST['product_style_id'])?$_POST['product_style_id']:0;
        $product_type_id=isset($_POST['product_type_id'])?$_POST['product_type_id']:0;
        $Group_data=array();
        $StyleTypeGroup_list=$this->StyleTypeGroup->find("all",array("conditions"=> array("StyleTypeGroup.style_id"=>$product_style_id,"StyleTypeGroup.type_id"=>$product_type_id,"StyleTypeGroup.status"=>1),"order"=>"StyleTypeGroup.orderby asc","fields"=>"StyleTypeGroup.id,StyleTypeGroup.group_name"));
        if(!empty($StyleTypeGroup_list)){
            foreach($StyleTypeGroup_list as $v){
                $Group_data[$v['StyleTypeGroup']['id']]=$v['StyleTypeGroup']['group_name'];
            }
            $result['flag']=1;
            $result['Group_data']=$Group_data;
        }
        die(json_encode($result));
    }
    
    function user_style_attr_value(){
        Configure::write('debug',1);
        $this->layout = "ajax";
        
        $product_style_id=isset($_POST['product_style_id'])?$_POST['product_style_id']:0;
        $product_type_id=isset($_POST['product_type_id'])?$_POST['product_type_id']:0;
        $group_name=isset($_POST['group_name'])?$_POST['group_name']:0;
        $user_style_id=isset($_POST['user_style_id'])?$_POST['user_style_id']:0;
        
        $style_type_group_id=0;
        
        //规格下拉
        $StyleTypeGroup_info=$this->StyleTypeGroup->find("first",array("conditions"=> array("StyleTypeGroup.style_id"=>$product_style_id,"StyleTypeGroup.type_id"=>$product_type_id,"StyleTypeGroup.group_name"=>$group_name)));
        
        if(!empty($StyleTypeGroup_info)){
            $style_type_group_id=$StyleTypeGroup_info['StyleTypeGroup']['id'];
        }
        $attr_list=$this->StyleTypeGroupAttributeValue->find("all",array("conditions"=> array("StyleTypeGroupAttributeValue.style_id"=>$product_style_id,"StyleTypeGroupAttributeValue.type_id"=>$product_type_id,"StyleTypeGroupAttributeValue.style_type_group_id"=>$style_type_group_id),"fields"=>"StyleTypeGroupAttributeValue.default_value,StyleTypeGroupAttributeValue.select_value,StyleTypeGroupAttributeValue.attribute_id"));
        $attr_ids = $this->ProductTypeAttribute->getattrids($product_type_id);
        $attr_group = $this->Attribute->find("all",array("conditions"=>array("Attribute.id"=>$attr_ids, "Attribute.status"=>1,'Attribute.type'=>'customize'),"fields"=>"Attribute.id,Attribute.code,Attribute.attr_type,AttributeI18n.name,AttributeI18n.default_value,AttributeI18n.attr_value","order"=>"Attribute.id"));
        
        $attr_values=array();
        $attr_value_infos=$this->Attribute->find('all',array('conditions'=>array('Attribute.id'=>$attr_ids),'fields'=>array('Attribute.id','AttributeI18n.default_value')));
        foreach($attr_value_infos as $k=>$v){
            $attr_values[$v['Attribute']['id']]=$v['AttributeI18n']['default_value'];
        }
        $show_attr_list=array();
        foreach($attr_group as $gk=>$gv){
            $show_attr_data=array();
            $show_attr_data['attribute_id']=$gv['Attribute']['id'];
            $show_attr_data['attr_name']=$gv['AttributeI18n']['name'];
            $show_attr_data['default_value']=$gv['AttributeI18n']['default_value'];
            $show_attr_data['select_value']=$gv['AttributeI18n']['attr_value'];
            $show_attr_data['attr_type']=$gv['Attribute']['attr_type'];
            foreach($attr_list as $ak=>$av){
                if($gv['Attribute']['id']==$av['StyleTypeGroupAttributeValue']['attribute_id']){
                    $show_attr_data['default_value']=!empty($av['StyleTypeGroupAttributeValue']['default_value'])?$av['StyleTypeGroupAttributeValue']['default_value']:$attr_values[$av['StyleTypeGroupAttributeValue']['attribute_id']];
                    $show_attr_data['select_value']=$av['StyleTypeGroupAttributeValue']['select_value'];
                }
            }
            $show_attr_list[$gk]=$show_attr_data;
        }
        if(!empty($user_style_id) && $user_style_id!=0){
            $user_style_data=$this->UserStyle->find('first',array('conditions'=>array('UserStyle.id'=>$user_style_id)));
            $user_style_value_data=$this->UserStyleValue->find('all',array('conditions'=>array('UserStyleValue.user_style_id'=>$user_style_id)));
            $user_style_value_data_list=array();
            foreach($user_style_value_data as $v){
                $user_style_value_data_list[$v['UserStyleValue']['attribute_id']]=$v['UserStyleValue']['attribute_value'];
            }
            $this->set('user_style_data',$user_style_data);
            $this->set('user_style_value_data_list',$user_style_value_data_list);
        }
        //版型规格尺寸列表
        $attrvaluelist=array();
        $pro_type_attr_type_list=array();//属性修改可选值列表
        if(isset($group_name)){
            $attrvalueInfo=$this->StyleTypeGroupAttributeValue->getattrvaluelist($product_style_id,$product_type_id,$group_name);
            foreach($attrvalueInfo as $v){
                $attrids[]=$v['StyleTypeGroupAttributeValue']['attribute_id'];
                $attrvaluelist[$v['StyleTypeGroupAttributeValue']['attribute_id']]=$v['StyleTypeGroupAttributeValue']['default_value'];
                if(trim($v['StyleTypeGroupAttributeValue']['select_value'])!=""){
                    $pro_type_attr_type_list[$v['StyleTypeGroupAttributeValue']['attribute_id']]=split("\r\n",$v['StyleTypeGroupAttributeValue']['select_value']);
                }
            }
            foreach($attr_values as $k=>$v){
                if(empty($attrvaluelist[$k])){
                    $attrvaluelist[$k]=$v;
                }
            }
        }
        $this->set('attrvaluelist',$attrvaluelist);
        $this->set('show_attr_list',$show_attr_list);
    }
        */
}
