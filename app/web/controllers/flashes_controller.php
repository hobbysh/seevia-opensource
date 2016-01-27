<?php

/*****************************************************************************
 * Seevia flash
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
App::import('Core', 'xml');
/**
 *这是一个名为FlashesController的控制器.
 */
class FlashesController extends AppController
{
    /*
    *@var $name
    *@var $helpers
    *@var $uses
    *@var $cacheQueries
    *@var $cacheAction
    */
    public $name = 'Flashes';
    public $helpers = array('Xml');
    public $uses = array('Flash','ProductGallery');
    public $cacheQueries = true;
    public $cacheAction = '1 hour';
    /**
     *flash控制器.
     *
     *@param $type
     *@param $type_id
     */
    public function index($type, $type_id = 0)
    {
        //$this->page_init();
      if ($type == 'P') {
          $flash_info = $this->Flash->find("type = '$type'");
      } else {
          $flash_info = $this->Flash->find("type = '$type' and type_id = $type_id ");
      }
        if (empty($flash_info)) {
            return;
        }
        $flash_info['Flash']['titlebgcolor'] = hexdec($flash_info['Flash']['titlebgcolor']);
        $flash_info['Flash']['titletextcolor'] = hexdec($flash_info['Flash']['titletextcolor']);
        $flash_info['Flash']['btntextcolor'] = hexdec($flash_info['Flash']['btntextcolor']);
        $flash_info['Flash']['btndefaultcolor'] = hexdec($flash_info['Flash']['btndefaultcolor']);
        $flash_info['Flash']['btnhovercolor'] = hexdec($flash_info['Flash']['btnhovercolor']);
        $flash_info['Flash']['btnfocuscolor'] = hexdec($flash_info['Flash']['btnfocuscolor']);

        $config['roundCorner'] = $flash_info['Flash']['roundcorner'];
        $config['autoPlayTime'] = $flash_info['Flash']['autoplaytime'];
        $config['isHeightQuality'] = $flash_info['Flash']['isheightquality'];
      //$config['normal'] = $flash_info['Flash']['normal'];
      $config['windowOpen'] = $flash_info['Flash']['windowopen'];
        $config['btnSetMargin'] = $flash_info['Flash']['btnsetmargin'];
        $config['btnDistance'] = $flash_info['Flash']['btndistance'];
        $config['titleBgColor'] = $flash_info['Flash']['titlebgcolor'];
        $config['titleTextColor'] = $flash_info['Flash']['titletextcolor'];
        $config['titleBgAlpha'] = $flash_info['Flash']['titlebgalpha'];
        $config['titleFont'] = $flash_info['Flash']['titlefont'];
        $config['titleMoveDuration'] = $flash_info['Flash']['titlemoveduration'];
        $config['btnAlpha'] = $flash_info['Flash']['btnalpha'];
        $config['btnTextColor'] = $flash_info['Flash']['btntextcolor'];
        $config['btnDefaultColor'] = $flash_info['Flash']['btndefaultcolor'];
        $config['btnHoverColor'] = $flash_info['Flash']['btnhovercolor'];
        $config['btnFocusColor'] = $flash_info['Flash']['btnfocuscolor'];
        $config['changImageMode'] = $flash_info['Flash']['changimagemode'];
        $config['isShowBtn'] = $flash_info['Flash']['isshowbtn'];
        $config['isShowTitle'] = $flash_info['Flash']['isshowtitle'];
        $config['scaleMode'] = $flash_info['Flash']['scalemode'];
        $config['transform'] = $flash_info['Flash']['transform'];
        $config['isShowAbout'] = $flash_info['Flash']['isshowabout'];

        if ($type == 'P') {
            $galleries = $this->ProductGallery->get_product_gallery($type_id);
            if (isset($galleries) && sizeof($galleries) > 0) {
                foreach ($galleries as $k => $v) {
                    $flash_info['FlashImage'][$k]['image'] = $this->url($v['ProductGallery']['img_detail'], false);
                    if (Configure::read('App.baseUrl')) {
                        $flash_info['FlashImage'][$k]['image'] = str_replace('index.php/', '', $flash_info['FlashImage'][$k]['image']);
                    }
                    $flash_info['FlashImage'][$k]['flash_id'] = 1;
                    $flash_info['FlashImage'][$k]['link'] = '#';
                    $flash_info['FlashImage'][$k]['title'] = '';
                }
            }
        } elseif ($flash_info['FlashImage']) {
            foreach ($flash_info['FlashImage'] as $k => $v) {
                $flash_info['FlashImage'][$k]['image'] = $this->url($v['image'], false);
                if (Configure::read('App.baseUrl')) {
                    $flash_info['FlashImage'][$k]['image'] = str_replace('index.php/', '', $flash_info['FlashImage'][$k]['image']);
                }
                if (isset($v['url']) && $v['url'] != '') {
                    $flash_info['FlashImage'][$k]['link'] = $this->url($v['url'], false);
                    unset($flash_info['FlashImage'][$k]['url']);
                } else {
                    $flash_info['FlashImage'][$k]['link'] = '#';
                }
            }
        }

        $channel = $flash_info['FlashImage'];

     // pr($channel);
      $flash = array('channel' => array('item' => $channel),'config' => $config);
        $xml_array = array('data' => $flash);
        $xml = new Xml($xml_array, array('format' => 'tags'));
 //	  pr($xml_array);
 //	  pr($xml);
      $result = $xml->toString(array('cdata' => true));
 //	  pr($result);
      $this->set('result', $result);
//	  pr($flash);
      Configure::write('debug', 0);
        $this->layoutPath = 'xml';
        $this->layout = 'default';
    }
    /**
     *url.
     *
     *@param $url
     *@param $full
     */
    public function url($url = null, $full = false)
    {
        return h(Router::url($url, $full));
    }
}
