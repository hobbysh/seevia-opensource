<?php 
/**
 * Pagination Component, responsible for managing the DATA required for pagination.
 */
class Pagination2Component extends Object
{
    // Configuration/Default variables
/*
 * Specify whether the component will use AJAX links if available.
 * Tests for the presence of the RequestHandler component and if present will generate
 * AJAX links. However, if the prototype library js has not been included, normal updates
 * will take place.
 *
 * @var boolean
 * @access public
 */
    public $ajaxAutoDetect = true;
/*
 * Specify the div to update if using ajax updates
 *
 * @var string
 * @access public
 */
    public $ajaxDivUpdate = 'content';
/*
 * The id of a form ID to be submitted with all pagination ajax links.
 *
 * @var string
 * @access public
 */
    public $ajaxFormId = null;
/*
 * Specify link style
 *
 * @var string "html"/"ajax"
 * @access public
 */
    public $style = 'html';
/*
 * Specify link parameter style
 *
 * @var string "get"/"pretty"
 * @access public
 */
    public $paramStyle = 'get';
/*
 * Used only for pretty style urls - must match the 
 *
 * @var string "get"/"pretty"
 * @access public
 */
    public $paramSeperator = '/';
/*
 * Specify DEFAULT start page number
 *
 * @var integer
 * @access public
 */
    public $page = 1;
/*
 * Specify DEFAULT number of items to be displayed per page. Also used as the limit
 * for the subsequent SQL search.
 *
 * @var integer
 * @access public
 */
    public $show = 5;
/*
 * Specify DEFAULT sort column.
 *
 * @var string
 * @access public
 */
    public $sortBy = 'id';
/*
 * Specify DEFAULT sort direction.
 *
 * @var string
 * @access public
 */
    public $direction = 'ASC';
/*
 * Specify the maximum number of pages to be included in the list of pages. Should be an odd number, otherwise rounded down.
 *
 * @var integer
 * @access public
 */
    public $maxPages = 10;
/*
 * Options for results per page.
 *
 * @var array
 * @access public
 */
    public $resultsPerPage = array(2,5,10,20,50,100,500);
/*
 * Show links to the first and last page, if the number of pages exceeds the maxPage count.
 *
 * @var boolean
 * @access public
 */
    public $showLimits = true;
/*
 * An array of parameter names which cannot be specified by the url
 *
 * @var array
 * @access public
 */
    public $privateParams = array();

    // Do not edit below this line unless you wish to customize the core functionality of this Component
/*
 * Place holder for the sort class. Irrelavent for models without associations
 *
 * @var boolean
 * @access private
 */
    public $sortByClass = null;
/*
 * Place holder for the model class.
 *
 * @var boolean
 * @access private
 */
    public $modelClass = null;
/*
 * Place holder for the base url
 *
 * @var boolean
 * @access private
 */
    public $url = null;
/*
 * Place holder for the controller
 *
 * @var boolean
 * @access private
 */
    public $controller = true;
/*
 * Place holder for the sanitize object
 *
 * @var boolean
 * @access private
 */
    public $sanitize = true;
/*
 * Place holder for the data array passed to the view
 *
 * @var boolean
 * @access private
 */
    public $paging;

