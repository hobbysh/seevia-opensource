<?php

/**
 *这是一个名为 AuthnumsController 的控制器
 *验证码控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class AuthnumsController extends AppController
{
    public $name = 'Authnums';
    public $helpers = array('Html');
    public $uses = array();
    public $components = array('Captcha');

    /**
     *后台验证码显示.
     */
    public function get_authnums()
    {
        $this->layout = 'blank'; //a blank layout 
        $this->set('captcha_data', $this->captcha->show()); //dynamically creates an image 
        exit();
    }

    public function get_authnumber()
    {
        Configure::write('debug', 1);
        $authnumber = isset($_SESSION['securimage_code_value']) ? $_SESSION['securimage_code_value'] : '';
        die($authnumber);
    }
}
