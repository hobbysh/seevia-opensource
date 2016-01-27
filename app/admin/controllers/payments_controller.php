<?php

/**
 *这是一个名为 PaymentsController 的控制器
 *后台支付方式管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class PaymentsController extends AppController
{
    public $name = 'Payments';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Html','Pagination','Ckeditor');
    public $uses = array('Payment','PaymentI18n','Language','Currency','Application','OperatorLog');

    /**
     *显示支付方式列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('payments_view');
        $this->menu_path = array('root' => '/system/','sub' => '/payments/');
        $this->set('title_for_layout', $this->ld['payment'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['payment'],'url' => '');

        $this->Payment->set_locale($this->backend_locale);
        $condition['order'] = 'Payment.status desc,Payment.id';
        $payment_tree = array();
        $payment_tree = $this->Payment->tree($condition);
        $this->set('payment_tree', $payment_tree);
    }

    public function view($id = 0)
    {
        $this->operator_privilege('payments_edit');
        $this->menu_path = array('root' => '/system/','sub' => '/payments/');
        $this->set('title_for_layout', $this->ld['edit_payment'].' - '.$this->ld['payment'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['payment_list'],'url' => '/payments/');
        if ($id == 0) {
            $this->navigations[] = array('name' => $this->ld['add_payment'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['edit_payment'],'url' => '');
        }
        if ($this->RequestHandler->isPost()) {
            if (isset($_REQUEST['payment_arr']) && count($_REQUEST['payment_arr']) > 0) {
                $configs = '$payment_arr = array(';
                $i = 0;
                foreach ($_REQUEST['payment_arr'] as $kk => $vv) {
                    ++$i;
                    if ($kk == 'languages_type') {
                        $configs .= "'".$kk."'=> array('name'=>'".$vv['name']."' , 'type' => '".$vv['type']."'";
                        if (isset($vv['sub']) && sizeof($vv['sub']) > 0) {
                            $m = 0;
                            $configs .= ",'value'=>array(";
                            foreach ($vv['sub'] as $a => $b) {
                                ++$m;
                                $configs .= "'".$a."' => array( 'name'=>'".$b['name']."' , 'value' => '".$b['value']."')";
                                if ($m < count($vv['sub'])) {
                                    $configs .= ',';
                                }
                            }
                            $configs .= '))';
                        }
                    } else {
                        $configs .= "'".$kk."'=> array('name'=>'".$vv['name']."','value'=>'".$vv['value']."' , 'type' => '".$vv['type']."'";
                        if (isset($vv['select_value'])) {
                            $n = 0;
                            $configs .= ", 'select_value' => array( ";
                            foreach ($vv['select_value'] as $kkk => $vvv) {
                                ++$n;
                                $configs .= "'".$vvv['name']."' => '".$vvv['value']."'";
                                if ($n < count($vv['select_value'])) {
                                    $configs .= ',';
                                }
                            }
                            $configs .= ')';
                        }
                        $configs .= ')';
                    }
                    if ($i < count($_REQUEST['payment_arr'])) {
                        $configs .= ',';
                    }
                }
                $configs .= ');';
            }
            $this->data['Payment']['config'] = @$configs;
            if (isset($_POST['config']) && count($_POST['config']) > 0) {
                $x = $this->bank_list($_POST['config']);
                $this->data['Payment']['config'] = serialize($x);
            }
            $this->Payment->save($this->data); //保存
            $payment_id = $this->Payment->id;
            $this->PaymentI18n->deleteAll(array('PaymentI18n.payment_id' => $payment_id));
            foreach ($this->data['PaymentI18n'] as $v) {
                $paymentI18n_info = array(
                       'locale' => $v['locale'],
                      'payment_id' => $payment_id,
                     'name' => isset($v['name']) ? $v['name'] : '',
                      'status' => 1,
                       'description' => isset($v['description']) ? $v['description'] : '',
                 );
                $this->PaymentI18n->saveAll(array('PaymentI18n' => $paymentI18n_info));//更新多语言
            }
            foreach ($this->data['PaymentI18n'] as $k => $v) {
                if ($v['locale'] == $this->backend_locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_payment'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            if (isset($_SESSION['app_lan'])) {
                unset($_SESSION['app_lan']);
                $this->redirect('/applications/');
            }
            $this->redirect('/payments/');
        }

        $payment = $this->Payment->localeformat($id);
        if (isset($payment['Payment']) && $payment['Payment']['code'] == 'paypal') {
            $languages = $this->Language->find('all');
            $locale = '';
            if (is_array($languages) && sizeof($languages) > 0) {
                foreach ($languages as $k => $v) {
                    $locale .= $v['Language']['locale'].' ';
                    if (!isset($payment_arr['languages_type']['value'][$v['Language']['locale']])) {
                        $payment_arr['languages_type']['value'][$v['Language']['locale']] = array(
                                                                                                    'name' => $v['Language']['name'],
                                                                                                    'value' => '',
                                                                                                    );
                    } else {
                        $payment_arr['languages_type']['value'][$v['Language']['locale']]['name'] = $v['Language']['name'];
                    }
                }
            }
            $locale_arr = explode(' ', $locale);
            if (is_array($payment_arr['languages_type']['value']) && sizeof($payment_arr['languages_type']['value']) > 0) {
                foreach ($payment_arr['languages_type']['value'] as $k => $v) {
                    if (!in_array($k, $locale_arr)) {
                        unset($payment_arr['languages_type']['value'][$k]);
                    } else {
                        $payment_arr['languages_type']['value'][$k]['select_value'] = array($this->ld['please_select'] => '0', $this->ld['currency_aud'] => 'AUD',$this->ld['currency_canadian_dollar'] => 'CAD',$this->ld['currency_euro'] => 'EUR',$this->ld['currency_pound'] => 'GBP',$this->ld['currency_yen'] => 'JPY',$this->ld['currency_us_dollar'] => 'USD',$this->ld['currency_hong_kong'] => 'HKD');
                    }
                }
            }
        }
        if (isset($payment['Payment']['config']) && !empty($payment['Payment']['config'])) {
            @$config_value = unserialize($payment['Payment']['config']);
        } else {
            $config_value = '';
        }
        $this->set('config_value', $config_value);
        $this->set('payment_arr', @$payment_arr);
        $this->set('payment', $payment);
        if (isset($payment['Payment']) && $payment['Payment']['parent_id'] != 0) {
            $payment_class_name = strtolower($payment['Payment']['code']);
            App::import('Vendor', 'payments/'.$payment_class_name);
            if (class_exists($payment_class_name, false)) {
                //验证当前支付方式是否存在相关sdk文件
                $c_payment = new $payment_class_name();
                if ($this->backend_locale == 'chi') {
                    $c_payment->config = isset($c_payment->config_cn) ? $c_payment->config_cn : array();
                } elseif ($this->backend_locale == 'eng') {
                    $c_payment->config = isset($c_payment->config_en) ? $c_payment->config_en : array();
                }
                $this->set('config', $c_payment->config);
            }
        }
        $this->Payment->set_locale($this->backend_locale);
        $parent_payment_list = $this->Payment->find('all', array('fields' => array('Payment.id', 'Payment.code', 'PaymentI18n.name'), 'conditions' => array('Payment.parent_id' => 0, 'Payment.status' => 1, 'Payment.id !=' => $id)));
        $this->set('parent_payment_list', $parent_payment_list);
    }

    /**
     *支付方式 启用.
     */
    public function install($id)
    {
        $this->Payment->updateAll(
                          array('Payment.status' => '1'),
                          array('Payment.id' => $id)
                       );
        $this->Payment->set_locale($this->backend_locale);
        $Payment_info = $this->Payment->find(array('Payment.id' => $id));

        $this->redirect('/payments/view/'.$id);
    }

    /**
     *支付方式 停用.
     */
    public function uninstall($id)
    {
        $this->Payment->updateAll(
                          array('Payment.status' => '0'),
                          array('Payment.id' => $id)
                       );
        $this->Payment->set_locale($this->backend_locale);
        $Payment_info = $this->Payment->find(array('Payment.id' => $id));

        $this->redirect('/payments/');
    }

    /**
     *列表排序修改.
     */
    public function update_payment_orderby()
    {
        $this->Payment->hasMany = array();
        $this->Payment->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_sort'];
        }
        if (is_numeric($val) && $this->Payment->save(array('id' => $id, 'orderby' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表状态修改.
     */
    public function toggle_on_status()
    {
        $this->Payment->hasMany = array();
        $this->Payment->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Payment->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表费用修改.
     */
    public function update_payment_fee()
    {
        $this->Payment->hasMany = array();
        $this->Payment->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->Payment->updateAll(
            array('fee' => "'".$val."'"),
            array('id' => $id)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function bank_list($data)
    {
        if (isset($data['bank'])) {
            $y = trim($data['bank']);
            $x = strtr($data['bank'], "\r", '');
            $x = strtr($data['bank'], "\n", ',');
            $x = explode(',', $x);
            $x['bb'] = $y;
            $data['bank'] = $x;
        }

        return $data;
    }

    public function checkpaymentcode()
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        $code = isset($_REQUEST['code']) ? $_REQUEST['code'] : '';
        $this->Payment->set_locale($this->backend_locale);
        $pay_counts = $this->Payment->find('count', array('conditions' => array('Payment.code' => $code, 'Payment.id !=' => $id)));
        $result['code'] = $pay_counts > 0 ? 1 : 0;
        $result['msg'] = $pay_counts > 0 ? $this->ld['code_already_exists'] : '';
        die(json_encode($result));
    }
}
