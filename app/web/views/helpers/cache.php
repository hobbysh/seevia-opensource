<?php

/*****************************************************************************
 * Seevia 商业版模板缓存模块
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
/**
 * Short description for file.
 *
 * Long description for file
 */
class CacheHelper extends AppHelper
{
    /*
 * Array of strings replaced in cached views.
 * The strings are found between <cake:nocache><cake:nocache> in views
 *
 * @var array
 * @access private
 */
    public $__replace = array();
/*
 * Array of string that are replace with there var replace above.
 * The strings are any content inside <cake:nocache><cake:nocache> and includes the tags in views
 *
 * @var array
 * @access private
 */
    public $__match = array();
/*
 * holds the View object passed in final call to CacheHelper::cache()
 *
 * @var View
 * @access public
 */
    public $view;
/*
 * cache action time
 *
 * @var object
 * @access public
 */
    public $cacheAction;
    /**
     * Main method used to cache a view.
     *
     * @param string $file  File to cache
     * @param string $out   output to cache
     * @param bool   $cache
     *
     * @return view ouput
     */
    public function cache($file, $out, $cache = false)
    {
        $cacheTime = 0;
        $useCallbacks = false;
        if (is_array($this->cacheAction)) {
            $contoller = Inflector::underscore($this->controllerName);
            $check = str_replace('/', '_', $this->here);
            $replace = str_replace('/', '_', $this->base);
            $match = str_replace($this->base, '', $this->here);
            $match = str_replace('//', '/', $match);
            $match = str_replace('/'.$contoller.'/', '', $match);
            $match = str_replace('/'.$this->controllerName.'/', '', $match);
            $check = str_replace($replace, '', $check);
            $check = str_replace('_'.$contoller.'_', '', $check);
            $check = str_replace('_'.$this->controllerName.'_', '', $check);
            $check = Inflector::slug($check);
            $check = preg_replace('/^_+/', '', $check);
            $keys = str_replace('/', '_', array_keys($this->cacheAction));
            $found = array_keys($this->cacheAction);
            $index = null;
            $count = 0;

            foreach ($keys as $key => $value) {
                if (strpos($check, $value) === 0) {
                    $index = $found[$count];
                    break;
                }
                ++$count;
            }

            if (isset($index)) {
                $pos1 = strrpos($match, '/');
                $char = strlen($match) - 1;

                if ($pos1 == $char) {
                    $match = substr($match, 0, $char);
                }

                $key = $match;
            } elseif ($this->action == 'index') {
                $index = 'index';
            }

            $options = $this->cacheAction;
            if (isset($this->cacheAction[$index])) {
                if (is_array($this->cacheAction[$index])) {
                    $options = array_merge(array('duration' => 0, 'callbacks' => false), $this->cacheAction[$index]);
                } else {
                    $cacheTime = $this->cacheAction[$index];
                }
            }

            if (array_key_exists('duration', $options)) {
                $cacheTime = $options['duration'];
            }
            if (array_key_exists('callbacks', $options)) {
                $useCallbacks = $options['callbacks'];
            }
        } else {
            $cacheTime = $this->cacheAction;
        }

        if ($cacheTime != '' && $cacheTime > 0) {
            $this->__parseFile($file, $out);
            if ($cache === true) {
                $cached = $this->__parseOutput($out);
                $this->__writeFile($cached, $cacheTime, $useCallbacks);
            }

            return $out;
        } else {
            return $out;
        }
    }
    /**
     * Parse file searching for no cache tags.
     *
     * @param string $file
     * @param bool   $cache
     */
    public function __parseFile($file, $cache)
    {
        if (is_file($file)) {
            $file = file_get_contents($file);
        } elseif ($file = fileExistsInPath($file)) {
            $file = file_get_contents($file);
        }

        preg_match_all('/(<cake:nocache>(?<=<cake:nocache>)[\\s\\S]*?(?=<\/cake:nocache>)<\/cake:nocache>)/i', $cache, $oresult, PREG_PATTERN_ORDER);
        preg_match_all('/(?<=<cake:nocache>)([\\s\\S]*?)(?=<\/cake:nocache>)/i', $file, $result, PREG_PATTERN_ORDER);

        if (!empty($this->__replace)) {
            foreach ($oresult['0'] as $k => $element) {
                $index = array_search($element, $this->__match);
                if ($index !== false) {
                    array_splice($oresult[0], $k, 1);
                }
            }
        }

        if (!empty($result['0'])) {
            $count = 0;
            foreach ($result['0'] as $block) {
                if (isset($oresult['0'][$count])) {
                    $this->__replace[] = $block;
                    $this->__match[] = $oresult['0'][$count];
                }
                ++$count;
            }
        }
    }
    /**
     * Parse the output and replace cache tags.
     *
     * @param sting $cache
     *
     * @return string with all replacements made to <cake:nocache><cake:nocache>
     */
    public function __parseOutput($cache)
    {
        $count = 0;
        if (!empty($this->__match)) {
            foreach ($this->__match as $found) {
                $original = $cache;
                $length = strlen($found);
                $position = 0;

                for ($i = 1; $i <= 1; ++$i) {
                    $position = strpos($cache, $found, $position);

                    if ($position !== false) {
                        $cache = substr($original, 0, $position);
                        $cache .= $this->__replace[$count];
                        $cache .= substr($original, $position + $length);
                    } else {
                        break;
                    }
                }
                ++$count;
            }

            return $cache;
        }

        return $cache;
    }
    /**
     * Write a cached version of the file.
     *
     * @param string $file
     * @param sting  $timestamp
     *
     * @return cached view
     */
    public function __writeFile($content, $timestamp, $useCallbacks = false)
    {
        $now = time();

        if (is_numeric($timestamp)) {
            $cacheTime = $now + $timestamp;
        } else {
            $cacheTime = strtotime($timestamp, $now);
        }
        $path = $this->here;
        if ($this->here == '/') {
            $path = 'home';
        }
        $cache = strtolower(Inflector::slug($path));

        if (empty($cache)) {
            return;
        }

            /* hobbysh 20090729 muti cache start*/
            $CookieComponent = &new CookieComponent();
        $controller = null;
        $CookieComponent->initialize($controller, false);
        $cache = strtolower(implode(array($_SERVER['SERVER_NAME'], $cache, $CookieComponent->read('locale'), $CookieComponent->read('template'), $CookieComponent->read('template_style')), '_'));
            /* hobbysh 20090729 muti cache end*/

        $cache = $cache.'.php';
        $file = '<!--cachetime:'.$cacheTime.'--><?php';

        if (empty($this->plugin)) {
            $file .= '
			App::import(\'Controller\', \''.$this->controllerName.'\');
			';
        } else {
            $file .= '
			App::import(\'Controller\', \''.$this->plugin.'.'.$this->controllerName.'\');
			';
        }

        $file .= '$controller =& new '.$this->controllerName.'Controller();
				$controller->plugin = $this->plugin = \''.$this->plugin.'\';
				$controller->helpers = $this->helpers = unserialize(\''.serialize($this->helpers).'\');
				$controller->base = $this->base = \''.$this->base.'\';
				$controller->layout = $this->layout = \''.$this->layout.'\';
				$controller->webroot = $this->webroot = \''.$this->webroot.'\';
				$controller->here = $this->here = \''.$this->here.'\';
				$controller->namedArgs  = $this->namedArgs  = \''.$this->namedArgs.'\';
				$controller->argSeparator = $this->argSeparator = \''.$this->argSeparator.'\';
				$controller->params = $this->params = unserialize(stripslashes(\''.addslashes(serialize($this->params)).'\'));
				$controller->action = $this->action = unserialize(\''.serialize($this->action).'\');
				$controller->data = $this->data = unserialize(stripslashes(\''.addslashes(serialize($this->data)).'\'));
				$controller->themeWeb = $this->themeWeb = \''.$this->themeWeb.'\';';

        if ($useCallbacks == true) {
            $file .= '
				$controller->constructClasses();
				$controller->Component->initialize($controller);
				$controller->beforeFilter();
				$controller->Component->startup($controller);';
        }

        $file .= '
				Router::setRequestInfo(array($this->params, array(\'base\' => $this->base, \'webroot\' => $this->webroot)));
				$loadedHelpers = array();
				$loadedHelpers = $this->_loadHelpers($loadedHelpers, $this->helpers);
				foreach (array_keys($loadedHelpers) as $helper) {
					$camelBackedHelper = Inflector::variable($helper);
					${$camelBackedHelper} =& $loadedHelpers[$helper];
					$this->loaded[$camelBackedHelper] =& ${$camelBackedHelper};
				}
		?>';
        $content = preg_replace('/(<\\?xml)/', "<?php echo '$1';?>", $content);
        $file .= $content;

        return cache('views'.DS.$cache, $file, $timestamp);
    }
}
