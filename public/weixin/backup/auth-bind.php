<?php
  /*
   This and authback.php for browser webpage wechat QRCode to authorize
  */
  session_start();
  $b = $_GET['b'];
  $t = $_GET['t'];
  $_SESSION['b'] = $b;
  $_SESSION['t'] = $t;
  $backurl = urlencode("http://www.mowork.cn/weixin/auth-bind-back.php");
  $redirect = "https://open.weixin.qq.com/connect/qrconnect?appid=wxd154847b8418bf92" . "&redirect_uri=$backurl" .  "&response_type=code&scope=snsapi_login&state=1#wechat_redirect";
  
  header("Location: $redirect");
