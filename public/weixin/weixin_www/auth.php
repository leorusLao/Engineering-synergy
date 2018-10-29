<?php
  /*
   This and authback.php for browser webpage wechat QRCode to authorize
  */
  $backurl = urlencode("http://www.mowork.cn/weixin/authback.php");
  $redirect = "https://open.weixin.qq.com/connect/qrconnect?appid=wxd154847b8418bf92" . "&redirect_uri=$backurl" .  "&response_type=code&scope=snsapi_login&state=1#wechat_redirect";
  
  header("Location: $redirect");
