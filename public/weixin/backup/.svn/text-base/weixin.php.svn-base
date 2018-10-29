<?php
/*
 This and weixin-back.php are for app to use Wechat authorization
*/
session_start();

if(isset($_REQUEST['callback_uri'])) {
	$_SESSION['callback_uri'] =  $_REQUEST['callback_uri'];
	 
}

 
$appid = 'wxfc27e6462366229c';
$secret = '3b166209a2e76bf7a298be1afebcd7d7';

$redirect_uri = urlencode("http://www.mowork.cn/weixin/weixin-back.php");

$callweichat = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect";
 
header("Location: $callweichat");

?>
