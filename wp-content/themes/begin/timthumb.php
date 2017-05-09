<?php
/**
 * TimThumb by Ben Gillbanks and Mark Maunder
 */

define ('VERSION', '2.8.14');

define('ALLOW_EXTERNAL',true);
define('ALLOW_ALL_EXTERNAL_SITES',true);
//function curPageURL()
//{
//    $pageURL = 'http';
//
//    if ($_SERVER["HTTPS"] == "on")
//    {
//        $pageURL .= "s";
//    }
//    $pageURL .= "://";
//
//    if ($_SERVER["SERVER_PORT"] != "80")
//    {
//        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
//    }
//    else
//    {
//        $pageURL .= $_SERVER["SERVER_NAME"] ;
//    }
//    return $pageURL;
//}
//
////Load a config file if it exists. Otherwise, use the values below
//if(!preg_match('/^'. addcslashes(curPageURL(),'/') .'.*/',$_GET['src'])){
//    header("location: ".$_GET['src']);
//    exit;
//}

//Load a config file if it exists. Otherwise, use the values below
if( file_exists(dirname(__FILE__) . '/timthumb-config.php'))    require_once('timthumb-config.php');