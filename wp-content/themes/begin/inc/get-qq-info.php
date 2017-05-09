<?php 
header('Content-Type: text/html;charset=utf-8');
$QQ=$_GET["qq"];
if($QQ!=''){
	$urlPre='http://r.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?g_tk=&uins=';
	$data=file_get_contents($urlPre.$QQ);
	$data=iconv("GB2312","UTF-8",$data);
	$pattern = '/portraitCallBack\((.*)\)/is';
	preg_match($pattern,$data,$result);
	$result=$result[1];
	echo $result;
}