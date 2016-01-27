<?php

/*****************************************************************************
 * Seevia 用户设置
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
uses('sanitize');
/**
 *这是一个名为ConfigsController的控制器
 *配置控制器.
 */
class ConfigsController extends AppController
{
    /*
    *@var $name
    *@var $components
    *@var $helpers
    *@var $uses
    */
    public $name = 'Configs';
    public $components = array('RequestHandler'); // Added 
    public $helpers = array('Html');
    public $uses = array('UserConfig','UserConfigI18n');

/*------------------------------------------------------ */
//-- 我的设置
/*------------------------------------------------------ */
    /**
     *函数 user_index 用于进入商品设置管理页面.
     */
    public function user_index()
    {
        //未登录转登录页
        if (!isset($_SESSION['User'])) {
            //	echo "111111111111";exit;
                $this->redirect('/login/');
        }
        $this->page_init();

        //当前位置
        $this->ur_heres[] = array('name' => __($this->ld['set'], true),'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $user_id = $_SESSION['User']['User']['id'];
        //我的设置
        $my_configs = array();

    /*
        if(!$this->check_myconfigs($user_id)){
            $res=$this->add_myconfigs($user_id);
        }else{
            $res=$this->UserConfig->get_myconfig($user_id);
        }*/
        $this->check_user_configs($user_id);
        $res = $this->UserConfig->get_myconfig($user_id);
        if (isset($res) && sizeof($res) > 0) {
            foreach ($res as $k => $v) {
                $user_config_info = $this->UserConfig->get_user_config_info($v['UserConfig']['code']);
                $res[$k]['UserConfigI18n'] = $user_config_info['UserConfigI18n'];
                $my_configs[$k] = $v;
                $my_configs[$k]['UserConfigI18n'] = $user_config_info['UserConfigI18n'];
                if (is_array($my_configs[$k]['UserConfigI18n'])) {
                    $my_configs[$k]['ConfigValues'] = array();
                    if (!empty($my_configs[$k]['UserConfigI18n']['values'])) {
                        if ($my_configs[$k]['UserConfig']['type'] == 'radio' || $my_configs[$k]['UserConfig']['type'] == 'select') {
                            $my_configs[$k]['ConfigValues'] = explode("\n", $my_configs[$k]['UserConfigI18n']['values']);
                            foreach ($my_configs[$k]['ConfigValues'] as $value_k => $value) {
                                $my_configs_value = explode(':', $value);
                                if (isset($my_configs_value[0]) && isset($my_configs_value[1])) {
                                    $my_configs[$k]['ConfigValues'][$my_configs_value[0]] = $my_configs_value[1];
                                }
                            }
                        }
                    }
                }
            }
            $this->set('my_configs', $my_configs);
        }
    //	pr($my_configs);
    //pr($my_configs);
    //pr($this->UserConfig->get_myconfig('0'));
        $this->pageTitle = $this->ld['set'].' - '.$this->configs['shop_title'];
    }

     /**
      *函数 user_add_myconfigs 用于添加用户设置.
      */
     public function user_add_myconfigs($user_id)
     {
         $my_configs = $this->UserConfig->get_myconfig('0');
         foreach ($my_configs as $key => $v) {
             $add_configs = array(
                'user_id' => $user_id,
                'code' => $v['UserConfig']['code'],
                'type' => $v['UserConfig']['type'],
                'value' => $v['UserConfig']['value'],
                'orderby' => $v['UserConfig']['orderby'],
            );

             $this->UserConfig->saveAll(array('UserConfig' => $add_configs));
             $id = $this->UserConfig->id;
             $add_configs_i18n = array(
                      'locale' => isset($v['UserConfigI18n']['locale']) ? $v['UserConfigI18n']['locale'] : 'chi',
                      'user_config_id' => $id,
                      'name' => isset($v['UserConfigI18n']['name']) ? $v['UserConfigI18n']['name'] : '',
                      'description' => isset($v['UserConfigI18n']['description']) ? $v['UserConfigI18n']['description'] : '',
                      'values' => isset($v['UserConfigI18n']['values']) ? $v['UserConfigI18n']['values'] : '',
                 );
             $this->UserConfigI18n->saveAll(array('UserConfigI18n' => $add_configs_i18n));
         }

         return $my_configs;
     }

   /**
    *函数 user_update_config 用于更新我的设置.
    */
   public function user_update_config()
   {
       $mrClean = new Sanitize();
       $this->page_init();
       $user_id = $_SESSION['User']['User']['id'];
       foreach ($_POST['code'] as $key => $val) {
           if ($val != '') {
               $condition = " UserConfig.user_id='".$user_id."' AND UserConfig.id='".$key."' ";
               $user_config = array(
        'value' => "'".$val."'",
        );
               $this->UserConfig->updateAll($user_config, $condition);
           }
       }
       $this->pageTitle = $this->ld['tips_edit_success'].' - '.$this->configs['shop_title'];
       $flash_url = $this->server_host.$this->user_webroot.'configs';
       $this->flash($this->ld['tips_edit_success'], $flash_url, '');
   }

    /**
     *函数 user_check_myconfigs 用于检查我的设置.
     */
    public function user_check_myconfigs($user_id)
    {
        if ($this->UserConfig->hasAny("UserConfig.user_id='".$user_id."'")) {
            $my_configs = $this->UserConfig->get_myconfig($user_id);
            $default_configs = $this->UserConfig->get_myconfig('0');
            $new_configs = array();
            $old_configs = array();
                 //   		pr($default_configs);
                 //	echo "-------------";
                 //pr($my_configs);
                 //处理是否有新增项目或者已修改，删除项目
                     foreach ($default_configs as $key => $v) {
                         $new_configs[$v['UserConfigI18n']['name']] = $v;
                     }
                     //	pr($new_configs);
                     //	echo "-------------";
                     foreach ($my_configs as $key => $v) {
                         $old_configs[$v['UserConfigI18n']['name']] = $v;
                         unset($new_configs[$v['UserConfigI18n']['name']]);//删除重复项目,得到新增项目
                     }
                      //	pr($old_configs);
                     foreach ($default_configs as $key => $v) {
                         unset($old_configs[$v['UserConfigI18n']['name']]);//删除重复项目，得到已保存的无用项目
                     }

            if ($new_configs) {
                //添加到数据库			
                             foreach ($new_configs as $key => $v) {
                                 $add_configs = array(
                                                         'user_id' => $user_id,
                                                         'code' => '',
                                                         'type' => $v['UserConfig']['type'],
                                                         'value' => $v['UserConfig']['value'],
                                                         'orderby' => $v['UserConfig']['orderby'],
                                       );
                                 $this->UserConfig->saveAll(array('UserConfig' => $add_configs));
                                 $id = $this->UserConfig->id;
                                 $add_configs_i18n = array(
                                                         'locale' => isset($v['UserConfigI18n']['locale']) ? $v['UserConfigI18n']['locale'] : 'chi',
                                                         'user_config_id' => $id,
                                                         'name' => isset($v['UserConfigI18n']['name']) ? $v['UserConfigI18n']['name'] : '',
                                                         'description' => isset($v['UserConfigI18n']['description']) ? $v['UserConfigI18n']['description'] : '',
                                                         'values' => isset($v['UserConfigI18n']['values']) ? $v['UserConfigI18n']['values'] : '',
                                        );
                                 $this->UserConfigI18n->saveAll(array('UserConfigI18n' => $add_configs_i18n));
                             }
            }
            if ($old_configs) {
                foreach ($old_configs as $key => $val) {
                    $this->UserConfig->deleteAll('UserConfig.id = '.$val['UserConfig']['id'].'', false);
                    $this->UserConfigI18n->del($val['UserConfigI18n']['id']);
                }
            }

            return true;
        } else {
            return false;
        }
    }
    /**
     *函数 user_check_user_configs 用于检查用户的设置.
     *
     *@param $id 用户好
     */
    public function user_check_user_configs($id)
    {
        $user_config_infos = $this->UserConfig->get_myconfig(0);
        $user_config_codes = array();
        if (isset($user_config_infos) && sizeof($user_config_infos) > 0) {
            foreach ($user_config_infos as $k => $v) {
                $user_config_codes[] = $v['UserConfig']['code'];
            }
        }
        $my_config_infos = $this->UserConfig->get_myconfig($id);
        $my_config_codes = array();
        if (isset($my_config_infos) && sizeof($my_config_infos) > 0) {
            foreach ($my_config_infos as $k => $v) {
                $my_config_codes[] = $v['UserConfig']['code'];
            }
        }
        if (isset($my_config_infos) && sizeof($my_config_infos) > 0) {
            if (isset($user_config_infos) && sizeof($user_config_infos) > 0) {
                foreach ($user_config_infos as $k => $v) {
                    if (!in_array($v['UserConfig']['code'], $my_config_codes)) {
                        $user_config_infos[$k]['UserConfig']['id'] = '';
                        $user_config_infos[$k]['UserConfig']['user_id'] = $id;
                        unset($user_config_infos[$k]['UserConfigI18n']);
                        $this->UserConfig->save($user_config_infos[$k]);
                    }
                }
                foreach ($my_config_infos as $k => $v) {
                    if (!in_array($v['UserConfig']['code'], $user_config_codes)) {
                        $this->UserConfig->deleteall("UserConfig.id = '".$v['UserConfig']['id']."'", false);
                    }
                }
            } else {
                foreach ($my_config_infos as $k => $v) {
                    $this->UserConfig->deleteall("UserConfig.id = '".$v['UserConfig']['id']."'", false);
                }
            }
        } else {
            foreach ($user_config_infos as $k => $v) {
                $user_config_infos[$k]['UserConfig']['id'] = '';
                $user_config_infos[$k]['UserConfig']['user_id'] = $id;
                unset($user_config_infos[$k]['UserConfigI18n']);
                $this->UserConfig->save($user_config_infos[$k]);
            }
        }
    }
}
