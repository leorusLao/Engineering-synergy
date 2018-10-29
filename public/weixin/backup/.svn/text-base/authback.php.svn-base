<html>
<head>
    <script src="http://test.mowork.cn/asset/js/jquery-1.11.3.min.js"></script>
    <script src="http://test.mowork.cn/asset/js/jquery.redirect.js"></script>
</head>
<body>
<?php
/*
 This is for browser webpage scan wechat SQRcode to authorize

*/
require 'vendor/autoload.php';
use Henter\WeChat\OAuth;

$appid = 'wxd154847b8418bf92';
$secret = '5f83859e8d9c5a2eb07ec27f283a3a16';
if (isset($_GET["code"])) {  
  $code = $_GET["code"];  
  $oauth = new \Henter\WeChat\OAuth($appid, $secret);
  if($access_token = $oauth->getAccessToken('code', $code)){
	$refresh_token = $oauth->getRefreshToken();
	$expires_in = $oauth->getExpiresIn();
	$openid = $oauth->getOpenid();
	$userinfo = $oauth->api('sns/userinfo', array('openid'=>$openid));
	$userinfo = array_merge($userinfo,array('client_type' => '3','identity_type' => 'wechat'));
        //die(var_dump($userinfo));
	$result = postJson($userinfo);
	$salt = '&SDFe4wfs9342w99t@3rfdsbNseEEWdge038@3^$%^';
 	$json = json_decode($result);
	$uid = $json->data->uid;
	$token = hash('sha256',$json->data->token.$salt);
	$unionid = hash('sha256',$userinfo['unionid'].$salt);
	$forward_url = "http://www.mowork.cn/login/wechat";	

	//header("Location: http://test.mowork.cn/login/wechat/$uid/$token/$unionid");        
	$html =<<<EOD
	<script>
 $.redirect("$forward_url",{ uid: "$uid", token: "$token", unionid: "$unionid"}); 
	</script> 
EOD;
	echo $html;
  }else{//error
	echo '<script>window.location.href = "http://www.mowork.cn/login"</script>';

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
