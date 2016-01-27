<?php

App::import('Controller', 'Commons');//加载公共控制器
class ProductCodeCreatesController extends AppController
{
    public $name = 'ProductCodeCreates';
    public $components = array('Pagination','RequestHandler','Phpexcel');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $uses = array('Application','Product','ProductI18n','Brand','InformationResource','InformationResourceI18n','Profile','ProfileFiled','ProfilesFieldI18n','OperatorLog','CategoryType','CategoryProduct');

    public function index()
    {
        $this->operator_privilege('product_code_creates_view');
        $this->set('title_for_layout', $this->ld['commodity_code_generation_tool'].' - '.$this->configs['shop_name']);
        $this->menu_path = array('root' => '/product/','sub' => '/products/');
        $this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products');
        $this->navigations[] = array('name' => $this->ld['commodity_code_generation_tool'],'url' => '');
        $brand_tree = $this->Brand->brand_tree($this->backend_locale);//品牌
        $this->CategoryType->set_locale($this->backend_locale);
        $category_type_tree = $this->CategoryType->tree();// 类目
        $category_tree = $this->CategoryProduct->tree('P', $this->locale);//分类树
        
        $this->set('brand_tree',$brand_tree);
        $this->set('category_type_tree',$category_type_tree);
        $this->set('category_tree',$category_tree);
        
        $yb_cid = $this->InformationResource->information_formated('yb_cid', 'chi', false);
        if (!empty($yb_cid)) {
            ksort($yb_cid['yb_cid']);
            $this->set('yb_cid', $yb_cid['yb_cid']);
        }
        if (isset($brand_tree)) {
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'点击了货号自动计算', $this->admin['id']);
            }
        }
        $this->set('brand_tree', $brand_tree);
    }

    public function brand_csv_export($code)
    {
        $filename = '品牌'.date('Ymd').'.xls';
        $this->Profile->set_locale($this->locale);
        $this->Profile->hasOne = array();
        $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $code, 'Profile.status' => 1)));
        $tmp = array();
        $fields_array = array();
        $newdatas = array();
        if (isset($profile_id) && !empty($profile_id)) {
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
            foreach ($profilefiled_info as $k => $v) {
                $tmp[] = $v['ProfilesFieldI18n']['description'];
                $fields_array[] = $v['ProfileFiled']['code'];
            }
        }
        $newdatas[] = $tmp;
        $brand_info = $this->Brand->find('all', array('order' => 'Brand.id desc', 'limit' => 10));//'recursive'=>-1
        foreach ($brand_info as $k => $v) {
            $user_tmp = array();
            foreach ($fields_array as $kk => $vv) {
                $fields_kk = explode('.', $vv);
                $user_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
            }
            $newdatas[] = $user_tmp;
        }
        $this->Phpexcel->output($filename, $newdatas);
        exit;
    }

    public function ybcid_csv_export()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];

        $filename = '商品类目.csv';
        $encoded_filename = urlencode($filename);
        $encoded_filename = str_replace('+', '%20', $encoded_filename);

        if (preg_match('/MSIE/', $ua)) {
            header('Content-Disposition: attachment; filename="'.$encoded_filename.'"');
        } elseif (preg_match('/Firefox/', $ua)) {
            header('Content-Disposition: attachment; filename*="utf8\'\''.$filename.'"');
        } else {
            header('Content-Disposition: attachment; filename="'.$filename.'"');
        }
        Configure::write('debug', 0);
        header('Content-type: application/vnd.ms-excel;charset=utf-8');
        $yb_cid = $this->InformationResource->information_formated('yb_cid', 'chi', false);
        $str = '类目名称,类目代码'."\n";
        echo iconv('utf-8', 'gbk//IGNORE', $str);
        ksort($yb_cid['yb_cid']);
        foreach ($yb_cid['yb_cid'] as $k => $v) {
            $str = $k.','.$v."\n";
            echo iconv('utf-8', 'gbk//IGNORE', $str);
        }

        exit;
    }

    public function doinsertybcid()
    {
        //pr($_REQUEST);die;
        $result['message'] = $this->ld['complete_failure'];
        $result['flag'] = 2;
        if (!empty($this->data)) {
            $parent = $this->InformationResource->find('first', array('conditions' => array('InformationResource.code' => 'yb_cid'), 'fields' => array('InformationResource.id')));
            if (!empty($_REQUEST['yb_cid_code_h'])) {
                //update
                if ($_REQUEST['yb_cid_code_h'] != $this->data['InformationResource']['information_value']) {
                    $c = $this->InformationResource->find('count', array('conditions' => array('information_value' => $this->data['InformationResource']['information_value'], 'parent_id' => $parent['InformationResource']['id'])));
                    if ($c > 0) {
                        $result['flag'] = 2;
                        $result['message'] = '类目代码重复';
                        die(json_encode($result));
                    }
                }
                $r = $this->InformationResource->find('first', array('conditions' => array('information_value' => $_REQUEST['yb_cid_code_h'], 'parent_id' => $parent['InformationResource']['id'])));

                $r['InformationResource']['information_value'] = $this->data['InformationResource']['information_value'];
                $r['InformationResourceI18n']['name'] = $this->data['InformationResourceI18n'][0]['name'];
                $this->InformationResource->save($r['InformationResource']);
                $this->InformationResourceI18n->save($r['InformationResourceI18n']);
                $result['flag'] = 1;
                $result['code'] = $this->data['InformationResource']['information_value'];
                $result['name'] = $this->data['InformationResourceI18n'][0]['name'];
                $result['act'] = 2;
            } else {
                $r = $this->InformationResource->find('count', array('conditions' => array('information_value' => $this->data['InformationResource']['information_value'], 'parent_id' => $parent['InformationResource']['id'])));
                if ($r > 0) {
                    $result['flag'] = 2;
                    $result['message'] = '类目代码重复';
                } else {
                    $this->data['InformationResource']['parent_id'] = $parent['InformationResource']['id'];

                    $this->InformationResource->saveAll(array('InformationResource' => $this->data['InformationResource']));
                    $this->InformationResourceI18n->saveAll(array('locale' => 'chi', 'information_resource_id' => $this->InformationResource->getLastInsertId(), 'name' => $this->data['InformationResourceI18n'][0]['name']));
                    $result['flag'] = 1;
                    $result['code'] = $this->data['InformationResource']['information_value'];
                    $result['name'] = $this->data['InformationResourceI18n'][0]['name'];
                    $result['act'] = 1;
                }
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function del_ybcid()
    {
        $result['message'] = $this->ld['complete_failure'];
        $result['flag'] = 2;
        if (!empty($this->data)) {
            $parent = $this->InformationResource->find('first', array('conditions' => array('InformationResource.code' => 'yb_cid'), 'fields' => array('InformationResource.id')));
            if (!empty($_REQUEST['yb_cid_code_h'])) {
                $r = $this->InformationResource->find('first', array('conditions' => array('InformationResource.information_value' => $_REQUEST['yb_cid_code_h'], 'parent_id' => $parent['InformationResource']['id'])));
        //		pr($r);

                $this->InformationResourceI18n->deleteAll(array('InformationResourceI18n.information_resource_id' => $r['InformationResource']['id']));
                $this->InformationResource->deleteAll(array('InformationResource.id' => $r['InformationResource']['id']));
                $result['flag'] = 1;
                $result['code'] = $this->data['InformationResource']['information_value'];
                $result['name'] = $this->data['InformationResourceI18n'][0]['name'];
                $result['act'] = 3;
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
