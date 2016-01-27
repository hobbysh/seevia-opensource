<?php

/*****************************************************************************
 * Seevia 短信日志
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
App::import('Vendor', 'weibo2', array('file' => 'saetv2.php'));
class WbmktUserinfosController extends AppController
{
    public $name = 'WbmktUserinfos';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Html','Pagination');
    public $uses = array('SynchroOperator','WeiboTeam','WeiboLog','WeiboThm');
    public function index()
    {
        $this->navigations[] = array('name' => '微营销管理','url' => '');
        $this->navigations[] = array('name' => '微博通','url' => '/wbmkt_userinfos/');
        $this->set('title_for_layout', '微博通'.' - '.$this->configs['shop_name']);

        $wb_result = $this->home_timeline();
    }

    //发布微博
    public function release()
    {
        if (empty($_POST['uptext'])) {
            $status = '发布微博';
        } else {
            $status = $_POST['uptext'];
        }
        if (isset($_FILES['upfile']['tmp_name']) && !empty($_FILES['upfile']['tmp_name'])) {
            $file_name = $_FILES['upfile']['name'];
            $file_types = explode('.', $file_name);
            $dian_count = count($file_types) - 1;
            $file_type = isset($file_types[$dian_count]) ? $file_types[$dian_count] : '';
            $file_t = '.'.$file_type;
            $filename = str_replace($file_t, '', $file_name);
            $dir_root = dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/data';
            if (!is_dir($dir_root.'/files/')) {
                mkdir($dir_root.'/files/', 0777);
                @chmod($dir_root.'/files/', 0777);
            }
            move_uploaded_file($_FILES['upfile']['tmp_name'], dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/data/files/'.date('Ymd').$file_name);
            $file_path = dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))).'/data/files/'.date('Ymd').$file_name;
            $file_url = 'http://'.$_SERVER['HTTP_HOST'].'/files/'.date('Ymd').$file_name;
            $pic = 'saas/src/dev/htdocs/vhosts/c59769a.ioco.dev/data/files/201302061.jpg';
            $this->statuses_upload($status, $pic);
            //$this->statuses_upload_url_text($status,$file_url);
        } else {
            $this->statuses_update($status);
        }
        $this->redirect('/');
    }

    //appkey token
    public function saetoauthv2()
    {
        $shop_oper = $this->SynchroOperator->find('first', array('conditions' => array('SynchroOperator.status' => 1)));
        $app_key = $shop_oper['SynchroOperator']['app_key'];
        $app_secret = $shop_oper['SynchroOperator']['app_secret'];
        $access_token = $shop_oper['SynchroOperator']['access_token'];
        $SaeTOAuthV2 = new SaeTOAuthV2($app_key, $app_secret, $access_token);

        return $SaeTOAuthV2;
    }
    //获取当前登录用户及其所关注用户的最新微博 
    public function home_timeline()
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'statuses/home_timeline';
        $usparm['count'] = 20;
        $usparm['page'] = 1;
        $usparm['base_app'] = 0;
        $usparm['feature'] = 0;
        $usparm['trim_user'] = 0;
        $wb_result = $SaeTOAuthV2->get($url, $usparm);
    //	pr($wb_result);die;
        if (isset($wb_result['statuses']) && isset($wb_result['total_number'])) {
            $this->set('wb_result', $wb_result['statuses']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    //获取最新的公共微博 
    public function public_timeline()
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'statuses/public_timeline';
        $parameters = array();
        $parameters['count'] = 20;
        $wb_result = $SaeTOAuthV2->get($url, $parameters);
        if (isset($wb_result['statuses']) && isset($wb_result['total_number'])) {
            $this->set('wb_result', $wb_result['statuses']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    //获取某个用户最新发表的微博列表
    public function user_timeline($uid = '2041846913')
    {
        //1706008551
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'statuses/user_timeline';
        $usparm = array();
        $usparm['uid'] = $uid;
        $usparm['count'] = 20;
        $usparm['page'] = 1;
        $usparm['base_app'] = 0;
        $usparm['feature'] = 0;
        $usparm['trim_user'] = 0;
        $wb_result = $SaeTOAuthV2->get($url, $usparm);
        if (isset($wb_result['statuses']) && isset($wb_result['total_number'])) {
            $this->set('wb_result', $wb_result['statuses']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    public function userinfo($uid)
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'statuses/user_timeline';
        $usparm = array();
        $usparm['uid'] = $uid;
        $usparm['count'] = 20;
        $usparm['page'] = 1;
        $usparm['base_app'] = 0;
        $usparm['feature'] = 0;
        $usparm['trim_user'] = 0;
        $wb_result = $SaeTOAuthV2->get($url, $usparm);
        if (isset($wb_result['statuses']) && isset($wb_result['total_number'])) {
            $this->set('wb_result', $wb_result['statuses']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    //获取最新的提到登录用户的微博列表，即@我的微博
    public function mentions()
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'statuses/mentions';
        $usparm = array();
        $usparm['count'] = 20;
        $usparm['page'] = 1;
        $usparm['filter_by_author'] = 0;//作者筛选类型，0：全部、1：我关注的人、2：陌生人，默认为0。
        $usparm['filter_by_source'] = 0;//来源筛选类型，0：全部、1：来自微博、2：来自微群，默认为0。
        $usparm['filter_by_type'] = 0;//原创筛选类型，0：全部微博、1：原创的微博，默认为0。 
        $wb_result = $SaeTOAuthV2->get($url, $usparm);
        if (isset($wb_result['statuses']) && isset($wb_result['total_number'])) {
            $this->set('wb_result', $wb_result['statuses']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    // 	获取@到我的评论 
    public function comment_mentions()
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'comments/mentions';
        $usparm = array();
        $usparm['count'] = 20;
        $usparm['page'] = 1;
        $usparm['filter_by_author'] = 0;//作者筛选类型，0：全部、1：我关注的人、2：陌生人，默认为0。
        $usparm['filter_by_source'] = 0;//来源筛选类型，0：全部、1：来自微博的评论、2：来自微群的评论，默认为0。 
        $wb_result = $SaeTOAuthV2->get($url, $usparm);
        if (isset($wb_result['statuses']) && isset($wb_result['total_number'])) {
            $this->set('wb_result', $wb_result['statuses']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    //根据微博ID返回某条微博的评论列表 
    public function comments_show($web_id = '3548170665960519')
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'comments/show';
        $usparm = array();
        $usparm['id'] = $web_id;
        $usparm['count'] = 20;
        $usparm['page'] = 1;
        $wb_result = $SaeTOAuthV2->get($url, $usparm);
//		pr($wb_result);die;
        if (isset($wb_result['comments']) && isset($wb_result['total_number'])) {
            $this->set('wb_result', $wb_result['comments']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    //对一条微博进行评论 
    public function comments_create($web_id = '3542291535129023', $comment = '评论回复内容')
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'comments/create';
        $parameters = array();
        $parameters['id'] = $web_id;// 	需要评论的微博ID。
        $parameters['comment'] = $comment;//评论内容，必须做URLencode，内容不超过140个汉字。 
//		$parameters['comment_ori']=0; //当评论转发微博时，是否评论给原微博，0：否、1：是，默认为0。
        $wb_result = $SaeTOAuthV2->post($url, $parameters);
//		pr($wb_result);die;
        if (isset($wb_result['status']) && isset($wb_result['user'])) {
            //$msg='评论成功';
            $this->set('wb_result', $wb_result['status']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    //回复一条评论 
    public function comments_reply($cid = '', $web_id = '3542291535129023', $comment = '评论回复内容')
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'comments/reply';
        $parameters = array();
        $parameters['cid'] = $cid;//需要回复的评论ID。
        $parameters['id'] = $web_id;//需要评论的微博ID。
        $parameters['comment'] = $comment;//回复评论内容，必须做URLencode，内容不超过140个汉字。
//		$parameters['without_mention']=0; //回复中是否自动加入“回复@用户名”，0：是、1：否，默认为0。
//		$parameters['comment_ori']=0; //当评论转发微博时，是否评论给原微博，0：否、1：是，默认为0。
        $wb_result = $SaeTOAuthV2->post($url, $parameters);
        pr($wb_result);
        die;
        if (isset($wb_result['status']) && isset($wb_result['user'])) {
            //$msg='回复成功';
            $this->set('wb_result', $wb_result['status']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    //转发一条微博 
    public function statuses_repost($id = '3542291535129023')
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'statuses/repost';
        $parameters = array();
        $parameters['id'] = $id;//要转发的微博ID。
//		$parameters['status']=$status;//添加的转发文本，必须做URLencode，内容不超过140个汉字，不填则默认为“转发微博”。
//		$parameters['is_comment']=0;//是否在转发的同时发表评论，0：否、1：评论给当前微博、2：评论给原微博、3：都评论，默认为0 。 
        $wb_result = $SaeTOAuthV2->post($url, $parameters);
        pr($wb_result);
        die;
        if (isset($wb_result['user']) && isset($wb_result['id'])) {
            //$msg='转发成功';
            $this->set('wb_result', $wb_result['status']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    //批量获取指定微博的转发数评论数 
    public function statuses_count($ids = '3542291535129023')
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'statuses/count';
        $parameters = array();
        $parameters['ids'] = $ids;//需要获取数据的微博ID，多个之间用逗号分隔，最多不超过100个。 
        $wb_result = $SaeTOAuthV2->get($url, $parameters);
        pr($wb_result);
        die;
        $wb_count = array();
        if (isset($wb_result[0])) {
            foreach ($wb_result as $k => $v) {
                $id = $v['id'];
//				$comments=$v['comments'];//评论数
//				$reposts=$v['reposts'];//转发数
                $wb_count[$id] = $v;
            }
            $this->set('wb_count', $wb_count);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    //根据微博ID删除指定微博 
    public function statuses_destroy($id = '3542291535129023')
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'statuses/destroy';
        $parameters = array();
        $parameters['id'] = $id;//需要删除的微博ID。 
        $wb_result = $SaeTOAuthV2->post($url, $parameters);
        pr($wb_result);
        die;
        if (isset($wb_result['user']) && isset($wb_result['id'])) {
            //$msg='删除成功';
            $this->set('wb_result', $wb_result['status']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    //发布一条新微博
    public function statuses_update($status)
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'statuses/update';
        $parameters = array();
        $parameters['status'] = $status;//要发布的微博文本内容，必须做URLencode，内容不超过140个汉字。
        $wb_result = $SaeTOAuthV2->post($url, $parameters);
        //pr($wb_result);die;
        if (isset($wb_result['id']) && isset($wb_result['user'])) {
            //$msg='发布成功';
            $this->set('wb_result', $wb_result['status']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    //上传图片并发布一条新微博 
    public function statuses_upload($status = '新内容', $pic = '')
    {
        $pic = '@'.$this->getImage('http://img.ioco.dev/i/2013/01/img_ioco_dev/c60216d.ioco.dev/original/0/1d4c7172262d9b97127008179bf9e6e23.png');
    //	$pic=URLencode('http://img.ioco.dev/i/2013/01/img_ioco_dev/c60216d.ioco.dev/original/0/1d4c7172262d9b97127008179bf9e6e23.png');
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'statuses/upload';
        $parameters = array();
        $parameters['status'] = $status;//要发布的微博文本内容，必须做URLencode，内容不超过140个汉字。
        $parameters['pic'] = $pic;//要上传的图片，仅支持JPEG、GIF、PNG格式，图片大小小于5M。
        $wb_result = $SaeTOAuthV2->post($url, $parameters);
        pr($parameters);
        pr($wb_result);
        die;
        if (isset($wb_result['id']) && isset($wb_result['user'])) {
            //$msg='发布成功';
            $this->set('wb_result', $wb_result['status']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }
    public function getImage($url = '', $filename = '')
    {
        $filename = substr($url, strrpos($url, '/') + 1);
        $filename = CACHE.$filename;
        $fp = fopen($filename, 'wb');
        fwrite($fp, file_get_contents($url));
        fclose($fp);

        return $filename;
    }

    //指定一个图片URL地址抓取后上传并同时发布一条新微博 访问级别：高级接口（需要授权）
    public function statuses_upload_url_text($status, $url)
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'statuses/upload_url_text';
        $parameters = array();
        $parameters['status'] = $status;//要发布的微博文本内容，必须做URLencode，内容不超过140个汉字。
        $parameters['url'] = $url;//图片的URL地址，必须以http开头。 
        $wb_result = $SaeTOAuthV2->post($url, $parameters);
        pr($wb_result);
        die;
        if (isset($wb_result['id']) && isset($wb_result['user'])) {
            //$msg='发布成功';
            $this->set('wb_result', $wb_result['status']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }

    //添加一条微博到收藏里
    public function favorites_create($id = '3542291535129023')
    {
        $SaeTOAuthV2 = $this->saetoauthv2();
        $url = 'favorites/create';
        $parameters = array();
        $parameters['id'] = $id;//要发布的微博文本内容，必须做URLencode，内容不超过140个汉字。
        $wb_result = $SaeTOAuthV2->post($url, $parameters);
        pr($wb_result);
        die;
        if (isset($wb_result['status']) && isset($wb_result['favorited_time'])) {
            //$msg='收藏成功';
            $this->set('wb_result', $wb_result['status']);
        } else {
            $msg = isset($wb_result['error']) ? $wb_result['error'] : '';
        }
    }
}
