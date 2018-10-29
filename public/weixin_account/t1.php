<?php
  $backurl = urlencode("http://www.mowork.cn/wexin/back.php");
            $str = "location: https://open.weixin.qq.com/connect/qrconnect?appid=wxd154847b8418bf92" . "&redirect_uri=$backurl" .  "&response_type=code&scope=snsapi_login&state=1#wechat_redirect";  
            header($str);  
          
      


 

