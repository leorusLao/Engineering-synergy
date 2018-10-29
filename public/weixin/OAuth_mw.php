<?php

class OAuth_mw {
    /**
     * @ignore
     */
    private $appid;

    /**
     * @ignore
     */
    private $secret;

    /**
     * @ignore
     */
    private $access_token;

    /**
     * @ignore
     */
    private $refresh_token;

    /**
     * @ignore
     */
    private $expires_in;

    /**
     * @ignore
     */
    private $openid;

    /**
     * @ignore
     */
    private $unionid;

    /**
     * Set up the API root URL
     *
     * @ignore
     */
    private $host = "https://api.weixin.qq.com/";

    /**
     * Set timeout
     *
     * @ignore
     */
    private $timeout = 5;

    /**
     * Set the user agnet
     *
     * @ignore
     */
    private $user_agent = 'Henter WeChat OAuth SDK';
    private $ticket = '';

    public $code;
    public $protocol;
    public $message;
    public $body;
    public $error;
    public $headers = array();
    public $cookies = array();
    public $data = array();


    /**
     * @param $appid
     * @param $secret
     * @param null $access_token
     * @return OAuth
     */
    public function __construct($appid, $secret, $access_token = null) {
        $this->appid = $appid;
        $this->secret = $secret;
        $this->access_token = $access_token;
        return $this;
    }

    public function error($error = NULL){
        if(is_null($error))
            return $this->error;

        $this->error = $error;
        return false;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if(is_array($this->data) || is_array($this->data = json_decode($this->body, true)))
            return $this->data;

        return null;
    }

    /**
     * 获取JssdkAccessToken
     *
     * @param $redirect_uri
     * @param string $scope
     * @param null $state
     * @return string
     */
    public function getJssdkAccessToken() {
        $params = array();
        $params['appid'] = $this->appid;
        $params['secret'] = $this->secret;
        $params['grant_type'] = 'client_credential';
        $uri = 'cgi-bin/token?';
        $this->postJson($this->host.$uri, 'GET', $params);
        $return = $this->toArray();
        if(!is_array($return) || !$return){
            return $this->error("get access token failed");
        }
        if (!isset($return['errcode'])){
            $this->access_token = $return['access_token'];
        }else{
            return $this->error("get access token failed: " . $return['errmsg']);
        }
        return $this->access_token;
    }


    /**
     * 获取Api_ticket
     *
     * @param $redirect_uri
     * @param string $scope
     * @param null $state
     * @return string
     */
    public function getApi_ticket($access_token) {
        $params = array();
        $params['access_token'] = $access_token;
        $params['type'] = 'jsapi';
        $url = 'cgi-bin/ticket/getticket';
        $this->postJson($this->host.$url, 'GET', $params);
        $return = $this->toArray();

        if(!is_array($return) || !$return){
            return $this->error("get ticket failed");
        }
        if ($return['errcode']==0){
            $this->ticket = $return['ticket'];
        }else{
            return $this->error("get access token failed: " . $return['errmsg']);
        }
        return $this->ticket;
    }

    /**
     * 获取signature
     *
     * @param $redirect_uri
     * @param string $scope
     * @param null $state
     * @return string
     */
    public function getSignature($api_ticket,$url) {
        $params = array();
        $params['jsapi_ticket']=$api_ticket;
        $params['noncestr']='Wm3WZYTPz0wzccnW';
        $params['timestamp']=time();
        $params['url'] = $url;
        $string1 = 'jsapi_ticket='.$params['jsapi_ticket'].'&noncestr='.$params['noncestr'].'&timestamp='.$params['timestamp'].'&url='.$url;
        $params['signature']=sha1($string1);
        return $params;
    }