    /**
     * Startup - Link the component to the controller.
     *
     * @param controller
     */
    public function startup(&$controller)
    {
        $this->controller = &$controller;
    }
    /**
     * Initialize the pagination data.
     *
     * @param unknown
     * @param array
     * @options array
     *
     * @return array
     */
    public function init($criteria = null, $parameters = array(), $options = array())
    {
        uses('sanitize');
        $this->Sanitize = &new Sanitize();

        $this->_initFields($options);
        $this->_checkAjax();
        $this->_initSort();
        $this->_initPaging($parameters);
        $this->_initURL();

        $this->_setParameter('show', $parameters);
        // If the number of results per page isn't in the list, reset to default
        if ((isset($this->paging['show'])) && (!in_array($this->paging['show'], $this->resultsPerPage))) {
            $this->paging['show'] = $this->paging['Defaults']['show'];
        }

        $this->_setParameter('page', $parameters);
        $this->_setParameter('sortBy', $parameters);
        $this->_setParameter('sortByClass', $parameters); // Overriding the model class if specified.
        $this->_setParameter('direction', $parameters);

        $this->_check4Form();

        $this->_setPrivateParameter('ajaxDivUpdate');
        $this->_setPrivateParameter('ajaxFormId');
        $this->_setPrivateParameter('maxPages');
        $this->_setPrivateParameter('showLimits');
        $this->_setPrivateParameter('style');
        $this->_setPrivateParameter('paramStyle');
        $this->_setPrivateParameter('paramSeperator');
        $this->_setPrivateParameter('url');

        if (isset($this->total)) {
            // If the field is already set, we  passed in the options the total number of results

            $count = $this->total;
        } else {
            $count = $this->controller->{$this->modelClass}->find('count', array('conditions' => $criteria));
        }
        $this->checkPage($count);
        $this->paging['total'] = $count;
        $this->trimResultsPerPage($count);

        $this->_setPrivateParameter('resultsPerPage');

        $this->paging['pageCount'] = ceil($count / $this->paging['show']);

        $this->controller->set('paging', $this->paging);
        $this->order = $this->paging['sortByClass'].'.'.$this->paging['sortBy'].' '.strtoupper($this->paging['direction']);

        // For backwards compatability & clarity
        $this->limit = $this->paging['show'];
        $this->page = $this->paging['page'];

        // For less code in the calling method..
        //return (Array($this->order,$this->paging['show'],$this->paging['page']));
        return $this->paging['page'];
    }

    /**
     * Don't give the choice to display pages with no results.
     *
     * @param int
     */
    public function trimResultsPerPage($count = 0)
    {
        while (($limit = current($this->resultsPerPage)) && (!isset($capKey))) {
            if ($limit >= $count) {
                $capKey = key($this->resultsPerPage);
            }
            next($this->resultsPerPage);

            if (isset($capKey)) {
                array_splice($this->resultsPerPage, ($capKey + 1));
            }
        }
    }

    /**
     * Set the page to the last if there would be no results, and to 1 if a negetive
     * page number is specified.
     *
     * @param int
     */
    public function checkPage($count = 0)
    {
        if ((($this->paging['page'] - 1) * $this->paging['show']) >= $count) {
            $this->paging['page'] = floor($count / $this->paging['show'] + 0.99);
        }
    }

    /**
     * Set Object fields.
     *
     * @param unknown
     */
    public function _initFields($options)
    {
        foreach ($options as $option => $val) {
            $this->$option = $val;
        }
    }
    /**
     * Set Pagination with default Parameters.
     *
     * @param unknown
     */
    public function _initPaging($parameters)
    {
        $this->paging['importParams'] = $parameters;
        $this->paging['Defaults'] = array(
                                        'page' => $this->page,
                                        'show' => $this->show,
                                        'sortBy' => $this->sortBy,
                                        'sortByClass' => $this->sortByClass,
                                        'direction' => $this->direction,
                                            );
    }
    /**
     * If everything is in place, use Ajax by default.
     *
     * @param unknown
     */
    public function _checkAjax()
    {
        if (($this->ajaxAutoDetect == true) && (isset($this->controller->RequestHandler) && (in_array('Ajax', $this->controller->helpers)))) {
            $this->style = 'ajax';
        }
    }

    /**
     * Set the DEFAULT sort class.
     *
     * @param unknown
     */
    public function _initSort()
    {
        if (!$this->modelClass) {
            $ModelClass = $this->modelClass = $this->controller->modelClass;
        } else {
            $ModelClass = $this->modelClass;
        }
        if (!$this->sortBy) {
            $this->sortBy = $this->controller->$ModelClass->primaryKey;
        }
        if (!$this->sortByClass) {
            $this->sortByClass = $ModelClass;
        }
    }

