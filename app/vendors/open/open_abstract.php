<?php
abstract class Open_Abstract
{

    protected $_token = '';
    protected $_debug =  false;
    protected $_setFlag = false;
    protected $_msg = array();

    /**
     * send msg to user
     * @param array $data
     */
    abstract public function responseMsg($data);

    /**
     * check token
     */

    abstract public function checkSignature();

    public function __construct($token, $debug)
    {
        $this->_token = $token;
        $this->_debug = $debug;
    }

    /**
     * vaild data
     */
    public function valid()
    {
        if ($this->checkSignature()) {
            if ( $_SERVER['REQUEST_METHOD']=='GET' ) {
                echo $_GET['echostr'];
                exit;
            }
        } else {
            $this->log('认证失败');
            exit;
        }
    }

    /**
     * get msg from open
     */
    public function requestMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if ($this->_debug) {
            $this->log($postStr);
        }
        if (!empty($postStr)) {
            $this->_msg = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
    }

    public function reply($data)
    {
        if ($this->_debug) {
            $this->log($data);
        }
        echo $data;
    }

    public function getMsg()
    {
        return $this->_msg;
    }

    public function getToken()
    {
    	return $this->_token;
    }
    /**
     * write log
     * @param string $log
     */
    private function log($log)
    {
       //write log
    }
}