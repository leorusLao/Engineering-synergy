<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class Weixin extends Controller
{	
	private $appid = 'wxfc27e6462366229c';
	private $secret = '3b166209a2e76bf7a298be1afebcd7d7';


    //curl方法
    public static function curl_get($url)
    { 
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
    public static function curl_post($url,$data)
    { 
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
        if(!$data){ 
            echo CURL_error($ch);
        }else{ 
            $data = json_decode($data);
            return $data;
        }
    }


    //获取普通的access_token,7200有效期，应该要保存下来的
    public function get_token()
    {
    	if(Redis::exists('token')){
    		$token_redis = Redis::get('token');
    	}
    	if(empty($token_redis)){
			$token = $this->update_token();
    	}else{ 
    		$token = Redis::get('token');
    	}
        return $token;
    }


    //更新redis中的access_token
    public function update_token()
    { 
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->secret;
        $data = self::curl_get($url);
        $token = '';
        if(!empty($data->access_token)){ 
        	$token = $data->access_token;
        	Redis::setEx('token',7100,$token);
        }
        return $token;
    }


    //获取jsapi_ticket
    public function get_jsticket($token)
    { 
    	if(Redis::exists('jsapi_ticket')){ 
    		$jsapi_ticket = Redis::get('jsapi_ticket');
    	}
    	if(empty($jsapi_ticket)){ 
    		$jsapi_ticket = $this->update_jsticket($token);
    	}
    	return $jsapi_ticket;
    }

    //更新jsapi_ticket
    public function update_jsticket($token)
    { 
    	$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$token.'&type=jsapi';
    	$data = self::curl_get($url);
    	$jsapi_ticket = '';
    	if(!empty($data->ticket)){ 
    		$jsapi_ticket = $data->ticket;
    		Redis::setEx('jsapi_ticket',7100,$jsapi_ticket);
    	}
    	return $jsapi_ticket;
    }


    //获取signature
    public function get_signature($jsapi_ticket,$url)
    { 
		$array['noncestr'] = randString(15);      //随机字符串
		$array['timestamp'] = $_SERVER['REQUEST_TIME'];     //时间戳
		$array['jsapi_ticket']=$jsapi_ticket;
        $array['url'] = $url;		
		$str = 'jsapi_ticket='.$array['jsapi_ticket'].'&noncestr='.$array['noncestr'].'&timestamp='.$array['timestamp'].'&url='.$array['url'];
		$array['signature'] = sha1($str);
		return $array;
    }


    //获取临时素材
    public function get_media($token,$media_id)
    { 
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$token.'&media_id='.$media_id;
        $str_img = self::curl_get($url); 
		$img_url = './uploads/common/'.date('Ymd').time().rand(001,999).'.jpg';
		$result = file_put_contents($img_url, $str_img);
		if($result){ 
			return $img_url;
		}else{ 
			return false;
		}
    }


    //设置临时素材
    public static function set_media($token)
    { 
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


    //腾讯云手机短信发送
    public static function send_message($mobile,$code,$type,$minute='30')
    {
        $sdkappid = '1400043310';
        $appkey = 'f5291ebfa729ccafe6f3b9cac461f8fd';
        $random = rand(10000,99999); //随机数
        $time = $_SERVER['REQUEST_TIME']; //时间戳
        $data['tel']['nationcode'] = '86'; //国家码
        $data['tel']['mobile'] = $mobile; //手机
        //$data['type'] = 0; //普通短信
        //$data['msg'] = '您正在注册MoWork，验证码为'.$code.'，'.$minute.'分钟内有效。';
        $data['extend'] = '';
        if($type==0){
            $data['tpl_id'] = 46770; //注册模板号
        }else if($type==1){ 
            $data['tpl_id'] = 70202; //登录模板号
        }
        $data['params'] = array($code,$minute);
        $sig_string = 'appkey='.$appkey.'&random='.$random.'&time='.$time.'&mobile='.$data['tel']['mobile'];
        $data['sig'] = hash('sha256',$sig_string); //hash加密
        $data['time'] = $time;
        $url = 'https://yun.tim.qq.com/v5/tlssmssvr/sendsms?sdkappid='.$sdkappid.'&random='.$random;
        $data = json_encode($data);
        $return = self::curl_post($url,$data);
        return $return;
    }


    //发送验证码
    public static function send_code($mobile,$type=0)
    {
        try{
            $code = rand(100000,999999);      
            $result = self::send_message($mobile,$code,$type);
            if(!empty($result) && $result->errmsg == 'OK'){
                Redis::setEx($mobile,1800,$code);
                $result = array('mobile'=>$mobile);
                return $result;
            }else{ 
            	Log::debug('sms error_code: '.$result->result.',errmsg: '.$result->errmsg);
                return false;
            }
        }catch(Exception $e){ return 10003; } 
    }


}
