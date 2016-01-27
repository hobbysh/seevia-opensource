<?php
require_once('open_abstract.php');
class Open_Wechat extends Open_Abstract
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];
	    //valid signature , option
	    if($this->checkSignature()){
	        echo $echoStr;
	        exit;
	    }
    }
    
    public function responseMsg($data)
    {
        $createTime = time();
        $funcFlag = $this->_setFlag ? 1 : 0;
        $msg = $this->getMsg();
        if ($data['type'] == 'text') {
            return $this->_getTextMsg($msg, $data);
        } elseif ($data['type'] == 'image') {
        	
        } elseif ($data['type'] == 'location') {

        }elseif($data['type'] == 'material'){
        	return $this->_getMaterial($msg, $data);
        }
    }

    public function checkSignature()
    {
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $tmpArr = array($this->_token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ( $tmpStr == $signature ) {
        	$flag=true;
            return true;
        } else {
        	$flag=false;
            return false;
        }
    }

    private function _getTextMsg($msg, $data)
    {
        $textTpl = "<xml>
        <ToUserName><![CDATA[{$msg->FromUserName}]]></ToUserName>
        <FromUserName><![CDATA[{$msg->ToUserName}]]></FromUserName>
        <CreateTime>{$createTime}</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        <FuncFlag>%s</FuncFlag>
        </xml>";
        return sprintf($textTpl, $data['content'], $funcFlag);
    }
    
    private function _getMaterial($msg,$data){
    	$textTpl = "<xml>
        <ToUserName><![CDATA[{$msg->FromUserName}]]></ToUserName>
        <FromUserName><![CDATA[{$msg->ToUserName}]]></FromUserName>
        <CreateTime>{$createTime}</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>";
        $textTpl.="<ArticleCount>".(sizeof($data['content'])>10?10:count($data['content']))."</ArticleCount><Articles>";
        $count_num=0;
        foreach($data['content'] as $k=>$v){
        	$textTpl.="<item>";
        	$textTpl.="<Title><![CDATA[{$v['Item']['Title']}]]></Title>";
			$textTpl.="<Description><![CDATA[{$v['Item']['Description']}]]></Description>";
			$textTpl.="<PicUrl><![CDATA[{$v['Item']['PicUrl']}]]></PicUrl>";
			$textTpl.="<Url><![CDATA[{$v['Item']['Url']}]]></Url>";
        	$textTpl.="</item>";
        	$count_num++;
        	if($count_num>10){
        		break;
        	}
        }
        $textTpl.="</Articles></xml>";
        return $textTpl;
    }
}
