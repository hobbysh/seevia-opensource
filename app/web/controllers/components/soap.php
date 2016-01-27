<?php 
    App::import('Vendor', 'IPReflectionClass', array('file' => 'wshelper'.DS.'lib'.DS.'soap'.DS.'IPReflectionClass.class.php'));
    App::import('Vendor', 'IPReflectionCommentParser', array('file' => 'wshelper'.DS.'lib'.DS.'soap'.DS.'IPReflectionCommentParser.class.php'));
    App::import('Vendor', 'IPXMLSchema', array('file' => 'wshelper'.DS.'lib'.DS.'soap'.DS.'IPXMLSchema.class.php'));
    App::import('Vendor', 'IPReflectionMethod', array('file' => 'wshelper'.DS.'lib'.DS.'soap'.DS.'IPReflectionMethod.class.php'));
    App::import('Vendor', 'WSDLStruct', array('file' => 'wshelper'.DS.'lib'.DS.'soap'.DS.'WSDLStruct.class.php'));
    App::import('Vendor', 'WSDLException', array('file' => 'wshelper'.DS.'lib'.DS.'soap'.DS.'WSDLException.class.php'));
    App::import('Vendor', 'IPReflectionProperty', array('file' => 'wshelper'.DS.'lib'.DS.'soap'.DS.'IPReflectionProperty.class.php'));
    /*
    * Soap component for handling soap requests in Cake 
    *
    * @author      Hobbysh (hobbysh@gmail.com)
    * @copyright   Copyright 2009, 上海实玮网络科技有限公司
    * @license     http://www.opensource.org/licenses/mit-license.php The MIT License
    */
    Configure::write('debug', 1);
    class SoapComponent extends Component
    {
        public $name = 'Soap';

        public $components = array('RequestHandler');

        public $controller;

        public $__settings = array(
            'wsdl' => false,
            'wsdlAction' => 'wsdl',
            'prefix' => 'soap',
            'action' => array('call'),
        );

        public function initialize($controller, $settings = array())
        {
            if (Configure::read('debug') != 0) {
                ini_set('soap.wsdl_cache_enabled', false);
            }

            $this->controller = $controller;

            if (isset($settings['wsdl']) && !empty($settings['wsdl'])) {
                $this->__settings['wsdl'] = $settings['wsdl'];
            }

            if (isset($settings['prefix'])) {
                $this->__settings['prefix'] = $settings['prefix'];
            }

            if (isset($settings['action'])) {
                $this->__settings['action'] = is_array($settings['action']) ? $settings['action'] : array($settings['action']);
            }

            parent::initialize($controller);
        }

        public function startup()
        {
            if (isset($this->controller->params['soap'])) {
                if ($this->__settings['wsdl'] != false) {
                    //render the wsdl file
                    if ($this->action() == $this->__settings['wsdlAction']) {
                        $this->autoRender = false;
                        header('Content-Type: text/xml'); // Add encoding if this doesn't work e.g. header('Content-Type: text/xml; charset=UTF-8'); 
                        echo $this->getWSDL($this->controller->name, 'call');
                        exit();
                    } elseif (in_array($this->action(), $this->__settings['action'])) {

                        //handle request

                        $this->autoRender = false;
                        $this->handle($this->controller->name, 'wsdl');
                        //stop script execution
                        $this->_stop();
                        exit();
                    }
                    exit();
                }
            }
        }

        /**
         * Return the current action.
         *
         * @return string
         */
        public function action()
        {
            return (!empty($this->__settings['prefix'])) ? str_replace($this->__settings['prefix'].'_', '',  $this->controller->action) : $this->controller->action;
        }

        /**
         * Return the url to the wsdl file.
         *
         * @return string
         */
        public function wsdlUrl()
        {
            return AppHelper::url(array('controller' => Inflector::underscore($this->controller->name), 'action' => $this->__settings['wsdlAction'], $this->__settings['prefix'] => true), true);
        }

    /**
     * Get WSDL for specified model.
     *
     * @param string $modelClass    : model name in camel case
     * @param string $serviceMethod : method of the controller that will handle SOAP calls
     */
    public function getWSDL($controllId, $serviceMethod = 'call')
    {
        $inflector = new Inflector();
        $controllClass = $inflector->camelize($controllId);
        $expireTime = '+1 year';
        $cachePath = $controllClass.'.wsdl';

        // Check cache if exist
        $wsdl = cache($cachePath, null, $expireTime);

        // If DEBUG > 0, compare cache modified time to model file modified time
        if ((Configure::read() > 0) && (!is_null($wsdl))) {
            $cacheFile = CACHE.$cachePath;
            if (is_file($cacheFile)) {
                $modelMtime = filemtime($this->__getControllFile($controllId));
                $cacheMtime = filemtime(CACHE.$cachePath);
                if ($modelMtime > $cacheMtime) {
                    $wsdl = null;
                }
            }
        }

        // Generate WSDL if not cached
        if (is_null($wsdl)) {
            $refl = new IPReflectionClass($controllClass.'Controller');

            $serviceURL = Router::url('/'.$this->__settings['prefix'].'/'.low($controllClass)."/$serviceMethod", true);

            $wsdlStruct = new WSDLStruct('http://partner.seevia.cn',
                                         $serviceURL,
                                         SOAP_RPC,
                                         SOAP_LITERAL);
            $wsdlStruct->setService($refl);
        //	print_r($wsdlStruct);
            try {
                $wsdl = $wsdlStruct->generateDocument();
                // cache($cachePath, $wsdl, $expireTime);
            } catch (WSDLException $exception) {
                if (Configure::read() > 0) {
                    $exception->Display();
                    exit();
                } else {
                    return;
                }
            }
        }

        return $wsdl;
    }

    /**
     * Handle SOAP service call.
     *
     * @param string $modelId    : underscore notation of the called model
     *                           without _service ending
     * @param string $wsdlMethod : method of the controller that will generate the WSDL
     */
    public function handle($controllId, $wsdlMethod = 'wsdl')
    {
        $inflector = new Inflector();
        $controllClass = $inflector->camelize($controllId);
        $wsdlCacheFile = CACHE.$controllClass.'.wsdl';

        // Try to create cache file if not exists
        if (!is_file($wsdlCacheFile)) {
            $this->getWSDL($controllId);
        }

        if (is_file($wsdlCacheFile)) {
            $server = new SoapServer($wsdlCacheFile);
        } else {
            $wsdlURL = Router::url('/'.$this->__settings['prefix'].'/'.low($controllClass)."/$wsdlMethod", true);

            $server = new SoapServer($wsdlURL);
        }
        $server->setObject($this->controller);
        $server->handle();
    }
    }
