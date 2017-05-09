<?php

// POST /WebServices/WeatherWebService.asmx/getWeatherbyCityName HTTP/1.1
// Host: www.webxml.com.cn
// Content-Type: application/x-www-form-urlencoded
// Content-Length: length

// theCityName=string

$data = 'theCityName=长沙';
$url = 'http://www.webxml.com.cn/WebServices/WeatherWebService.asmx/getWeatherbyCityName';
$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, "user-agent:Mozilla/5.0 (Windows NT 5.1; rv:24.0) Gecko/20100101 Firefox/24.0");
// curl_setopt ($ch, CURLOPT_PROXY, "http://127.0.0.1:1080");
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_HEADER,0);
curl_setopt ($ch, CURLOPT_POST,1);
curl_setopt ($ch, CURLOPT_POSTFIELDS,$data);
curl_setopt ($ch, CURLOPT_HTTPHEADER,array(
	'application/x-www-form-urlencoded;charset=utf-8',
	'Content-Length: '.strlen($data)
	));
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);


 $output = curl_exec($ch);


if(!curl_errno($ch)){
	echo $output;
}else{
	echo 'Curl error: ' . curl_error($ch);
}
curl_close($ch);



?>