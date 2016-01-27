<?php

/*****************************************************************************
 * Seevia 在线调查控制
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 VotesController 的投票控制器.
 */
class VotesController extends AppController
{
    /*
        *@var $name
        *@var $components
        *@var $helpers
        *@var $uses
    */
    public $name = 'Votes';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html', 'Form', 'Javascript');
    public $uses = array('VoteLog','Vote','VoteOption');

    //在线调研
    public function view($id)
    {
        $this->pageTitle = $this->ld['votes'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['votes'],'url' => '/votes/');
        $this->params['id'] = $id;
        $this->page_init($this->params);
    }

    /**
     *已经提交表决.
     *
     *@todo 内容全被注释掉了
     */
    public function vote_already_submited()
    {
        /*   $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('vote_log')." ".
           "WHERE ip_address = '$ip_address' AND vote_id = '$vote_id' ";
        */
    }
    /**
     *保存投票.
     */
    public function save_vote($vote_id = 0)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['type'] = 0;
        $result['msg'] = '';
        if ($this->RequestHandler->isPost()) {
            $vote_option_id = isset($_POST['vote_option_id']) ? $_POST['vote_option_id'] : '';
            if ($vote_id != '0' && $vote_option_id != '') {
                $ip_address = $this->real_ip();//获取Ip地址
                $system_type = $this->get_os();//操作系统
                $browser_type = $this->getbrowser();//浏览器类型

                //判断是否为重复投票
                $vote_log = $this->VoteLog->get_vote_log($vote_id, $ip_address);
                if (isset($vote_log['VoteLog'])) {
                    $result['msg'] = $this->ld['have_voted'];
                } else {
                    //保存选项票数
                    if (isset($_POST['vote_option_id'])) {
                        $arr1 = explode(';', $_POST['vote_option_id']);//字符串分割成数组
                        if (sizeof($arr1) >= 1) {
                            $vo_id = array();
                            foreach ($arr1 as $ak => $av) {
                                $arr2 = explode(',', $av);
                                array_push($vo_id, $arr2[0]);
                                $data['VoteOption']['id'] = $arr2[0];
                                $data['VoteOption']['option_count'] = $arr2[1];
                                $this->VoteOption->save($data);
                            }
                            $vote_option_id = implode(';', $vo_id);//数组合并成字符串
                        }
                    }
                    $vote_log_data['user_id'] = isset($_SESSION['User']) ? $_SESSION['User']['User']['id'] : '0';
                    $vote_log_data['vote_id'] = $vote_id;
                    $vote_log_data['ip_address'] = $ip_address;
                    $vote_log_data['system'] = $system_type;
                    $vote_log_data['vote_option_id'] = $vote_option_id;
                    $vote_log_data['status'] = '1';
                    $this->VoteLog->save($vote_log_data);

                    //联动调整vote_count投票人数
                    $VoteLog_count = $this->VoteLog->find('count', array('conditions' => array('VoteLog.vote_id' => $vote_id, 'VoteLog.status' => '1')));
                    $vote_data['id'] = $vote_id;
                    $vote_data['vote_count'] = $VoteLog_count;
                    $this->Vote->save($vote_data);

                    $result['type'] = 1;
                    $result['msg'] = $this->ld['voting_success'];
                    $result['vote_count'] = $VoteLog_count;
                }
            }
        }
        die(json_encode($result));
    }

    public function act_view()
    {
        $this->layout = 'ajax';
        Configure::write('debug', 0);

        if (!empty($_POST['vote']) && !empty($_POST['vote_category_id'])) {
            // pr($_POST);exit;
            $ip_address = $this->real_ip();

            $vote_log_add_temp = array('id' => '','user_id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0,'ip_address' => $ip_address,'system' => $this->get_os(),'browser' => $this->getbrowser(),'status' => 1);
            $k = $_POST['vote_category_id'];
            $v = $_POST['vote'];
            $vote_log = $this->VoteLog->get_vote_log($k, $ip_address);

            if (!empty($vote_log)) {
                $msg = $this->ld['vote_not_again'];
                $result['msg'] = $msg;
                die(json_encode($result));

                return;
            }
            $vote_count = $this->Vote->find('first', array('conditions' => array('Vote.id' => $_POST['vote_category_id'])));
            $this->Vote->save(array('Vote' => array('id' => $_POST['vote_category_id'], 'vote_count' => $vote_count['Vote']['vote_count'] + 1)));

            $vote_log_add = $vote_log_add_temp;
        //	$vote_log_add['vote_id'] = $_POST['vote_category_id'];
        //	$vote_log_add['vote_option_id'] = $_POST['vote'];
            $vote_log_add['vote_id'] = $k;
            $vote_log_add['vote_option_id'] = $v;
            $this->VoteLog->save(array('VoteLog' => $vote_log_add));
        //	$option_count = $this->VoteLog->find('count',array('conditions'=>array('VoteLog.vote_id'=>$_POST['vote_category_id'],'VoteLog.vote_option_id'=>$_POST['vote'])));
        //	$this->VoteOption->save(array('VoteOption'=>array('id'=>$_POST['vote'],'option_count'=>$option_count)));
            $option_count = $this->VoteLog->find('count', array('conditions' => array('VoteLog.vote_id' => $k, 'VoteLog.vote_option_id' => $v)));
            $this->VoteOption->save(array('VoteOption' => array('id' => $v, 'option_count' => $option_count)));
              //pr($option_count);pr($vote_count);exit;
            $msg = $this->ld['vote_success'];
            $result['msg'] = $msg;
            die(json_encode($result));
        } else {
            $msg = $this->ld['please_select'];
            $result['msg'] = $msg;
            die(json_encode($result));
        }
    }

    /**
     *实际地址.
     *
     *@return $realip
     */
    public function real_ip()
    {
        static $realip = null;

        if ($realip !== null) {
            return $realip;
        }

        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip) {
                    $ip = trim($ip);

                    if ($ip != 'unknown') {
                        $realip = $ip;

                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }

        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

        return $realip;
    }
    /**
     *获得游览器.
     *
     *@return $realip
     */
    public function getbrowser()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = self::CID_detect_browser($agent);
        $browser_type1 = isset($browser[0]) ? $browser[0].' ' : 'Unknow Browser';
        $browser_type2 = isset($browser[2]) ? $browser[2] : '';

        return $browser_type1.$browser_type2;
    }

    /**
     * 获得客户端的操作系统.
     */
    public function get_os()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $system_type = self::CID_detect_browser($agent);

        return isset($system_type[4]) ? $system_type[4] : 'Unknow Os';
    }

    public function CID_windows_detect_os($ua)
    {
        $os_name = $os_code = $os_ver = $pda_name = $pda_code = $pda_ver = null;

        if (preg_match('/Windows 95/i', $ua) || preg_match('/Win95/', $ua)) {
            $os_name = 'Windows';
            $os_code = 'windows';
            $os_ver = '95';
        } elseif (preg_match('/Windows NT 5.0/i', $ua) || preg_match('/Windows 2000/i', $ua)) {
            $os_name = 'Windows';
            $os_code = 'windows';
            $os_ver = '2000';
        } elseif (preg_match('/Win 9x 4.90/i', $ua) || preg_match('/Windows ME/i', $ua)) {
            $os_name = 'Windows';
            $os_code = 'windows';
            $os_ver = 'ME';
        } elseif (preg_match('/Windows.98/i', $ua) || preg_match('/Win98/i', $ua)) {
            $os_name = 'Windows';
            $os_code = 'windows';
            $os_ver = '98';
        } elseif (preg_match('/Windows NT 6.0/i', $ua)) {
            $os_name = 'Windows';
            $os_code = 'windows_vista';
            $os_ver = 'Vista';
        } elseif (preg_match('/Windows NT 6.1/i', $ua)) {
            $os_name = 'Windows';
            $os_code = 'windows_win7';
            $os_ver = '7';
        } elseif (preg_match('/Windows NT 6.2/i', $ua)) {
            $os_name = 'Windows';
            $os_code = 'windows_win8';
            $os_ver = '8';
        } elseif (preg_match('/Windows NT 5.1/i', $ua)) {
            $os_name = 'Windows';
            $os_code = 'windows';
            $os_ver = 'XP';
        } elseif (preg_match('/Windows NT 5.2/i', $ua)) {
            $os_name = 'Windows';
            $os_code = 'windows';
            if (preg_match('/Win64/i', $ua)) {
                $os_ver = 'XP 64 bit';
            } else {
                $os_ver = 'Server 2003';
            }
        } elseif (preg_match('/Mac_PowerPC/i', $ua)) {
            $os_name = 'Mac OS';
            $os_code = 'macos';
        } elseif (preg_match('/Windows Phone/i', $ua)) {
            $matches = explode(';', $ua);
            $os_name = $matches[2];
            $os_code = 'windows_phone7';
        } elseif (preg_match('/Windows NT 4.0/i', $ua) || preg_match('/WinNT4.0/i', $ua)) {
            $os_name = 'Windows';
            $os_code = 'windows';
            $os_ver = 'NT 4.0';
        } elseif (preg_match('/Windows NT/i', $ua) || preg_match('/WinNT/i', $ua)) {
            $os_name = 'Windows';
            $os_code = 'windows';
            $os_ver = 'NT';
        } elseif (preg_match('/Windows CE/i', $ua)) {
            list($os_name, $os_code, $os_ver, $pda_name, $pda_code, $pda_ver) = self::CID_pda_detect_os($ua);
            $os_name = 'Windows';
            $os_code = 'windows';
            $os_ver = 'CE';
            if (preg_match('/PPC/i', $ua)) {
                $os_name = 'Microsoft PocketPC';
                $os_code = 'windows';
                $os_ver = '';
            }
            if (preg_match('/smartphone/i', $ua)) {
                $os_name = 'Microsoft Smartphone';
                $os_code = 'windows';
                $os_ver = '';
            }
        } else {
            $os_name = 'Unknow Os';
            $os_code = 'other';
        }

        return array($os_name, $os_code, $os_ver, $pda_name, $pda_code, $pda_ver);
    }

    public function CID_unix_detect_os($ua)
    {
        $os_name = $os_ver = $os_code = null;
        if (preg_match('/Linux/i', $ua)) {
            $os_name = 'Linux';
            $os_code = 'linux';
            if (preg_match('#Debian#i', $ua)) {
                $os_code = 'debian';
                $os_name = 'Debian GNU/Linux';
            } elseif (preg_match('#Mandrake#i', $ua)) {
                $os_code = 'mandrake';
                $os_name = 'Mandrake Linux';
            } elseif (preg_match('#Kindle Fire#i', $ua)) {
                //for Kindle Fire
                $matches = explode(';', $ua);
                $os_code = 'kindle';
                $matches2 = explode(')', $matches[4]);
                $os_name = $matches[2].$matches2[0];
            } elseif (preg_match('#Android#i', $ua)) {
                //Android
                $matches = explode(';', $ua);
                $os_code = 'android';
                $matches2 = explode(')', $matches[4]);
                $os_name = $matches[2].$matches2[0];
            } elseif (preg_match('#SuSE#i', $ua)) {
                $os_code = 'suse';
                $os_name = 'SuSE Linux';
            } elseif (preg_match('#Novell#i', $ua)) {
                $os_code = 'novell';
                $os_name = 'Novell Linux';
            } elseif (preg_match('#Ubuntu#i', $ua)) {
                $os_code = 'ubuntu';
                $os_name = 'Ubuntu Linux';
            } elseif (preg_match('#Red ?Hat#i', $ua)) {
                $os_code = 'redhat';
                $os_name = 'RedHat Linux';
            } elseif (preg_match('#Gentoo#i', $ua)) {
                $os_code = 'gentoo';
                $os_name = 'Gentoo Linux';
            } elseif (preg_match('#Fedora#i', $ua)) {
                $os_code = 'fedora';
                $os_name = 'Fedora Linux';
            } elseif (preg_match('#MEPIS#i', $ua)) {
                $os_name = 'MEPIS Linux';
            } elseif (preg_match('#Knoppix#i', $ua)) {
                $os_name = 'Knoppix Linux';
            } elseif (preg_match('#Slackware#i', $ua)) {
                $os_code = 'slackware';
                $os_name = 'Slackware Linux';
            } elseif (preg_match('#Xandros#i', $ua)) {
                $os_name = 'Xandros Linux';
            } elseif (preg_match('#Kanotix#i', $ua)) {
                $os_name = 'Kanotix Linux';
            }
        } elseif (preg_match('/FreeBSD/i', $ua)) {
            $os_name = 'FreeBSD';
            $os_code = 'freebsd';
        } elseif (preg_match('/NetBSD/i', $ua)) {
            $os_name = 'NetBSD';
            $os_code = 'netbsd';
        } elseif (preg_match('/OpenBSD/i', $ua)) {
            $os_name = 'OpenBSD';
            $os_code = 'openbsd';
        } elseif (preg_match('/IRIX/i', $ua)) {
            $os_name = 'SGI IRIX';
            $os_code = 'sgi';
        } elseif (preg_match('/SunOS/i', $ua)) {
            $os_name = 'Solaris';
            $os_code = 'sun';
        } elseif (preg_match('#iPod.*.CPU.([a-zA-Z0-9.( _)]+)#i', $ua, $matches)) {
            $os_name = 'iPod';
            $os_code = 'iphone';
            $os_ver = $matches[1];
        } elseif (preg_match('#iPhone.*.CPU.([a-zA-Z0-9.( _)]+)#i', $ua, $matches)) {
            $os_name = 'iPhone';
            $os_code = 'iphone';
            $os_ver = $matches[1];
        } elseif (preg_match('#iPad.*.CPU.([a-zA-Z0-9.( _)]+)#i', $ua, $matches)) {
            $os_name = 'iPad';
            $os_code = 'ipad';
            $os_ver = $matches[1];
        } elseif (preg_match('/Mac OS X.([0-9. _]+)/i', $ua, $matches)) {
            $os_name = 'Mac OS';
            $os_code = 'macos';
            if (count(explode(7, $matches[1])) > 1) {
                $matches[1] = 'Lion '.$matches[1];
            } elseif (count(explode(8, $matches[1])) > 1) {
                $matches[1] = 'Mountain Lion '.$matches[1];
            }
            $os_ver = 'X '.$matches[1];
        } elseif (preg_match('/Macintosh/i', $ua)) {
            $os_name = 'Mac OS';
            $os_code = 'macos';
        } elseif (preg_match('/Unix/i', $ua)) {
            $os_name = 'UNIX';
            $os_code = 'unix';
        } elseif (preg_match('/CrOS/i', $ua)) {
            $os_name = 'Google Chrome OS';
            $os_code = 'chromeos';
        } elseif (preg_match('/Fedor.([0-9. _]+)/i', $ua, $matches)) {
            $os_name = 'Fedora';
            $os_code = 'fedora';
            $os_ver = $matches[1];
        } else {
            $os_name = 'Unknow Os';
            $os_code = 'other';
        }

        return array($os_name, $os_code, $os_ver);
    }

    public function CID_pda_detect_os($ua)
    {
        $os_name = $os_code = $os_ver = $pda_name = $pda_code = $pda_ver = null;
        if (preg_match('#PalmOS#i', $ua)) {
            $os_name = 'Palm OS';
            $os_code = 'palm';
        } elseif (preg_match('#Windows CE#i', $ua)) {
            $os_name = 'Windows CE';
            $os_code = 'windows';
        } elseif (preg_match('#QtEmbedded#i', $ua)) {
            $os_name = 'Qtopia';
            $os_code = 'linux';
        } elseif (preg_match('#Zaurus#i', $ua)) {
            $os_name = 'Linux';
            $os_code = 'linux';
        } elseif (preg_match('#Symbian#i', $ua)) {
            $os_name = 'Symbian OS';
            $os_code = 'symbian';
        } elseif (preg_match('#PalmOS/sony/model#i', $ua)) {
            $pda_name = 'Sony Clie';
            $pda_code = 'sony';
        } elseif (preg_match('#Zaurus ([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $pda_name = 'Sharp Zaurus '.$matches[1];
            $pda_code = 'zaurus';
            $pda_ver = $matches[1];
        } elseif (preg_match('#Series ([0-9]+)#i', $ua, $matches)) {
            $pda_name = 'Series';
            $pda_code = 'nokia';
            $pda_ver = $matches[1];
        } elseif (preg_match('#Nokia ([0-9]+)#i', $ua, $matches)) {
            $pda_name = 'Nokia';
            $pda_code = 'nokia';
            $pda_ver = $matches[1];
        } elseif (preg_match('#SIE-([a-zA-Z0-9]+)#i', $ua, $matches)) {
            $pda_name = 'Siemens';
            $pda_code = 'siemens';
            $pda_ver = $matches[1];
        } elseif (preg_match('#dopod([a-zA-Z0-9]+)#i', $ua, $matches)) {
            $pda_name = 'Dopod';
            $pda_code = 'dopod';
            $pda_ver = $matches[1];
        } elseif (preg_match('#o2 xda ([a-zA-Z0-9 ]+);#i', $ua, $matches)) {
            $pda_name = 'O2 XDA';
            $pda_code = 'o2';
            $pda_ver = $matches[1];
        } elseif (preg_match('#SEC-([a-zA-Z0-9]+)#i', $ua, $matches)) {
            $pda_name = 'Samsung';
            $pda_code = 'samsung';
            $pda_ver = $matches[1];
        } elseif (preg_match('#SonyEricsson ?([a-zA-Z0-9]+)#i', $ua, $matches)) {
            $pda_name = 'SonyEricsson';
            $pda_code = 'sonyericsson';
            $pda_ver = $matches[1];
        } elseif (preg_match('#Kindle\/([a-zA-Z0-9. ×\(.\)]+)#i', $ua, $matches)) {
            //for Kindle
            $pda_name = 'kindle';
            $pda_code = 'kindle';
            $pda_ver = $matches[1];
        } else {
            $pda_name = 'Unknow Os';
            $pda_code = 'other';
        }

        return array($os_name, $os_code, $os_ver, $pda_name, $pda_code, $pda_ver);
    }

    public function CID_detect_browser($ua)
    {
        $browser_name = $browser_code = $browser_ver = $os_name = $os_code = $os_ver = $pda_name = $pda_code = $pda_ver = null;
        $ua = preg_replace('/FunWebProducts/i', '', $ua);
        if (preg_match('#MovableType[ /]([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'MovableType';
            $browser_code = 'mt';
            $browser_ver = $matches[1];
        } elseif (preg_match('#WordPress[ /]([a-zA-Z0-9.]*)#i', $ua, $matches)) {
            $browser_name = 'WordPress';
            $browser_code = 'wp';
            $browser_ver = $matches[1];
        } elseif (preg_match('#typepad[ /]([a-zA-Z0-9.]*)#i', $ua, $matches)) {
            $browser_name = 'TypePad';
            $browser_code = 'typepad';
            $browser_ver = $matches[1];
        } elseif (preg_match('#drupal#i', $ua)) {
            $browser_name = 'Drupal';
            $browser_code = 'drupal';
            $browser_ver = count($matches) > 0 ? $matches[1] : '';
        } elseif (preg_match('#symbianos/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $os_name = 'SymbianOS';
            $os_ver = $matches[1];
            $os_code = 'symbian';
        } elseif (preg_match('#avantbrowser.com#i', $ua)) {
            $browser_name = 'Avant Browser';
            $browser_code = 'avantbrowser';
        } elseif (preg_match('#(Camino|Chimera)[ /]([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Camino';
            $browser_code = 'camino';
            $browser_ver = $matches[2];
            $os_name = 'Mac OS';
            $os_code = 'macos';
            $os_ver = 'X';
        } elseif (preg_match('#anonymouse#i', $ua, $matches)) {
            $browser_name = 'Anonymouse';
            $browser_code = 'anonymouse';
        } elseif (preg_match('#PHP#', $ua, $matches)) {
            $browser_name = 'PHP';
            $browser_code = 'php';
        } elseif (preg_match('#danger hiptop#i', $ua, $matches)) {
            $browser_name = 'Danger HipTop';
            $browser_code = 'danger';
        } elseif (preg_match('#w3m/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'W3M';
            $browser_code = 'w3m';
            $browser_ver = $matches[1];
        } elseif (preg_match('#Shiira[/]([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Shiira';
            $browser_code = 'shiira';
            $browser_ver = $matches[1];
            $os_name = 'Mac OS';
            $os_code = 'macos';
            $os_ver = 'X';
        } elseif (preg_match('#Dillo[ /]([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Dillo';
            $browser_code = 'dillo';
            $browser_ver = $matches[1];
        } elseif (preg_match('#Epiphany/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Epiphany';
            $browser_code = 'epiphany';
            $browser_ver = $matches[1];
            list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
        } elseif (preg_match('#UP.Browser/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Openwave UP.Browser';
            $browser_code = 'openwave';
            $browser_ver = $matches[1];
        } elseif (preg_match('#DoCoMo/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'DoCoMo';
            $browser_code = 'docomo';
            $browser_ver = $matches[1];
            if ($browser_ver == '1.0') {
                preg_match('#DoCoMo/([a-zA-Z0-9.]+)/([a-zA-Z0-9.]+)#i', $ua, $matches);
                $browser_ver = $matches[2];
            } elseif ($browser_ver == '2.0') {
                preg_match('#DoCoMo/([a-zA-Z0-9.]+) ([a-zA-Z0-9.]+)#i', $ua, $matches);
                $browser_ver = $matches[2];
            }
        } elseif (preg_match('#(SeaMonkey)/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Mozilla SeaMonkey';
            $browser_code = 'seamonkey';
            $browser_ver = $matches[2];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#Kazehakase/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Kazehakase';
            $browser_code = 'kazehakase';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#Flock/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Flock';
            $browser_code = 'flock';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#(Firefox|Phoenix|Firebird|BonEcho|GranParadiso|Minefield|Iceweasel)/4([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Mozilla Firefox';
            $browser_code = 'firefox';
            $browser_ver = '4'.$matches[2];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#(Firefox|Phoenix|Firebird|BonEcho|GranParadiso|Minefield|Iceweasel)/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Mozilla Firefox';
            $browser_code = 'firefox';
            $browser_ver = $matches[2];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#Minimo/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Minimo';
            $browser_code = 'mozilla';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#MultiZilla/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'MultiZilla';
            $browser_code = 'mozilla';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#SE 2([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'SouGou Browser';
            $browser_code = 'sogou';
            $browser_ver = '2'.$matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#baidubrowser ([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'BaiDu Browser';
            $browser_code = 'baidubrowser';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#360([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = '360 Browser';
            $browser_code = '360se';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#QQBrowser/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'QQ Browser';
            $browser_code = 'qqbrowser';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('/PSP \(PlayStation Portable\)\; ([a-zA-Z0-9.]+)/', $ua, $matches)) {
            $pda_name = 'Sony PSP';
            $pda_code = 'sony-psp';
            $pda_ver = $matches[1];
        } elseif (preg_match('#Galeon/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Galeon';
            $browser_code = 'galeon';
            $browser_ver = $matches[1];
            list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
        } elseif (preg_match('#iCab/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'iCab';
            $browser_code = 'icab';
            $browser_ver = $matches[1];
            $os_name = 'Mac OS';
            $os_code = 'macos';
            if (preg_match('#Mac OS X#i', $ua)) {
                $os_ver = 'X';
            }
        } elseif (preg_match('#K-Meleon/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'K-Meleon';
            $browser_code = 'kmeleon';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#Lynx/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Lynx';
            $browser_code = 'lynx';
            $browser_ver = $matches[1];
            list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
        } elseif (preg_match('#Links \\(([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Links';
            $browser_code = 'lynx';
            $browser_ver = $matches[1];
            list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
        } elseif (preg_match('#ELinks[/ ]([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'ELinks';
            $browser_code = 'lynx';
            $browser_ver = $matches[1];
            list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
        } elseif (preg_match('#ELinks \\(([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'ELinks';
            $browser_code = 'lynx';
            $browser_ver = $matches[1];
            list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
        } elseif (preg_match('#Konqueror/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Konqueror';
            $browser_code = 'konqueror';
            $browser_ver = $matches[1];
            list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            if (!$os_name) {
                list($os_name, $os_code, $os_ver) = self::CID_pda_detect_os($ua);
            }
        } elseif (preg_match('#NetPositive/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'NetPositive';
            $browser_code = 'netpositive';
            $browser_ver = $matches[1];
            $os_name = 'BeOS';
            $os_code = 'beos';
        } elseif (preg_match('#OmniWeb#i', $ua)) {
            $browser_name = 'OmniWeb';
            $browser_code = 'omniweb';
            $os_name = 'Mac OS';
            $os_code = 'macos';
            $os_ver = 'X';
        } elseif (preg_match('#Chrome/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Google Chrome';
            $browser_code = 'chrome';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#Arora/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Arora';
            $browser_code = 'arora';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#Maxthon( |\/)([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Maxthon';
            $browser_code = 'maxthon';
            $browser_ver = $matches[2];
            if (preg_match('/Win/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#CriOS/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Chrome for iOS';
            $browser_code = 'crios';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#Safari/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Safari';
            $browser_code = 'safari';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#opera mini#i', $ua)) {
            $browser_name = 'Opera Mini';
            $browser_code = 'opera';
            preg_match('#Opera/([a-zA-Z0-9.]+)#i', $ua, $matches);
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#Opera.(.*)Version[ /]([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Opera';
            $browser_code = 'opera';
            $browser_ver = $matches[2];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
            if (!$os_name) {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
            if (!$os_name) {
                list($os_name, $os_code, $os_ver, $pda_name, $pda_code, $pda_ver) = self::CID_pda_detect_os($ua);
            }
            if (!$os_name) {
                if (preg_match('/Wii/i', $ua)) {
                    $os_name = 'Nintendo Wii';
                    $os_code = 'nintendo-wii';
                }
            }
        } elseif (preg_match('#Opera/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Opera Mini';
            $browser_code = 'opera';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#WebPro/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'WebPro';
            $browser_code = 'webpro';
            $browser_ver = $matches[1];
            $os_name = 'PalmOS';
            $os_code = 'palmos';
        } elseif (preg_match('#WebPro#i', $ua, $matches)) {
            $browser_name = 'WebPro';
            $browser_code = 'webpro';
            $os_name = 'PalmOS';
            $os_code = 'palmos';
        } elseif (preg_match('#Netfront/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Netfront';
            $browser_code = 'netfront';
            $browser_ver = $matches[1];
            list($os_name, $os_code, $os_ver, $pda_name, $pda_code, $pda_ver) = self::CID_pda_detect_os($ua);
        } elseif (preg_match('#Xiino/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Xiino';
            $browser_code = 'xiino';
            $browser_ver = $matches[1];
        } elseif (preg_match('/wp-blackberry\/([a-zA-Z0-9.]*)/i', $ua, $matches)) {
            $browser_name = 'WordPress for BlackBerry';
            $browser_code = 'wordpress';
            $browser_ver = $matches[1];
            $pda_name = 'BlackBerry';
            $pda_code = 'blackberry';
        } elseif (preg_match('#Blackberry([0-9]+)#i', $ua, $matches)) {
            $pda_name = 'Blackberry';
            $pda_code = 'blackberry';
            $pda_ver = $matches[1];
        } elseif (preg_match('#Blackberry#i', $ua)) {
            $pda_name = 'Blackberry';
            $pda_code = 'blackberry';
        } elseif (preg_match('#SPV ([0-9a-zA-Z.]+)#i', $ua, $matches)) {
            $pda_name = 'Orange SPV';
            $pda_code = 'orange';
            $pda_ver = $matches[1];
        } elseif (preg_match('#LGE-([a-zA-Z0-9]+)#i', $ua, $matches)) {
            $pda_name = 'LG';
            $pda_code = 'lg';
            $pda_ver = $matches[1];
        } elseif (preg_match('#MOT-([a-zA-Z0-9]+)#i', $ua, $matches)) {
            $pda_name = 'Motorola';
            $pda_code = 'motorola';
            $pda_ver = $matches[1];
        } elseif (preg_match('#Nokia ?([0-9]+)#i', $ua, $matches)) {
            $pda_name = 'Nokia';
            $pda_code = 'nokia';
            $pda_ver = $matches[1];
        } elseif (preg_match('#NokiaN-Gage#i', $ua)) {
            $pda_name = 'Nokia';
            $pda_code = 'nokia';
            $pda_ver = 'N-Gage';
        } elseif (preg_match('#Blazer[ /]?([a-zA-Z0-9.]*)#i', $ua, $matches)) {
            $browser_name = 'Blazer';
            $browser_code = 'blazer';
            $browser_ver = $matches[1];
            $os_name = 'Palm OS';
            $os_code = 'palm';
        } elseif (preg_match('#SIE-([a-zA-Z0-9]+)#i', $ua, $matches)) {
            $pda_name = 'Siemens';
            $pda_code = 'siemens';
            $pda_ver = $matches[1];
        } elseif (preg_match('#SEC-([a-zA-Z0-9]+)#i', $ua, $matches)) {
            $pda_name = 'Samsung';
            $pda_code = 'samsung';
            $pda_ver = $matches[1];
        } elseif (preg_match('/wp-iphone\/([a-zA-Z0-9.]*)/i', $ua, $matches)) {
            $browser_name = 'WordPress for iOS';
            $browser_code = 'wordpress';
            $browser_ver = $matches[1];
            $pda_name = 'iPhone & iPad';
            $pda_code = 'ipad';
        } elseif (preg_match('/wp-android\/([a-zA-Z0-9.]*)/i', $ua, $matches)) {
            $browser_name = 'WordPress for Android';
            $browser_code = 'wordpress';
            $browser_ver = $matches[1];
            $pda_name = 'Android';
            $pda_code = 'android';
        } elseif (preg_match('/wp-windowsphone\/([a-zA-Z0-9.]*)/i', $ua, $matches)) {
            $browser_name = 'WordPress for Windows Phone 7';
            $browser_code = 'wordpress';
            $browser_ver = $matches[1];
            $pda_name = 'Windows Phone 7';
            $pda_code = 'windows_phone7';
        } elseif (preg_match('/wp-nokia\/([a-zA-Z0-9.]*)/i', $ua, $matches)) {
            $browser_name = 'WordPress for Nokia';
            $browser_code = 'wordpress';
            $browser_ver = $matches[1];
            $pda_name = 'Nokia';
            $pda_code = 'nokia';
        } elseif (preg_match('#SAMSUNG-(S.H-[a-zA-Z0-9_/.]+)#i', $ua, $matches)) {
            $pda_name = 'Samsung';
            $pda_code = 'samsung';
            $pda_ver = $matches[1];
            if (preg_match('#(j2me|midp)#i', $ua)) {
                $browser_name = 'J2ME/MIDP Browser';
                $browser_code = 'j2me';
            }
        } elseif (preg_match('#SonyEricsson ?([a-zA-Z0-9]+)#i', $ua, $matches)) {
            $pda_name = 'SonyEricsson';
            $pda_code = 'sonyericsson';
            $pda_ver = $matches[1];
        } elseif (preg_match('#(j2me|midp)#i', $ua)) {
            $browser_name = 'J2ME/MIDP Browser';
            $browser_code = 'j2me';
            // mice
        } elseif (preg_match('/GreenBrowser/i', $ua)) {
            $browser_name = 'GreenBrowser';
            $browser_code = 'greenbrowser';
            if (preg_match('/Win/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#TencentTraveler ([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = '腾讯TT浏览器';
            $browser_code = 'tencenttraveler';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#UCWEB([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'UCWEB';
            $browser_code = 'ucweb';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#MSIE ([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Internet Explorer';
            $browser_ver = $matches[1];
            if (strpos($browser_ver, '7') !== false || strpos($browser_ver, '8') !== false) {
                $browser_code = 'ie8';
            } elseif (strpos($browser_ver, '9') !== false) {
                $browser_code = 'ie9';
            } elseif (strpos($browser_ver, '10') !== false) {
                $browser_code = 'ie10';
            } else {
                $browser_code = 'ie';
            }
            list($os_name, $os_code, $os_ver, $pda_name, $pda_code, $pda_ver) = self::CID_windows_detect_os($ua);
        } elseif (preg_match('#Universe/([0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Universe';
            $browser_code = 'universe';
            $browser_ver = $matches[1];
            list($os_name, $os_code, $os_ver, $pda_name, $pda_code, $pda_ver) = self::CID_pda_detect_os($ua);
        } elseif (preg_match('#Netscape[0-9]?/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Netscape';
            $browser_code = 'netscape';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#^Mozilla/5.0#i', $ua) && preg_match('#rv:([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Mozilla';
            $browser_code = 'mozilla';
            $browser_ver = $matches[1];
            if (preg_match('/Windows/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } elseif (preg_match('#^Mozilla/([a-zA-Z0-9.]+)#i', $ua, $matches)) {
            $browser_name = 'Netscape Navigator';
            $browser_code = 'netscape';
            $browser_ver = $matches[1];
            if (preg_match('/Win/i', $ua)) {
                list($os_name, $os_code, $os_ver) = self::CID_windows_detect_os($ua);
            } else {
                list($os_name, $os_code, $os_ver) = self::CID_unix_detect_os($ua);
            }
        } else {
            $browser_name = 'Unknow Browser';
            $browser_code = 'null';
        }

        if (!$pda_name && !$os_name) {
            $pda_name = 'Unknow Os';
            $pda_code = 'other';
            $os_name = 'Unknow Os';
            $os_code = 'other';
        }

        return array($browser_name, $browser_code, $browser_ver, $os_name, $os_code, $os_ver, $pda_name, $pda_code, $pda_ver);
    }
}
