<?php
@header("Content-Type: text/html; charset=gb2312");

if(isset($_GET['u'])) $url = safeurl($_GET['u']);
(empty($url)) && exit();
$f = isset($_GET['f']) ? (!preg_match("/^[0-9]+$/",$_GET['f']) ? 0 : intval($_GET['f'])) : 0;
$f = 2; //默认为超清.
if($f == 1) {
	$f = 'high';
} else if($f == 2) {
	$f = 'super';
} else if($f == 5) {
	$f = 'super2';
} else if($f == 9) {
	$f = 'real';
} else {
	$f = 'normal';
}

$page = getsite('http://www.flvcd.com/parse.php?kw=http://v.youku.com/v_show/id_'.$url.'.html&flag=one&format='.$f);
$video = getbody($page,'<strong>当前解析视频','<br>花费时间',1);
$videos = explode('</a>',$video);
$video = '';
for($i=0;$i<count($videos);++$i){
	$tmp = getbody($videos[$i],'href="','"',1);
	if(!empty($tmp)) {
		$i > 0 && $video .= '|';
		$video .= $tmp;
	}
}
echo $video;
exit();
//(!empty($video)) && @header("location:".$video);

function getsite($url){
	$buf=parse_url($url);
	if($buf["scheme"]=="http"){
		$host=$buf["host"];
		$page=$buf["path"];
		if(trim($buf["query"])!=="") $page.="?".trim($buf["query"]);
		$myHeader="GET ".$url." HTTP/1.1\r\n";
		$myHeader.="Host:".$host."\r\n";
		$myHeader.="Connection:close\r\n";
		$myHeader.="Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5\r\n";
		$myHeader.="Accept-Language:zh-cn,zh;q=0.5\r\n";
		$myHeader.="Accept-Charset:gb2312,utf-8;q=0.7,*;q=0.7\r\n";
		$myHeader.="User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; zh-CN; rv:5.0.1) Gecko/20100101 Firefox/5.0.1 Web-Sniffer/1.0.20\r\n";
		$myHeader.="Referer: http://".$host."/\r\n\r\n";
		$server=$host;
		$port=80;
		$res="";
		if(false!==($fp = @fsockopen($server,$port,$errno,$errstr,30))){
			@fputs ($fp, $myHeader);
			while (!@feof($fp)) $res.= @fgets($fp,1024);
			@fclose ($fp);
		}else{
			return false;
		}
		if(strlen($res)==0) return false;
		return $res;
	}else{
		$fileName=$url;
		if(false!==@file_exists($fileName)){
			if(false!==($buf=@implode("",file($fileName)))&&@strlen($buf)>0){
				return $buf;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
}
function getbody($s,$ss,$se,$sn){
	$arr = explode($ss,$s);
	@$t=$arr[1];
	if(empty($t)) return '';
	if(empty($se)){
		return $t;
	}else{
		$arr=explode($se,$t);
		if($sn==1){
			return $arr[0];
		}elseif($sn==2){
			return $ss.$arr[0];
		}elseif($sn==3){
			return $arr[0].$se;
		}else{
			return $ss.$arr[0].$se;
		}
	}
}
function safeurl($s) {
	$s = str_replace('%20','',$s);
	$s = str_replace('%27','',$s);
	$s = str_replace('%2527','',$s);
	$s = str_replace('*','',$s);
	$s = str_replace('"','&quot;',$s);
	$s = str_replace("'",'',$s);
	$s = str_replace('"','',$s);
	$s = str_replace(';','',$s);
	$s = str_replace('<','&lt;',$s);
	$s = str_replace('>','&gt;',$s);
	$s = str_replace("{",'',$s);
	$s = str_replace('}','',$s);
	$s = str_replace('\\','',$s);
	return trim($s);
}
?>