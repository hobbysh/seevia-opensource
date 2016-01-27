<form action="/sms.php" method="post">
<input type="hidden" value="reg" name="action"/>
<input type="submit" value="注册" />
</form>
<form action="/sms.php" method="post">
<input type="hidden" value="send" name="action"/>
<input type="submit" value="发送" />
</form>

<form action="sms.php" method="post" >
<input type="hidden" value="get" name="action"/>
<input type="submit" value="获取" />
</form>
<?php
date_default_timezone_set('PRC'); //设置默认时区为北京时间
//短信接口用户名 $uid
$uid = '凌凯帐号';
//短信接口密码 $passwd
$passwd = '您的密码';
//发送到的目标手机号码 $telphone
$telphone = '13679040526';
//短信内容 $message
$message = '您的娃娃是全校第一,恭喜您！'.time().'【凌凯http】';

$action = isset($_POST['action']) ? $_POST['action'] : '';
if ($action == 'reg') {
    echo '开始注册..<br/>';
    $regURl = "http://mb345.com:999/WS/Reg.aspx?CorpID={$uid}&Pwd={$passwd}&CorpName=testcompany&LinkMan=yangfx&Tel=&Mobile=13800000000&Email=&Memo=";
    $regResult = file_get_contents($regURl);
    if ($regResult == 0) {
        echo '注册成功!<br/>';
    } else {
        echo '注册失败!'.$regResult;
    }
    exit;
}

if ($action == 'send') {
    $gateway = "http://mb345.com:999/ws/batchSend.aspx?CorpID={$uid}&Pwd={$passwd}&Mobile={$telphone}&Content={$message}&Cell=&SendTime=";
    $result = file_get_contents($gateway);

    if ($result == 0 || $result == 1) {
        echo '发送成功! 发送时间'.date('Y-m-d H:i:s');
    } else {
        echo '发送失败, 错误提示代码: '.$result;
    }
    exit;
}

if ($action == 'get') {
    echo '准备获取短信....<br/>';
    $getURl = "http://mb345.com:999/WS/Get.aspx?CorpID={$uid}&Pwd={$passwd}";

    $result = file_get_contents($getURl);
    echo '获取结果为:'.$result;
    exit;
}

?>