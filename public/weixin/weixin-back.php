<?php session_start()?>
<html>
<head>
    <script src="http://test.mowork.cn/asset/js/jquery-1.11.3.min.js"></script>
    <script src="http://test.mowork.cn/asset/js/jquery.redirect.js"></script>
</head>
<body>
<?php
/*
 This and weixin.php are for app to use Wechat authorization
*/

require 'vendor/autoload.php';
use Henter\WeChat\OAuth;

$appid = 'wxfc27e6462366229c';
$secret = '3b166209a2e76bf7a298be1afebcd7d7';


if (isset($_GET["code"])) {

  $code = $_GET["code"];
  $oauth = new \Henter\WeChat\OAuth($appid, $secret);


  if($access_token = $oauth->getAccessToken('code', $code)){
	$refresh_token = $oauth->getRefreshToken();
	$expires_in = $oauth->getExpiresIn();
	$openid = $oauth->getOpenid();
	$callback = $_SESSION['callback_uri'];
	$tmpArray = explode('=', $callback);
	$tmpArr = explode('&', $tmpArray[1]);

	$userinfo = $oauth->api('sns/userinfo', array('openid'=>$openid));
	$userinfo = array_merge($userinfo,array('client_type' => '3','identity_type' => 'wechat', 'company_id' => $tmpArr[0]));
	$result = postJson($userinfo);

	$json = json_decode($result);
	$uid = $json->data->uid;
	$token = $json->data->token;

	$mpart = "http://weixin.mowork.cn/#/weixin?toPath=";
	$callback = $mpart.$callback.'&uid='.$uid.'&token='.$token;

	$html =<<<EOD
	<script>
 $.redirect("$callback", null, 'get');
	</script>
EOD;
	echo $html;
  } else {//error

	echo '<script>window.location.href = "http://weixin.mowork.cn/#/error"</script>';

  }

}

function postJson(array $data){
 $data_string = json_encode($data);

$ch = curl_init('http://www.mowork.cn/api/wechat/login');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
);

$result = curl_exec($ch);

return $result;
}
?>

</body>
</html>
