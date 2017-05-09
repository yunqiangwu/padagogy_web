<?php


$ch = curl_init();

curl_setopt ($ch, CURLOPT_PROXY, "http://127.0.0.1:1080");
//"http://www.baidu.com"
curl_setopt ($ch, CURLOPT_URL, "http://www.baidu.com");
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);


$output =  curl_exec($ch);

curl_close($ch);

echo str_replace(array("百度","Baidu"), array("屌丝","Diaoshi"), $output)


?>