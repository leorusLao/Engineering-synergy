<?php
/*
 This is for browser webpage scan wechat SQRcode to authorize
*/
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');  
// 响应类型  
header('Access-Control-Allow-Methods:GET');  
// 响应头设置  
header('Access-Control-Allow-Headers:x-requested-with,content-type');  

require 'vendor/autoload.php';
require './OAuth_mw.php';

$type=!empty($_GET['type'])?$_GET['type']:'mw_wx';
$url = !empty($_GET['url'])?$_GET['url']:'';
if($type=='mw_wx'){
	$appid = 'wxfc27e6462366229c';
	$secret = '3b166209a2e76bf7a298be1afebcd7d7';
}else{ 
	$appid = 'wxfc27e6462366229c';
	$secret = '3b166209a2e76bf7a298be1afebcd7d7';
}

$oauth = new OAuth_mw($appid, $secret);
if($access_token = $oauth->getJssdkAccessToken()){
	$api_ticket = $oauth->getApi_ticket($access_token);

	$data = $oauth->getSignature($api_ticket,$url);
	$data['appid'] = $appid;
	if(!empty($data['signature'])){
		return ajaxReturn('00000','success','',$data);
	}else{ 
		$data = '';
		return ajaxReturn('10000','failure','获取失败',$data);
	}
} 


function ajaxReturn($reasonCode, $result, $description, $data = array())
{
    header('Content-type: application/json');
    if(!empty($data)) {
    	$arr_result = array('data' => $data,'description'=>$description,'reasonCode'=>$reasonCode,'result'=>$result);
    }
    echo json_encode($arr_result, JSON_UNESCAPED_UNICODE);
    exit;
}

?>
