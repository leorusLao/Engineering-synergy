<?php
/*
1. go to https://mp.weixin.qq.com
2. developer center: configuration Items:
3. Server Config: Modify config
   set up: 1.url->
           2. token
           etc.
4. change permissons: chmod 777 access_toke.json,jsapi_ticket.json;
   on third party server or this file where is located.
   setup parameters for TOKEN, $appId,$appSecret on this file to match parameters on whchat server.
5. on wechat server excute modifying configuration.
6. Modification either success or failure.  




*/ 
 
//traceHttp();

define("TOKEN", "m2e0v1a5nV6E3X22180");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    file_put_contents('qqqq',$_GET('echostr'));
    echo $_GET('echostr'); 
    //$wechatObj->valid();
}else{
	$access_token = $wechatObj->getAccessToken();
    $wechatObj->responseMsg($access_token);
}

class wechatCallbackapiTest
{
	private $appId = "wxe18e720355871e06";
    private $appSecret = "1545c6a0ea824d161dd3072403202fdd";
	 
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg($access_token)
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            if($keyword == "?" || $keyword == "？")
            {
                $msgType = "text";
                $contentStr = date("Y-m-d H:i:s",time());
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
            
            
            $recType =  trim($postObj->MsgType);
            switch ($recType)
            {
                case "text":
                    $resultStr = $this->receiveText($postObj).date('Y-m-d H:i:s') ;
                    break;
                case "event":
                    $resultStr = $this->receiveEvent($postObj);
                    break;
                default:
                    $resultStr = "";
                    break;
            }
            
            echo $resultStr;
            
        }else{
        	
        	switch ($_GET['act']){
        		case 0:
        		  $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token";
        	 
            $postdata =<<<EOD
{
    "button": [
        
        {
            "name": "ME推广", 
            "sub_button": [
                 {
                    "type": "view", 
                    "name": "网站推广", 
                    "url": "http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=203949598&idx=1&sn=27bb7d33a61e373523738583cc699bc1#rd"
                    
                }, 
                 {
                    "type": "view", 
                    "name": "数字化推广", 
                    "url": "http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=204508146&idx=1&sn=9e3f0b53a894df1fa15c3c2813af2c7c&scene=18#wechat_redirect"
                }, 
               
                {
                    "type": "view", 
                    "name": "贴片推广", 
                    "url": "https://v.memyth.com/The_Trend-cn.html"
                }, 
                
                {
                    "type": "view", 
                    "name": "免费广告", 
                     "url": "https://www.memyth.com/free-ad-list"
                }, 
                
                {
                    "type": "view", 
                    "name": "我要推广", 
                    "url": "https://www.memyth.com/come-soon"
                }
            ]
        },   
        {
             
            "name": "ME微商城", 
             "sub_button": [
             		{
                    "type": "view", 
                    "name": "全球热卖", 
            		"url": "https://www.memyth.com/come-soon"
             		},
             		{
                    "type": "view", 
                    "name": "品牌专场", 
            		"url": "https://www.memyth.com/come-soon"
             		},
             		{
                    "type": "view", 
                    "name": "特卖专区", 
            		"url": "http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=203949632&idx=1&sn=5806d5fc51f48faebe4c1b389b322867&scene=18#wechat_redirect"
             		},
             		{
                    "type": "click", 
                    "name": "微信支付", 
            		"key": "EVENT_PAYSOLUTION"
             		},
             		{
                    "type": "view", 
                    "name": "绿色理念", 
            		"url": "https://www.memyth.com/come-soon"
             		}
            		
            ]
        },  
        {    
             "name": "走进ME", 
             "sub_button":
             [
             	{     
            	 "type": "view", 
            	 "name": "公司介绍", 
           		 "url": "https://www.memyth.com/company-info"
            	 },
             	
             	{
                    "type": "view",  
                    "name": "新媒体商机", 
                    "url": "https://v.memyth.com/The_Trend-cn.html"
              	},
              	{
                    "type": "view",
                    "name": "帮助中心", 
                    "url": "https://www.memyth.com/faq"
              	},
              	{
                    "type": "view",
                    "name": "加入我们", 
                    "url": "http://mp.weixin.qq.com/s?__biz=MzAwODExNTMyNA==&mid=203552358&idx=1&sn=b28a020ea0db2323d45aefeba6aff520#rd"
              	},
              	{
                    "type": "view", 
                    "name": "联系我们", 
                    "url": "https://www.memyth.com/contact"
              	}
              
             ] 
        }
    ]
}
EOD;
        		
        		break;
        		case  1:
        		    $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=$access_token";
        	 		$postdata = '{
    		"type":"news",
    		"offset":0,
    		"count":3
			}';
        			break;
        		case 2:
        		
        		 $url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$access_token";
        	 //$postdata = '{"media_id":"iORtwAWwiCvvLlu369cdLsxkEl28oEhZkY5ugxZaf2c"}';
        	 
        	 $postdata = '{ 
        	  "filter":{
      "is_to_all":true
 
   }, 		
      "image": 
		 {  "media_id":"iORtwAWwiCvvLlu369cdLsxkEl28oEhZkY5ugxZaf2c",
            
		 },"msgtype":   "image"
}';
        		
