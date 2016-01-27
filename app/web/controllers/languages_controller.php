<?php

class LanguagesController extends AppController
{
    public $name = 'Languages';
    public $helpers = array('Html','Pagination');
    public $components = array('Pagination','RequestHandler');
    public $uses = array('Language','LanguageDictionarie');

    public function index()
    {
        /*判断权限*/
        //$this->operator_privilege('langauage_view');
        /*end*/
        $this->page_init();
        $this->pageTitle = '语言管理'.' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => '功能管理','url' => '');
        $this->navigations[] = array('name' => '语言管理','url' => '/languages/');
        $this->set('navigations', $this->navigations);
        $data = $this->Language->find('all', array('order' => array('Language.id ASC')));
        $this->set('languages', $data);
    }

    public function edit($id)
    {
        $this->pageTitle = '编辑语言 - 语言管理'.' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => '功能管理','url' => '');
        $this->navigations[] = array('name' => '语言管理','url' => '/languages/');
        $this->navigations[] = array('name' => '编辑语言','url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->Language->save($this->data);
            //操作员日志
//    	    if(isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1){
//    	    //$this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'编辑语言:'.$this->data['Language']['name'] ,'operation');
//    	    }
            $this->flash('语言 '.$this->data['Language']['name'].' 编辑成功。点击这里继续编辑该语言。', '/languages/edit/'.$id);
            $this->redirect('/languages/');
        }
        $this->data = $this->Language->findById($id);
        //leo20090722导航显示
        $this->navigations[] = array('name' => $this->data['Language']['name'],'url' => '');
        $this->set('navigations', $this->navigations);
    }

    public function add()
    {
        $this->pageTitle = '添加语言 - 语言管理'.' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => '功能管理','url' => '');
        $this->navigations[] = array('name' => '语言管理','url' => '/languages/');
        $this->navigations[] = array('name' => '添加语言','url' => '');
        $this->set('navigations', $this->navigations);
        if ($this->RequestHandler->isPost()) {
            $this->Language->saveall($this->data);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'添加语言:'.$this->data['Language']['name'], 'operation');
            }
            //$this->flash("语言 ".$this->data['Language']['name']." 添加成功。点击这里继续编辑该语言。","/languages/edit/".$this->Language->getLastInsertID(),10);
            $this->redirect('/languages/');
        }
    }

    public function remove($id)
    {
        //$pn = $this->Language->find('list',array('fields' => array('Language.id','Language.name'),'conditions'=> 
                                        //array('Language.id'=>$id,'Language.locale'=>$this->locale)));
        //$this->Language->del($id);
        //操作员日志
        //if(isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1){
        //$this->log('操作员'.$_SESSION['Operator_Info']['Operator']['name'].' '.'删除语言:'.@$pn[$id] ,'operation');
        //}
        $this->Language->deleteAll("Language.id='".$id."'");
        $this->redirect('/languages');
    }
}
