<form action="/sms.php" method="post">
<input type="hidden" value="reg" name="action"/>
<input type="submit" value="ע��" />
</form>
<form action="/sms.php" method="post">
<input type="hidden" value="send" name="action"/>
<input type="submit" value="����" />
</form>

<form action="sms.php" method="post" >
<input type="hidden" value="get" name="action"/>
<input type="submit" value="��ȡ" />
</form>
<?php
date_default_timezone_set('PRC'); //����Ĭ��ʱ��Ϊ����ʱ��
//���Žӿ��û��� $uid
$uid = '�迭�ʺ�';
//���Žӿ����� $passwd
$passwd = '��������';
//���͵���Ŀ���ֻ����� $telphone
$telphone = '13679040526';
//�������� $message
$message = '����������ȫУ��һ,��ϲ����'.time().'���迭http��';

$action = isset($_POST['action']) ? $_POST['action'] : '';
if ($action == 'reg') {
    echo '��ʼע��..<br/>';
    $regURl = "http://mb345.com:999/WS/Reg.aspx?CorpID={$uid}&Pwd={$passwd}&CorpName=testcompany&LinkMan=yangfx&Tel=&Mobile=13800000000&Email=&Memo=";
    $regResult = file_get_contents($regURl);
    if ($regResult == 0) {
        echo 'ע��ɹ�!<br/>';
    } else {
        echo 'ע��ʧ��!'.$regResult;
    }
    exit;
}

if ($action == 'send') {
    $gateway = "http://mb345.com:999/ws/batchSend.aspx?CorpID={$uid}&Pwd={$passwd}&Mobile={$telphone}&Content={$message}&Cell=&SendTime=";
    $result = file_get_contents($gateway);

    if ($result == 0 || $result == 1) {
        echo '���ͳɹ�! ����ʱ��'.date('Y-m-d H:i:s');
    } else {
        echo '����ʧ��, ������ʾ����: '.$result;
    }
    exit;
}

if ($action == 'get') {
    echo '׼����ȡ����....<br/>';
    $getURl = "http://mb345.com:999/WS/Get.aspx?CorpID={$uid}&Pwd={$passwd}";

    $result = file_get_contents($getURl);
    echo '��ȡ���Ϊ:'.$result;
    exit;
}

?>