        			break;
        		
        	}
        	 
        	   
        	 
           $this->httpPost($url,$postdata); 
        	 
        	 
            exit;
        }
    }
    
    public function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("access_token.json"));
   
    if ($data->expire_time < time()) {
      // 如果是企业号用以下URL获取access_token
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
      
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
      $res = json_decode($this->httpGet($url));
      $access_token = $res->access_token;
      if ($access_token) {
        $data->expire_time = time() + 7000;
        $data->access_token = $access_token;
        $fp = fopen("access_token.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $access_token = $data->access_token;
    }
     
    return $access_token;
  }
  
   public function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }
  
  public function httpPost($url,$jsonData){
  		$ch = curl_init($url);
  		curl_setopt_array($ch, array(
   		 CURLOPT_POST => TRUE,
  		 CURLOPT_RETURNTRANSFER => TRUE,
   		 CURLOPT_HTTPHEADER => array(
       
        'Content-Type: application/json'
    	),
    	CURLOPT_POSTFIELDS => $jsonData));

		// Send the request
		$response = curl_exec($ch);

	// Check for errors
	if($response === FALSE){
    	die(curl_error($ch));
	}
	
	$responseData = json_decode($response, TRUE);
	
	var_dump($responseData);
  }
  
   private function receiveText($object)
    {
        $funcFlag = 0;
        $contentStr = "你发送的内容为：".$object->Content;
        $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
        return $resultStr;
    }
    
    private function receiveEvent($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr[] = array("title" => "欢迎关注M.E全球!",
                 "Description" =>"M.E提供移动互联网广告产品及服务", 
                        "PicUrl" =>"https://www.memyth.com/asset/img/logo-ME.png", 
                        "Url" =>"http://10lines.com/wechat/video.html");
            case "unsubscribe":
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "company":
                        $contentStr[] = array("Title" =>"公司简介", 
                        "Description" =>"M.E提供移动互联网广告产品及服务", 
                        "PicUrl" =>"https://www.memyth.com/asset/img/logo-ME.png", 
                        "Url" =>"weixin://addfriend/pondbaystudio");
                        break;
                    case "EVENT_PAYSOLUTION":
                        $contentStr[] = array("Title" =>"<<微信支付>>", 
                        "Description" =>"正在程序申请中，稍后敬请使用。", 
                        "PicUrl" =>"https://www.memyth.com/asset/img/logo-ME.png", 
                        "Url" =>"weixin://addfriend/pondbaystudio");
                        break;
                      
                    default:
                        $contentStr[] = array("Title" =>"默认菜单回复", 
                        "Description" =>"自定义菜单测试接口", 
                        "PicUrl" =>"https://www.memyth.com/asset/img/logo-ME.png", 
                        "Url" =>"weixin://addfriend/pondbaystudio");
                        break;
                }
                break;
            default:
                break;      

        }
        if (is_array($contentStr)){
            $resultStr = $this->transmitNews($object, $contentStr);
        }else{
            $resultStr = $this->transmitText($object, $contentStr);
        }
        return $resultStr;
    }

    private function transmitText($object, $content, $funcFlag = 0)
    {
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>%d</FuncFlag>
</xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $funcFlag);
        return $resultStr;
    }

    private function transmitNews($object, $arr_item, $funcFlag = 0)
    {
        //首条标题28字，其他标题39字
        if(!is_array($arr_item))
            return;

        $itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
        $item_str = "";
        foreach ($arr_item as $item)
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);

        $newsTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<Content><![CDATA[]]></Content>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
<FuncFlag>%s</FuncFlag>
</xml>";

        $resultStr = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($arr_item), $funcFlag);
        return $resultStr;
    }
 
  
}

function traceHttp()
{
    logger("\n\nREMOTE_ADDR:".$_SERVER["REMOTE_ADDR"].(strstr($_SERVER["REMOTE_ADDR"],'101.226')? " FROM WeiXin": "Unknown IP"));
    logger("QUERY_STRING:".$_SERVER["QUERY_STRING"]);
}
function logger($log_content)
{
    if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
        sae_set_display_errors(false);
        sae_debug($log_content);
        sae_set_display_errors(true);
    }else{ //LOCAL
        $max_size = 500000;
        $log_filename = "log.html";
        if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
        file_put_contents($log_filename, date('Y-m-d H:i:s').$log_content."\r\n", FILE_APPEND);
    }
}
?>