    public function postJson($url, $method, $parameters){

        $data_string = json_encode($parameters);  

        $url = $url . (strpos($url, '?') ? '&' : '?') . http_build_query($parameters);
      
        $ch = curl_init();      
        curl_setopt($ch, CURLOPT_URL, $url);            
        curl_setopt($ch, CURLOPT_HEADER, true);                                                   
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);                                                                    
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
            'Content-Type: application/json',                                                                               
            'Content-Length: ' . strlen($data_string))                                                                      
        );                                                                                                                  
         
        $result = curl_exec($ch); 

        $error = false;
        $res =  $this->response($result, $error);
        return $res;
    }


    /**
     * Parse response
     *
     * @param $response
     * @return array
     */

    public function response($response, $error = false)
    {
        if (is_string($response) && ($parsed = $this->parse($response))) {
            foreach ($parsed as $key => $value) {
                $this->{$key} = $value;
            }
        }
        $this->error = $error;
    }


    /**
     * Parse response
     *
     * @param $response
     * @return array
     */
    public function parse($response)
    {
        $body_pos = strpos($response, "\r\n\r\n");
        $header_string = substr($response, 0, $body_pos);
        if ($header_string == 'HTTP/1.1 100 Continue') {
            $head_pos = $body_pos + 4;
            $body_pos = strpos($response, "\r\n\r\n", $head_pos);
            $header_string = substr($response, $head_pos, $body_pos - $head_pos);
        }
        $header_lines = explode("\r\n", $header_string);

        $headers = array();
        $code = false;
        $body = false;
        $protocol = null;
        $message = null;
        $data = array();
        $cookie_lines = array();

        foreach ($header_lines as $index => $line) {
            if ($index === 0) {
                preg_match("/^(HTTP\/\d\.\d) (\d{3}) (.*?)$/", $line, $match);
                list(, $protocol, $code, $message) = $match;
                $code = (int)$code;
                continue;
            }
            list($key, $value) = explode(":", $line, 2);
            $headers[strtolower(trim($key))] = trim($value);
            if(strtolower(trim($key)) == 'set-cookie'){
                $cookie_lines[] = trim($value);
            }
        }

        if (is_numeric($code)) {
            $body_string = substr($response, $body_pos + 4);

            $body = (string)$body_string;
            $result['header'] = $headers;
        }

        $data = json_decode($body, true);

        return $code ? array(
            'code'     => $code,
            'body'     => $body,
            'headers'  => $headers,
            'message'  => $message,
            'protocol' => $protocol,
            'data'     => $data
        ) : false;
    }

    //curl方法
    public static function curl_get($url){ 
        $ch = CURL_init();
        CURL_setopt($ch,CURLOPT_URL,$url);
        CURL_setopt($ch,CURLOPT_HEADER,0);  //不要头部信息
        CURL_setopt($ch,CURLOPT_RETURNTRANSFER,1);  //数据返回给句柄
        CURL_setopt($ch,CURLOPT_TIMEOUT_MS,3000);   //超时放弃
        CURL_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        CURL_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        $data = CURL_exec($ch);
        CURL_close($ch);
        if(empty($data)){ 
            echo CURL_error($ch);
        }else{ 
            if(!empty(json_decode($data))){ 
                $data = json_decode($data);
            }
            return $data;
        }
    }


    //curl方法(post)
    public static function curl_post($url,$data){ 
        $ch = CURL_init();
        CURL_setopt($ch,CURLOPT_URL,$url);
        CURL_setopt($ch,CURLOPT_POST,1);   //post方式
        CURL_setopt($ch,CURLOPT_POSTFIELDS,$data);  //post提交数据
        CURL_setopt($ch,CURLOPT_HEADER,0);  //不要头部信息
        CURL_setopt($ch,CURLOPT_RETURNTRANSFER,1);  //数据返回给句柄
        CURL_setopt($ch,CURLOPT_TIMEOUT_MS,3000);   //超时放弃
        CURL_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        CURL_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        $data = CURL_exec($ch);
        CURL_close($ch);
        if(!$data){ 
            echo CURL_error($ch);
        }else{ 
            $data = json_decode($data);
            return $data;
        }
    }


    //获取普通的access_token,7200有效期，应该要保存下来的
    public function get_token(){ 
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->secret;
        $data = self::curl_get($url);
        if(!empty($data->access_token)){
            $token = $data->access_token;
        }else{ 
            $token = '';
        }
        return $token;
    }


    //获取临时素材
    public function get_media($token,$media_id){ 
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id='.$media_id;
        $str_img = self::curl_get($url);       
        return $str_img;
    }


    //设置临时素材
    public static function set_media($token){ 
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$token.'&type=image';
        $img_path = 'D:\wamp3\www\svn-mowork\public\weixin\aabbcc.jpg';
        $cfile = curl_file_create($img_path);   //use the CURLFile Class 替换@的使用方法
        $data = array('access_token'=>$token,'type'=>'image','media'=>$cfile);
        $result = self::curl_post($url,$data);
        if(!empty($result->media_id)){
            $media_id = $result->media_id;
        }else{ 
            $media_id = '';
        }
        return $media_id;
    }


}