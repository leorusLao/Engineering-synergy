<?php


require 'vendor/autoload.php';
use Henter\WeChat\OAuth;

//$oauth = new \Henter\WeChat\OAuth('wxfc27e6462366229c', '3b166209a2e76bf7a298be1afebcd7d7');
//$oauth = new \Henter\WeChat\OAuth('wx320e1a3f4857f0ed', '2e6ac7a77bb482f90b5feec69336dc8a');
$oauth = new \Henter\WeChat\OAuth('wxd154847b8418bf92', '5f83859e8d9c5a2eb07ec27f283a3a16');
$callback_url = urlencode('http://www.mowork.cn/weixin/back.php');
$url = $oauth->getAuthorizeURL($callback_url);
header("Location: $url");

?>


