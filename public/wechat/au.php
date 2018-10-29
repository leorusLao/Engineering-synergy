<html>
<head>

<script src="http://test.mowork.cn/asset/js/jquery-1.10.1.min.js"></script>
<script src='http://test.mowork.cn/asset/js/jquery.qrcode.min.js'></script>

</head>
<?php

  //$redirect = urlencode("http://www.mowork.cn/wechat/au.php");
  //$req = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxfc27e6462366229c&redirect_uri=$redirect&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
  //header("Location: $req");
?>
<?php
if($_GET["code"]){
  file_put_contents('qqqq',$_GET['code']);
  //print_r($_GET['code']);

}
else {?>

 
<div id="qrcode" style="margin:20px;margin-bottom:50px;max-width:140px;height:140px;!important;float:left"></div>
<?php

};

?>



<script type='text/javascript'>

$(function() {

	$('#qrcode').qrcode({
        "render": "image",
        "ecLevel": "L",
        "width": 130,
        "height": 130,
        "color": "#a3a",
        "text":"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxfc27e6462366229c&redirect_uri=http://www.mowork.cn/wechat/au.php&response_type=code&scope=snsapi_base&state=123#wechat_redirect" 
    });
	 
});

</script>
