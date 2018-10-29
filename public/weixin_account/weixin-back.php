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
	$userinfo = $oauth->api('sns/userinfo', array('openid'=>$openid));
	$result = postJson($userinfo);
	$salt = '&SDFe4wfs9342w99t@3rfdsbNseEEWdge038@3^$%^';
 	$json = json_decode($result);
	$uid = $json->data->uid;
	$token = hash('sha256',$json->data->token.$salt);
	$unionid = hash('sha256',$userinfo['unionid'].$salt);
		 
	$callback = $_SESSION['callback_uri'].'&uid='.$uid.'&token='.$token;
 	
	//file_put_contents('qqqq','step 5...callback==='.$callback.';token==='.$json->data->token.';toPath==='.$toPath);
	
	$html =<<<EOD
	<script>
 $.redirect("$callback"); 
	</script> 
EOD;
	echo $html;
  } else {//error
  	 	 
	echo '<script>window.location.href = "http://weixin.mowork.cn/#/error"</script>';

  } 

}

function postJson(array $data){
 $data_string = json_encode($data);                                                                                  
 
$ch = curl_init('http://test.mowork.cn/api/user/login');                                                                     
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
