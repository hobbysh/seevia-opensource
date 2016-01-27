<?php

/*****************************************************************************
 * Seevia 语言管理
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
class LanguagesController extends AppController
{
    public $name = 'Languages';
    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler');
    public $uses = array('Language','Dictionary','Navigation','ConfigI18n','NavigationI18n','InformationResourceI18n','OperatorLog');

    public function index()
    {
        if (($this->Language->find('count')) <= 0) {
            $this->redirect('/');
        }
        /*判断权限*/
        $this->operator_privilege('languages_view');
        $this->menu_path = array('root' => '/system/','sub' => '/languages/');
        /*end*/
        $this->pageTitle = $this->ld['manage_languages'].' - '.$this->configs['shop_name'];
        $this->set('title_for_layout', $this->pageTitle);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_languages'],'url' => '');
        $node['config'] = 'node';
        $node['use'] = true;
        $x = $this->Language->find('all', array('cache' => $node));
        $lost_lan1 = array();
        $lost_lan2 = array();
        $data = $this->Language->find('all', array('order' => array('Language.is_default desc')));
        foreach ($data as $k2 => $v2) {
            $lost_lan2[] = $v2['Language']['locale'];
        }
        foreach ($x as $k1 => $v1) {
            if (!in_array($v1['Language']['locale'], $lost_lan2)) {
                $lost_lan1[] = $v1['Language'];
            }
        }
        if (!empty($lost_lan1)) {
            $this->set('lost', $lost_lan1);
        }
        $this->set('languages', $data);
    }

    public function view($id = 0)
    {
        $this->operator_privilege('languages_edit');
        $this->menu_path = array('root' => '/system/','sub' => '/languages/');
        $this->pageTitle = $this->ld['edit_languages'].' - '.$this->ld['manage_languages'].' - '.$this->configs['shop_name'];
        $this->set('title_for_layout', $this->pageTitle);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['language_list'],'url' => '/languages/');
        $this->navigations[] = array('name' => $this->ld['edit_languages'],'url' => '');
        $this->set('act', $id);
        if ($this->RequestHandler->isPost()) {
            $foo_arr = $this->get_app_lan();
            if (isset($id) && $id != '') {
                //此处开始时编辑操作	
                if ($this->data['Language']['backend'] == 0) {
                    $foo = $this->Language->find('first', array('conditions' => array('Language.locale' => $foo_arr, 'Language.backend' => 1)));
                    if (!empty($foo)) {
                        ;
                    } else {
                        $msg = '已是唯一后台默认语言';
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");window.location.href="/admin/languages/"</script>';
                        die();
                    }
                }
                if (isset($this->data['Language']['is_default']) && $this->data['Language']['is_default'] == 1) {
                    $this->Language->updateAll(array('is_default' => 0));
                    if (empty($foo)) {
                        $this->data['Language']['backend'] = 1;
                    }
                }
                if ($this->data['Language']['front'] == 0) {
                    $foo_arr = array();
                    foreach ($this->apps['Applications'] as $k => $v) {
                        $code = explode('-', $k);
                        if ($code[1] == 'LANG' && $code[2] != strtoupper($this->data['Language']['locale'])) {
                            $foo_arr[] = strtolower($code[2]);
                        }
                    }
                    $foo = $this->Language->find('first', array('conditions' => array('Language.locale' => $foo_arr, 'Language.front' => 1)));
                    if (!empty($foo)) {
                        echo 1;
                    } else {
                        $msg = '已是唯一前台默认语言';
                        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");window.location.href="/admin/languages/"</script>';
                        die();
                    }
                }
                $this->Language->save($this->data);
            } else {
                //此处开始是添加
                $this->Language->saveall($this->data);
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_languages'].':id '.$id.' '.$this->data['Language']['name'], $this->admin['id']);
            }
            $this->flash($this->ld['language'].$this->data['Language']['name'].$this->ld['edited_language_success'], '/languages/edit/'.$id);
            if (isset($_SESSION['app_lan'])) {
                unset($_SESSION['app_lan']);
                $this->redirect('/applications/');
            }
            $this->redirect('/languages/');
        }
        $this->data = $this->Language->findById($id);
        //leo20090722导航显示
        $this->navigations[] = array('name' => $this->data['Language']['name'],'url' => '');
    }

    public function get_app_lan()
    {
        $foo_arr = array();
        foreach ($this->apps['Applications'] as $k => $v) {
            $code = explode('-', $k);
            if ($code[1] == 'LANG' && $code[2] != strtoupper($this->data['Language']['locale'])) {
                $foo_arr[] = strtolower($code[2]);
            }
        }

        return $foo_arr;
    }

    public function remove($id)
    {
        if (count($this->Language->find('all')) == 1) {
            echo "<script>alert('".$this->ld['last_language_no_deleted']."')</script>";
        } else {
            $data = $this->Language->find('first', array('conditions' => array('Language.id' => $id)));
            $locale['locale'] = $data['Language']['locale'];
            $this->Language->deleteAll("Language.id='".$id."'");
        }
        $this->redirect('/languages');
    }

    public function install($locale1)
    {
        $node['config'] = 'node';
        $node['use'] = true;
        $tmp = $this->Language->find('first', array('cache' => $node, 'conditions' => array('Language.locale' => $locale1)));
        $def = $this->Language->find('first', array('conditions' => array('is_default' => 1)));
        $save_file = array(
        'Language' => array(
            'locale' => $tmp['Language']['locale'],
            'name' => $tmp['Language']['name'],
            'charset' => $tmp['Language']['charset'],
            'map' => $tmp['Language']['map'],
            'img01' => $tmp['Language']['img01'],
            'img02' => $tmp['Language']['img02'],
            'front' => $tmp['Language']['front'],
            'backend' => $tmp['Language']['backend'],
            'is_default' => $tmp['Language']['is_default'],
            'google_translate_code' => $tmp['Language']['google_translate_code'],
            'is_default' => 0,
            ),
        );
        $tmp = $this->Language->find('first', array('conditions' => array('Language.locale' => $locale1)));
        //模板多语言
        $bci18n = $this->ConfigI18n->find('all', array('conditions' => array('ConfigI18n.locale' => $locale1)));
        $bci18n2 = array();
        foreach ($bci18n as $bck => $bcv) {
            $bci18n2[$bcv['ConfigI18n']['config_id']] = $bcv['ConfigI18n'];
        }
        $cc = $this->ConfigI18n->find('all', array('conditions' => array('ConfigI18n.locale' => $def['Language']['locale'])));
        foreach ($cc as $k1 => $v1) {
            $v1['ConfigI18n']['locale'] = $locale1;
            $v1['ConfigI18n']['name'] = isset($bci18n2[$v1['ConfigI18n']['config_id']]['name']) ? $bci18n2[$v1['ConfigI18n']['config_id']]['name'] : '';
            $v1['ConfigI18n']['options'] = isset($bci18n2[$v1['ConfigI18n']['config_id']]['options']) ? $bci18n2[$v1['ConfigI18n']['config_id']]['options'] : '';
            $v1['ConfigI18n']['description'] = isset($bci18n2[$v1['ConfigI18n']['config_id']]['description']) ? $bci18n2[$v1['ConfigI18n']['config_id']]['description'] : '';
            $v1['ConfigI18n']['id'] = '';
            pr($v1);
        }
        die();
        //菜单多语言
        $yy = $this->NavigationI18n->find('all', array('conditions' => array('NavigationI18n.locale' => $locale1)));
        if (empty($yy)) {
            $dd = $this->NavigationI18n->find('all', array('conditions' => array('NavigationI18n.locale' => $def['Language']['locale'])));
            foreach ($dd as $k2 => $v2) {
                $v2['NavigationI18n']['locale'] = $locale1;
                $v2['NavigationI18n']['id'] = '';
                $this->NavigationI18n->save($v2);
            }
        }
        //文章多语言
        $this->ArticleI18n->deleteAll("ArticleI18n.locale='".$locale1."'");
        $ee = $this->ArticleI18n->find('all', array('conditions' => array('ArticleI18n.locale' => $def['Language']['locale'])));
        foreach ($ee as $k3 => $v3) {
            $v3['ArticleI18n']['locale'] = $locale1;
            $v3['ArticleI18n']['id'] = '';
            $this->ArticleI18n->save($v3);
        }
        //分类多语言
        $this->CategoryArticleI18n->deleteAll("CategoryArticleI18n.locale='".$locale1."'");
        $ef = $this->CategoryArticleI18n->find('all', array('conditions' => array('CategoryArticleI18n.locale' => $def['Language']['locale'])));
        foreach ($ef as $k3 => $v3) {
            $v3['CategoryArticleI18n']['locale'] = $locale1;
            $v3['CategoryArticleI18n']['id'] = '';
            $this->CategoryArticleI18n->save($v3);
        }
        //商品多语言
        $pro = $this->ProductI18n->find('all', array('conditions' => array('ProductI18n.locale' => $locale1)));
        if (empty($pro)) {
            $ff = $this->ProductI18n->find('all', array('conditions' => array('ProductI18n.locale' => $def['Language']['locale'])));
            foreach ($ff as $k3 => $v3) {
                $v3['ProductI18n']['locale'] = $locale1;
                $v3['ProductI18n']['id'] = '';
                $this->ProductI18n->save($v3);
            }
        }
        //品牌多语言（处理中）
        $pro_brand = $this->BrandI18n->find('all', array('conditions' => array('BrandI18n.locale' => $locale1)));
        if (empty($pro_brand)) {
            $ff = $this->BrandI18n->find('all', array('conditions' => array('BrandI18n.locale' => $def['Language']['locale'])));
            foreach ($ff as $k4 => $v4) {
                $v4['BrandI18n']['locale'] = $locale1;
                $v4['BrandI18n']['id'] = '';
                $this->BrandI18n->save($v4);
            }
        }
        if (constant('Version')) {
            $this->loadModel('ShippingI18n');
            //配送方式多语言
            $pro_shipping = $this->ShippingI18n->find('all', array('conditions' => array('ShippingI18n.locale' => $locale1)));
            if (empty($pro_shipping)) {
                $pro_base_shipping = $this->ShippingI18n->find('all', array('cache' => $node, 'conditions' => array('ShippingI18n.locale' => $locale1)));
                foreach ($pro_base_shipping as $p) {
                    $p['ShippingI18n']['id'] = '';
                    $base_shipping_info['ShippingI18n'] = $p['ShippingI18n'];
                    $this->ShippingI18n->saveall($base_shipping_info);
                }
            }
        }
        //支付方式多语言
        $pro_payment = $this->Payment->find('all', array('conditions' => array('PaymentI18n.locale' => $locale1)));
        if (empty($pro_payment)) {
            $pro_base_payment = $this->Payment->find('all', array('conditions' => array('PaymentI18n.locale' => $locale1)));
            foreach ($pro_base_payment as $v6) {
                $v6['PaymentI18n']['id'] = '';
                $base_payment_info['PaymentI18n'] = $v6['PaymentI18n'];
                $this->PaymentI18n->saveall($base_payment_info);
            }
        }
        $iri = $this->InformationResourceI18n->find('all', array('conditions' => array('InformationResourceI18n.locale' => $locale1)));
        if (empty($iri)) {
            $ff2 = $this->InformationResourceI18n->find('all', array('conditions' => array('InformationResourceI18n.locale' => $def['Language']['locale'])));
            foreach ($ff2 as $k9 => $v9) {
                $v9['InformationResourceI18n']['locale'] = $locale1;
                $v9['InformationResourceI18n']['id'] = '';
                $this->InformationResourceI18n->save($v9);
            }
        }
        $this->redirect('/languages/view/'.$xid);
    }

    public function tmpset($locale1)
    {
        if (empty($locale1)) {
            die('go out!');
        }
        Configure::write('debug', 1);
        $def = $this->Language->find('first', array('conditions' => array('is_default' => 1)));
        $xx = $this->ConfigI18n->find('all', array('conditions' => array('ConfigI18n.locale' => $locale1)));
        if (empty($xx)) {
            $bci18n = ClassRegistry::init('ConfigI18n')->find('all', array('conditions' => array('ConfigI18n.locale' => $locale1)));
            $bci18n2 = array();
            foreach ($bci18n as $bck => $bcv) {
                $bci18n2[$bcv['ConfigI18n']['config_id']] = $bcv['ConfigI18n'];
            }
            $cc = $this->ConfigI18n->find('all', array('conditions' => array('ConfigI18n.locale' => $def['Language']['locale'])));
            foreach ($cc as $k1 => $v1) {
                $v1['ConfigI18n']['locale'] = $locale1;
                $v1['ConfigI18n']['name'] = isset($bci18n2[$v1['ConfigI18n']['config_id']]['name']) ? $bci18n2[$v1['ConfigI18n']['config_id']]['name'] : '';
                $v1['ConfigI18n']['options'] = isset($bci18n2[$v1['ConfigI18n']['config_id']]['options']) ? $bci18n2[$v1['ConfigI18n']['config_id']]['options'] : '';
                $v1['ConfigI18n']['description'] = isset($bci18n2[$v1['ConfigI18n']['config_id']]['description']) ? $bci18n2[$v1['ConfigI18n']['config_id']]['description'] : '';
                $v1['ConfigI18n']['id'] = '';
                $cc = $this->ConfigI18n->save($v1);
            }
        }
        die();
    }
}
