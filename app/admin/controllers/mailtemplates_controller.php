<?php

/*****************************************************************************
 * svsys 邮件模板
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
class MailtemplatesController extends AppController
{
    public $name = 'Mailtemplates';
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $components = array('Pagination','RequestHandler','Email');
    public $uses = array('MailTemplate','MailTemplateI18n','OperatorLog');

    public function index($page = 1)
    {
        $this->operator_privilege('mailtemplates_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/system/','sub' => '/mailtemplates/');
        $this->set('title_for_layout', $this->ld['email_template'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['email_template'],'url' => '/mailtemplates/');

        $this->MailTemplate->set_locale($this->backend_locale);

        $shop_name = $this->configs['shop_name'];
        $condition['type'] = 'template';

        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $total = $this->MailTemplate->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'mailtemplates','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'MailTemplate');
        $this->Pagination->init($condition, $parameters, $options);
        $mailtemplate_list = $this->MailTemplate->find('all', array('page' => $page, 'limit' => $rownum, 'conditions' => $condition));
        foreach ($mailtemplate_list as $k => $v) {
            $title = $v['MailTemplateI18n']['title'];
            @eval("\$title = \"$title\";");
            $v['MailTemplateI18n']['title'] = @$title;
            $mailtemplate_list[$k] = $v;
        }
        $this->set('mailtemplate_list', $mailtemplate_list);
    }

    public function view($id = 0)
    {
        $this->operator_privilege('mailtemplates_edit');
        $this->menu_path = array('root' => '/system/','sub' => '/mailtemplates/');
        $this->set('title_for_layout', $this->ld['email_edit'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['email_template'],'url' => '/mailtemplates/');
        $shop_name = $this->configs['shop_name'];
        if ($this->RequestHandler->isPost()) {
            $this->data['MailTemplate']['type'] = 'template';
            if (isset($this->data['MailTemplate']['id']) && $this->data['MailTemplate']['id'] != '') {
                $this->MailTemplate->save(array('MailTemplate' => $this->data['MailTemplate'])); //关联保存
            } else {
                $this->MailTemplate->saveAll(array('MailTemplate' => $this->data['MailTemplate'])); //关联保存
                $id = $this->MailTemplate->getLastInsertId();
            }
            $this->MailTemplateI18n->deleteall(array('mail_template_id' => $id)); //删除原有多语言
            foreach ($this->data['MailTemplateI18n'] as $v) {
                $v['mail_template_id'] = $id;
                $this->MailTemplateI18n->saveAll(array('MailTemplateI18n' => $v));//更新多语言
            }
            foreach ($this->data['MailTemplateI18n'] as $k => $v) {
                if ($v['locale'] == $this->backend_locale) {
                    $userinformation_name = $v['title'];
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].'邮件模板:id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->data = $this->MailTemplate->localeformat($id);
        if (isset($this->data['MailTemplateI18n'][$this->backend_locale]['title'])) {
            $this->navigations[] = array('name' => $this->ld['edit'].'-'.$this->data['MailTemplateI18n'][$this->backend_locale]['title'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['add_email_template'],'url' => '');
        }
    }

    /**
     *删除一个邮件模板
     *
     *@param int $id 输入邮件模板ID
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_email_template_failure'];
        $pn = $this->MailTemplateI18n->find('list', array('fields' => array('MailTemplateI18n.mail_template_id', 'MailTemplateI18n.title'), 'conditions' => array('MailTemplateI18n.mail_template_id' => $id, 'MailTemplateI18n.locale' => $this->backend_locale)));
        $this->MailTemplate->deleteAll(array('MailTemplate.id' => $id));
        $this->MailTemplateI18n->deleteAll(array('mail_template_id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_email_template'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_email_template_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*
        批量删除
    */
    public function removeall()
    {
        $MailTemplate_checkboxes = $_REQUEST['checkboxes'];
        $MailTemplate_Ids = '';
        foreach ($MailTemplate_checkboxes as $k => $v) {
            $MailTemplate_Ids = $MailTemplate_Ids.$v.',';
            $MailTemplate_Ids_arr[] = $v;
        }
        $MailTemplateI18n_ids = $this->MailTemplateI18n->find('list', array('fields' => array('MailTemplateI18n.mail_template_id', 'MailTemplateI18n.name'), 'conditions' => array('MailTemplateI18n.mail_template_id' => $id, 'MailTemplateI18n.locale' => $this->backend_locale)));

        $this->MailTemplate->deleteAll(array('MailTemplate.id' => $MailTemplate_Ids_arr));
        $this->MailTemplateI18n->deleteAll(array('mail_template_id' => $MailTemplateI18n_ids));
        $MailTemplate_Ids = substr($MailTemplate_Ids, 0, strlen($MailTemplate_Ids) - 1);

        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_email_template'].':'.$MailTemplate_Ids, $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }

    public function install($code)
    {
        $node['config'] = 'node';
        $node['use'] = true;
        //echo $code;
        $tmp = $this->MailTemplate->find('first', array('cache' => $node, 'conditions' => array('MailTemplate.code' => $code)));
    //	var_dump($tmp);
        $save_file = array(
        'MailTemplate' => array(
            'code' => $tmp['MailTemplate']['code'],
            'status' => $tmp['MailTemplate']['status'],
            'type' => $tmp['MailTemplate']['type'],
            ),
        );
        //echo 1;
        //var_dump($save_file);
        $this->MailTemplate->save($save_file);
        $newid = $this->MailTemplate->id;
        //echo $newid;
        $save_file_i18n = array(
        'MailTemplateI18n' => array(
            'locale' => $tmp['MailTemplateI18n']['locale'],
            'mail_template_id' => $newid,
            'title' => $tmp['MailTemplateI18n']['title'],
            'description' => $tmp['MailTemplateI18n']['description'],
            'text_body' => $tmp['MailTemplateI18n']['text_body'],
            'html_body' => $tmp['MailTemplateI18n']['html_body'],
            ),
        );
        //var_dump($this->MailTemplateI18n->save($save_file_i18n));
        $this->MailTemplateI18n->save($save_file_i18n);
        $this->redirect('/mailtemplates/');
    }
}
