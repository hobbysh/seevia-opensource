<?php

/*****************************************************************************
 * Seevia 关注用户管理
* ===========================================================================
* 版权所有  上海实玮网络科技有限公司，并保留所有权利。
* 网站地址: http://www.seevia.cn
* ---------------------------------------------------------------------------
* 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
* 不允许对程序代码以任何形式任何目的的再发布。
* ===========================================================================
* $开发: 上海实玮$
* $Id$*/

class OpenUsersController extends AppController
{
    public $name = 'OpenUsers';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination');
    public $uses = array('OpenUser','OpenModel', 'OpenUserMessage', 'OperatorLog', 'OpenRelation','OpenKeywordError');

    public function index()
    {
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_users/');
        /*判断权限*/
        $this->operator_privilege('open_users_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        /*end*/
        $this->pageTitle = $this->ld['focus_user_management'].' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['focus_user_management'],'url' => '');
        $condition = '';
        $page = 1;
        if (isset($_REQUEST['keyword']) && trim($_REQUEST['keyword']) != '') {
            $_REQUEST['keyword']=trim($_REQUEST['keyword']);
            $condition['or']['OpenUser.nickname like'] = '%'.urlencode($_REQUEST['keyword']).'%';
            $condition['or']['OpenUser.openid like'] = '%'.$_REQUEST['keyword'].'%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        if (isset($_REQUEST['openType']) && trim($_REQUEST['openType']) != '') {
		$_REQUEST['openType']=trim($_REQUEST['openType']);
            $condition['and']['OpenUser.open_type'] = $_REQUEST['openType'];
            $this->set('openType', $_REQUEST['openType']);
        }
        if (isset($_REQUEST['open_type_id']) && trim($_REQUEST['open_type_id']) != '') {
            $_REQUEST['open_type_id']=trim($_REQUEST['open_type_id']);
            $condition['and']['OpenUser.open_type_id'] = $_REQUEST['open_type_id'];
            $this->set('open_type_id', $_REQUEST['open_type_id']);
        }
        if (isset($_REQUEST['subscribe']) && trim($_REQUEST['subscribe']) != '') {
            $_REQUEST['subscribe']=trim($_REQUEST['subscribe']);
            $condition['and']['OpenUser.subscribe'] = $_REQUEST['subscribe'];
            $this->set('subscribe', $_REQUEST['subscribe']);
        }
        $total = $this->OpenUser->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'OpenUser';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'OpenUser','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OpenUser');
        $this->Pagination->init($condition, $parameters, $options);
        $user_list = $this->OpenUser->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'OpenUser.created desc'));
        $this->set('user_list', $user_list);
        //获取微信公众号类型（服务号）及认证状态
        $open_type = $this->OpenModel->find('all', array('conditions' => array('OpenModel.status' => 1, 'OpenModel.verify_status' => 1)));
        $this->set('open_type', $open_type);

        $this->set('title_for_layout', $this->ld['focus_user_management'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    //编辑
    public function view($id = 0)
    {
        $this->operator_privilege('open_users_view');
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_users/');
        $this->pageTitle = $this->ld['view_user_records'].' - '.$this->configs['shop_name'];
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['focus_user_management'],'url' => '/open_users/');

        if ($this->RequestHandler->isPost()) {
            $this->data['OpenModel']['id'] = $id;
            $this->OpenModel->save(array('OpenModel' => $this->data['OpenModel']));
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->data = $this->OpenUser->findById($id);
        //历史记录
        $msgList = $this->OpenUserMessage->getListByUserId($id);
        //关注记录
        $relationList = $this->OpenRelation->getListByUserId($id);
        //导般 名称设置
        $this->navigations[] = array('name' => $this->ld['edit'].'- '.urldecode($this->data['OpenUser']['nickname']),'url' => '');
        $this->set('title_for_layout', $this->ld['view_user_records'].' - '.$this->configs['shop_name']);
        $this->set('msgList', $msgList);
        $this->set('relationList', $relationList);
    }

    public function sendMsg($id)
    {
        if ($this->RequestHandler->isPost()) {
            $result['code'] = 0;
            $result['msg'] = '';
            $openId = $_POST['openid'];
            $openType = $_POST['open_type'];
            $openTypeId = $_POST['open_type_id'];

            $openModelInfo = $this->OpenModel->getInfoByOpenTypeId($openTypeId);
            if (empty($openModelInfo)) {
                $result['msg'] = '公众号信息不完善';
                $_SESSION['OPEN_MESSAGE'] = '公众号信息不完善！';
                die(json_encode($result));
            }
            $appId = $openModelInfo['OpenModel']['app_id'];
            $appSecret = $openModelInfo['OpenModel']['app_secret'];
            $accessToken = $openModelInfo['OpenModel']['token'];
            if (!$this->OpenModel->validateToken($openModelInfo)) {
                //无效重新获取
                $accessToken = $this->OpenModel->getAccessToken($appId, $appSecret);
                if (empty($accessToken)) {
                    $result['msg'] = '公众号信息验证失败';
                    $_SESSION['OPEN_MESSAGE'] = '公众号信息验证失败！';
                    $this->redirect('/open_users/view/'.$id);
                    die(json_encode($result));
                }
                $openModelInfo['OpenModel']['token'] = $accessToken;
                $this->OpenModel->save($openModelInfo);
            }

            $reply_type = $_POST['reply_type'];
            $post_data['touser'] = $openId;

            $reply_data = '';
            $default_PicUrl = '/admin/skins/default/img/element_default.jpg';
            if ($reply_type == 'picture') {
                $reply_picture = $_POST['reply_picture'];
                if ($element_data = $this->_getElementMsg($reply_picture)) {
                    foreach ($element_data as $k => $v) {
                        $reply_data['articles'][$k]['title'] = $v['OpenElement']['title'];
                        $reply_data['articles'][$k]['description'] = mb_substr($this->emptyreplace($v['OpenElement']['description']), 0, 100, 'utf-8').'...';
                        $reply_data['articles'][$k]['picurl'] = $this->server_host.($v['OpenElement']['media_url'] != '' ? $v['OpenElement']['media_url'] : $default_PicUrl);
                        $reply_data['articles'][$k]['url'] = empty($v['OpenElement']['url']) ? $this->server_host.'/open_elements/'.$v['OpenElement']['id'] : $v['OpenElement']['url'];
                    }
                    $post_data['msgtype'] = 'news';
                    $post_data['news']['articles'] = $reply_data['articles'];
                }
            } elseif ($reply_type == 'article') {
                $reply_article = $_POST['reply_article'];
                if ($article_data = $this->_getArticleMsg($reply_article)) {
                    $reply_data['articles'][0]['title'] = $article_data['Article']['title'];
                    $reply_data['articles'][0]['description'] = mb_substr($this->emptyreplace($article_data['ArticleI18n']['content']), 0, 100, 'utf-8').'...';
                    $reply_data['articles'][0]['picurl'] = $this->server_host.($article_data['ArticleI18n']['img01'] != '' ? $article_data['ArticleI18n']['img01'] : $default_PicUrl);
                    $reply_data['articles'][0]['url'] = $this->server_host.'/articles/'.$article_data['Article']['id'];

                    $post_data['msgtype'] = 'news';
                    $post_data['news']['articles'] = $reply_data['articles'];
                }
            } elseif ($reply_type == 'product') {
                $reply_product = $_POST['reply_product'];
                if ($product_data = $this->_getProductMsg($reply_product)) {
                    $reply_data['articles'][0]['title'] = $product_data['ProductI18n']['name'];
                    $reply_data['articles'][0]['description'] = mb_substr($this->emptyreplace($product_data['ProductI18n']['description']), 0, 100, 'utf-8').'...';
                    $reply_data['articles'][0]['picurl'] = $this->server_host.($product_data['Product']['img_thumb'] != '' ? $product_data['Product']['img_thumb'] : $default_PicUrl);
                    $reply_data['articles'][0]['url'] = $this->server_host.'/products/'.$product_data['Product']['id'];

                    $post_data['msgtype'] = 'news';
                    $post_data['news']['articles'] = $reply_data['articles'];
                }
            } else {
                $post_data['msgtype'] = 'text';
                $post_data['text']['content'] = $_POST['reply_content'];
            }
            $url = $this->OpenModel->getPostUrl($openType, $accessToken);
            $post_data_str = $this->to_josn($post_data);
            $results = $this->https_request($url, $post_data_str);

            if (isset($post_data['news']['articles'][0]['title'])) {
                $reply_content = "<a target='_blank' href='".$post_data['news']['articles'][0]['url']."'>".$post_data['news']['articles'][0]['title'].'</a>';
            } else {
                $reply_content = $post_data['text']['content'];
            }
            if (!empty($results)) {
                if ($results['errcode'] == 0) {
                    $result['code'] = 1;
                    $result['msg'] = '发送成功';
                } else {
                    $result['msg'] = $results['errmsg'];
                }
            } else {
                $result['msg'] = '发送失败';
            }
            $this->_saveMsg($id, $reply_content, $openId, $openTypeId, $openType, $results);
            if (isset($_POST['keyword_error_id'])) {
                $keyword_error_data['id'] = $_POST['keyword_error_id'];
                $keyword_error_data['status'] = '1';
                $this->OpenKeywordError->save($keyword_error_data);
            }
            die(json_encode($result));
        } else {
            $this->redirect('/open_users/');
        }
    }

    /*
    * 取素材回复信息
    */
    private function _getElementMsg($Id)
    {
        $this->loadModel('OpenElement');
        $conditions['or']['OpenElement.id'] = $Id;
        $conditions['or']['OpenElement.parent_id'] = $Id;
        $cond['conditions'] = $conditions;
        $cond['order'] = 'OpenElement.id';
        $open_elementInfos = $this->OpenElement->find('all', $cond);
        if (!empty($open_elementInfos) && sizeof($open_elementInfos) > 0) {
            return $open_elementInfos;
        } else {
            return false;
        }
    }

    /**
     * 取商品信息.
     */
    private function _getProductMsg($Id)
    {
        $this->loadModel('Product');
        $condition['AND']['Product.id'] = $Id;
        $condition['AND']['Product.status'] = '1';
        $condition['AND']['Product.forsale'] = '1';
        $condition['AND']['ProductI18n.locale'] = 'chi';
        $fields = 'Product.id,ProductI18n.name,ProductI18n.description,Product.img_thumb';
        $productInfo = $this->Product->find('first', array('conditions' => $condition, 'fields' => $fields));
        if (empty($productInfo)) {
            return false;
        } else {
            return $productInfo;
        }
    }

    /*
    * 取文章信息
    */
    private function _getArticleMsg($Id)
    {
        $this->loadModel('Article');
        $cond['Article.id'] = $Id;
        $cond['Article.type !='] = 'V';
        $cond['Article.status'] = '1';
        $cond['ArticleI18n.locale'] = 'chi';
        $fields = 'Article.id,ArticleI18n.title,ArticleI18n.content,ArticleI18n.img01';
        $article_data = $this->Article->find('first', array('conditions' => $cond, 'fields' => $fields));
        if (!empty($article_data)) {
            return $article_data;
        } else {
            return false;
        }
    }

    /*
        调用接口
    */
    private function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        return json_decode($output, true);
    }

    /*
        去除字符串空格
    */
    private function emptyreplace($str)
    {
        $str = trim($str);
        $str = strip_tags($str, '');
        $str = ereg_replace("\t", '', $str);
        $str = ereg_replace("\r\n", '', $str);
        $str = ereg_replace("\r", '', $str);
        $str = ereg_replace("\n", '', $str);
        $str = ereg_replace(' ', ' ', $str);

        return trim($str);
    }

    /*
        $data   需要转换josn提交的数据
    */
    private function to_josn($data)
    {
        $this->arrayRecursive($data, 'urlencode');
        $json = json_encode($data);

        return urldecode($json);
    }

    /************************************************************** 
    * 对数组中所有元素做处理,保留中文 
    * @param string &$array 要处理的数组
    * @param string $function 要执行的函数 
    * @return boolean $apply_to_keys_also 是否也应用到key上 
    * @access public 
    * 
    *************************************************************/
    private function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        --$recursive_counter;
    }

    private function _saveMsg($id, $msg, $openId, $openTypeId, $openType = 'wechat', $results = array())
    {
        $userMsg = array();
        $userMsg['OpenUserMessage']['open_type'] = $openType;
        $userMsg['OpenUserMessage']['open_type_id'] = $openTypeId;
        $userMsg['OpenUserMessage']['open_user_id'] = $id;
        $userMsg['OpenUserMessage']['send_from'] = 0;
        $userMsg['OpenUserMessage']['msgtype'] = 'text';
        $userMsg['OpenUserMessage']['message'] = $msg;
        $userMsg['OpenUserMessage']['return_code'] = isset($results['errcode']) ? $results['errcode'] : '';
        $userMsg['OpenUserMessage']['return_message'] = isset($results['errmsg']) ? $results['errmsg'] : '';
        $this->OpenUserMessage->save($userMsg);
    }

    /**
     *获取关注列表.
     *
     *获取公众平台关注用户列表
     *
     *@author   zhta
     *
     *@version  $Id$
     */
    public function api_user_action($id)
    {
        $this->operator_privilege('open_users_update');
        //token重新获取
        $openModelInfo = $this->OpenModel->getInfoById($id);
        if (!empty($openModelInfo)) {
            $openType = empty($openModelInfo['OpenModel']['open_type']) ? '' : $openModelInfo['OpenModel']['open_type'];
            $appId = $openModelInfo['OpenModel']['app_id'];
            $appSecret = $openModelInfo['OpenModel']['app_secret'];
            if (!$this->OpenModel->validateToken($openModelInfo)) {
                //无效重新获取
                $accessToken = $this->OpenModel->getAccessToken($appId, $appSecret, $openType);
                $openModelInfo['OpenModel']['token'] = $accessToken;
                $this->OpenModel->save($openModelInfo);
            }
            if ($openType == 'wechat') {
                $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$openModelInfo['OpenModel']['token'];
            }
            if (isset($url)) {
                $result = file_get_contents($url);
                $return_msg = $result;
                $result = json_decode($result, true);
                if (isset($result['data']['openid'])) {
                    foreach ($result['data']['openid'] as $k => $v) {
                        $this->_saveUser($openModelInfo['OpenModel']['token'], $v, $openModelInfo['OpenModel']['open_type_id']);
                    }
                    $msg = '更新关注列表成功';
                    $flag = 'ok';
                } else {
                    $msg = '获取关注列表失败';
                    $flag = 'error';
                }
                $this->OpenUserMessage->saveMsg('user/get', $openModelInfo['OpenModel']['token'], 0, $openModelInfo['OpenModel']['open_type_id'], 0, $flag, $return_msg);
                echo '<meta charset=utf-8 /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/admin/open_users/"</script>';
                die();
            }
        }
        $this->redirect('/open_users/');
    }

    /**
     *获取用户基本信息.
     *
     *获取关注用户的基本信息
     *
     *@author   zhta
     *
     *@version  $Id$
     */
    private function _saveUser($accessToken, $openId, $openTypeId, $openType = 'wechat')
    {
        if ($openType == 'wechat') {
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$accessToken.'&openid='.$openId.'&lang=zh_CN';
        }
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        if (!empty($result) && !isset($result['errcode'])) {
            $userInfo = $this->OpenUser->getInfoByOpenId($result['openid']);
            if (empty($userInfo)) {
                //check if exist， if exist set subscribe 1
            } else {
                $userInfo['OpenUser']['subscribe'] = 1;
            }
            $userInfo['OpenUser']['open_type'] = $openType;
            $userInfo['OpenUser']['open_type_id'] = $openTypeId;
            $userInfo['OpenUser']['openid'] = $result['openid'];
            $userInfo['OpenUser']['nickname'] = urlencode($result['nickname']);
            $userInfo['OpenUser']['sex'] = $result['sex'];
            $userInfo['OpenUser']['language'] = $result['language'];
            $userInfo['OpenUser']['city'] = $result['city'];
            $userInfo['OpenUser']['province'] = $result['province'];
            $userInfo['OpenUser']['country'] = $result['country'];
            $userInfo['OpenUser']['headimgurl'] = $result['headimgurl'];
            $userInfo['OpenUser']['subscribe_time'] = $result['subscribe_time'];
            $this->OpenUser->saveAll($userInfo);
        }
    }

    public function quickreply($open_user_id = 0, $action = 'page_show')
    {
        $this->operator_privilege('open_users_view');
        $this->loadModel('Product');
        $this->loadModel('Article');
        $this->loadModel('SkuProduct');
        $this->loadModel('OpenElement');
        if ($this->RequestHandler->isPost()) {
            if ($action == 'page_show') {
                $this->data = $this->OpenUser->findById($open_user_id);
                $open_element_list = $this->OpenElement->find('list', array('fields' => array('OpenElement.id', 'OpenElement.title'), 'conditions' => array('OpenElement.parent_id' => 0)));
                $this->set('open_element_list', $open_element_list);
            } elseif ($action == 'select_article') {
                if (isset($_POST['keyword'])) {
                    $keyword = $_POST['keyword'];
                    $cond['or']['ArticleI18n.title LIKE'] = '%'.$keyword.'%';
                    $cond['or']['ArticleI18n.subtitle LIKE'] = '%'.$keyword.'%';
                    $cond['or']['ArticleI18n.meta_keywords LIKE'] = '%'.$keyword.'%';
                }
                $cond['Article.type !='] = 'V';
                $cond['Article.status'] = '1';
                $condition['ArticleI18n.locale'] = 'chi';
                $articleinfos = $this->Article->find('all', array('fields' => array('Article.id', 'ArticleI18n.title'), 'conditions' => $cond));
                $article_list = array();
                foreach ($articleinfos as $v) {
                    $data['key'] = $v['Article']['id'];
                    $data['value'] = $v['ArticleI18n']['title'];
                    $article_list[] = $data;
                }
                die(json_encode($article_list));
            } elseif ($action == 'select_product') {
                if (isset($_POST['keyword'])) {
                    $keyword = $_POST['keyword'];
                    $condition['or']['ProductI18n.name like'] = '%'.$keyword.'%';
                    $condition['or']['Product.code like'] = '%'.$keyword.'%';
                    $condition['or']['ProductI18n.meta_keywords like'] = '%'.$keyword.'%';
                }
                $sku_code = $this->SkuProduct->find('list', array('fields' => array('SkuProduct.sku_product_code')));
                if (!empty($sku_code)) {
                    $condition['Not']['Product.code'] = $sku_code;
                }
                $condition['AND']['Product.status'] = '1';
                $condition['AND']['Product.forsale'] = '1';
                $condition['AND']['ProductI18n.locale'] = 'chi';
                $productInfo = $this->Product->find('all', array('fields' => array('Product.id', 'ProductI18n.name'), 'conditions' => $condition));
                $product_list = array();
                foreach ($productInfo as $v) {
                    $data['key'] = $v['Product']['id'];
                    $data['value'] = $v['ProductI18n']['name'];
                    $product_list[] = $data;
                }
                die(json_encode($product_list));
            }
        }
    }
}