    /**
     * Set the base url for updates.
     *
     * @param unknown
     */
    public function _initURL()
    {
        if ($this->url) {
            // A url was specified in the paramters

            if (substr($this->url, -1, 1) != '/') {
                $this->url .= '/';
            }
        } else {
            // No url in the parameters, derive it.

            if ($this->paramStyle == 'get') {
                $this->url = str_replace($this->controller->webroot, '/', $this->controller->here);
            } else {
                $this->url = '';
                if (isset($this->controller->params['admin'])) {
                    $this->url .= '/'.$this->controller->params['admin'];
                    $action = substr($this->controller->action, strlen($this->controller->params['admin'].'_'));
                } else {
                    $action = $this->controller->action;
                }
                if ($this->controller->plugin) {
                    $this->url .= '/'.$this->controller->plugin;
                }
                $this->url .= '/'.$this->controller->name;
                $this->url .= '/'.$action;
                if (isset($this->paging['importParams']['_unamedParameters'])) {
                    $unnamedString = implode('/', $this->paging['importParams']['_unamedParameters']);
                    $this->url .= '/'.$unnamedString;
                    unset($this->paging['importParams']['_unamedParameters']);
                }
                $this->url .= '/';
            }
        }
        if (defined('BASE_URL')) { // Hack for no mod_rewrite
            $this->url = preg_replace('!'.BASE_URL.'!', '', $this->url); // Remove the base from the url
            $this->url = preg_replace("!\?.*!", '', $this->url); // Remove the get parameters
        }
    }

    /**
     * If the parameters have been changed/set by a form action, update the params array.
     * Would perhaps be best to redirect to the equivalent url, which isn't implemented as
     * the relavent method is in the helper and as such inaccessible here.
     *
     * @param unknown
     */
    public function _check4Form()
    {
        if (isset($this->controller->data['pagination'])) {
            if (isset($this->controller->data['pagination']['sortByComposite'])) {
                $Composite = array();
                $Composite = explode('::', $this->controller->data['pagination']['sortByComposite']);
                if (isset($Composite[0])) {
                    $this->controller->data['pagination']['sortBy'] = $Composite[0];
                }
                if (isset($Composite[1])) {
                    $this->controller->data['pagination']['direction'] = $Composite[1];
                }
                if (isset($Composite[2])) {
                    $this->controller->data['pagination']['sortByClass'] = $Composite[2];
                } else {
                    $this->controller->data['pagination']['sortByClass'] = $this->paging['Defaults']['sortByClass'];
                }
                unset($this->controller->data['pagination']['sortByComposite']);
            }
            foreach ($this->controller->data['pagination'] as $parameter => $value) {
                if (!in_array($parameter, $this->privateParams)) {
                    $this->paging[$parameter] = $this->Sanitize->paranoid($value, array('-', '_'));
                }
            }
        }
    }

    /**
     * Set a parameter to be passed to the view which cannot be specified/overriden from the url.
     *
     * @param unknown
     */
    public function _setPrivateParameter($parameter)
    {
        $this->paging[$parameter] = $this->$parameter;
    }

    /**
     * Set a parameter to be passed to the view overriden from the url if present.
     *
     * @param unknown
     * @param array
     * @param field
     */
    public function _setParameter($parameter, $parameters = array(), $field = null)
    {
        $field = $field ? $field : $parameter;

        if (in_array($parameter, $this->privateParams)) {
            $this->paging[$field] = $this->paging['Defaults'][$field];
        } else {
            if ($this->paramStyle == 'get') {
                if (isset($_GET[$parameter])) {
                    $this->paging[$field] = $this->Sanitize->paranoid($_GET[$parameter], array('-', '_'));
                } else {
                    $this->paging[$field] = $this->$field;
                }
            } elseif ($this->paramStyle == 'pretty') {
                if (isset($parameters[$parameter])) {
                    $this->paging[$field] = $this->Sanitize->paranoid($parameters[$parameter], array('-', '_'));
                } else {
                    $this->paging[$field] = $this->$field;
                }
            } else {
                echo('parameter error');
                die;
            }
        }
    }
}
?> 